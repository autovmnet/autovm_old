#!/bin/bash

# Nginx configuration
config="server {
  listen 80;

  server_name localhost;

  index index.php;
  root /var/www/autovm/web;

  location / {
    try_files \$uri \$uri/ /index.php\$is_args\$args;
  }

  location ~ \\.php\$ {
    include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
  }

  location ~ \\.ht {
    deny all;
  }
}"

# Update repositories
apt update -y

# Install requirements
apt install -y nginx git unzip php7.2-fpm php7.2-cli php7.2-mysql php7.2-mbstring php7.2-gd php7.2-curl php7.2-zip php7.2-xml mysql-server python-pip && pip install spur pysphere crypto netaddr

# Random password
password=$(openssl rand -base64 16)

# PHP config
php_config="<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=autovm',
    'username' => 'autovm',
    'password' => '$password',
    'charset' => 'utf8',
];"

# Configure MySQL
mysql -u root -e "CREATE USER autovm@localhost IDENTIFIED WITH mysql_native_password BY '$password';GRANT ALL PRIVILEGES ON *.* TO autovm@localhost; FLUSH PRIVILEGES;CREATE DATABASE autovm DEFAULT CHARACTER SET utf8;"

# Configure Nginx
sed -i 's/# multi_accept on/multi_accept on/' /etc/nginx/nginx.conf && echo $config > /etc/nginx/sites-available/default && service nginx restart

# Configure PHP
sed -i 's/max_execution_time = 30/max_execution_time = 300/' /etc/php/7.2/fpm/php.ini && service php7.2-fpm restart

# Configure AutoVM
cd /var/www && rm -rf html && git clone https://github.com/autovmnet/autovm && cd autovm && php7.2 composer.phar install && echo $php_config > /var/www/autovm/config/db.php && mysql -u root -proot autovm < database.sql && mysql -u root -e "USE autovm;UPDATE user SET auth_key = '$password'" && php7.2 yii migrate --interactive=0 && chmod -R 0777 /var/www/autovm

# Configure Cron
cd /tmp && echo -e "*/5 * * * * php /var/www/autovm/yii cron/index\n0 0 * * * php /var/www/autovm/yii cron/reset" > cron && crontab cron

# Find address
address=$(ip address | grep "scope global" | grep -Po '(?<=inet )[\d.]+')

# MySQL details
clear && echo -e "\033[104mThe platform installation has been completed successfully.\033[0m\n\nMySQL information:\nUsername: autovm\nDatabase: autovm\nPassword: \033[0;32m$password\033[0m\n\n\nLogin information:\nAddress: http://$address\nUsername: admin@admin.com\nPassword: admin\n\nAttention: Please run \033[0;31mmysql_secure_installation\033[0m for the security"