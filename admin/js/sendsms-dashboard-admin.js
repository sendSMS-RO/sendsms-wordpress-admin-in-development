// We are using jquery only for wordpress specific calls
jQuery( document ).ready(
	function () {
		// attach the modal window so I can show error messaged later on
		// AJAX for send a test
		jQuery( '#button-send-a-test-message' ).on(
			'click',
			function ($) {
				let modal = new jBox(
					'Modal',
					{
						repositionOnContent: true,
						width: 250
					}
				);
				jQuery( '#button-send-a-test-message' ).html( sendsms_object.text_button_sending );
				jQuery( '#button-send-a-test-message' ).attr( 'disabled', 'disabled' );
				jQuery.post(
					sendsms_object.sendsms_ajax_url,
					{
						'action': 'send_a_test_sms',
						'security': sendsms_object.security,
						'phone_number': jQuery( '#phone_number' ).val(),
						'short': jQuery( '#short' ).is( ":checked" ),
						'gdpr': jQuery( '#gdpr' ).is( ":checked" ),
						'message': jQuery( '#message' ).val()
					},
					function (response) {
						modal.setContent( response.data );
						modal.open();
						jQuery( '#button-send-a-test-message' ).html( sendsms_object.text_button_send );
						jQuery( '#button-send-a-test-message' ).removeAttr( 'disabled' );
						if (undefined !== response.success && false === response.success) {
							modal.setTitle( "Error" );
							return;
						}
						modal.setTitle( "Success" );
					}
				);
			}
		);
		// AJAX for mass sms
		jQuery( '#button-send-mass-message' ).on(
			'click',
			function ($) {
				let modal = new jBox(
					'Modal',
					{
						repositionOnContent: true,
						width: 250
					}
				);
				jQuery( '#button-send-mass-message' ).html( sendsms_object.text_button_sending );
				jQuery( '#button-send-mass-message' ).attr( 'disabled', 'disabled' );
				jQuery.post(
					sendsms_object.sendsms_ajax_url,
					{
						'action': 'send_mass_sms',
						'security': sendsms_object.security,
						'message': jQuery( '#message' ).val(),
						'receivers_type': jQuery( '#receiver_type' ).val(),
						'role': jQuery( '#role_selector' ).val()
					},
					function (response) {
						modal.setContent( response.data );
						modal.open();
						jQuery( '#button-send-mass-message' ).html( sendsms_object.text_button_send );
						jQuery( '#button-send-mass-message' ).removeAttr( 'disabled' );
						if (undefined !== response.success && false === response.success) {
							modal.setTitle( "Error" );
							return;
						}
						modal.setTitle( "Success" );
					}
				);
			}
		);
		// AJAX to edit a subscriber
		jQuery( '.sendsms-dashboard-subscribers-edit' ).on(
			'click',
			function ($) {
				var parentTr = jQuery( $.target ).parent().closest( 'tr' );
				activateEditForm(
					$.target,
					".sendsms-dashboard-subscribers-edit",
					jQuery( parentTr ).children( ".phone" ).eq( 0 ).children( 'p' ).text(),
					jQuery( parentTr ).children( ".first_name" ).eq( 0 ).children( 'p' ).text(),
					jQuery( parentTr ).children( ".last_name" ).eq( 0 ).children( 'p' ).text(),
					jQuery( parentTr ).children( ".date" ).eq( 0 ).children( 'p' ).text(),
					jQuery( parentTr ).children( ".ip_address" ).eq( 0 ).children( 'p' ).text(),
					jQuery( parentTr ).children( ".browser" ).eq( 0 ).children( 'p' ).text()
				);
				jQuery( '.sendsms-dashboard-subscribers-update' ).on(
					'click',
					function () {
						jQuery.post(
							sendsms_object.sendsms_ajax_url,
							{
								'action': 'update_a_subscriber',
								'security': sendsms_object.security,
								'old_phone': jQuery( "#sendsms_dashboard_edit_old_phone" ).val(),
								'phone': jQuery( "#sendsms_dashboard_edit_phone_number" ).val(),
								'first_name': jQuery( "#sendsms_dashboard_edit_first_name" ).val(),
								'last_name': jQuery( "#sendsms_dashboard_edit_last_name" ).val(),
								'date': jQuery( "#sendsms_dashboard_edit_date" ).val(),
								'ip_address': jQuery( "#sendsms_dashboard_edit_ip_address" ).val(),
								'browser': jQuery( "#sendsms_dashboard_edit_browser" ).val()
							},
							function (response) {
								let modal = new jBox(
									'Modal',
									{
										repositionOnContent: true,
										width: 250
									}
								);
								if (undefined !== response.success && false === response.success) {
									modal.setTitle( "Error" );
									modal.setContent( sendsms_object['text_' + response.data] );
									modal.open();
									return;
								}
								if (response.success) {
									cancelEdit();
									var parentTr = jQuery( $.target ).parent().closest( 'tr' );
									modal.setTitle( "Success" )
									modal.setContent( sendsms_object['text_' + response.data.info] );
									modal.open();
									jQuery( '.sendsms-dashboard-subscribers-update' ).off( 'click' );
									jQuery( parentTr ).children( ".phone" ).eq( 0 ).children( 'p' ).text( response.data.new_data.phone );
									jQuery( parentTr ).children( ".first_name" ).eq( 0 ).children( 'p' ).text( response.data.new_data.first_name );
									jQuery( parentTr ).children( ".last_name" ).eq( 0 ).children( 'p' ).text( response.data.new_data.last_name );
									jQuery( parentTr ).children( ".date" ).eq( 0 ).children( 'p' ).text( response.data.new_data.date );
									jQuery( parentTr ).children( ".ip_address" ).eq( 0 ).children( 'p' ).text( response.data.new_data.ip_address );
									jQuery( parentTr ).children( ".browser" ).eq( 0 ).children( 'p' ).text( response.data.new_data.browser );
									return;
								}
							}
						)
					}
				)
			}
		);
		// ajax to sync subscribers
		jQuery( '#sendsms-dashboard-subscribers-synchronize' ).on(
			'click',
			function ($) {
				jQuery( '#sendsms-dashboard-subscribers-synchronize' ).html( '<i class="sendsms-dashboard-fa-spinner fas fa-spinner"></i>' );
				jQuery( '#sendsms-dashboard-subscribers-synchronize' ).addClass( "sendsms-dashboard-disabled" );
				jQuery.post(
					sendsms_object.sendsms_ajax_url,
					{
						'action': 'synchronize_contacts',
						'security': sendsms_object.security
					},
					function (response) {
						let modal = new jBox(
							'Modal',
							{
								repositionOnContent: true,
								width: 250
							}
						);
						if (undefined !== response.success && false === response.success) {
							modal.setTitle( "Error" );
						}
						if (response.success) {
							modal.setTitle( "Success" );
						}
						jQuery( '#sendsms-dashboard-subscribers-synchronize' ).removeClass( "sendsms-dashboard-disabled" );
						modal.setContent( sendsms_object['text_' + response.data] );
						modal.open();
						jQuery( '#sendsms-dashboard-subscribers-synchronize' ).html( 'Synchronize subscribers' );
					}
				)
			}
		)
		jQuery( '#sendsms-dashboard-subscribers-add-new' ).on(
			'click',
			function ($) {
				jQuery( '#sendsms-dashboard-overlay' ).show();
				jQuery( '#sendsms-dashboard-add-new-form' ).show();
			}
		)
		jQuery( '#receiver_type' ).on(
			"change",
			function () {
				if (jQuery( this ).val() == "users") {
					jQuery( '#role_text' ).removeClass( 'sendsms-hidden' );
					jQuery( '#role_selector' ).removeClass( 'sendsms-hidden' );
				} else {
					jQuery( '#role_text' ).addClass( 'sendsms-hidden' );
					jQuery( '#role_selector' ).addClass( 'sendsms-hidden' );
				}
			}
		);
	}
);

