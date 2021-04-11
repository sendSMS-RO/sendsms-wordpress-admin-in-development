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
		if (!current_user_can('activate_plugins'))
			return;
		$plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
		error_log($plugin);
		check_admin_referer("activate-plugin_{$plugin}");

		$installed_ver = get_option('sendsms_dashboard_db_version');

		//updates
		if ($installed_ver != SENDSMS_DASHBOARD_VERSION) {
			add_option('sendsms_dashboard_db_version', SENDSMS_DASHBOARD_VERSION);
		}
	}
}
