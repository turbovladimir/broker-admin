crete_network:
	docker network create -d bridge broker_network --subnet 172.30.0.0/24

build_prod:
	docker-compose \
		-f docker/compose_prod.yaml \
	down --rmi all -v
	docker-compose \
		-f docker/compose_prod.yaml \
	up -d
	docker exec -it c-broker-prod composer install

build_beta:
	docker-compose \
		-f docker/compose_beta.yaml \
	down --rmi all -v
	docker-compose \
		-f docker/compose_beta.yaml \
	up -d
	docker exec -it c-broker-beta composer install