// count the number of characters
document.addEventListener(
	"DOMContentLoaded",
	function () {
		var sendsms_dashboard_content = document.getElementsByClassName( 'sendsms_dashboard_content' )[0];
		if (sendsms_dashboard_content != undefined) {
			sendsms_dashboard_content.addEventListener(
				"input",
				function (event) {
					lengthCounter( event.target, document.getElementById( event.target.dataset['sendsmsCounter'] ) );
				}
			);
			sendsms_dashboard_content.addEventListener(
				"change",
				function (event) {
					lengthCounter( event.target, document.getElementById( event.target.dataset['sendsmsCounter'] ) );
				}
			);

			function lengthCounter(textarea, counter) {
				var length   = textarea.value.length;
				var messages = length / 160 + 1;
				if (length > 0) {
					if (length % 160 === 0) {
						messages--;
					}
					counter.textContent = sendsms_object.text_message_contains_something + Math.floor( messages ) + " (" + length + ")";
				} else {
					counter.textContent = sendsms_object.text_message_is_empty;
				}
			}
		}
	}
);

// this will activate the edit form of a subscriber
function activateEditForm(currentObject, selector, phone, first_name, last_name, date, ip_address, browser) {
	// reactivate all elements
	jQuery( selector ).parent().closest( 'tr' ).show();
	var workingTr = jQuery( currentObject ).parent().closest( 'tr' );
	jQuery( workingTr ).hide();
	jQuery( workingTr ).after( jQuery( ".sendsms-dashboard-edit-form" ).show() );
	jQuery( "#sendsms_dashboard_edit_old_phone,#sendsms_dashboard_edit_phone_number" ).val( phone );
	jQuery( "#sendsms_dashboard_edit_first_name" ).val( first_name );
	jQuery( "#sendsms_dashboard_edit_last_name" ).val( last_name );
	jQuery( "#sendsms_dashboard_edit_date" ).val( date.replace( " ", "T" ) );
	jQuery( "#sendsms_dashboard_edit_ip_address" ).val( ip_address );
	jQuery( "#sendsms_dashboard_edit_browser" ).val( browser );
}

