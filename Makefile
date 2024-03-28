vendor:
	composer install

lint: vendor
	-composer run phpcs
	-composer run phpstan

composer-repo-update:
	curl -s 'https://composer.sutunam.com/m2/update?git=git@git.sutunam.com:magento2-extensions/sutunam-sentry.git'
