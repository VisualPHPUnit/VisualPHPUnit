.PHONY: tools autofix build

autofix:
	find . -name '*.php'  -path ./vendor -prune | xargs dos2unix
	find . -name '*.sh'   -path ./vendor -prune | xargs dos2unix
	find . -name '*.json' -path ./vendor -prune | xargs dos2unix
	find . -name '*.md'   -path ./vendor -prune | xargs dos2unix
	find . -name '*.xml'  -path ./vendor -prune | xargs dos2unix
	find . -name '*.php'  -path ./vendor -prune | xargs expand
	find . -name '*.sh'   -path ./vendor -prune | xargs expand
	find . -name '*.json' -path ./vendor -prune | xargs expand
	find . -name '*.md'   -path ./vendor -prune | xargs expand
	find . -name '*.xml'  -path ./vendor -prune | xargs expand
	php phpcbf.phar
	php phpcs.phar
	php php-cs-fixer.phar fix ./src
	php php-cs-fixer.phar fix ./bin

setup:
	php composer.phar self-update
	php composer.phar update
	npm install
	ln -sf ./node_modules/grunt-cli/bin/grunt grunt
	ln -sf ./node_modules/bower/bin/bower
	./bower install

tools:
	if [ ! -e composer.phar ];      then wget -O ./composer.phar --no-check-certificate https://getcomposer.org/download/1.2.0/composer.phar; fi
#	if [ ! -e phpDocumentor.phar ]; then wget -O ./phpDocumentor.phar --no-check-certificate https://www.phpdoc.org/phpDocumentor.phar; fi
	if [ ! -e phpcs.phar ];         then wget -O ./phpcs.phar --no-check-certificate https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar; fi
	if [ ! -e phpcbf.phar ];        then wget -O ./phpcbf.phar --no-check-certificate https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar; fi
	if [ ! -e phpunit.phar ];       then wget -O ./phpunit.phar --no-check-certificate https://phar.phpunit.de/phpunit.phar; fi
	if [ ! -e php-cs-fixer.phar ];  then wget -O ./php-cs-fixer.phar --no-check-certificate https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v1.11.6/php-cs-fixer.phar; fi
	chmod 755 ./*.phar
	
build:
	rm -rf ./build
	php ./phpcs.phar
	php ./phpunit.phar --testdox -c ./phpunit.xml.dist
	./grunt build

default: autofix

