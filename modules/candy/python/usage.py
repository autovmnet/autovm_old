import re
from server import get_arg, get_ssh, response, append, space

ssh = get_ssh()

if not ssh:
  response(False)
  
address = get_arg('ip[ip]')

try:
  result = ssh.run(['sh', '-c', 'vim-cmd vmsvc/getallvms'])
except:
  response(False)
  
output = result.output

try:
  result = re.search(append('([0-9]+).*', address, '.*'), output)
except:
  response(False)
  
machine = result.group(1)

if not machine:
  response(False)
  
try:
  result = ssh.run(['sh', '-c', space('vim-cmd', 'vmsvc/get.summary', machine)])
except:
  response(False)
  
output = result.output

def get_property(name):
  
  try:
    result = re.search(append(name, '\s*=\s*([0-9]+)\,'), output)
  except:
    return None
  
  return result.group(1)

cpu = get_property('maxCpuUsage')

if not cpu:
  cpu = 0
  
used_cpu = get_property('overallCpuUsage')

if not used_cpu:
  used_cpu = 0
  
ram = get_property('maxMemoryUsage')

if not ram:
  ram = 0
  
used_ram = get_property('guestMemoryUsage')

if not used_ram:
  used_ram = 0
  
uptime = get_property('uptimeSeconds')

if not uptime:
  uptime = 0
  
data = {'cpu': cpu, 'usedCpu': used_cpu, 'ram': ram, 'usedRam': used_ram, 'uptime': uptime}

response(True, data)