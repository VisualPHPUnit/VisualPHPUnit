'use strict';

/**
 * @ngdoc function
 * @name VisualPHPUnit.controller:ArchivesCtrl
 * @description # ArchivesCtrl Controller of the VisualPHPUnit
 */
angular.module('VisualPHPUnit').controller('ArchivesCtrl', function($scope, $http) {
	var config = {
			method : 'GET',
			url : 'http://localhost:8001/archives'
		};
		var responsePromise = $http(config);
		responsePromise.success(function(data, status, headers, config) {
			$("#archives").empty();
			jQuery.each(data['snapshots'], function(k, v) {
				$("#archives").append('<button type="button" class="list-group-item">'+v.date+'</button>');
			});
		});
});
