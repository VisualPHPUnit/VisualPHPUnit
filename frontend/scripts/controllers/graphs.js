'use strict';

/**
 * @ngdoc function
 * @name VisualPHPUnit.controller:GraphsCtrl
 * @description # GraphsCtrl Controller of the VisualPHPUnit
 */
angular
        .module('VisualPHPUnit')
        .controller(
                'GraphsCtrl',
                function($scope, $http) {
                    Chart.defaults.global.responsive = true;
                    Chart.defaults.global.maintainAspectRatio = false;
                    var options = {

                        // /Boolean - Whether grid lines are shown across the
                        // chart
                        scaleShowGridLines : true,

                        // String - Colour of the grid lines
                        scaleGridLineColor : "rgba(0,0,0,.05)",

                        // Number - Width of the grid lines
                        scaleGridLineWidth : 1,

                        // Boolean - Whether to show horizontal lines (except X
                        // axis)
                        scaleShowHorizontalLines : true,

                        // Boolean - Whether to show vertical lines (except Y
                        // axis)
                        scaleShowVerticalLines : true,

                        // Boolean - Whether the line is curved between points
                        bezierCurve : true,

                        // Number - Tension of the bezier curve between points
                        bezierCurveTension : 0.4,

                        // Boolean - Whether to show a dot for each point
                        pointDot : true,

                        // Number - Radius of each point dot in pixels
                        pointDotRadius : 4,

                        // Number - Pixel width of point dot stroke
                        pointDotStrokeWidth : 1,

                        // Number - amount extra to add to the radius to cater
                        // for hit detection outside the drawn point
                        pointHitDetectionRadius : 20,

                        // Boolean - Whether to show a stroke for datasets
                        datasetStroke : true,

                        // Number - Pixel width of dataset stroke
                        datasetStrokeWidth : 2,

                        // Boolean - Whether to fill the dataset with a colour
                        datasetFill : true,

                        // String - A legend template
                        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

                    };
                    var data = {
                        labels : [ "January", "February", "March", "April",
                                "May", "June", "July" ],

                        datasets : [ {
                            label : "Passed",
                            fillColor : "rgba(220,220,220,0.0)",
                            strokeColor : "rgba(92,184,92,1)",
                            pointColor : "rgba(220,220,220,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(220,220,220,1)",
                            data : [ 10, 59, 80, 81, 56, 55, 40 ]
                        }, {
                            label : "Failed",
                            fillColor : "rgba(220,220,220,0.0)",
                            strokeColor : "rgba(217,83,79,1)",
                            pointColor : "rgba(220,220,220,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(151,187,205,1)",
                            data : [ 20, 48, 40, 19, 86, 27, 90 ]
                        }, {
                            label : "Skipped",
                            fillColor : "rgba(220,220,220,0.0)",
                            strokeColor : "rgba(91,192,222,1)",
                            pointColor : "rgba(220,220,220,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(151,187,205,1)",
                            data : [ 30, 48, 15, 19, 67, 27, 50 ]
                        }, {
                            label : "Not Implemented",
                            fillColor : "rgba(220,220,220,0.0)",
                            strokeColor : "rgba(240,173,78,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(151,187,205,1)",
                            data : [ 40, 48, 40, 10, 86, 27, 10 ]
                        }, {
                            label : "Error",
                            fillColor : "rgba(220,220,220,0.0)",
                            strokeColor : "rgba(51,122,183,1)",
                            pointColor : "rgba(151,187,205,1)",
                            pointStrokeColor : "#fff",
                            pointHighlightFill : "#fff",
                            pointHighlightStroke : "rgba(151,187,205,1)",
                            data : [ 50, 50, 42, 23, 90, 30, 93 ]
                        } ]
                    };

                    var ctx = $("#chart").get(0).getContext("2d");
                    $('#draw').on('click', function() {
                                        var config = {
                                            method : 'GET',
                                            url : 'http://localhost:8001/graphs'
                                        };
                                        var responsePromise = $http(config);
                                        responsePromise
                                                .success(function(data) {

                                                    var forgraph = prepareData(data);

                                                    var graphdata = {
                                                        labels : forgraph['label'],

                                                        datasets : [
                                                                {
                                                                    label : "Passed",
                                                                    fillColor : "rgba(220,220,220,0.0)",
                                                                    strokeColor : "rgba(92,184,92,1)",
                                                                    pointColor : "rgba(92,184,92,1)",
                                                                    pointStrokeColor : "#fff",
                                                                    pointHighlightFill : "#fff",
                                                                    pointHighlightStroke : "rgba(220,220,220,1)",
                                                                    data : forgraph['passed']
                                                                },
                                                                {
                                                                    label : "Failed",
                                                                    fillColor : "rgba(220,220,220,0.0)",
                                                                    strokeColor : "rgba(217,83,79,1)",
                                                                    pointColor : "rgba(217,83,79,1)",
                                                                    pointStrokeColor : "#fff",
                                                                    pointHighlightFill : "#fff",
                                                                    pointHighlightStroke : "rgba(151,187,205,1)",
                                                                    data : forgraph['failed']
                                                                },
                                                                {
                                                                    label : "Skipped",
                                                                    fillColor : "rgba(220,220,220,0.0)",
                                                                    strokeColor : "rgba(91,192,222,1)",
                                                                    pointColor : "rgba(91,192,222,1)",
                                                                    pointStrokeColor : "#fff",
                                                                    pointHighlightFill : "#fff",
                                                                    pointHighlightStroke : "rgba(151,187,205,1)",
                                                                    data : forgraph['skipped']
                                                                },
                                                                {
                                                                    label : "Not Implemented",
                                                                    fillColor : "rgba(220,220,220,0.0)",
                                                                    strokeColor : "rgba(240,173,78,1)",
                                                                    pointColor : "rgba(240,173,78,1)",
                                                                    pointStrokeColor : "#fff",
                                                                    pointHighlightFill : "#fff",
                                                                    pointHighlightStroke : "rgba(151,187,205,1)",
                                                                    data : forgraph['notImplemented']
                                                                },
                                                                {
                                                                    label : "Error",
                                                                    fillColor : "rgba(220,220,220,0.0)",
                                                                    strokeColor : "rgba(51,122,183,1)",
                                                                    pointColor : "rgba(51,122,183,1)",
                                                                    pointStrokeColor : "#fff",
                                                                    pointHighlightFill : "#fff",
                                                                    pointHighlightStroke : "rgba(151,187,205,1)",
                                                                    data : forgraph['error']
                                                                } ]
                                                    };

                                                    var chart = new Chart(ctx)
                                                            .Line(graphdata,
                                                                    options);

                                                });

                                    });
                });

function prepareData(data) {
    var label = [];
    var error = [];
    var skipped = [];
    var notImplemented = [];
    var failed = [];
    var passed = [];

    for ( var key in data['error']) {
        label.push(key);
    }
    for ( var key in data['error']) {
        error.push(parseInt(data['error'][key]));
    }
    for ( var key in data['skipped']) {
        skipped.push(parseInt(data['skipped'][key]));
    }
    for ( var key in data['notImplemented']) {
        notImplemented.push(parseInt(data['notImplemented'][key]));
    }
    for ( var key in data['failed']) {
        failed.push(parseInt(data['failed'][key]));
    }
    for ( var key in data['passed']) {
        passed.push(parseInt(data['passed'][key]));
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
