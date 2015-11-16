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
                url : 'http://localhost:8001/run',
                data : request
            };
            var responsePromise = $http(config);
            responsePromise.success(function(data) {
                var result = data[0];
                if (data !== 'nofiles') {
                    Vpu.renderSuite("#result", result);
                }
            });
        });

    });

    responsePromise.error(function(data, status, headers, config) {
        console.log('failur');
        console.log(data);
    });

    Vpu.addFilterEvents();
});

var Vpu = {
    statusNameMapping : {
        'passed' : 'Passed',
        'failed' : 'Failed',
        'skipped' : 'Skipped',
        'notImplemented' : 'Not implemented',
        'error' : 'Error'
    },
    addFilterEvents : function() {
        var resultTypes = [ 'passed', 'failed', 'skipped', 'notImplemented',
                'error' ];
        jQuery.each(resultTypes, function(k, v) {

            $("input[name='" + v + "']").change(function() {
                $(".vpu-" + v).parent().toggle();
            });
        });
    },
    applyStatusFilter : function() {
        var resultTypes = [ 'passed', 'failed', 'skipped', 'notImplemented',
                'error' ];
        jQuery.each(resultTypes, function(k, v) {
            if ($("input[name='" + v + "']:checked").length > 0) {
                $(".vpu-" + v).parent().toggle();
            }
        });
    },
    renderTest : function(test) {
        var self = this;
        return '<div class="panel panel-default">' + '<div class="vpu-'
                + test['status'] + ' panel-heading">' + test['friendly-name']
                + ' <span class="text-muted small">( ' + test['class'] + '::'
                + test['name'] + ' )</span>'
                + '<span class="pull-right label label-default">'
                + self.statusNameMapping[test['status']] + '</span></div>'
                + self.rewriteErrorMessage(test);
    },
    renderSuite : function(parentNode, result) {

        var self = this;
        result['passedPercentage'] = self.getPercentage(result['passed'],
                result['total'])
                + '%';
        result['failedPercentage'] = self.getPercentage(result['failed'],
                result['total'])
                + '%';
        result['skippedPercentage'] = self.getPercentage(result['skipped'],
                result['total'])
                + '%';
        result['notImplementedPercentage'] = self.getPercentage(
                result['notImplemented'], result['total'])
                + '%';
        result['errorPercentage'] = self.getPercentage(result['error'],
                result['total'])
                + '%';

        $(parentNode).empty();
        $(parentNode).append(
                '<p class="text-muted">Completed in ' + result['time']
                        + ' seconds!</p>');
        $(parentNode)
                .append(
                        '<div class="progress">'
                                + '<div class="progress-bar progress-bar-success" title="Passed ('
                                + result['passedPercentage']
                                + ')" style="width:'
                                + result['passedPercentage']
                                + '"><span class="sr-only">'
                                + result['passedPercentage']
                                + '</span></div>'
                                + '<div class="progress-bar progress-bar-danger" title="Failed ('
                                + result['failedPercentage']
                                + ')" style="width:'
                                + result['failedPercentage']
                                + '">'
                                + '<span class="sr-only">'
                                + result['failedPercentage']
                                + '</span></div>'
                                + '<div class="progress-bar progress-bar-info progress-bar" title="Skipped ('
                                + result['skippedPercentage']
                                + ')" style="width:'
                                + result['skippedPercentage']
                                + '"><span class="sr-only">'
                                + result['skippedPercentage']
                                + '</span></div>'
                                + '<div class="progress-bar progress-bar-warning progress-bar" title="Not implemented ('
                                + result['notImplementedPercentage']
                                + ')" style="width:'
                                + result['notImplementedPercentage']
                                + '">'
                                + '<span class="sr-only">'
                                + result['notImplementedPercentage']
                                + '</span>'
                                + '</div>'
                                + '<div class="progress-bar progress-bar-error" title="Error ('
                                + result['errorPercentage']
                                + ')" style="width:'
                                + result['errorPercentage']
                                + '">'
                                + '<span class="sr-only">'
                                + result['errorPercentage']
                                + '</span>'
                                + '</div>' + '</div>');

        jQuery.each(result['tests'], function(k, v) {
            $(parentNode).append(self.renderTest(v));
        });

    },
    rewriteErrorMessage : function(test) {
        if (test['status'] !== 'passed') {
            if (test['message'] === 'Failed asserting that two strings are equal.'
                    && test['expected'] !== '' && test['actual'] !== '') {
                test['message'] = 'Failed asserting that two strings are equal. \'<var>'
                        + test['expected']
                        + '</var>\' was expected, but the actual value was \'<var>'
                        + test['actual'] + '</var>\'.';
            }
            return '<div class="panel-body"><samp>' + test['message']
                    + '</samp></div></div>';
        }
        return '';
    },
    getPercentage : function(val1, val2) {
        return Math.floor(((val1 / val2) * 100));
    }
};
