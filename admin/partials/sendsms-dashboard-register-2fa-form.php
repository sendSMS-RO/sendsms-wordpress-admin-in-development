<!-- inactive -->
<form id="mo_validate_form" name="sendsms_validation_register_form" method="post" action="<?php echo esc_url( sendsms_dashboard_login_url( array( 'action' => 'sendsms_validate' ), 'register_post' ) ); ?>">
	<input type="hidden" id="from_both" name="from_both" value="false">
	<input type="hidden" name="user_login" value="<?php echo wp_unslash( maybe_unserialize( $_COOKIE['sanitized_user_login'] ) ); ?>">
	<input type="hidden" name="user_email" value="<?php echo wp_unslash( maybe_unserialize( $_COOKIE['user_email'] ) ); ?>">
	<input type="hidden" name="sendsms_phone" value="<?php echo wp_unslash( maybe_unserialize( $_COOKIE['sendsms_phone'] ) ); ?>">
	<input type="hidden" name="redirect_to" value="">
	<input type="hidden" name="wp-submit" value="Register">
	<input type="text" name="sendsms_register_validation_token" title="<?php echo __( 'Enter Code', 'sendsms-dashboard' ); ?>">
	<input type="submit" name="sendsms_register_2fa_submit" id="sendsms_register_2fa_submit" value="Validate OTP">
</form>

<?php
function sendsms_dashboard_login_url( $params = array(), $scheme = 'login' ) {
	if ( ! is_array( $params ) ) {
		$params = array();
	}

	$params = urlencode_deep( $params );

	return add_query_arg( $params, site_url( 'wp-login.php', $scheme ) );
} ?>
