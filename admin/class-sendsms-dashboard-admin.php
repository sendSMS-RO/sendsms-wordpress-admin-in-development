<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sendsms_Dashboard
 * @subpackage Sendsms_Dashboard/admin
 * @author     sendSMS <support@sendsms.ro>
 */
class Sendsms_Dashboard_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;



	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/sendsms-dashboard-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sendsms-dashboard-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Loads the menu file
	 * 
	 * @since	1.0.0
	 */
	public function load_menu()
	{
		add_menu_page(
			__('SendSMS Dashboard', 'sendsms-dashboard'),
			__('SendSMS', 'sendsms-dashboard'),
			'manage_options',
			$this->plugin_name,
			array($this, 'page_settings'),
			plugin_dir_url(__FILE__) . 'img/sendsms-dashboard-setting.png'
		);
		#this will add a submenu
		// add_submenu_page(
		// 	$this->plugin_name,
		// 	"Test",
		// 	"Test Title",
		// 	"manage_options",
		// 	$this->plugin_name . "idk yet",
		// 	plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-test-admin-display.php'
		// );
	}

	/**
	 * Register setting fields
	 * 
	 * @since	1.0.0
	 */
	public function load_settings()
	{
		register_setting(
			'sendsms_dashboard_plugin_settings',
			'sendsms_dashboard_plugin_settings',
			array($this, 'sendsms_dashboard_settings_sanitize')
		);

		add_settings_section(
			'sendsms_dashboard_general',
			__('General Settings', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_section_callback'),
			'sendsms_dashboard_plugin'
		);

		add_settings_field(
			'sendsms_dashboard_username',
			__('SendSMS Username', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_setting_username_callback'),
			'sendsms_dashboard_plugin',
			'sendsms_dashboard_general'
		);
	}

	public function sendsms_dashboard_settings_sanitize($args)
	{
		error_log(json_encode($args));
		return $args;
	}

	public function page_settings()
	{
		include(plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-settings-admin-display.php');
	}

	public function sendsms_dashboard_section_callback($args)
	{
		include(plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-settings-section-admin-display.php');
	}

	//Validators
	public function sendsms_dashboard_setting_username_callback($args)
	{
		$setting = get_option('sendsms_dashboard_plugin_settings')['username'];
		error_log($setting);
		// output the field
?>
		<input type="text" name="sendsms_dashboard_plugin_settings[username]" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
	}
}
