install:
	composer install --prefer-dist --no-progress --no-suggest --optimize-autoloader
database:
	php bin/console doctrine:database:create --if-not-exists --no-interaction
	php bin/console doctrine:migration:migrate --no-interaction
fixtures:
	php bin/console hautelook:fixtures:load
test:
	php bin/phpunit
cs-check:
	php-cs-fixer fix --dry-run --using-cache=no --verbose --diff
cs-fix:
	php-cs-fixer fix --using-cache=no --verbose --diff
cs:
	./vendor/squizlabs/php_codesniffer/bin/phpcs --standard=phpcs.xml.dist -n -p
