from pysphere import VITask
from pysphere.resources import VimService_services as VI

def reconfig(server, vm, cdrom):
  
  request = VI.ReconfigVM_TaskRequestMsg()
  
  _this = request.new__this(vm._mor)
  _this.set_attribute_type(vm._mor.get_attribute_type())
  
  request.set_element__this(_this)

  spec = request.new_spec()
  
  dev_change = spec.new_deviceChange()
  dev_change.set_element_device(cdrom)
  dev_change.set_element_operation('edit')

  spec.set_element_deviceChange([dev_change])
  
  request.set_element_spec(spec)
  
  ret = server._proxy.ReconfigVM_Task(request)._returnval

  task = VITask(ret, server)
  status = task.wait_for_state([task.STATE_SUCCESS,task.STATE_ERROR])
  
  if status == task.STATE_SUCCESS:
    return True
  
  return False