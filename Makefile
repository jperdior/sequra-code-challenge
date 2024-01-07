export PROJECT_NAME := sequra-challenge
export CURRENT_PATH := $(shell pwd)
export BACKEND_CONTAINER := backend

DOCKER_COMPOSE=docker-compose -p ${PROJECT_NAME} -f ${CURRENT_PATH}/ops/docker/docker-compose.yml -f ${CURRENT_PATH}/ops/docker/docker-compose.dev.yml

restart: stop start

start: docker-build docker-up

stop:
	@${DOCKER_COMPOSE} down --remove-orphans

docker-build:
	@${DOCKER_COMPOSE} build #--no-cache

docker-up:
	@${DOCKER_COMPOSE} up -d

logs:
	@${DOCKER_COMPOSE} logs -f

#COMPOSER

composer-require-backend:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} composer require ${PACKAGE}

composer-remove-backend:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} composer remove ${PACKAGE}

#MIGRATION

create-migrations:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console doctrine:migrations:diff

execute-migrations:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console doctrine:migrations:migrate

execute-migration:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console doctrine:migrations:migrate ${VERSION}

drop-database:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console doctrine:database:drop --force

create-database:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console doctrine:database:create

#FIXTURES

load-fixtures:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console doctrine:fixtures:load --no-debug

#TEST

tests:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php vendor/bin/phpunit --testdox --log-junit junit.xml

test:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php vendor/bin/phpunit --testdox --log-junit junit.xml --filter ${TEST}

behat:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php vendor/bin/behat --format=progress

behat-developing:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php vendor/bin/behat --format=progress --tags=developing

#CODESTYLE

fix-codestyle-dry:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php vendor/bin/php-cs-fixer fix --dry-run --diff

fix-codestyle:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php vendor/bin/php-cs-fixer fix

#PHPSTAN

phpstan:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php vendor/bin/phpstan analyse src

#CONSUME ORDER

run:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} supervisord -c ops/supervisor/supervisor.conf

enqueue-orders:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console app:enqueue-orders /app/src/DataFixtures/orders.csv

consume-orders:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console messenger:consume purchase_created

consume-disbursement-lines:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console messenger:consume disbursement_line_created

consume-commands:
	@${DOCKER_COMPOSE} exec ${BACKEND_CONTAINER} php bin/console messenger:consume async

#OPEN
open-rabbitmq-manager:
	open http://localhost:15672