//We are using jquery only for wordpress specific calls
jQuery(document).ready(function() {
    //attach the modal window so I can show error messaged later on
    //AJAX for send a test
    jQuery('#button-send-a-test-message').on('click', function($) {
        let modal = new jBox('Modal', {
            repositionOnContent: true,
            width: 250
        });
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
    //AJAX to edit a subscriber
    jQuery('.sendsms-dashboard-subscribers-edit').on('click', function($) {
        var parentTr = jQuery($.target).parent().closest('tr');
        activateEditForm(
            $.target,
            ".sendsms-dashboard-subscribers-edit",
            jQuery(parentTr).children(".phone").eq(0).children('p').text(),
            jQuery(parentTr).children(".name").eq(0).children('p').text(),
            jQuery(parentTr).children(".date").eq(0).children('p').text(),
            jQuery(parentTr).children(".ip_address").eq(0).children('p').text(),
            jQuery(parentTr).children(".browser").eq(0).children('p').text());
        jQuery('.sendsms-dashboard-subscribers-update').on('click', function() {
            jQuery.post(sendsms_object.ajax_url, {
                'action': 'update_a_subscriber',
                'security': sendsms_object.security,
                'old_phone': jQuery("#sendsms_dashboard_edit_old_phone").val(),
                'phone': jQuery("#sendsms_dashboard_edit_phone_number").val(),
                'name': jQuery("#sendsms_dashboard_edit_name").val(),
                'date': jQuery("#sendsms_dashboard_edit_date").val(),
                'ip_address': jQuery("#sendsms_dashboard_edit_ip_address").val(),
                'browser': jQuery("#sendsms_dashboard_edit_browser").val()
            }, function(response) {
                let modal = new jBox('Modal', {
                    repositionOnContent: true,
                    width: 250
                });
                if (undefined !== response.success && false === response.success) {
                    modal.setTitle("Error");
                    modal.setContent(sendsms_object['text_' + response.data]);
                    modal.open();
                    return;
                }
                if (response.success) {
                    cancelEdit();
                    var parentTr = jQuery($.target).parent().closest('tr');
                    modal.setTitle("Success")
                    modal.setContent(sendsms_object['text_' + response.data.info]);
                    modal.open();
                    jQuery('.sendsms-dashboard-subscribers-update').off('click');
                    jQuery(parentTr).children(".phone").eq(0).children('p').text(response.data.new_data.phone);
                    jQuery(parentTr).children(".name").eq(0).children('p').text(response.data.new_data.name);
                    jQuery(parentTr).children(".date").eq(0).children('p').text(response.data.new_data.date);
                    jQuery(parentTr).children(".ip_address").eq(0).children('p').text(response.data.new_data.ip_address);
                    jQuery(parentTr).children(".browser").eq(0).children('p').text(response.data.new_data.browser);
                    return;
                }
            })
        })
    });
    jQuery('#sendsms-dashboard-subscribers-synchronize').on('click', function($) {
        jQuery('#sendsms-dashboard-subscribers-synchronize').html('<i class="sendsms-dashboard-fa-spinner fas fa-spinner"></i>');
        jQuery.post(sendsms_object.ajax_url, {
            'action': 'synchronize_contacts',
            'security': sendsms_object.security
        }, function(response) {
            let modal = new jBox('Modal', {
                repositionOnContent: true,
                width: 250
            });
            if (undefined !== response.success && false === response.success) {
                modal.setTitle("Error");
                modal.setContent(sendsms_object['text_' + response.data]);
                modal.open();
                return;
            }
            if (response.success) {
                cancelEdit();
                modal.setTitle("Success")
                modal.setContent(sendsms_object['text_' + response.data.info]);
                modal.open();
                return;
            }
        })
    })
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

function activateEditForm(currentObject, selector, phone, name, date, ip_address, browser) {
    //reactivate all elements
    jQuery(selector).parent().closest('tr').show();
    var workingTr = jQuery(currentObject).parent().closest('tr');
    jQuery(workingTr).hide();
    jQuery(workingTr).after(jQuery(".sendsms-dashboard-edit-form").show());
    jQuery("#sendsms_dashboard_edit_old_phone,#sendsms_dashboard_edit_phone_number").val(phone);
    jQuery("#sendsms_dashboard_edit_name").val(name);
    jQuery("#sendsms_dashboard_edit_date").val(date.replace(" ", "T"));
    jQuery("#sendsms_dashboard_edit_ip_address").val(ip_address);
    jQuery("#sendsms_dashboard_edit_browser").val(browser);
}

function cancelEdit() {
    jQuery(".sendsms-dashboard-subscribers-edit").parent().closest('tr').fadeIn(400);
    jQuery(".sendsms-dashboard-edit-form").hide();
    jQuery("#sendsms_dashboard_edit_old_phone,#sendsms_dashboard_edit_phone_number").val("");
    jQuery("#sendsms_dashboard_edit_name").val("");
    jQuery("#sendsms_dashboard_edit_date").val("");
    jQuery("#sendsms_dashboard_edit_ip_address").val("");
    jQuery("#sendsms_dashboard_edit_browser").val("");
}