$(document).ready(function() {
		
	$('.expand.button').each(function(index) {
		$(this).click(function(e) {
			
			if($(this).text() == '-') {				
				if($(this).parent().hasClass('test')) {
					$(this).next('.more').slideUp();
				} else {
					$(this).parent().next('.more').slideUp();
				}
				$(this).text('+');
			} else {
				if($(this).parent().hasClass('test')) {
					$(this).next('.more').slideDown();
				} else {
					$(this).parent().next('.more').slideDown();
				}
				$(this).text('-');
			}
		});
	});
	

	
	// function run() {
	// 	$.ajax({
	// 		url: '../PHPUnit.php',
	// 
	// 		complete: function() {
	// 			//called when complete
	// 		},
	// 
	// 		success: function(response) {
	// 			var obj = jsonParse(response);
	// 			console.log(obj);
	// 			var object = eval(response);
	// 			console.log(object[0].event);
	// 			// alert(obj);
	// 			console.log(response);
	// 
	// 		},
	// 
	// 		error: function() {
	// 			//called when there is an error
	// 		}
	// 	});
	// }
});
