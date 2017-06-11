// jQuery( document ).on( 'click', '.love-button', function() {
// 	var post_id = jQuery(this).data('id');
// 	jQuery.ajax({
// 		url : postlove.ajax_url,
// 		type : 'post',
// 		data : {
// 			action : 'send_contact_form_email',
// 		},
// 		success : function( response ) {
// 			alert(response)
// 		}
// 	});
// })
jQuery(document).ready(function($) {

	$('.submitted-form').on('submit', function(e) {
		e.preventDefault();

	jQuery.ajax({
		url : postlove.ajax_url,
		type : 'post',
		data : {
			action : 'send_client_contact_email',
		},
		success : function( response ) {
			alert(response)
		}
	});
	});

});