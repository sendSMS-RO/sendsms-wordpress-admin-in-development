//We are using jquery only for wordpress specific calls
jQuery(document).ready(function() {
    //AJAX for send a test
    jQuery('#button-send-a-test-message').on('click', function($) {
        jQuery.post(sendsms_object.ajax_url, {
            'action': 'send_a_test_sms',
            'security': sendsms_object.security
        }, function(response) {
            if (undefined !== response.success && false === response.success) {
                console.log(response);
                return;
            }
            // Parse your response here.	
        });
    });

    //activate tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});

//count the number of characters
document.addEventListener("DOMContentLoaded", (event) => {
    var sendsms_daschboard_content = document.getElementsByClassName('sendsms_dashboard_content')[0];
    sendsms_daschboard_content.addEventListener("input", (event) => 
        {
            lenghtCounter(event.target, document.getElementById(event.target.dataset['sendsmsCounter']));
        });
    sendsms_daschboard_content.addEventListener("change", (event) => 
        {
            lenghtCounter(event.target, document.getElementById(event.target.dataset['sendsmsCounter']));
        });
    function lenghtCounter(textarea, counter)
    {
        var lenght = textarea.value.length;
        var messages = lenght / 160 + 1;
        if(lenght > 0)
        {
            if(lenght % 160 === 0)
            {
                messages--;
            }
            counter.textContent = sendsms_object.text_message_contains_something + Math.floor(messages) + " (" + lenght + ")";
        }else
        {
            counter.textContent = sendsms_object.text_message_is_empty;
        }
    }
});