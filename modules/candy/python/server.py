import os
import ssl
import sys
import json
import spur
import socket
import urlparse
from pysphere import VIServer

ssl._create_default_https_context = ssl._create_unverified_context

# Arguments
args = sys.argv[1]
args = urlparse.parse_qsl(args, keep_blank_values=True)
args = dict(args)

def get_arg(name):
  
  return args.get(name)

def get_server():
  
  serve = VIServer()
  
  try:
    serve.connect(get_arg('server[ip]'), get_arg('server[username]'), get_arg('server[password]'), sock_timeout=60)
  except:
    return None
  
  return serve

def get_center():
  
  serve = VIServer()
  
  try:
    serve.connect(get_arg('server[vcenter_ip]'), get_arg('server[vcenter_username]'), get_arg('server[vcenter_password]'), sock_timeout=60)
  except:
    return None
  
  return serve

def get_ssh():
  
  try:
    ssh = spur.SshShell(hostname=get_arg('server[ip]'), port=get_arg('server[port]'), username=get_arg('server[username]'), password=get_arg('server[password]'), missing_host_key=spur.ssh.MissingHostKey.accept, connect_timeout=60)
  except:
    return None
  
  try:
    ssh.run(['echo', '-n', 'hello'])
  except:
    return None
  
  return ssh
  
def append(*args):
  
  result = ''
  
  for arg in args:
    result = result + str(arg)
    
  return result
  
def space(*args):
  
  result, space = ['', ' ']
  
  for arg in args:
    result = result + str(arg) + space
    
  return result

def path(*args):
  
  result = ''
  
  for arg in args:
    result = os.path.join(result, str(arg))
    
  return result

def online(address):
  
  response = os.system(space('ping', '-c 1', address, '> /dev/null'))
  
  if response == 0:
    return True
  
  return False
      
def write(name, content):
  
  machine = get_arg('vps[id]')
  
  if not machine:
    return False
  
  first = path(os.path.dirname(__file__), 'runtime', machine)
  
  if not os.path.exists(first):
    os.mkdir(first)
    
  second = path(first, name)
  
  with open(second, 'a') as file:
    file.write(str(content))
    file.write('\n')
    
  return True

def delete(name):
  
  machine = get_arg('vps[id]')
  
  if not machine:
    return False
  
  first = path(os.path.dirname(__file__), 'runtime', machine, 'log')
  
  if os.path.exists(first):
    os.remove(first)
    
  return True

def response(ok, data = None, log = None):
  
  if data:
    print json.dumps({'ok': ok, 'data': data})
  else:
    print json.dumps({'ok': ok})
    
  if log:
    write('log', log)
    
  sys.exit(0)