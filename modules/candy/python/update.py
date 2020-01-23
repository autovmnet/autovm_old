from pysphere.resources import VimService_services as VI
from server import get_server, get_arg, response, path, space, append

serve = get_server()

if not serve:
  response(False)
  
# Address
address = get_arg('ip[ip]')
  
try:
  machine = serve.get_vm_by_name(address)
except:
  response(False)
  
try:
  online = machine.is_powered_on()
except:
  response(False)
  
if online:
  try:
    machine.power_off()
  except:
    response(False)
  
# CPU cores
cpu_cores = get_arg('plan[cpu_core]')

if not cpu_cores:
  
  cpu_cores = get_arg('vps[vps_cpu_core]')
  
# CPU mhz
cpu_mhz = get_arg('plan[cpu_mhz]')

if not cpu_mhz:
  
  cpu_mhz = get_arg('vps[vps_cpu_mhz]')
  
# RAM
ram = get_arg('plan[ram]')

if not ram:
  
  ram = get_arg('vps[vps_ram]')
  
# HARD
hard = get_arg('plan[hard]')
  

request = VI.ReconfigVM_TaskRequestMsg()
_this = request.new__this(machine._mor)
_this.set_attribute_type(machine._mor.get_attribute_type())
request.set_element__this(_this)
spec = request.new_spec()
cpu_allocation = spec.new_cpuAllocation()
memory_allocation = spec.new_memoryAllocation()

cpu_allocation.set_element_limit(int(cpu_mhz))
obj_shares = cpu_allocation.new_shares()
obj_shares.Level = "normal"
obj_shares.Shares = 496000
cpu_allocation.Shares = obj_shares

memory_allocation.set_element_limit(int(ram))
obj_shares = memory_allocation.new_shares()
obj_shares.Level = "normal"
obj_shares.Shares = 2048000
memory_allocation.Shares = obj_shares
spec.set_element_cpuAllocation(cpu_allocation)
spec.set_element_memoryAllocation(memory_allocation)
spec.set_element_memoryMB(int(ram))
spec.set_element_numCPUs(int(cpu_cores)) 
request.Spec = spec

try:
  serve._proxy.ReconfigVM_Task(request)._returnval
except:
  response(False)

try:
  machine.power_on()
except:
  response(False)
  
response(True)