'use strict';

/**
 * @ngdoc function
 * @name VisualPHPUnit.controller:MainCtrl
 * @description # MainCtrl Controller of the VisualPHPUnit
 */
angular.module('VisualPHPUnit').controller('MainCtrl', function($scope, $http) {

	var config = {
		method : 'GET',
		url : 'http://localhost:8001/tests'
	};
	var responsePromise = $http(config);
	responsePromise.success(function(data, status, headers, config) {
		$('#tree').treeview({
			data : data,
			levels : 1,
			showTags : true,
			multiSelect : true,
			expandIcon : 'glyphicon glyphicon-folder-close',
			collapseIcon : 'glyphicon glyphicon-folder-open',
			emptyIcon : 'glyphicon glyphicon-file'
		});
		$('#run').on('click', function(event, data) {
			//console.log($('#tree').treeview(true).getSelected());
			var selected = $('#tree').treeview(true).getSelected();
			var files = [];
			jQuery.each(selected, function(k, v) {
				console.log(v.path);
				files.push(v.path);
			});
			var config = {
				method : 'POST',
				url : 'http://localhost:8001/run',
				params : {
				          files : files
			}
			};
			var responsePromise = $http(config);
			responsePromise.success(function(data, status, headers, config) {
				var data = data[0];
				console.log(data);
				$("#result").empty();
				
				
				$("#result").append('<div class="progress">'+
				  '<div class="progress-bar progress-bar-success" title="Passed ('+getPercentage(data['passed'], data['total'])+'%)" style="width: '+getPercentage(data['passed'], data['total'])+'%">'+
				    '<span class="sr-only">'+getPercentage(data['passed'], data['total'])+'%</span>'+
				  '</div>'+
				  '<div class="progress-bar progress-bar-danger" title="Failed ('+getPercentage(data['failed'], data['total'])+'%)" style="width: '+getPercentage(data['failed'], data['total'])+'%">'+
				  '<span class="sr-only">'+getPercentage(data['failed'], data['total'])+'%</span>'+
				  '</div>'+
				  '<div class="progress-bar progress-bar-info progress-bar" title="Skipped ('+getPercentage(data['skipped'], data['total'])+'%)" style="width: '+getPercentage(data['skipped'], data['total'])+'%">'+
				    '<span class="sr-only">'+getPercentage(data['skipped'], data['total'])+'%</span>'+
				  '</div>'+
				  '<div class="progress-bar progress-bar-primary progress-bar" title="Not implemented ('+getPercentage(data['notImplemented'], data['total'])+'%)" style="width: '+getPercentage(data['notImplemented'], data['total'])+'%">'+
				    '<span class="sr-only">'+getPercentage(data['notImplemented'], data['total'])+'%</span>'+
				  '</div>'+
				  '<div class="progress-bar progress-bar-warning" title="Error ('+getPercentage(data['error'], data['total'])+'%)" style="width: '+getPercentage(data['error'], data['total'])+'%">'+
				  '<span class="sr-only">'+getPercentage(data['error'], data['total'])+'%</span>'+
				  '</div>'+
				'</div>');
				jQuery.each(data['tests'], function(k, v) {
					if (v.status == 'passed') {
						$("#result").append('<div class="alert alert-success" role="alert">'+ formatTitle(v)+'</div>');
					}
					if (v.status == 'failed') {
						$("#result").append('<div class="alert alert-danger" role="alert">'+ formatTitle(v)+expected(v)+formatInfo(v)+'</div>');
					}
					if (v.status == 'skipped') {
						$("#result").append('<div class="alert alert-info" role="alert">'+ formatTitle(v)+formatInfo(v)+'</div>');
					}
					if (v.status == 'notImplemented') {
						$("#result").append('<div class="alert alert-warning" role="alert">'+ formatTitle(v)+formatInfo(v)+'</div>');
					}
					if (v.status == 'error') {
						$("#result").append('<div class="alert alert-warning" role="alert">'+ formatTitle(v)+formatInfo(v)+'</div>');
					}
				});
			    $('[data-toggle="popover"]').popover()
			});
		});
	});

	responsePromise.error(function(data, status, headers, config) {
		console.log('failur');
		console.log(data);
	});

});

function formatTitle(test) {
	return test['friendly-name'] + ' <span class="text-muted small">(' + test['class'] +'::'+ test['name'] +')</span>';
}

function formatInfo(test) {
	return '<button type="button" class="btn pull-right" data-placement="left" data-toggle="popover" title="Info" data-content="'+ test['message'] +'">More info</button>';
}

function expected(test) {
	if (test['expected'] != '' && test['actual'] != '') {
		return '<span class="text-muted small">[' + test['expected'] +'::'+ test['actual'] +']</span>';
	}
}



function getPercentage(val1, val2) {
	return parseInt((val1 / val2) * 100);
}




