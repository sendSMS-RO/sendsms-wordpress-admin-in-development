//We are using jquery only for wordpress specific calls
jQuery(document).ready(function() {
    //attach the modal window so I can show error messaged later on
    let modal = new jBox('Modal', {
        repositionOnContent: true,
        width: 250
    });
    //AJAX for send a test
    jQuery('#button-send-a-test-message').on('click', function($) {
        jQuery('#button-send-a-test-message').html(sendsms_object.text_button_sending);
        jQuery('#button-send-a-test-message').attr('disabled', 'disabled');
        jQuery.post(sendsms_object.ajax_url, {
            'action': 'send_a_test_sms',
            'security': sendsms_object.security,
            'phone_number': jQuery('#phone_number').val(),
            'short': jQuery('#short').is(":checked"),
            'gdpr': jQuery('#gdpr').is(":checked"),
            'message': jQuery('#message').val()
        }, function(response) {
            modal.setContent(response.data);
            console.log(response.data);
            modal.open();
            jQuery('#button-send-a-test-message').html(sendsms_object.text_button_send);
            jQuery('#button-send-a-test-message').removeAttr('disabled');
            if (undefined !== response.success && false === response.success) {
                modal.setTitle("Error");
                return;
            }
            modal.setTitle("Succes");
            // Parse your response here.	
        });
    });
    new jBox('Tooltip', {
        attach: '.tooltip',
        preventDefault: true,
        getTitle: 'data-title',
        addClass: 'sendsms-general-tooltip',
        maxWidth: 200
    });
});

//count the number of characters
document.addEventListener("DOMContentLoaded", (event) => {
    var sendsms_daschboard_content = document.getElementsByClassName('sendsms_dashboard_content')[0];
    if (sendsms_daschboard_content != undefined) {
        sendsms_daschboard_content.addEventListener("input", (event) => {
            lenghtCounter(event.target, document.getElementById(event.target.dataset['sendsmsCounter']));
        });
        sendsms_daschboard_content.addEventListener("change", (event) => {
            lenghtCounter(event.target, document.getElementById(event.target.dataset['sendsmsCounter']));
        });

        function lenghtCounter(textarea, counter) {
            var lenght = textarea.value.length;
            var messages = lenght / 160 + 1;
            if (lenght > 0) {
                if (lenght % 160 === 0) {
                    messages--;
                }
                counter.textContent = sendsms_object.text_message_contains_something + Math.floor(messages) + " (" + lenght + ")";
            } else {
                counter.textContent = sendsms_object.text_message_is_empty;
            }
        }
    }
});