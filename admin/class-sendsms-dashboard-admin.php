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
		wp_enqueue_style($this->plugin_name . "-bootstrap", plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sendsms-dashboard-admin.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . "-bootstrap", plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), $this->version, false);
		wp_localize_script(
			$this->plugin_name,
			'sendsms_ajax_object',
			[
				'ajax_url' => admin_url('admin-ajax.php'),
				'security' => wp_create_nonce('sendsms-security-nonce')
			]
		);
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
		add_submenu_page(
			$this->plugin_name,
			"Send a test",
			"Send a test SMS",
			"manage_options",
			$this->plugin_name . ' send a test',
			array($this, 'page_test')
		);
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

		add_settings_field(
			'sendsms_dashboard_password',
			__('SendSMS Password / Api Key', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_setting_password_callback'),
			'sendsms_dashboard_plugin',
			'sendsms_dashboard_general'
		);

		add_settings_field(
			'sendsms_dashboard_label',
			__('SendSMS Label', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_setting_label_callback'),
			'sendsms_dashboard_plugin',
			'sendsms_dashboard_general'
		);

		add_settings_field(
			'sendsms_dashboard_store_type',
			__('Do you own a Romanian store?', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_setting_store_type_callback'),
			'sendsms_dashboard_plugin',
			'sendsms_dashboard_general'
		);
	}

	public function sendsms_dashboard_settings_sanitize($args)
	{
		foreach ($args as $key => $value) {
			switch ($key) {
				case 'password':
					$args[$key] = trim($value);
					break;
				case 'store_type':
					$args[$key] = 1;
					break;
			}
		}
		return $args;
	}

	//TEST PAGE
	public function page_test()
	{
		include(plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-test-admin-display.php');
	}

	//Ajax handler
	public function send_a_test_sms()
	{
		error_log("ajunge aici");
		if (!check_ajax_referer('sendsms-security-nonce', 'security', false)) {
			wp_send_json_error('Invalid security token sent.');
			wp_die();
		}
		echo 'merge';
	}
	//EO TEST PAGE

	//SETINGS PAGE
	public function page_settings()
	{
		include(plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-settings-admin-display.php');
	}

	public function sendsms_dashboard_section_callback($args)
	{
		include(plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-settings-section-admin-display.php');
	}

	//Field creators
	public function sendsms_dashboard_setting_username_callback($args)
	{
		$setting = $this->get_setting('username');
?>
		<input type="text" name="sendsms_dashboard_plugin_settings[username]" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
	<?php
	}

	public function sendsms_dashboard_setting_password_callback($args)
	{
		$setting = $this->get_setting('password');
	?>
		<input type="password" name="sendsms_dashboard_plugin_settings[password]" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
	<?php
	}

	public function sendsms_dashboard_setting_label_callback($args)
	{
		$setting = $this->get_setting('label', '1898');
	?>
		<input type="text" name="sendsms_dashboard_plugin_settings[label]" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
	<?php
	}

	public function sendsms_dashboard_setting_store_type_callback($args)
	{
		$setting = $this->get_setting('store_type', false);
		error_log($setting);
	?>
		<input type="checkbox" name="sendsms_dashboard_plugin_settings[store_type]" value="true" <?= $setting ? "checked" : "" ?>>
		<p><?= __("This setting helps make phone number formatting easier", "sendsms-dashboard") ?></p>
<?php
	}
	//EO SETTINGS PAGE

	//GENERAL FUNCTIONS
	public function get_setting($setting, $default = "")
	{
		return esc_html(isset(get_option('sendsms_dashboard_plugin_settings')["$setting"]) ? get_option('sendsms_dashboard_plugin_settings')["$setting"] : $default);
	}
	//EO GENERAL FUNCTIONS
}
