# DAST benchmark
 
## Getting started

```
pip3 install -r requirements.txt
sudo apt install docker.io
apt-get remove docker-compose
sudo curl -SL https://github.com/docker/compose/releases/download/v2.27.0/docker-compose-linux-x86_64 -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose
```

### Usage:
```
python3 manager.py <command>
--start: create and start all containers
--stop: stop all containers
--remove: remove all containers
--restart: stop, remove and start all containers
--count-requests: print number of requests made to primary nginx container
--get-time: print time difference between first and last request made to the server
--reset-stats: reset number of requests made as well as time between first and last request
```
router for pages should be exported on port 80
