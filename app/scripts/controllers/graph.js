'use strict';

/**
 * @ngdoc function
 * @name VisualPHPUnit.controller:GraphCtrl
 * @description # GraphCtrl Controller of the VisualPHPUnit
 */
angular.module('VisualPHPUnit').controller('GraphCtrl', function($scope, $http) {
    $scope.today = function() {
        $scope.dt1 = new Date();
        $scope.dt2 = new Date();
    };
    $scope.today();

    $scope.clear = function() {
        $scope.dt1 = null;
        $scope.dt2 = null;
    };

    $scope.open1 = function() {
        $scope.status1.opened = true;
    };

    $scope.open2 = function() {
        $scope.status2.opened = true;
    };

    $scope.status1 = {
        opened : false
    };

    $scope.status2 = {
        opened : false
    };

    Chart.defaults.global.responsive = true;
    Chart.defaults.global.maintainAspectRatio = false;
    var options = {
        scaleShowGridLines : true,
        scaleGridLineColor : "rgba(0,0,0,.05)",
        scaleGridLineWidth : 1,
        scaleShowHorizontalLines : true,
        scaleShowVerticalLines : true,
        bezierCurve : true,
        bezierCurveTension : 0.4,
        pointDot : true,
        pointDotRadius : 4,
        pointDotStrokeWidth : 1,
        pointHitDetectionRadius : 20,
        datasetStroke : true,
        datasetStrokeWidth : 2,
        datasetFill : true,
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
    };

    $('#draw').on(
            'click',
            function() {
                $('#chart').remove();
                $('#chartContainer').append('<canvas id="chart"><canvas>');
                var ctx = $("#chart").get(0).getContext("2d");
                var timeframe = $('input:radio[name=timeframe]:checked').val();
                var start = '' + $scope.dt1.getFullYear() + '-' + ($scope.dt1.getMonth() + 1) + '-'
                        + $scope.dt1.getDate();
                var end = '' + $scope.dt2.getFullYear() + '-' + ($scope.dt2.getMonth() + 1) + '-'
                        + $scope.dt2.getDate();

                var config = {
                    method : 'GET',
                    url : Vpu.getBackend() +'/graph/' + timeframe + '/' + start + '/' + end
                };
                var responsePromise = $http(config);
                responsePromise.success(function(data) {

                    var forgraph = prepareData(data);

                    var graphdata = {
                        labels : forgraph['label'],

                        datasets : [
                                createDataSet("Passed", "rgba(92,184,92,1)", forgraph['passed']),
                                createDataSet("Failed", "rgba(217,83,79,1)", forgraph['failed']),
                                createDataSet("Skipped", "rgba(91,192,222,1)", forgraph['skipped']),
                                createDataSet("Not Implemented", "rgba(240,173,78,1)",
                                        forgraph['notImplemented']),
                                createDataSet("Error", "rgba(51,122,183,1)", forgraph['error']) ]
                    };
                    new Chart(ctx).Line(graphdata, options);
                });

            });
});

/**
 * Create a data set to use in the Chart
 * 
 * @param label
 *                The label for the data set
 * @param color
 *                The RGBA color for the data set
 * @param data
 *                The data
 * @returns Object
 */
function createDataSet(label, color, data) {
    return {
        label : label,
        fillColor : "rgba(220,220,220,0.0)",
        strokeColor : color,
        pointColor : color,
        pointStrokeColor : "#fff",
        pointHighlightFill : "#fff",
        pointHighlightStroke : "rgba(151,187,205,1)",
        data : data
    };
}

/**
 * Distribute test results on the selected time periode
 * 
 * @param Object
 *                data
 * @returns Object
 */
function prepareData(data) {

    var label = [];
    var error = [];
    var skipped = [];
    var notImplemented = [];
    var failed = [];
    var passed = [];

    for ( var key1 in data['error']) {
        label.push(key1);
    }
    for ( var key2 in data['error']) {
        error.push(parseInt(data['error'][key2]));
    }
    for ( var key3 in data['skipped']) {
        skipped.push(parseInt(data['skipped'][key3]));
    }
    for ( var key4 in data['notImplemented']) {
        notImplemented.push(parseInt(data['notImplemented'][key4]));
    }
    for ( var key5 in data['failed']) {
        failed.push(parseInt(data['failed'][key5]));
    }
    for ( var key6 in data['passed']) {
        passed.push(parseInt(data['passed'][key6]));
    }
    return {
        label : label,
        error : error,
        notImplemented : notImplemented,
        skipped : skipped,
        failed : failed,
        passed : passed
    };
}
