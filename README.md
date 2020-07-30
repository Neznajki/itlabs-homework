# installation
* echo '127.0.0.1 tournament-service.local.net' | sudo tee -a /etc/hosts
```shell script
docker network create local.net 
docker run -d --name local_network -p 80:80 -p 443:443 --restart always --net local.net -v /var/run/docker.sock:/tmp/docker.sock:ro -v .docker/certs:/etc/nginx/certs/:ro jwilder/nginx-proxy:latest
```
* docker-compose up -d
* go to >> http://tournament-service.local.net/

# estimates
* creating db structure 2h // init project
* creating entity repository 2h
* creating team grid 2h
* creating output for challenge create 1h
* started division challenge creation 1h
* creating challenge start 2h
* creating division match calculation logic 4h
* first play of initiation 4h
* play of logic handle init tests and make some fixes 3h