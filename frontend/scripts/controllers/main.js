'use strict';

/**
 * @ngdoc function
 * @name VisualPHPUnit.controller:MainCtrl
 * @description # MainCtrl Controller of the VisualPHPUnit
 */
angular.module('VisualPHPUnit').controller('MainCtrl', function($scope, $http) {

	var path = '/Users/jsf/Web/VisualPHPUnit/app/test';
	var config = {
		method : 'GET',
		url : 'http://localhost:8001/file-list',
		params : {
			dir : path
		}
	};
	var responsePromise = $http(config);
	responsePromise.success(function(data, status, headers, config) {
		$('#tree').treeview({
			data : data,
			levels : 1,
			showTags :true,
			multiSelect : true,
			expandIcon : 'glyphicon glyphicon-folder-close',
			collapseIcon : 'glyphicon glyphicon-folder-open',
			emptyIcon : 'glyphicon glyphicon-file'
		});
		$('#run').on('click', function(event, data) {
			console.log($('#tree').treeview(true).getSelected());
			// check options
			// do ajax to vpu
			// present result
		});
	});

	responsePromise.error(function(data, status, headers, config) {
		console.log('failur');
		console.log(data);
	});

});
