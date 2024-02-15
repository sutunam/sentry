vendor:
	composer install

tests: vendor
	vendor/bin/phpunit

lint: vendor
	-composer run phpcs
	-composer run phpstan

composer-repo-update:
	curl -s 'https://composer.sutunam.com/m2/update?git=git@git.sutunam.com:magento2-extensions/module-web-api-logger.git'
