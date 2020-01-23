from server import get_arg, get_server, get_center, response, online

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
  
data = {'power': 'off', 'network': 'down'}

try:
  status = machine.is_powered_on()
except:
  response(False)
  
if status:
  data.update({'power': 'on'})
  
if status:
  if online(address):
    data.update({'network': 'up'})
  
response(True, data)