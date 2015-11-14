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
			var selected = $('#tree').treeview(true).getSelected();
			var files = [];
			jQuery.each(selected, function(k, v) {
				console.log(v.path);
				files.push(v.path);
			});
			var config = {
				method : 'POST',
				url : 'http://localhost:8001/run',
				data : files
			};
			var responsePromise = $http(config);
			responsePromise.success(function(data, status, headers, config) {
				var data = data[0];
				console.log(data);
				if (data != 'nofiles') {
					$("#result").empty();
					$("#result").append('<p class="text-muted">Completed in '+ data['time'] +' seconds!</p>');
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
					  '<div class="progress-bar progress-bar-warning progress-bar" title="Not implemented ('+getPercentage(data['notImplemented'], data['total'])+'%)" style="width: '+getPercentage(data['notImplemented'], data['total'])+'%">'+
					    '<span class="sr-only">'+getPercentage(data['notImplemented'], data['total'])+'%</span>'+
					  '</div>'+
					  '<div class="progress-bar progress-bar-error" title="Error ('+getPercentage(data['error'], data['total'])+'%)" style="width: '+getPercentage(data['error'], data['total'])+'%">'+
					  '<span class="sr-only">'+getPercentage(data['error'], data['total'])+'%</span>'+
					  '</div>'+
					'</div>');
					
					jQuery.each(data['tests'], function(k, v) {
						$("#result").append(renderTest(v));
					});
				    $('[data-toggle="popover"]').popover()
				}
			});
		});
	});

	responsePromise.error(function(data, status, headers, config) {
		console.log('failur');
		console.log(data);
	});

});

function renderTest(test) {
	var label = 'default';
	var text = 'default';
	var message = test['message'];
	var body = '';
	if (test.status == 'passed') {
		label = 'success';
		text = 'Passed';
	}
	if (test.status == 'failed') {
		label = 'danger';
		text = 'Failed';
	}
	if (test.status == 'skipped') {
		label = 'default';
		text = 'Skipped';
	}
	if (test.status == 'notImplemented') {
		label = 'default';
		text = 'Not implemented';
	}
	if (test.status == 'error') {
		label = 'danger';
		text = 'Error';
	}
	
	if (test['message'] == 'Failed asserting that two strings are equal.') {
		if (test['expected'] != '' && test['actual'] != '') {
			 message = 'Failed asserting that two strings are equal. \'<var>' + test['expected'] +'</var>\' was expected, but the actual value was \'<var>' + test['actual'] +'</var>\'.';
		}
	}
	
	if (test.status != 'passed') {
		body = '<div class="panel-body"><samp>' + message + '</samp></div></div>';
	}
	return '<div class="panel panel-default">'
	+'<div class="vpu-' + test.status + ' panel-heading">'+ 
	test['friendly-name'] +
	' <span class="text-muted small">( ' + test['class'] +'::'+ test['name'] +' )</span>' +
	'<span class="pull-right label label-default">'+ text +'</span></div>'+ body;
}

function getPercentage(val1, val2) {
	return Math.floor(((val1 / val2) * 100));
}




