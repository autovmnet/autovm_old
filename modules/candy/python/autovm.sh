#!/bin/bash

# Config vm ip address and disk automatically.

# Find main interface name
name=\$(ls /sys/class/net | head -n 1)


# Find VM OS
if [ -f /etc/debian_version ]; then
    OS_ACTUAL=\$(lsb_release -i | cut -f2)
    VER=\$(lsb_release -r | cut -f2)

elif [ -f /etc/centos-release ]; then
    OS_ACTUAL=Centos
    VER=\$(cat /etc/centos-release | tr -dc "0-9." | cut -d \. -f1)
fi



if [ "\$8" = no ]; then

# Ubuntu
if [ "\$OS_ACTUAL" = Ubuntu ] ; then

    if  [ "\$VER" = "18.04" ]; then   

        mask2cdr ()
        {
           local x=\${1##*255.}
           set -- 0^^^128^192^224^240^248^252^254^ \$(( (\${#1} - \${#x})*2 )) \${x%%.*}
           x=\${1%%\$3*}
           echo \$(( \$2 + (\${#x}/4) ))
        }

        subnet=\$(mask2cdr \$3)

        cat > /etc/netplan/config.yaml <<EOL
network:
  version: 2
  renderer: networkd
  ethernets:
   \$name:
    dhcp4: no
    addresses: [\$1/\$subnet]
    gateway4: \$2
    nameservers:
      addresses: [\$4,\$5]
    routes:
      - to: \$2/\$subnet
        via: 0.0.0.0
        scope: link
EOL
        netplan apply
        
        
    elif  [ "\$VER" = "16.04" ]; then
    
        cat > /etc/network/interfaces <<EOL
auto lo
iface lo inet loopback
auto \$name
iface \$name inet static
address \$1
gateway \$2
netmask \$3
dns-nameservers \$4
dns-nameservers \$5
dns-search google.com
EOL
        
        service networking restart
    fi

# Debian

elif [ "\$OS_ACTUAL" = Debian  ] ; then

    if  [ "\$VER" = "10" ] || [ "\$VER" = "9" ] || [ "\$VER" = "8" ]; then
        
        cat > /etc/network/interfaces <<EOL
auto lo
iface lo inet loopback
auto \$name
iface \$name inet static
address \$1
gateway \$2
netmask \$3
dns-nameservers \$4
dns-nameservers \$5
dns-search google.com
EOL
        
        service networking restart
        
        cat > /etc/resolv.conf <<EOL
nameserver \$4
nameserver \$5
search google.com
EOL
    
    fi

# Centos
elif [ "\$OS_ACTUAL" = Centos  ] ; then

    if  [ "\$VER" = "8" ] || [ "\$VER" = "7" ]; then
        
        cat > /etc/sysconfig/network-scripts/ifcfg-\$name <<EOL
DEVICE=\$name
TYPE=Ethernet
ONBOOT=yes
IPADDR=\$1
GATEWAY=\$2
NETMASK=\$3
DNS1=\$4
DNS2=\$5
EOL
        
        systemctl restart NetworkManager
                    
    fi

fi
fi



# Find virtualization system and change password and extend disk

virtualization=\$(systemd-detect-virt)

if [ \$virtualization = vmware ] ; then
    echo "root:\$6" | chpasswd

    if [ "\$7" = yes ]; then
        
        if [ "\$OS_ACTUAL" = Debian  ] ; then
        
            cat > /etc/apt/sources.list<<EOL
deb http://deb.debian.org/debian/ stable main contrib
deb-src http://deb.debian.org/debian/ stable main contrib

deb http://deb.debian.org/debian/ stable-updates main contrib
deb-src http://deb.debian.org/debian/ stable-updates main contrib

deb http://deb.debian.org/debian-security stable/updates main
deb-src http://deb.debian.org/debian-security stable/updates main

deb http://ftp.debian.org/debian buster-backports main
deb-src http://ftp.debian.org/debian buster-backports main
EOL

            apt update && apt install parted lvm2 -y
        fi
        
        if [ -e /dev/sda2 ]; then
            sda=3
        else
            sda=2
        fi
        
        if  [ "\$VER" = "16.04" -o "\$OS_ACTUAL" = Debian ]; then
            parted /dev/sda --script mkpart primary \$(parted /dev/sda print | grep -E "lvm" | xargs -n1 | sed -n 3p) 100%
        else
            parted /dev/sda --script mkpart primary \$(parted /dev/sda print | grep -E "lvm|extended" | xargs -n1 | sed -n 3p) 100%
        fi
        
        pvscan --cache
        partprobe
        vg=\$(vgs --noheadings | xargs -n1 | sed -n 1p)
        pvcreate /dev/sda\$sda
        vgextend "\$vg" /dev/sda\$sda
        lvextend /dev/"\$vg"/root /dev/sda\$sda
        
        if [ -f /etc/debian_version ]; then
            resize2fs /dev/"\$vg"/root
            
        elif [ "\$OS_ACTUAL" = Centos  ] ; then
            if  [ "\$VER" = "8" ]; then            
                 xfs_growfs /
            else :
                xfs_growfs /dev/"\$vg"/root 
            fi
        fi
    fi
fi
