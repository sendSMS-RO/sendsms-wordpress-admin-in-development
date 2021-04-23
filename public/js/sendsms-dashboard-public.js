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
            clearOutputToUser();
            if (undefined !== response.success && false === response.success) {
                jQuery('#sensms-widget-error-message').html(sendsms_object_public['text_' + response.data]);
                return;
            }
            if (response.success) {
                jQuery('#sensms-widget-success-message').html(sendsms_object_public['text_' + response.data]);
                return;
            }
            if (response == "waiting_validation") {
                jQuery('#sensms-widget-feedback-message').html(sendsms_object_public['text_' + response]);
                jQuery('#sendsms_subscribe').off('click');
                jQuery('#sendsms-widget-add-form').css('display', 'none');
                jQuery('#sendsms-widget-verify-form').css('display', 'block');
                //this will validate the code sent to the sms
                jQuery('#sendsms_validate').on('click', function($) {
                    jQuery.post(sendsms_object_public.ajax_url, {
                        'action': 'unsubscribe_verify_code',
                        'security': sendsms_object_public.security,
                        'phone_number': jQuery('#sendsms_widget_phone_number').val(),
                        'name': jQuery('#sendsms_widget_name').val(),
                        'code': jQuery('#sendsms_widget_validation_field').val()
                    }, function(response) {
                        clearOutputToUser();
                        if (undefined !== response.success && false === response.success) {
                            jQuery('#sensms-widget-error-message').html(sendsms_object_public['text_' + response.data]);
                            return;
                        }
                    });
                });
            }
        });
    });
});

function clearOutputToUser()
{
    jQuery('#sensms-widget-error-message').html("");
    jQuery('#sensms-widget-success-message').html("");
    jQuery('#sensms-widget-feedback-message').html("");
}
