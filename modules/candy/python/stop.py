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
  
try:
  machine.power_off()
except:
  response(False)
  
response(True)