'use strict';

/**
 * @ngdoc overview
 * @name VisualPHPUnit
 * @description
 * # VisualPHPUnit
 *
 * Main module of the application.
 */
angular
  .module('VisualPHPUnit', [
    'ngAnimate',
    'ngAria',
    'ngCookies',
    'ngMessages',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ui.bootstrap'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl',
        controllerAs: 'main'
      })
      .when('/archives', {
        templateUrl: 'views/archives.html',
        controller: 'ArchivesCtrl',
        controllerAs: 'archives'
      })
      .when('/graph', {
    	  templateUrl: 'views/graph.html',
    	  controller: 'GraphCtrl',
    	  controllerAs: 'graph'
      })
      .when('/about', {
    	  templateUrl: 'views/about.html',
    	  controller: 'AboutCtrl',
    	  controllerAs: 'about'
      })
      .when('/config', {
    	  templateUrl: 'views/config.html',
    	  controller: 'ConfigCtrl',
    	  controllerAs: 'config'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
