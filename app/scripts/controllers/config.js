'use strict';

/**
 * @ngdoc function
 * @name VisualPHPUnit.controller:ConfigCtrl
 * @description # ConfigCtrl Controller of the VisualPHPUnit
 */
angular.module('VisualPHPUnit').controller('ConfigCtrl', function() {
	$('#protocol').val(Vpu.getBackend(true)['protocol']);
	$('#host').val(Vpu.getBackend(true)['host']);
	$('#port').val(Vpu.getBackend(true)['port']);
	$('#apply').on('click', function() {
		//TODO regexp input validation
		//TODO Better error message if backend cannot be reached.
		Vpu.setBackend($('#protocol').val(),$('#host').val(), $('#port').val());
	});
});