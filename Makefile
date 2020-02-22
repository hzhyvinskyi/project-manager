up: docker-up
init: docker-down-clear docker-pull docker-build docker-up app-init
test: app-test

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

app-init: app-composer-install

app-composer-install:
	docker-compose run --rm php-cli composer install

app-test:
	docker-compose run --rm php-cli php bin/phpunit

build-production:
	docker build --pull --file=app/docker/production/nginx.docker --tag ${REGISTRY_ADDRESS}/nginx:${IMAGE_TAG} app
	docker build --pull --file=app/docker/production/php-cli.docker --tag ${REGISTRY_ADDRESS}/php-cli:${IMAGE_TAG} app
	docker build --pull --file=app/docker/production/php-fpm.docker --tag ${REGISTRY_ADDRESS}/php-fpm:${IMAGE_TAG} app

push-production:
	docker push ${REGISTRY_ADDRESS}/nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/php-cli:${IMAGE_TAG}

deploy-production:
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'rm -rf docker-compose .yml .env'
	scp -P ${PRODUCTION_PORT} docker-compose-production.yml ${PRODUCTION_HOST}:docker-compose.yml
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "REGISTRY_ADDRESS=${REGISTRY_ADDRESS}" >> .env'
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose pull'
	ssh ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose --build -d'
