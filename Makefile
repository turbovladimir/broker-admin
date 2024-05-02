crete_network:
	docker network create -d bridge broker_network --subnet 172.30.0.0/24

build_prod:
	docker-compose \
		-f docker/compose_prod.yaml \
	down
	docker-compose \
		-f docker/compose_prod.yaml \
	up -d

build_beta:
	docker-compose \
		-f docker/compose_beta.yaml \
	down
	docker-compose \
		-f docker/compose_beta.yaml \
	up -d