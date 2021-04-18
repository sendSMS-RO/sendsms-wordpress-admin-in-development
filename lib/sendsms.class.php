<?php
require_once('functions.php');
if (!defined('WPINC')) {
	die;
}

class SendSMS
{
    var $functions;

    function __construct()
    {
        $this->functions = new SendsmsFunctions();
    }
    /**
     * Send a message with sendsms
     * 
     * @since 1.0.0
     */
    function message_send($short, $gdpr, $to, $content, $type)
    {
        global $wpdb;
        $content = sanitize_textarea_field($content);
        $this->functions->get_auth($username, $password, $label);
        $to = $this->functions->validate_phone($to);
        error_log($to);
        $content = sanitize_textarea_field($content);
        $args['headers'] = [
            'url' => get_site_url()
        ];
        $results = json_decode(wp_remote_retrieve_body(wp_remote_get('https://api.sendsms.ro/json?action=message_send' . ($gdpr ? "_gdpr" : "") . '&username=' . urlencode($username) . '&password=' . urlencode($password) . '&from=' . urlencode($label) . '&to=' . urlencode($to) . '&text=' . urlencode($content) . '&short=' . ($short ? 'true' : 'false'), $args)), true);
        $table_name = $wpdb->prefix . 'sendsms_dashboard_history';
        $wpdb->query(
            $wpdb->prepare(
                "
                INSERT INTO $table_name
                (`phone`, `status`, `message`, `details`, `content`, `type`, `sent_on`)
                VALUES ( %s, %s, %s, %s, %s, %s, %s)",
                $to,
                isset($results['status']) ? $results['status'] : '',
                isset($results['message']) ? $results['message'] : '',
                isset($results['details']) ? $results['details'] : '',
                $content,
                $type,
                date('Y-m-d H:i:s')
            )
        );
        return $results;
    }
}
