from server import get_arg, get_server, get_center, response

serve = get_center()

if not serve:
  serve = get_server()
  
if not serve:
  response(False)
  
address = get_arg('ip[ip]')

try:
  machine = serve.get_vm_by_name(address)
except:
  response(False)

if not machine:
  response(False)
  
# Operating system username
username = get_arg('username')

# And its password
password = get_arg('password')

try:
  machine.login_in_guest(username, password)
except:
  response(False)
  
# And its name
name = get_arg('os[operation_system]')

if 'debian 9.6' in name:
  program, command = ['/bin/sh', ['-c', 'curl http://file.autovm.net/machine/debian/extend.sh | sh']]

elif 'ubuntu 16.04' in name:
  program, command = ['/bin/sh', ['-c', 'curl http://file.autovm.net/machine/ubuntu/extend.sh | sh']]
  
elif 'centos 7' in name:
  program, command = ['/bin/sh', ['-c', 'curl http://file.autovm.net/machine/centos/7/extend.sh | sh']]

elif 'windows' in name and 'windows 2003' not in name:
  program, command = ['cmd.exe', ['/c', 'echo select volume c > diskpart.txt & echo extend >> diskpart.txt & diskpart /s diskpart.txt\r']]

if program:
  try:
    machine.start_process(program, command)
  except:
    response(False)
    
  response(True)
  
response(False)