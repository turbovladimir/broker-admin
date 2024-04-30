#!/bin/bash

cd docker && docker-compose -f compose_prod.yaml down && docker-compose -f compose_prod.yaml up -d
