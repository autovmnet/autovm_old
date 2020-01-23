from pysphere.resources import VimService_services as VI
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
  status = machine.is_powered_on()
except:
  response(False)
  
if status:
  try:
    machine.power_off()
  except:
    response(False)
    
request = VI.Destroy_TaskRequestMsg()

_this = request.new__this(machine._mor)
_this.set_attribute_type(machine._mor.get_attribute_type())

request.set_element__this(_this)

try:
  serve._proxy.Destroy_Task(request)
except:
  response(False)
  
response(True)