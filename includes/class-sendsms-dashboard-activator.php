<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sendsms_Dashboard
 * @subpackage Sendsms_Dashboard/includes
 * @author     sendSMS <support@sendsms.ro>
 */
class Sendsms_Dashboard_Activator
{

	/**
	 * Make all the preparations for the plugin
	 *
	 * Create the necesary databases. We will delete the database only when 
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $wpdb;
		if (!current_user_can('activate_plugins'))
			return;
		$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
		check_admin_referer("activate-plugin_{$plugin}");

		$installed_ver = get_option('sendsms_dashboard_db_version');

		//updates
		if ($installed_ver != SENDSMS_DASHBOARD_VERSION) {
			error_log($wpdb->prefix);
			$table_name = $wpdb->prefix . 'sendsms_dashboard_history';
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE `$table_name` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `phone` varchar(255) DEFAULT NULL,
				  `status` varchar(255) DEFAULT NULL,
				  `message` varchar(255) DEFAULT NULL,
				  `details` longtext,
				  `content` longtext,
				  `type` varchar(255) DEFAULT NULL,
				  `sent_on` datetime DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			add_option('sendsms_dashboard_db_version', SENDSMS_DASHBOARD_VERSION);
		}
	}
}
