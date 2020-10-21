


$(document).ready(function() {
	var bodyW = parseInt($('body').css('width'));
	if(bodyW < 801){
		$('.tree_table > div').on('touchstart', function(){
			$(this).find('img').trigger('click'); 
		})
	}
	
	
});