<?php

/**
 * This function generates the html responsible for the 2fa login form
 *
 * @since 1.0.0
 */
function sendsms_dashboard_printHTML2fa( $user, $nonce, $redirect, $class, $error = '', $regenCode = true, $hasPhone = true, $phone = '' ) {
	$interim_login = isset( $_REQUEST['interim-login'] );
	$rememberme    = false;
	if ( ! empty( $_REQUEST['rememberme'] ) ) {
		$rememberme = true;
	}
	$rememberme = intval( $rememberme );

	if ( ! function_exists( 'login_header' ) ) {
		include_once SENDSMS_DASHBOARD_PLUGIN_DIRECTORY . 'includes/class-wp-login.php';
	}

	login_header();

	if ( ! empty( $error ) ) {
		echo '<div id="login_error"><strong>' . esc_html( $error ) . '</strong><br /></div>';
	}

	if ( $hasPhone ) {
		?>

		<form name="sendsms_validate_form" id="loginform" action="<?php echo esc_url( sendsms_dashboard_login_url( array( 'action' => 'sendsms_validate' ), 'login_post' ) ); ?>" method="post" autocomplete="off">
			<input type="hidden" name="wp-auth-id" id="wp-auth-id" value="<?php echo esc_attr( $user->ID ); ?>" />
			<input type="hidden" name="wp-auth-nonce" id="wp-auth-nonce" value="<?php echo esc_attr( $nonce ); ?>" />
			<input type="hidden" name="wp-auth-phone" id="wp-auth-phone" value="<?php echo esc_attr( $phone ); ?>" />
			<?php if ( $interim_login ) { ?>
				<input type="hidden" name="interim-login" value="1" />
			<?php } else { ?>
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>" />
			<?php } ?>
			<input type="hidden" name="rememberme" id="rememberme" value="<?php echo esc_attr( $rememberme ); ?>" />
			<?php
			include_once ABSPATH . '/wp-admin/includes/template.php';
			?>
			<p><?php esc_html_e( 'Enter the code received via SMS.', 'sendsms-dashboard' ); ?></p><br />
			<p>
				<label for="authcode"><?php esc_html_e( 'Verification Code:', 'sendsms-dashboard' ); ?></label>
				<input name="code" id="authcode" class="input" value="" />
			</p>
			<?php
			submit_button( __( 'Submit', 'sendsms-dashboard' ) );
			?>
		</form>
		<?php
		if ( $regenCode ) {
			error_log( 'gen code' );
			$class->generate_auth_code( $user, $phone );
		}
	} else {
		?>
		<form name="sendsms_validate_form" id="loginform" action="<?php echo esc_url( sendsms_dashboard_login_url( array( 'action' => 'sendsms_send_code' ), 'login_post' ) ); ?>" method="post" autocomplete="off">
			<input type="hidden" name="wp-auth-id" id="wp-auth-id" value="<?php echo esc_attr( $user->ID ); ?>" />
			<input type="hidden" name="wp-auth-nonce" id="wp-auth-nonce" value="<?php echo esc_attr( $nonce ); ?>" />
			<?php if ( $interim_login ) { ?>
				<input type="hidden" name="interim-login" value="1" />
			<?php } else { ?>
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>" />
			<?php } ?>
			<input type="hidden" name="rememberme" id="rememberme" value="<?php echo esc_attr( $rememberme ); ?>" />
			<?php
			include_once ABSPATH . '/wp-admin/includes/template.php';
			?>
			<div <?php echo $hasPhone ? 'style="display: none;"' : ''; ?>>
				<p><?php esc_html_e( 'Please enter a phone number.', 'sendsms-dashboard' ); ?></p><br />
				<p>
					<label for="phone"><?php esc_html_e( 'Phone number:', 'sendsms-dashboard' ); ?></label>
					<input type="tel" name="phone" id="phone" class="input" value="" />
				</p>
			</div>
			<?php
			submit_button( __( 'Send code', 'sendsms-dashboard' ) );
			?>
		</form>
		<?php
	}
	do_action( 'login_footer' );
}

function sendsms_dashboard_login_url( $params = array(), $scheme = 'login' ) {
	if ( ! is_array( $params ) ) {
		$params = array();
	}

	$params = urlencode_deep( $params );

	return add_query_arg( $params, site_url( 'wp-login.php', $scheme ) );
}
