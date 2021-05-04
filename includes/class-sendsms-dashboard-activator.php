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
		if( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
		global $wpdb;
		if (!current_user_can('activate_plugins'))
			return;
		$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
		check_admin_referer("activate-plugin_{$plugin}");

		$installed_ver = get_option('sendsms_dashboard_db_version');

		//updates
		//TO DO: refactor this...
		if ($installed_ver != SENDSMS_DB_VERSION) {
			$table_name_history = $wpdb->prefix . 'sendsms_dashboard_history';
			$table_name_subscribers = $wpdb->prefix . 'sendsms_dashboard_subscribers';
			$table_name_ip_address = $wpdb->prefix . 'sendsms_dashboard_ip_address';
			$charset_collate = $wpdb->get_charset_collate();

			$sql1 = "CREATE TABLE `$table_name_history` (
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
			$sql2 = "CREATE TABLE `$table_name_subscribers` (
				  `phone` varchar(50) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `date` datetime NOT NULL,
				  `ip_address` varchar(20) DEFAULT NULL,
				  `browser` text DEFAULT NULL, 
				  PRIMARY KEY (`phone`)
				) $charset_collate;";
			$sql3 = "CREATE TABLE `$table_name_ip_address` (
				  `ip_address` varchar(20) NOT NULL,
				  `date_cycle_start` datetime DEFAULT NULL,
				  `request_no` int DEFAULT NULL,
				  PRIMARY KEY (`ip_address`)
				) $charset_collate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql1);
			dbDelta($sql2);
			dbDelta($sql3);

			add_option('sendsms_dashboard_db_version', SENDSMS_DB_VERSION);
		}
	}
}
