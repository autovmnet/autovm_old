import re
from server import get_ssh, response, space

ssh = get_ssh()

if not ssh:
  response(False)
  
try:
  result = ssh.run(['sh', '-c', 'esxcli network vm list | awk "{print$2}"'])
except:
  response(False)
  
output = result.output

try:
  addresses = re.findall('(\d+\.\d+\.\d+\.\d+)', output)
except:
  response(False)
  
servers = {}

for address in addresses:
  servers[address] = 0
  
for address in servers:
  
  try:
    result = ssh.run(['sh', '-c', space('esxcli network vm list | grep', address, "| awk '{print$1}' | xargs esxcli network vm port list -w | sed -e 's/Port\ ID://g' | head -n1 | awk '{print$1}' | xargs esxcli network port stats get -p")])
  except:
    continue
   
  output = result.output

  try:
    stats = re.findall('Bytes\s*.*?\:\s*([0-9]+)', output)
  except Exception:
    continue
      
  try:
    first = stats.pop()
  except:
    continue
    
  try:
    second = stats.pop()
  except:
    continue
    
  servers[address] = int(first) + int(second)
    
response(True, servers)