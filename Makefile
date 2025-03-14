#Makefile

install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR12 tests src bin 
test:
	composer run-script phpunit tests