<?php
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}
require_once plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . 'lib' . DIRECTORY_SEPARATOR . 'sendsms.class.php';

// add error/update messages
if ( isset( $_GET['settings-updated'] ) ) {
	// add settings saved message with the class of "updated"
	add_settings_error( 'sendsms-dashboard_messages', 'sendsms-dashboard_messages', __( 'Settings Saved', 'sendsms-dashboard' ), 'updated' );
}
$tabs = array(
	'general'      => __( 'General', 'sendsms-dashboard' ),
	'user'         => __( 'User', 'sendsms-dashboard' ),
	'subscription' => __( 'Subscription', 'sendsms-dashboard' ),
);
$api  = new SendSMS();
// show error/update messages
settings_errors( 'sendsms-dashboard_messages' );
?>
<div class="wrap">
	<h1 class="sendsms-text-center"><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<div class="sendsms-container-grid-settings">
		<div class="sendsms-item-input-1 sendsms-left-panel-settings">
			<img class="sendsms-image-center-xs" src=<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'img' . DIRECTORY_SEPARATOR . 'logo-test-area.png'; ?>>
			<p><?php echo __( 'If you don\'t have an account, you can register <a href="https://hub.sendsms.ro/register" target="_blank">here</a>', 'sendsms-dashboard' ); ?></p>
			<p>
				<?php
				$response = $api->get_user_balance();
				if ( $response['status'] >= 0 ) {
					echo __( 'Your current sendsms.ro balance is: €', 'sendsms-dashboard' ) . esc_html( $response['details'] );
				} else {
					echo __( 'Please configure your account first', 'sendsms-dashboard' );
				}
				?>
			</p>
			<ul class="sendsms-setting-list">
				<?php
				foreach ( $tabs as $key => $value ) {
					?>
					<li class="sendsms-setting-section-title"><a href=
					<?php
					echo add_query_arg(
						array(
							'settings-updated' => false,
							'tab'              => $key,
						)
					);
					?>
					><?php echo esc_html( $value ); ?></a></li>
					<?php
				}
				?>
			</ul>
		</div>
		<form class="sendsms-item-input-1" action="options.php" method="post">
			<?php
			settings_fields( 'sendsms_dashboard_plugin_settings' );
			?>
			<?php
			if ( ! isset( $_GET['tab'] ) ) {
				$_GET['tab'] = 'general';
			}
			$_GET['tab'] = sanitize_text_field( $_GET['tab'] );
			if ( ! array_key_exists( $_GET['tab'], $tabs ) ) {
				$_GET['tab'] = 'general';
			}

			foreach ( $tabs as $key => $value ) {
				?>
				<div <?php echo esc_html( $_GET['tab'] ) != $key ? "style='display:none'" : ''; ?>>
					<?php do_settings_sections( "sendsms_dashboard_plugin_$key" ); ?>
				</div>
				<?php
			}
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
</div>
