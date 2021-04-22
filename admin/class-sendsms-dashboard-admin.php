<?php
require_once(plugin_dir_path(dirname(__FILE__)) . 'lib' . DIRECTORY_SEPARATOR . 'sendsms.class.php');
require_once(plugin_dir_path(dirname(__FILE__)) . 'lib' . DIRECTORY_SEPARATOR . 'functions.php');
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

	private $functions;

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
		$this->functions = new SendSMSFunctions();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/sendsms-dashboard-admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . "-jBox", 'https://cdn.jsdelivr.net/gh/StephanWagner/jBox@v1.2.14/dist/jBox.all.min.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sendsms-dashboard-admin.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . "-font-awesome", plugin_dir_url(__FILE__) . 'js/all.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . "-jBox", 'https://cdn.jsdelivr.net/gh/StephanWagner/jBox@v1.2.7/dist/jBox.all.min.js', array('jquery'), $this->version, false);
		wp_localize_script(
			$this->plugin_name,
			'sendsms_object',
			[
				'ajax_url' => admin_url('admin-ajax.php'),
				'security' => wp_create_nonce('sendsms-security-nonce'),
				'text_message_contains_something' => __('The approximate number of messages: ', 'sendsms-dashboard'),
				'text_message_is_empty' => __('The field is empty', 'sendsms-dashboard'),
				'text_button_sending' => __('It\'s being sent...', 'sendsms-dashboard'),
				'text_button_send' => __('Send Message', 'sendsms-dashboard')
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
			__("Send a test", 'sendsms-dashboard'),
			__("Send a test SMS", 'sendsms-dashboard'),
			"manage_options",
			$this->plugin_name . '_send_a_test',
			array($this, 'page_test')
		);
		add_submenu_page(
			$this->plugin_name,
			__("History", 'sendsms-dashboard'),
			__("History", 'sendsms-dashboard'),
			"manage_options",
			$this->plugin_name . '_history',
			array($this, 'page_history')
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
			"<div class='sendsms-settings-title'>" . __('General Settings', 'sendsms-dashboard') . "</div>",
			array($this, 'sendsms_dashboard_section_callback'),
			'sendsms_dashboard_plugin_general'
		);

		add_settings_field(
			'sendsms_dashboard_username',
			__('SendSMS Username', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_setting_username_callback'),
			'sendsms_dashboard_plugin_general',
			'sendsms_dashboard_general'
		);

		add_settings_field(
			'sendsms_dashboard_password',
			__('SendSMS Password / Api Key', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_setting_password_callback'),
			'sendsms_dashboard_plugin_general',
			'sendsms_dashboard_general'
		);

		add_settings_field(
			'sendsms_dashboard_label',
			__('SendSMS Label', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_setting_label_callback'),
			'sendsms_dashboard_plugin_general',
			'sendsms_dashboard_general'
		);

		add_settings_field(
			'sendsms_dashboard_cc',
			__('Country Code', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_setting_cc_callback'),
			'sendsms_dashboard_plugin_general',
			'sendsms_dashboard_general'
		);

		add_settings_section(
			'sendsms_dashboard_user',
			"<div class='sendsms-settings-title'>" . __('User Settings', 'sendsms-dashboard') . "</div>",
			array($this, 'sendsms_dashboard_section_user_callback'),
			'sendsms_dashboard_plugin_user'
		);

		add_settings_field(
			'sendsms_dashboard_user_add_phone_field',
			__('Add phone number field?', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_user_add_phone_field_callback'),
			'sendsms_dashboard_plugin_user',
			'sendsms_dashboard_user'
		);

		add_settings_section(
			'sendsms_dashboard_subscription',
			"<div class='sendsms-settings-title'>" . __('Subscription Settings', 'sendsms-dashboard') . "</div>",
			array($this, 'sendsms_dashboard_section_subscription_callback'),
			'sendsms_dashboard_plugin_subscription'
		);

		add_settings_field(
			'sendsms_dashboard_subscribe_phone_verification_field',
			__('SMS verification?', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_subscribe_phone_verification_field_callback'),
			'sendsms_dashboard_plugin_subscription',
			'sendsms_dashboard_subscription'
		);

		add_settings_field(
			'sendsms_dashboard_subscribe_verification_message_field',
			__('Verification message?', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_subscribe_verification_message_field_callback'),
			'sendsms_dashboard_plugin_subscription',
			'sendsms_dashboard_subscription'
		);

		add_settings_field(
			'sendsms_dashboard_ip_limits_field',
			__('IP limit', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_ip_limit_field_callback'),
			'sendsms_dashboard_plugin_subscription',
			'sendsms_dashboard_subscription'
		);

		add_settings_field(
			'sendsms_dashboard_restricted_ips_field',
			__('Restricted IP addresses', 'sendsms-dashboard'),
			array($this, 'sendsms_dashboard_restricted_ips_field_callback'),
			'sendsms_dashboard_plugin_subscription',
			'sendsms_dashboard_subscription'
		);
	}

	/**
	 * Sanitize the settings before they are saved to the db
	 */
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

	//HISTORY PAGE
	public function page_history()
	{
		include(plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-history-admin-display.php');
	}
	//EO HISTORY PAGE

	//TEST PAGE
	public function page_test()
	{
		include(plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-test-admin-display.php');
	}

	//Ajax handler
	/**
	 * This will send a test message when an ajax event is called
	 */
	public function send_a_test_sms()
	{
		if (!check_ajax_referer('sendsms-security-nonce', 'security', false)) {
			wp_send_json_error(__('Invalid security token sent.', 'sendsms-dashboard'));
			wp_die();
		}
		if (empty($_POST['message'])) {
			wp_send_json_error(__('The message box is empty', 'sendsms-dashboard'));
		}
		$api = new SendSMS();
		$result = $api->message_send(
			$_POST['short'] == 'true' ? true : false,
			$_POST['gdpr'] == 'true' ? true : false,
			isset($_POST['phone_number']) ? $_POST['phone_number'] : "",
			isset($_POST['message']) ? $_POST['message'] : "",
			'TEST'
		);
		if ($result['status'] > 0) {
			wp_send_json_success(__('Message sent', 'sendsms-dashboard'));
		} else {
			wp_send_json_error(__('Status: ', 'sendsms-dashboard') . $result['status'] . __("<br>Message: ", 'sendsms-dashboard') . $result['message'] . __("<br>Details: ", 'sendsms-dashboard') . $result['details']);
		}
	}
	//EO TEST PAGE

	//SETINGS PAGE
	public function page_settings()
	{
		include(plugin_dir_path(__FILE__) . 'partials/sendsms-dashboard-settings-admin-display.php');
	}

	/**
	 * These are the callbacks to each section
	 */
	public function sendsms_dashboard_section_callback($args)
	{
	}

	public function sendsms_dashboard_section_user_callback($args)
	{
	}

	public function sendsms_dashboard_section_subscription_callback($args)
	{
	}
	//Field creators
	/**
	 * There functions will just display the fields of the setings page
	 */
	public function sendsms_dashboard_setting_username_callback($args)
	{
		$setting = $this->functions->get_setting_esc('username');
?>
		<input type="text" name="sendsms_dashboard_plugin_settings[username]" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
	<?php
	}

	public function sendsms_dashboard_setting_password_callback($args)
	{
		$setting = $this->functions->get_setting_esc('password');
	?>
		<input type="password" name="sendsms_dashboard_plugin_settings[password]" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
	<?php
	}

	public function sendsms_dashboard_setting_label_callback($args)
	{
		$setting = $this->functions->get_setting_esc('label', '1898');
	?>
		<input type="text" name="sendsms_dashboard_plugin_settings[label]" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
	<?php
	}

	public function sendsms_dashboard_setting_cc_callback($args)
	{
		$setting = $this->functions->get_setting_esc('cc', "INT");
	?>
		<select type="checkbox" name="sendsms_dashboard_plugin_settings[cc]">
			<option value="INT">International</option>
			<?php
			foreach ($this->functions->country_codes as $key => $value) {
				echo "<option value='$key' " . ($setting == $key ? "selected" : "") . ">$key (+$value)</option>";
			}
			?>
		</select>
	<?php
	}

	public function sendsms_dashboard_user_add_phone_field_callback($args)
	{
		$setting = $this->functions->get_setting_esc('add_phone_field', false);
	?>
		<input type="checkbox" name="sendsms_dashboard_plugin_settings[add_phone_field]" value="1" <?= $setting ? "checked" : "" ?>>
		<p class="sendsms-dashboard-subscript"><?= __("Add a phone number field in the user editing and user registration form", "sendsms-dashboard") ?></p>
	<?php
	}

	public function sendsms_dashboard_ip_limit_field_callback($args)
	{
		$setting = $this->functions->get_setting_esc('ip_limit', '');
	?>
		<input type="text" name="sendsms_dashboard_plugin_settings[ip_limit]" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
		<p class="sendsms-dashboard-subscript"><?= __("The maximum number of subscriptions/unsubscriptions an IP address can make per minute. This is used as follows: maximum_ip_addresses/minutes (eg: 5/10 - 5 maximum registrations every 10 minutes). You can use -1 for no restrictions (eg: 5/-1 - 5 maximum registrations on that ip). No restriction will be applied if the field is empty or if it has invalid characters.", 'sendsms-dashboard') ?></p>
	<?php
	}

	public function sendsms_dashboard_restricted_ips_field_callback($args)
	{
		$setting = $this->functions->get_setting_esc('restricted_ips', '');
	?>
		<textarea cols="30" rows="5" name="sendsms_dashboard_plugin_settings[restricted_ips]"><?php echo isset($setting) ? esc_textarea($setting) : ''; ?></textarea>
		<p class="sendsms-dashboard-subscript"><?= __("These ip addresses will not be able to register subscribe/unsubscribe. Put every IP address on a separed line.", 'sendsms-dashboard') ?></p>
	<?php
	}

	public function sendsms_dashboard_subscribe_verification_message_field_callback($args)
	{
		$setting = $this->functions->get_setting_esc('subscribe_verification_message', '');
	?>
		<textarea cols="30" rows="5" name="sendsms_dashboard_plugin_settings[subscribe_verification_message]"><?php echo isset($setting) ? esc_textarea($setting) : ''; ?></textarea>
		<p class="sendsms-dashboard-subscript"><?= __("You must specify the {code} key message. The {code} key will be automatically replaced with the unique validation code. If the {code} key is not specified, the validation code will be placed at the end of the message", 'sendsms-dashboard') ?></p>
	<?php
	}

	public function sendsms_dashboard_subscribe_phone_verification_field_callback($args)
	{
		$setting = $this->functions->get_setting_esc('subscribe_phone_verification', false);
	?>
		<input type="checkbox" name="sendsms_dashboard_plugin_settings[subscribe_phone_verification]" value="1" <?= $setting ? "checked" : "" ?>>
		<p class="sendsms-dashboard-subscript"><?= __("This will send a verification code when someone subscribe/unsubscribe", "sendsms-dashboard") ?></p>
<?php
	}

	//EO SETTINGS PAGE

	/**
	 * Add the phone number field inside the add new user
	 * 
	 * @since 1.0.0	 
	 */
	public function add_new_user_field()
	{
		include(plugin_dir_path(__FILE__) . 'partials/user/sendsms-dashboard-mobile-field.php');
	}

	/**
	 * Save the phone number to db
	 * 
	 * @since 1.0.0
	 */
	public function user_register_metadata($user_id)
	{
		if (isset($_POST['sendsms_phone_number'])) {
			update_user_meta($user_id, 'sendsms_phone_number', $_POST['sendsms_phone_number']);
		}
	}

	/**
	 * Show the phone number field in the editing page of an user
	 * 
	 * @since 1.0.0
	 */
	public function add_new_user_field_to_edit_form($args)
	{
		$fields['sendsms_phone_number'] = __('Phone number', 'sendsms-dashboard');

		return $fields;
	}

	/**
	 * Add a field to the register form aka wp-login.php
	 */
	public function add_register_field()
	{
		include(plugin_dir_path(__FILE__) . 'partials/user/sendsms-dashboard-mobile-field-register.php');
	}
}
