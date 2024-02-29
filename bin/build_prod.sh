#!/bin/bash

git pull
docker exec -it c-broker-admin sh -c "cd /app && composer install"
