#Makefile

install:
	composer install

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

gendiff:
	./bin/gendiff -h

stan:
	composer exec --verbose -- vendor/bin/phpstan analyse -l 6 ./src/

test:
	composer exec -v phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover tests/build/logs/clover.xml