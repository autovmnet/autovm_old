from server import get_server, get_center, get_ssh, response

data = {'server': False, 'center': False, 'ssh': False, 'storage': False}

serve = get_server()

if serve:
  data.update({'server': True})
  
center = get_center()

if center:
  data.update({'center': True})
  
ssh = get_ssh()

if ssh:
  data.update({'ssh': True})
  
if serve:

  result = None

  try:
    result = serve.get_datastores().items()
  except:
    pass
  
  if result:
    for first, second in result:
      if 'atastore' in second:
        data.update({'storage': True})
        
response(True, data)
