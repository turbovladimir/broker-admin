crete_network:
	docker network create -d bridge broker_network --subnet 172.30.0.0/24

build_prod:
	docker-compose \
		-f docker/compose_prod.yaml \
	down --rmi all -v
	docker-compose \
		-f docker/compose_prod.yaml \
	up -d
	docker exec -it c-broker-prod composer install --no-dev

build_beta:
	cp assets/content/img/admin/logo/prod/* assets/content/img/admin/logo/beta
	docker exec c-postgres pg_dump -U app broker > backup.sql
	docker exec -it c-postgres psql -U app -d postgres -c "SELECT pg_terminate_backend( pid ) FROM pg_stat_activity WHERE pid <> pg_backend_pid( ) AND datname = 'broker_beta'"
	docker exec -it c-postgres psql -U app -d postgres -c "DROP DATABASE broker_beta;"
	docker exec -it c-postgres psql -U app -d postgres -c "CREATE DATABASE broker_beta;"
	cat backup.sql | docker exec -i c-postgres psql -U app -d broker_beta
	docker-compose \
		-f docker/compose_beta.yaml \
	down --rmi all -v
	docker-compose \
		-f docker/compose_beta.yaml \
	up -d
	docker exec -it c-broker-beta composer install