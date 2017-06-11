jQuery(document).ready(function($){
	$(".header").click(function(){
		$("#slide").toggleClass("open");
	});
	$(".logo-area").click(function(){
		$("#slide").toggleClass("open");
	});
	$(document).on( 'click', '.nav-tab-wrapper a', function() {
		$('section').hide();
		$('section').eq($(this).index()).show();
		return false;
	})
});