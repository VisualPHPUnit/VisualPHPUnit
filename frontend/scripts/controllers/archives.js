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
			$('#archives').treeview({
				data : data,
				levels : 1,
				showTags : false,
				multiSelect : false,
				expandIcon : 'glyphicon glyphicon-folder-close',
				collapseIcon : 'glyphicon glyphicon-folder-open',
				emptyIcon : 'glyphicon glyphicon-th-list'
			});
			$('#show').on('click', function(event, data) {
				var selected = $('#archives').treeview(true).getSelected();	
				if (typeof selected[0] != 'undefined') {
					var id = selected[0]['id'];
					var config = {
							method : 'GET',
							url : 'http://localhost:8001/suite/'+ id,
						};
						var responsePromise = $http(config);
						responsePromise.success(function(data, status, headers, config) {
							if (data != 'nosuite') {
								renderTestSuite('#suite', data);
							}
						});
					}			
			});
		});
		toggleTestsResults();
});
