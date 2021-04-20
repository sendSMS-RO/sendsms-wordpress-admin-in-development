jQuery(document).ready(function() {
    //AJAX for send a test
    jQuery('#sendsms_subscribe').on('click', function($) {
        jQuery.post(sendsms_object_public.ajax_url, {
            'action': 'subscribe_to_newsletter',
            'security': sendsms_object_public.security,
			'phone_number': jQuery('#sendsms_widget_phone_number').val(),
			'name': jQuery('#sendsms_widget_name').val(),
            'gdpr': jQuery('#sendsms_widget_gdpr').is(":checked"),
        }, function(response) {
            jQuery('#sensms-widget-error-message').html("");
            jQuery('#sensms-widget-success-message').html("");
            jQuery('#sensms-widget-feedback-message').html("");
            if (undefined !== response.success && false === response.success) {
                jQuery('#sensms-widget-error-message').html(sendsms_object_public['text_' + response.data]);
                return;
            }
            // Parse your response here.	
        });
    });
});
