'use strict';

/**
 * @ngdoc function
 * @name VisualPHPUnit.controller:MainCtrl
 * @description # MainCtrl Controller of the VisualPHPUnit
 */
angular.module('VisualPHPUnit').controller('MainCtrl', function($scope, $http) {
    var config = {
        method : 'GET',
        url : Vpu.getBackend() + '/tests'
    };
    var responsePromise = $http(config);
    responsePromise.success(function(data) {
        $('#tree').treeview({
            data : data,
            levels : 1,
            showTags : true,
            multiSelect : true,
            expandIcon : 'glyphicon glyphicon-folder-close',
            collapseIcon : 'glyphicon glyphicon-folder-open',
            emptyIcon : 'glyphicon glyphicon-file'
        });
        $('#run').on('click', function() {
            var selected = $('#tree').treeview(true).getSelected();
            var files = [];
            var request;
            jQuery.each(selected, function(k, v) {
                files.push(v.path);
            });
            request = {
                config : {
                    snapshot : $("input[name='snapshot']").is(':checked')
                },
                files : files
            };

            var config = {
                method : 'POST',
                url : Vpu.getBackend() + '/run',
                data : request
            };
            var responsePromise = $http(config);
            responsePromise.success(function(data) {
                var result = data[0];
                if (data != 'nofiles') {
                    Vpu.renderSuite("#result", result);
                }
            });
        });

    });

    responsePromise.error(function(data) {
        console.log('failur');
        console.log(data);
    });

    Vpu.addFilterEvents();
});