up:
	docker compose up -d

down:
	docker compose down

composer-install:
	docker run --rm --interactive --user 1000:1000 --tty --volume ${PWD}:/app composer install

dev-db-create:
	docker compose exec php php bin/console doctrine:database:create --env=dev --if-not-exists

dev-db-schema-update:
	docker compose exec php php bin/console doctrine:schema:update --force --env=dev

clear:
	docker compose exec php php bin/console cache:clear

run-unit-tests:
	docker compose exec php vendor/phpunit/phpunit/phpunit

init: up composer-install dev-db-create dev-db-schema-update clear run-unit-tests
