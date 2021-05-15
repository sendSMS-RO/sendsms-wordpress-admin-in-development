<?php
require_once(plugin_dir_path(dirname(__FILE__)) . 'lib' . DIRECTORY_SEPARATOR . 'functions.php');
require_once(plugin_dir_path(dirname(__FILE__)) . 'lib' . DIRECTORY_SEPARATOR . 'sendsms.class.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'extension' . DIRECTORY_SEPARATOR . 'sendsms-dashboard-subscribe-widget.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'extension' . DIRECTORY_SEPARATOR . 'sendsms-dashboard-unsubscribe-widget.php');

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sendsms_Dashboard
 * @subpackage Sendsms_Dashboard/public
 * @author     sendSMS <support@sendsms.ro>
 */
class Sendsms_Dashboard_Public
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
	private $api;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->functions = new SendSMSFunctions();
		$this->api = new SendSMS();
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/sendsms-dashboard-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sendsms-dashboard-public.js', array('jquery'), $this->version, false);
		wp_localize_script(
			$this->plugin_name,
			'sendsms_object_public',
			[
				'ajax_url' => admin_url('admin-ajax.php'),
				'security' => wp_create_nonce('sendsms-security-nonce'),
				'text_subscription_success' => __('Subscribed succesfuly', 'sendsms-dashboard'),
				'text_unsubscription_success' => __('Unsubscribed succesfuly', 'sendsms-dashboard'),
				'text_nogdpr' => __('You need to accept the privacy policy', 'sendsms-dashboard'),
				'text_internal_error' => __('Internal error', 'sendsms-dashboard'),
				'text_too_many_requests' => __('Too many requests', 'sendsms-dashboard'),
				'text_dublicate_number' => __('The number is already subscribed', 'sendsms-dashboard'),
				'text_ip_restricted' => __('You are unable to make a request from this ip', 'sendsms-dashboard'),
				'text_invalid_security_nonce' => __('Invalid security token sent.', 'sendsms-dashboard'),
				'text_field_phone_number' => __('The phone number field is either empty or it could not be converted to a valid phone number', 'sendsms-dashboard'),
				'text_field_first_name' => __('Please enter a first name', 'sendsms-dashboard'),
				'text_field_last_name' => __('Please enter a last name', 'sendsms-dashboard'),
				'text_waiting_validation' => __('Please enter the verification code sent to your phone via SMS', 'sendsms-dashboard'),
				'text_invalid_verification_code' => __('The verification field is empty or it is not valid', 'sendsms-dashboard'),
				'text_phone_not_found' => __('We were unable to find this phone number inside our database', 'sendsms-dashboard'),
			]
		);
	}

	public function widget_initialization()
	{
		register_widget('SendSMSSubscriber');
		register_widget('SendSMSUnsubscriber');
	}

	/**
	 * This will handle the ajax call of someone subscribing to your newsletter
	 */
	public function subscribe_to_newsletter()
	{
		if (!check_ajax_referer('sendsms-security-nonce', 'security', false)) {
			wp_send_json_error("invalid_security_nonce");
			wp_die();
		}
		if ($_POST['gdpr'] == 'false') {
			wp_send_json_error("nogdpr");
			wp_die();
		}
		error_log(json_encode($_POST));
		$first_name = sanitize_text_field($_POST['first_name']);
		if (empty($first_name)) {
			wp_send_json_error("field_first_name");
			wp_die();
		}
		$last_name = sanitize_text_field($_POST['last_name']);
		if (empty($last_name)) {
			wp_send_json_error("field_last_name");
			wp_die();
		}
		$phone = sanitize_text_field($this->functions->clear_phone_number($_POST['phone_number']));
		if (empty($phone)) {
			wp_send_json_error("field_phone_number");
			wp_die();
		}
		if ($this->functions->is_subscriber_db($phone)) {
			wp_send_json_error("dublicate_number");
			wp_die();
		} else {
			//doing ip checks
			$ip_address = $this->functions->get_ip_address();
			$restricted_ips = $this->functions->get_setting("restricted_ips", "");
			if ($this->functions->is_restricted_ip($ip_address, $restricted_ips)) {
				wp_send_json_error("ip_restricted");
				wp_die();
			}
			if ($this->functions->too_many_requests($ip_address)) {
				wp_send_json_error("too_many_requests");
				wp_die();
			}
			if ($this->functions->get_setting('subscribe_phone_verification', false)) {
				$content = $this->functions->get_setting("subscribe_verification_message", "");
				$result = $this->api->message_send(false, false, $phone, $content, "CODE");
				if ($result['status'] > 0) {
					wp_send_json("waiting_validation");
				} else {
					wp_send_json_error("internal_error");
				}
				wp_die();
			} else {
				$this->functions->add_subscriber_db($first_name, $last_name, $phone, $ip_address);
				wp_send_json_success("subscription_success");
				wp_die();
			}
		}
	}

	/**
	 * This will handle the unsubscription process
	 * 
	 * @since 1.0.0 
	 */
	function unsubscribe_from_newsletter()
	{
		if (!check_ajax_referer('sendsms-security-nonce', 'security', false)) {
			wp_send_json_error("invalid_security_nonce");
			wp_die();
		}
		$phone = sanitize_text_field($this->functions->clear_phone_number($_POST['phone_number']));
		if (empty($phone)) {
			wp_send_json_error("field_phone_number");
			wp_die();
		}
		if ($this->functions->is_subscriber_db($phone)) {
			//doing ip checks
			$ip_address = $this->functions->get_ip_address();
			$restricted_ips = $this->functions->get_setting("restricted_ips", "");
			if ($this->functions->is_restricted_ip($ip_address, $restricted_ips)) {
				wp_send_json_error("ip_restricted");
				wp_die();
			}
			if ($this->functions->too_many_requests($ip_address)) {
				wp_send_json_error("too_many_requests");
				wp_die();
			}
			if ($this->functions->get_setting('subscribe_phone_verification', false)) {
				$content = $this->functions->get_setting("subscribe_verification_message", "");
				$result = $this->api->message_send(false, false, $phone, $content, "CODE");
				if ($result['status'] > 0) {
					wp_send_json("waiting_validation");
				} else {
					wp_send_json_error("internal_error");
				}
				wp_die();
			} else {
				$this->functions->remove_subscriber_db($phone, $ip_address);
				wp_send_json_success("unsubscription_success");
				wp_die();
			}
		} else {
			wp_send_json_error("phone_not_found");
			wp_die();
		}
	}

	/**
	 * This will verify the subscribe code
	 */
	public function subscribe_verify_code()
	{
		$first_name = sanitize_text_field($_POST['first_name']);
		$last_name = sanitize_text_field($_POST['last_name']);
		$phone = sanitize_text_field($this->functions->clear_phone_number($_POST['phone_number']));
		if (!check_ajax_referer('sendsms-security-nonce', 'security', false)) {
			wp_send_json_error("invalid_security_nonce");
			wp_die();
		}
		if (!isset($_COOKIE['sendsms_subscribe_check']) || empty($first_name) || empty($last_name)) {
			wp_send_json_error("internal_error");
			wp_die();
		}
		$isValidToken = $this->functions->verifyVerificationCode($phone);
		if (empty($phone) || !$isValidToken) {
			wp_send_json_error("invalid_verification_code");
			wp_die();
		}
		$ip_address = $this->functions->get_ip_address();
		$this->functions->add_subscriber_db($first_name, $last_name, $phone, $ip_address);
		wp_send_json_success("subscription_success");
		wp_die();
	}

	/**
	 * This will verify the unsubscribe code
	 */
	public function unsubscribe_verify_code()
	{
		$phone = sanitize_text_field($this->functions->clear_phone_number($_POST['phone_number']));
		if (!check_ajax_referer('sendsms-security-nonce', 'security', false)) {
			wp_send_json_error("invalid_security_nonce");
			wp_die();
		}
		if (!isset($_COOKIE['sendsms_subscribe_check'])) {
			wp_send_json_error("internal_error");
			wp_die();
		}
		$isValidToken = $this->functions->verifyVerificationCode($phone);
		if (empty($phone) || !$isValidToken) {
			wp_send_json_error("invalid_verification_code");
			wp_die();
		}
		$ip_address = $this->functions->get_ip_address();
		$this->functions->remove_subscriber_db($phone, $ip_address);
		wp_send_json_success("unsubscription_success");
		wp_die();
	}
}
