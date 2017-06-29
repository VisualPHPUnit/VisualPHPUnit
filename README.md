[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VisualPHPUnit/VisualPHPUnit/badges/quality-score.png)]
(https://scrutinizer-ci.com/g/VisualPHPUnit/VisualPHPUnit/)
[![Build Status](https://travis-ci.org/VisualPHPUnit/VisualPHPUnit.svg)](https://travis-ci.org/VisualPHPUnit/VisualPHPUnit)
[![Dependency Status](https://www.versioneye.com/user/projects/580f9c405fe47d001229cb99/badge.svg?style=flat)](https://www.versioneye.com/user/projects/580f9c405fe47d001229cb99)
[![Project Stats](https://www.openhub.net/p/VisualPHPUnit/widgets/project_thin_badge.gif)](https://www.openhub.net/p/VisualPHPUnit)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg?style=flat-square)](https://php.net/)
[![codecov.io](http://codecov.io/github/VisualPHPUnit/VisualPHPUnit/coverage.svg)](http://codecov.io/github/VisualPHPUnit/VisualPHPUnit)
![Version Eye](http://php-eye.com/badge/visualphpunit/visualphpunit/tested.svg)
[![PHPPackages Rank](http://phppackages.org/p/visualphpunit/visualphpunit/badge/rank.svg)](http://phppackages.org/p/visualphpunit/visualphpunit)

# VisualPHPUnit

VisualPHPUnit is a visual front-end for PHPUnit.

Version [3.1.0](https://github.com/VisualPHPUnit/VisualPHPUnit/releases/tag/v3.1.0) is the latest and greatest.
Version [2.3.2](https://github.com/VisualPHPUnit/VisualPHPUnit/releases/tag/v2.3.2) is also working just fine.

VisualPHPUnit is **not** php 7 compatible at this time due to the way phpunit is utilized. Php 7 will be supported in the next major release.

## Versions

* [1.x](https://github.com/VisualPHPUnit/VisualPHPUnit/tree/1.x) Initial concept and code by [Matt Mueller](https://github.com/matthewmueller)
* [2.x](https://github.com/VisualPHPUnit/VisualPHPUnit/tree/2.x) A complete rewrite by [Nick Sinopoli](https://github.com/NSinopoli)
* [3.x](https://github.com/VisualPHPUnit/VisualPHPUnit/tree/3.x) A complete rewrite by [Johannes Skov Frandsen](https://github.com/localgod)

## Setup

On *nix

 * Checkout or download VisualPHPUnit
 * Run `make tools setup build`

On Windows

 * Checkout or download VisualPHPUnit
 * Run `php -r "readfile('https://getcomposer.org/installer');" | php`.
 * Run `./composer.phar install` to install php dependencies
 * Run `npm install` to install node tools
 * Run `mklink grunt .\node_modules\grunt-cli\bin\grunt`
 * Run `mklink bower .\node_modules\bower\bin\bower`
 * Run `bower install` to install javascript dependencies
 * Run `grunt build` to build frontend

To run

 * Run `./bin/vpu -c vpu.json -s` to start VisualPHPUnit with the build-in php server.
 * Run `./bin/vpu -c vpu.json -t` to stop VisualPHPUnit with the build-in php server.

You may browse localhost:8000 to access the test suites.

You can update `./vpu.json` to add additional test suites.

You may run tests from the console like this `./bin/vpu -c vpu.json testfile.php`. You may append `-a` to archive your test results. You may append --help for options

## Development

### Frontend
The frontend is a single-page javascript application ([SPA](https://en.wikipedia.org/wiki/Single-page_application)) based on [Angularjs](https://angularjs.org/) and [Bootstrap](http://getbootstrap.com/). You will need [Node.js](https://nodejs.org/), [Npm](https://www.npmjs.com/), [Grunt](http://gruntjs.com/) and [Bower](http://bower.io/) to build the frontend.

### Backend
The backend is a REST application based on [Silex](http://silex.sensiolabs.org/). You will need [Composer](https://getcomposer.org/) to install relevant dependencies.You need [php](http://php.net/) >=5.6 to run VisualPHPUnit.


## Running VPU in dev mode

### Setup dependencies

On *nix

 * Checkout or download VisualPHPUnit
 * Run `make tools setup`

On Windows

 * Checkout or download VisualPHPUnit
 * Run `php -r "readfile('https://getcomposer.org/installer');" | php`.
 * Run `./composer.phar install` to install php dependencies
 * Run `npm install` to install node tools
 * Run `bower install` to install javascript dependencies


## Start VPU in dev mode
You need two consoles for this setup, one for the frontend and one for the backend.
```bash
cd backend
php -S localhost:8001
cd ../app
grunt serve
```

You may read more about VisualPHPUnit [here](http://visualphpunit.github.io/VisualPHPUnit/) or in the [wiki](https://github.com/VisualPHPUnit/VisualPHPUnit/wiki).
