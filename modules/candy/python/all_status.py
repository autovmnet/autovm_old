import re
from server import get_arg, get_ssh, response, append, space

ssh = get_ssh()

if not ssh:
  response(False)
  
address = get_arg('ip[ip]')

try:
  result = ssh.run(['sh', '-c', 'esxcli vm process list | grep -i \"Display Name\" | sed \"s/Display Name://g\"'])
except:
  response(False)
  
response(True, result.output)