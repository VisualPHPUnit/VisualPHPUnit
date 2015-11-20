[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VisualPHPUnit/VisualPHPUnit/badges/quality-score.png?b=3.x)]
(https://scrutinizer-ci.com/g/VisualPHPUnit/VisualPHPUnit/?branch=3.x)
[![Build Status](https://travis-ci.org/VisualPHPUnit/VisualPHPUnit.svg)](https://travis-ci.org/VisualPHPUnit/VisualPHPUnit)
[![Dependency Status](https://www.versioneye.com/user/projects/55f547b3a4155f00090005b5/badge.svg?style=flat)](https://www.versioneye.com/user/projects/55f547b3a4155f00090005b5)
[![Project Stats](https://www.openhub.net/p/VisualPHPUnit/widgets/project_thin_badge.gif)](https://www.openhub.net/p/VisualPHPUnit)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg?style=flat-square)](https://php.net/)
[![codecov.io](http://codecov.io/github/VisualPHPUnit/VisualPHPUnit/coverage.svg?branch=3.x)](http://codecov.io/github/VisualPHPUnit/VisualPHPUnit?branch=3.x)


# VisualPHPUnit

VisualPHPUnit is a visual front-end for PHPUnit. Current stable release is [v2.3.1](https://github.com/VisualPHPUnit/VisualPHPUnit/releases/tag/v2.3.1)

## Versions

* [1.x](https://github.com/VisualPHPUnit/VisualPHPUnit/tree/1.x) Initial concept and code by Matt Mueller
* [2.x](https://github.com/VisualPHPUnit/VisualPHPUnit/tree/2.x) A complete rewrite by Nick Sinopoli
* [3.x.*](https://github.com/VisualPHPUnit/VisualPHPUnit/tree/3.x) A complete rewrite by Johannes Skov Frandsen

## Development

### Frontend
The frontend is a single-page javascript application (SPA) based on angularjs and bootstrap.

#### Framework
* [Angularjs](https://angularjs.org/)
* [Bootstrap](http://getbootstrap.com/)

#### Required tools
* [Node.js](https://nodejs.org/)
* [Npm](https://www.npmjs.com/)
* [Grunt](http://gruntjs.com/)
* [Bower](http://bower.io/) 

### Backend
The backend is a REST application based on Silex.

#### Framework

* [Silex](http://silex.sensiolabs.org/)


#### Required tools

* [php](http://php.net/) >=5.6
* [Composer](https://getcomposer.org/)


## Running VPU in dev mode

### Setup dependencies
```
#!Bash	
git clone git@github.com:VisualPHPUnit/VisualPHPUnit.git
git checkout 3.x
cd backend
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install
npm install
bower install
```

## Start VPU in dev mode
You need two consoles for this setup, one for the frontend and one for the backend.
```
#!Bash
#backend
cd backend
php -S localhost:8000
#frontend
cd ../frontend
grunt serve
```

## Running VPU at the Command Line

VPU can be run at the command line, making it possible to automate the generation of test results via cron.

You may append --help for options

### Usage

```bash
# from the project root
bin/vpu
```

You may read more about VisualPHPUnit [here](http://visualphpunit.github.io/VisualPHPUnit/) or in the [wiki](https://github.com/VisualPHPUnit/VisualPHPUnit/wiki).
