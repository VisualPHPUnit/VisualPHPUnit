'use strict';

var Vpu = {
	backend : {
		'protocol' : 'http',
		'host' : 'localhost',
		'port' : 8001
	},
	getBackend : function(asObject) {
		if (Cookies.get('backend') != undefined) {
			var backend = Cookies.getJSON('backend')
			if (asObject) {
				return backend;
			}
			return backend.protocol + '://' + backend.host + ':' + backend.port;	
		}
		if (asObject) {
			return Vpu.backend;
		}
		return Vpu.backend.protocol + '://' + Vpu.backend.host + ':' + Vpu.backend.port;
	},
	setBackend : function(protocol, host, port) {
		Cookies.set('backend', { protocol: protocol, host: host, port: port });
		
	},
    statusNameMapping : {
        'passed' : 'Passed',
        'failed' : 'Failed',
        'skipped' : 'Skipped',
        'notImplemented' : 'Not implemented',
        'error' : 'Error'
    },
    addFilterEvents : function() {
        var resultTypes = [ 'passed', 'failed', 'skipped', 'notImplemented', 'error' ];
        jQuery.each(resultTypes, function(k, v) {

            $("input[name='" + v + "']").change(function() {
                $(".vpu-" + v).parent().toggle();
            });
        });
    },
    applyStatusFilter : function() {
        var resultTypes = [ 'passed', 'failed', 'skipped', 'notImplemented', 'error' ];
        jQuery.each(resultTypes, function(k, v) {
            if ($("input[name='" + v + "']:checked").length > 0) {
                $(".vpu-" + v).parent().toggle();
            }
        });
    },
    renderTest : function(test) {
        var self = this;
        return '<div class="panel panel-default">' + '<div class="vpu-' + test['status'] + ' panel-heading">'
                + test['friendly-name'] + ' <span class="text-muted small">( ' + test['class'] + '::' + test['name']
                + ' )</span>' + '<span class="pull-right label label-default">'
                + self.statusNameMapping[test['status']] + '</span></div>' + self.rewriteErrorMessage(test);
    },
    renderSuite : function(parentNode, result) {

        var self = this;
        result['passedPercentage'] = self.getPercentage(result['passed'], result['total']) + '%';
        result['failedPercentage'] = self.getPercentage(result['failed'], result['total']) + '%';
        result['skippedPercentage'] = self.getPercentage(result['skipped'], result['total']) + '%';
        result['notImplementedPercentage'] = self.getPercentage(result['notImplemented'], result['total']) + '%';
        result['errorPercentage'] = self.getPercentage(result['error'], result['total']) + '%';

        $(parentNode).empty();
        $(parentNode).append('<p class="text-muted">Completed in ' + result['time'] + ' seconds!</p>');
        $(parentNode).append(
                '<div class="progress">' + '<div class="progress-bar progress-bar-success" title="Passed ('
                + result['passedPercentage'] + ')" style="width:' + result['passedPercentage']
                + '"><span class="sr-only">' + result['passedPercentage'] + '</span></div>'
                + '<div class="progress-bar progress-bar-danger" title="Failed (' + result['failedPercentage']
                + ')" style="width:' + result['failedPercentage'] + '">' + '<span class="sr-only">'
                + result['failedPercentage'] + '</span></div>'
                + '<div class="progress-bar progress-bar-info progress-bar" title="Skipped ('
                + result['skippedPercentage'] + ')" style="width:' + result['skippedPercentage']
                + '"><span class="sr-only">' + result['skippedPercentage'] + '</span></div>'
                + '<div class="progress-bar progress-bar-warning progress-bar" title="Not implemented ('
                + result['notImplementedPercentage'] + ')" style="width:' + result['notImplementedPercentage']
                + '">' + '<span class="sr-only">' + result['notImplementedPercentage'] + '</span>' + '</div>'
                + '<div class="progress-bar progress-bar-error" title="Error (' + result['errorPercentage']
                + ')" style="width:' + result['errorPercentage'] + '">' + '<span class="sr-only">'
                + result['errorPercentage'] + '</span>' + '</div>' + '</div>');

        jQuery.each(result['tests'], function(k, v) {
            $(parentNode).append(self.renderTest(v));
        });

    },
    rewriteErrorMessage : function(test) {
        var self = this;
        if (test['status'] !== 'passed') {
            if (test['message'] === 'Failed asserting that two strings are equal.' && test['expected'] !== ''
                    && test['actual'] !== '') {
                test['message'] = 'Failed asserting that two strings are equal. \'<var>' + test['expected']
                        + '</var>\' was expected, but the actual value was \'<var>' + test['actual'] + '</var>\'.';
            }

            return '<div class="panel-body"><samp>' + test['message'] + '</samp>' + self.renderTrace(test['trace'])
                    + '</div></div>';
        }
        return '';
    },
    renderTrace : function(trace) {
        var output = '';
        jQuery.each(trace, function(k, v) {
            output = output + '<p class="text-muted">' + v['file'] + ' line: ' + v['line'] + '</p>';

        });
        return output;
    },
    getPercentage : function(val1, val2) {
        return Math.floor(((val1 / val2) * 100));
    }
};
