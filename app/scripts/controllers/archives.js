'use strict';

/**
 * @ngdoc function
 * @name VisualPHPUnit.controller:ArchivesCtrl
 * @description # ArchivesCtrl Controller of the VisualPHPUnit
 */
angular.module('VisualPHPUnit').controller('ArchivesCtrl',
        function($scope, $http) {
            var config = {
                method : 'GET',
                url : Vpu.getBackend() + '/archives'
            };
            var responsePromise = $http(config);
            responsePromise.success(function(data) {
                $('#archives').treeview({
                    data : data,
                    levels : 1,
                    showTags : false,
                    multiSelect : false,
                    expandIcon : 'glyphicon glyphicon-folder-close',
                    collapseIcon : 'glyphicon glyphicon-folder-open',
                    emptyIcon : 'glyphicon glyphicon-th-list'
                });
                $('#show').on('click', function() {
                    var selected = $('#archives').treeview(true).getSelected();
                    if (typeof selected[0] !== 'undefined') {
                        var id = selected[0]['id'];
                        var config = {
                            method : 'GET',
                            url : Vpu.getBackend() + '/suite/' + id
                        };
                        var responsePromise = $http(config);
                        responsePromise.success(function(data) {
                            if (data !== 'nosuite') {
                                Vpu.renderSuite("#suite", data);
                            }
                            Vpu.applyStatusFilter();
                        });
                    }
                });
            });
            Vpu.addFilterEvents();

        });