// just the cancel edit button
function cancelEdit() {
	jQuery( ".sendsms-dashboard-subscribers-edit" ).parent().closest( 'tr' ).fadeIn( 400 );
	jQuery( ".sendsms-dashboard-edit-form" ).hide();
	jQuery( "#sendsms_dashboard_edit_old_phone,#sendsms_dashboard_edit_phone_number" ).val( "" );
	jQuery( "#sendsms_dashboard_edit_first_name" ).val( "" );
	jQuery( "#sendsms_dashboard_edit_last_name" ).val( "" );
	jQuery( "#sendsms_dashboard_edit_date" ).val( "" );
	jQuery( "#sendsms_dashboard_edit_ip_address" ).val( "" );
	jQuery( "#sendsms_dashboard_edit_browser" ).val( "" );
}

function closeAddNewForm() {
	jQuery( '#sendsms-dashboard-overlay' ).hide();
	jQuery( '#sendsms-dashboard-add-new-form' ).hide();
	jQuery( "#sendsms-widget-unsubscribe-error-message" ).text( "" );
}

function validateAddNewForm() {
	phone      = jQuery( "#sendsms_dashboard_add_new_phone_number" ).val();
	first_name = jQuery( "#sendsms_dashboard_add_new_first_name" ).val();
	last_name  = jQuery( "#sendsms_dashboard_add_new_last_name" ).val();
	date       = jQuery( "#sendsms_dashboard_add_new_date" ).val();

	if (phone === "" || first_name === "" || last_name === "" || date === "") {
		jQuery( "#sendsms-widget-unsubscribe-error-message" ).text( sendsms_object.text_empty_fields );
		return false;
	}
	return true;
}
