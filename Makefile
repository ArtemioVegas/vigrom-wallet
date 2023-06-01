up: docker-up
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up app-init
test-docker: app-test-docker

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

app-init: app-composer-install app-migrations app-fixtures

app-composer-install:
	docker-compose run --rm app-php-cli composer install

cli:
	docker-compose run --rm app-php-cli php bin/console

app-migrations:
	docker-compose run --rm app-php-cli php bin/console doctrine:migrations:migrate --no-interaction

app-fixtures:
	docker-compose run --rm app-php-cli php bin/console doctrine:fixtures:load --no-interaction

app-test-docker:
	docker-compose run --rm app-php-cli php bin/phpunit
