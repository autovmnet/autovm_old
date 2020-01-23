from pysphere import VIProperty
from server import get_server, response

serve = get_server()

if not serve:
  response(False)
    
try:
  result = serve.get_datastores().items()
except:
  response(False)
  
storages = []

for first, second in result:
  
  try:
    props = VIProperty(serve, first)
  except:
    continue
    
  gigabyte = props.summary.capacity / 1073741824
  
  if 'datastore' in second:
    
    storage = {'name': second, 'hash': first, 'capacity': gigabyte}
    
    storages.append(storage)
    
if storages:
  response(True, storages)
  
response(False)