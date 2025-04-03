.PHONY: tools autofix build build-backend build-frontend doc sonar

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
	-@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./phpcbf.phar
	-@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./phpcs.phar
	-@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./php-cs-fixer.phar fix ./src
	-@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./php-cs-fixer.phar fix ./bin

setup:
	@docker run --rm -v ${PWD}:/data -w /data --user $(shell id -u):$(shell id -g) composer:1.6.3 composer install
	@docker run -dt --name javascript -v ${PWD}:/data -w /data node:9.9.0-alpine
	@docker exec javascript npm install
	@docker exec javascript apk update
	@docker exec javascript apk add git
	@docker exec javascript ./node_modules/bower/bin/bower install --allow-root
	@docker stop javascript
	@docker rm javascript

clean:
	if [ -e ./build ]; then rm -rf ./build ; fi
	if [ -e ./dist ]; then rm -rf ./dist ; fi
	if [ -e ./docs ]; then rm -rf ./docs ; fi

tools:
	if [ ! -e phpmd.phar ];        then wget -O ./phpmd.phar --no-check-certificate https://phpmd.org/static/latest/phpmd.phar; fi
	if [ ! -e phploc.phar ];       then wget -O ./phploc.phar --no-check-certificate https://phar.phpunit.de/phploc.pharr; fi
	if [ ! -e phpdox.phar ];       then wget -O ./phpdox.phar --no-check-certificate https://github.com/theseer/phpdox/releases/download/0.12.0/phpdox-0.12.0.phar; fi
	if [ ! -e phpcs.phar ];        then wget -O ./phpcs.phar --no-check-certificate https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.7.2/phpcs.phar; fi
	if [ ! -e phpcbf.phar ];       then wget -O ./phpcbf.phar --no-check-certificate https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.7.2/phpcbf.phar; fi
	if [ ! -e php-cs-fixer.phar ]; then wget -O ./php-cs-fixer.phar --no-check-certificate https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/releases/download/v3.75.0/php-cs-fixer.phar; fi
	chmod 755 ./*.phar

build: build-backend build-frontend

build-frontend:
	if [ -e ./dist ]; then rm -rf ./dist ; fi
	@docker run -dt --name javascript -v ${PWD}:/data -w /data node:9.9.0-alpine
	@docker exec javascript apk update
	@docker exec javascript apk add git
	@docker exec javascript ./node_modules/grunt-cli/bin/grunt build --force
	@docker stop javascript
	@docker rm javascript

build-backend:
	if [ -e ./build ]; then rm -rf ./build ; fi
	rm -rf ./build
	@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./phpcs.phar
	@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./bin/phpunit --testdox -c ./phpunit.xml.dist
	#@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./phpmd.phar ./src text ./pmd.xml

doc: build-backend
	if [ -e ./docs ]; then rm -rf ./docs ; fi
	-@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./phpmd.phar ./src xml ./pmd.xml  > ./build/phpmd.xml
	@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./phpcs.phar --report=xml --report-file=./build/phpcs.xml
	@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./phploc.phar --log-xml=./build/phploc.xml .
	@docker run --rm -v ${PWD}:/data -w /data php:7.1-cli ./phpdox.phar

default: autofix
