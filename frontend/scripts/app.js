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
    'ngSanitize'
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
      .when('/graphs', {
    	  templateUrl: 'views/graphs.html',
    	  controller: 'GraphsCtrl',
    	  controllerAs: 'graphs'
      })
      .when('/help', {
    	  templateUrl: 'views/help.html',
    	  controller: 'HelpCtrl',
    	  controllerAs: 'help'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
