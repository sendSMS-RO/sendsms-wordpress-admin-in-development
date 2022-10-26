jQuery(document).ready(function () {
    //AJAX for send a test
    jQuery('#sendsms_widget_subscribe_submit').on('click', function ($) {
        jQuery.post(sendsms_object_public.sendsms_ajax_url, {
            'action': 'subscribe_to_newsletter',
            'security': sendsms_object_public.security,
            'phone_number': jQuery('#sendsms_widget_subscribe_phone_number').val(),
            'first_name': jQuery('#sendsms_widget_subscribe_first_name').val(),
            'last_name': jQuery('#sendsms_widget_subscribe_last_name').val(),
            'gdpr': jQuery('#sendsms_widget_subscribe_gdpr').is(":checked"),
        }, function (response) {
            clearOutputToUser("subscribe");
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
                jQuery('#sendsms_widget_subscribe_submit').off('click');
                jQuery('#sendsms-widget-subscribe-add-form').css('display', 'none');
                jQuery('#sendsms-widget-subscribe-verify-form').css('display', 'block');
                //this will validate the code sent to the sms
                jQuery('#sendsms_widget_subscribe_validate').on('click', function ($) {
                    jQuery.post(sendsms_object_public.sendsms_ajax_url, {
                        'action': 'subscribe_verify_code',
                        'security': sendsms_object_public.security,
                        'phone_number': jQuery('#sendsms_widget_subscribe_phone_number').val(),
                        'first_name': jQuery('#sendsms_widget_subscribe_first_name').val(),
                        'last_name': jQuery('#sendsms_widget_subscribe_last_name').val(),
                        'code': jQuery('#sendsms_widget_subscribe_validation_field').val()
                    }, function (response) {
                        clearOutputToUser("subscribe");
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

    jQuery('#sendsms_widget_unsubscribe_submit').on('click', function ($) {
        jQuery.post(sendsms_object_public.sendsms_ajax_url, {
            'action': 'unsubscribe_from_newsletter',
            'security': sendsms_object_public.security,
            'phone_number': jQuery('#sendsms_widget_unsubscribe_phone_number').val()
        }, function (response) {
            clearOutputToUser("unsubscribe");
            if (undefined !== response.success && false === response.success) {
                jQuery('#sendsms-widget-unsubscribe-error-message').html(sendsms_object_public['text_' + response.data]);
                return;
            }
            if (response.success) {
                jQuery('#sendsms-widget-unsubscribe-success-message').html(sendsms_object_public['text_' + response.data]);
                return;
            }
            if (response == "waiting_validation") {
                jQuery('#sendsms-widget-unsubscribe-feedback-message').html(sendsms_object_public['text_' + response]);
                jQuery('#sendsms_widget_unsubscribe_submit').off('click');
                jQuery('#sendsms-widget-unsubscribe-add-form').css('display', 'none');
                jQuery('#sendsms-widget-unsubscribe-verify-form').css('display', 'block');
                //this will validate the code sent to the sms
                jQuery('#sendsms_widget_unsubscribe_validate').on('click', function ($) {
                    jQuery.post(sendsms_object_public.sendsms_ajax_url, {
                        'action': 'unsubscribe_verify_code',
                        'security': sendsms_object_public.security,
                        'phone_number': jQuery('#sendsms_widget_unsubscribe_phone_number').val(),
                        'code': jQuery('#sendsms_widget_unsubscribe_validation_field').val()
                    }, function (response) {
                        clearOutputToUser("unsubscribe");
                        if (undefined !== response.success && false === response.success) {
                            jQuery('#sendsms-widget-unsubscribe-error-message').html(sendsms_object_public['text_' + response.data]);
                            return;
                        }
                        if (response.success) {
                            jQuery('#sendsms-widget-unsubscribe-success-message').html(sendsms_object_public['text_' + response.data]);
                            jQuery('#sendsms-widget-unsubscribe-verify-form').css('display', 'none');
                            return;
                        }
                    });
                });
            }
        });
    });
});

function clearOutputToUser($action) {
    if ($action == "subscribe") {
        jQuery('#sendsms-widget-subscribe-error-message').html("");
        jQuery('#sendsms-widget-subscribe-success-message').html("");
        jQuery('#sendsms-widget-subscribe-feedback-message').html("");
    } else {
        jQuery('#sendsms-widget-unsubscribe-error-message').html("");
        jQuery('#sendsms-widget-unsubscribe-success-message').html("");
        jQuery('#sendsms-widget-unsubscribe-feedback-message').html("");
    }
}