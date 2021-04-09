jQuery(document).ready(function() {
	jQuery('#button-send-a-test-message').on('click', function($) {
		jQuery.post(sendsms_ajax_object.ajax_url, {
			    'action': 'send_a_test_sms',
			    'securty': sendsms_ajax_object.securty
			}, function(response) {
			
			    if (undefined !== response.success && false === response.success) {
			        return;
			    }
			    // Parse your response here.	
			});
	})
});
