jQuery(document).ready(function() {
    //AJAX for send a test
    jQuery('#sendsms_subscribe').on('click', function($) {
        jQuery.post(sendsms_object_public.ajax_url, {
            'action': 'subscribe_to_newsletter',
            'security': sendsms_object_public.security,
            'phone_number': jQuery('#sendsms_widget_subscribe_phone_number').val(),
            'name': jQuery('#sendsms_widget_subscribe_name').val(),
            'gdpr': jQuery('#sendsms_widget_subscribe_gdpr').is(":checked"),
        }, function(response) {
            clearOutputToUser();
            if (undefined !== response.success && false === response.success) {
                jQuery('#sendsms-widget-subscribe-error-message').html(sendsms_object_public['text_' + response.data]);
                return;
            }
            if (response.success) {
                jQuery('#sendsms-widget-subscribe-success-message').html(sendsms_object_public['text_' + response.data]);
                return;
            }
            if (response == "waiting_validation") {
                jQuery('#sendsms-widget-subscribe-feedback-message').html(sendsms_object_public['text_' + response]);
                jQuery('#sendsms_subscribe').off('click');
                jQuery('#sendsms-widget-subscribe-add-form').css('display', 'none');
                jQuery('#sendsms-widget-subscribe-verify-form').css('display', 'block');
                //this will validate the code sent to the sms
                jQuery('#sendsms_validate').on('click', function($) {
                    jQuery.post(sendsms_object_public.ajax_url, {
                        'action': 'subscribe_verify_code',
                        'security': sendsms_object_public.security,
                        'phone_number': jQuery('#sendsms_widget_subscribe_phone_number').val(),
                        'name': jQuery('#sendsms_widget_subscribe_name').val(),
                        'code': jQuery('#sendsms_widget_subscribe_validation_field').val()
                    }, function(response) {
                        clearOutputToUser();
                        if (undefined !== response.success && false === response.success) {
                            jQuery('#sendsms-widget-subscribe-error-message').html(sendsms_object_public['text_' + response.data]);
                            return;
                        }
                        if (response.success) {
                            jQuery('#sendsms-widget-subscribe-success-message').html(sendsms_object_public['text_' + response.data]);
                            jQuery('#sendsms-widget-subscribe-verify-form').css('display', 'none');
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
    jQuery('#sendsms-widget-subscribe-error-message').html("");
    jQuery('#sendsms-widget-subscribe-success-message').html("");
    jQuery('#sendsms-widget-subscribe-feedback-message').html("");
}
