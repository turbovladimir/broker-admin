#!/bin/bash

git pull
cd docker && docker-compose -f compose_prod.yaml down && docker-compose -f compose_prod.yaml up -d
docker exec -it c-broker-admin sh -c "composer install"
