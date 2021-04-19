jQuery(document).ready(function() {
    //AJAX for send a test
    jQuery('#sendsms_subscribe').on('click', function($) {
        jQuery.post(sendsms_object_public.ajax_url, {
            'action': 'sendsms_dashboard_subscribe',
            'security': sendsms_object_public.security,
			'phone_number': jQuery('#sendsms_widget_phone_number').val(),
			'name': jQuery('#sendsms_widget_name').val(),
            'gdpr': jQuery('#sendsms_widget_gdpr').is(":checked"),
        }, function(response) {
            if (undefined !== response.success && false === response.success) {
                return;
            }
            // Parse your response here.	
        });
    });
});
