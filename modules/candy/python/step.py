import os
from server import path, get_arg, response

machine = get_arg('vps[id]')

if not machine:
  response(False)
  
first = path(os.path.dirname(os.path.realpath(__file__)), 'runtime', machine, 'status')

if not os.path.exists(first):
  response(False)
  
lines = []

with open(first) as file:
  lines = file.readlines()
  
status = lines.pop()

if not status:
  response(False)
  
step, percent = status.strip().split(':')

if not step or not percent:
  response(False)
  
data = {
  'step': step,
  'percent': percent
}

response(True, data)