<?php
require_once(plugin_dir_path(dirname(__FILE__)) . 'lib' . DIRECTORY_SEPARATOR . 'functions.php');
require_once(dirname(__FILE__) . '\extension' . DIRECTORY_SEPARATOR . 'sendsms-dashboard-subscribe-widget.php');
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
				'text_success' => __('Successful subscription', 'sendsms-dashboard'),
				'text_nogdpr' => __('You need to accept the privacy policy', 'sendsms-dashboard'),
				'text_internal_error' => __('Internal error', 'sendsms-dashboard'),
				'text_too_many_requests' => __('Too many requests', 'sendsms-dashboard'),
				'text_dublicate_number' => __('The number is already in the database', 'sendsms-dashboard'),
				'text_ip_restricted' => __('You are unable to make a request from this ip', 'sendsms-dashboard'),
			]
		);
	}

	public function subscribe_widget()
	{
		register_widget('SendSMSSubscriber');
	}

	/**
	 * This will handle the ajax call of someone subscribing to your newsletter
	 */
	public function subscribe_to_newsletter()
	{
	}
}
