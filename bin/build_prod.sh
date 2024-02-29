#!/bin/bash

git pull
cd docker && docker-compose down && docker-compose up -d
docker exec -it c-broker-admin sh -c "cd /app && composer install"
