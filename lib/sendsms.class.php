<?php
class SendSMS
{
    /**
     * Send a message with sendsms
     * 
     * @since 1.0.0
     */
    function message_send($short, $gdpr, $to, $content, $type)
    {
        global $wpdb;
        $content = sanitize_textarea_field($content);
        $this->get_auth($username, $password, $label);
        $to = $this->validate_phone($to);
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

    /**
     * Get Plugin settings
     * 
     * @since 1.0.0
     */
    function get_auth(&$username, &$password, &$label)
    {
        $username = $this->get_setting('username', '');
        $password = $this->get_setting('password', '');
        $label = $this->get_setting('label', '1898');
    }

    /**
     * Validate the phone number if needed
     * 
     * @since 1.0.0
     */
    function validate_phone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (substr($phone, 0, 1) == '0' && strlen($phone) == 10) {
            $phone = '4' . $phone;
        } elseif (substr($phone, 0, 1) != '0' && strlen($phone) == 9) {
            $phone = '40' . $phone;
        } elseif (strlen($phone) == 13 && substr($phone, 0, 2) == '00') {
            $phone = substr($phone, 2);
        }
        return $phone;
    }

    /**
     * Get an individual setting
     */
    public function get_setting($setting, $default = "")
    {
        return isset(get_option('sendsms_dashboard_plugin_settings')["$setting"]) ? get_option('sendsms_dashboard_plugin_settings')["$setting"] : $default;
    }
}
