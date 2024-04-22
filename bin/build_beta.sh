#!/bin/bash

git reset --hard
git pull
cd docker && docker-compose -f compose_beta.yaml down && docker-compose -f compose_beta.yaml up -d
docker exec -it c-broker-beta sh -c "composer dump-env beta && composer install"
