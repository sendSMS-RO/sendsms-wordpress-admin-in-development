<?php
echo $args['before_widget'];
if ( ! empty( $instance['title'] ) ) {
	echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
}
wp_nonce_field( 'sendsms-security-nonce' );
?>
<div id="sendsms-widget-subscribe-error-message" style="color:red"></div>
<div id="sendsms-widget-subscribe-success-message" style="color:green"></div>
<div id="sendsms-widget-subscribe-feedback-message"></div>
<div id="sendsms-widget-subscribe-add-form">
	<div class="sendsms-widget-subscribe-first-name-field">
		<label id="sendsms_forFirstName"><?php echo __( 'First Name', 'sendsms-dashboard' ); ?></label>
		<input id="sendsms_widget_subscribe_first_name" type="text" aria-label="<?php echo __( 'First Name', 'sendsms-dashboard' ); ?>" aria-describedby="sendsms_forFirstName">
	</div>
	<div class="sendsms-widget-subscribe-last-name-field">
		<label id="sendsms_forLastName"><?php echo __( 'Last Name', 'sendsms-dashboard' ); ?></label>
		<input id="sendsms_widget_subscribe_last_name" type="text" aria-label="<?php echo __( 'Last Name', 'sendsms-dashboard' ); ?>" aria-describedby="sendsms_forLastName">
	</div>
	<div class="sendsms-widget-subscribe-phone-field">
		<label id="sendsms_forPhoneNumber"><?php echo __( 'Phone number', 'sendsms-dashboard' ); ?></label>
		<input id="sendsms_widget_subscribe_phone_number" type="number" aria-label="<?php echo __( 'Phone number', 'sendsms-dashboard' ); ?>" aria-describedby="sendsms_forPhoneNumber">
	</div>
	<div class="sendsms-widget-subscribe-gdpr-field">
		<input id="sendsms_widget_subscribe_gdpr" type="checkbox" aria-label="<?php echo __( 'I agree with the privacy policy', 'sendsms-dashboard' ); ?>" aria-describedby="sendsms_forGdpr">
		<label id="sendsms_forGdpr">
			<?php
			_e( 'I agree with the ', 'sendsms-dashboard' );
			?>
			<a href="<?php echo ! empty( $instance['gdpr_link'] ) ? esc_url( $instance['gdpr_link'] ) : ''; ?>"><?php echo _e( 'privacy policy', 'sendsms-dashboard' ); ?></a>
					</label>
	</div>
	<div class="sendsms-widget-subscribe-send-button">
		<button class="button" id="sendsms_widget_subscribe_submit" type="button" aria-label="<?php echo __( 'Submit', 'sendsms-dashboard' ); ?>"><?php echo __( 'Submit', 'sendsms-dashboard' ); ?></button>
	</div>
</div>
<div id="sendsms-widget-subscribe-verify-form">
	<div id="sendsms-widget-subscribe-code-field">
		<label id="sendsms_forValidationField"><?php echo __( 'Code', 'sendsms-dashboard' ); ?></label>
		<input id="sendsms_widget_subscribe_validation_field" type="text" aria-label="<?php echo __( 'Code', 'sendsms-dashboard' ); ?>" aria-describedby="sendsms_forValidationField">
	</div>
	<div class="sendsms-widget-subscribe-validation-button">
		<button class="button" id="sendsms_widget_subscribe_validate" type="button" aria-label="<?php echo __( 'Verify', 'sendsms-dashboard' ); ?>"><?php echo __( 'Verify', 'sendsms-dashboard' ); ?></button>
	</div>
</div>
<?php
echo $args['after_widget'];
