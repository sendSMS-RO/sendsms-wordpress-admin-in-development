<?php
require_once 'functions.php';
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
        $content = $content;
        $this->functions->get_auth($username, $password, $label);
        $to = $this->functions->validate_phone($to);
        $args['headers'] = [
            'url' => get_site_url()
        ];
        $results = array();
        if (strtolower($type) === "code") {
            if (!strpos($content, '{code}')) {
                $content .= '{code}';
            }
            $code = $this->functions->generateVerificationCode($to);
            $newContent = str_replace('{code}', $code, $content);
            $results = json_decode(wp_remote_retrieve_body(wp_remote_get('https://api.sendsms.ro/json?action=message_send&username=' . urlencode($username) . '&password=' . urlencode($password) . '&from=' . urlencode($label) . '&to=' . urlencode($to) . '&text=' . urlencode($newContent), $args)), true);
        } else {
            $results = json_decode(wp_remote_retrieve_body(wp_remote_get('https://api.sendsms.ro/json?action=message_send' . ($gdpr ? "_gdpr" : "") . '&username=' . urlencode($username) . '&password=' . urlencode($password) . '&from=' . urlencode($label) . '&to=' . urlencode($to) . '&text=' . urlencode($content) . '&short=' . ($short ? 'true' : 'false'), $args)), true);
        }
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
     * Get user balance
     * 
     * @since 1.0.0
     */
    function get_user_balance()
    {
        $this->functions->get_auth($username, $password, $label);
        $results = json_decode(wp_remote_retrieve_body(wp_remote_get('http://api.sendsms.ro/json?action=user_get_balance&username=' . urlencode($username) . '&password=' . urlencode($password))), true);
        return $results;
    }

    /**
     * Create a group on sendsms.ro
     * 
     * @since 1.0.0
     */
    function create_group()
    {
        $this->functions->get_auth($username, $password, $label);
        $name = 'Wordpress - ' . get_site_url();
        $results = json_decode(wp_remote_retrieve_body(wp_remote_get('https://api.sendsms.ro/json?action=address_book_group_add&username=' . urlencode($username) . '&password=' . urlencode($password) . '&name=' . urldecode($name))), true);
        return $results;
    }

    /**
     * Delete a group
     * 
     * @since 1.0.0
     */
    function delete_group($id)
    {
        $this->functions->get_auth($username, $password, $label);
        $results = json_decode(wp_remote_retrieve_body(wp_remote_get('https://api.sendsms.ro/json?action=address_book_group_delete&username=' . urlencode($username) . '&password=' . urlencode($password) . '&group_id=' . $id)), true);
        return $results;
    }

    /**
     * Get all groups from sensms.ro
     * 
     * @since 1.0.0
     */
    function get_groups()
    {
        $this->functions->get_auth($username, $password, $label);
        $results = json_decode(wp_remote_retrieve_body(wp_remote_get('https://api.sendsms.ro/json?action=address_book_groups_get_list&username=' . urlencode($username) . '&password=' . urlencode($password))), true);
        return $results;
    }

    /**
     * Add a contact to a group
     * 
     * @since 1.0.0
     */
    function add_contact($group_id, $first_name, $last_name, $phone_number)
    {
        $this->functions->get_auth($username, $password, $label);
        $results = json_decode(wp_remote_retrieve_body(wp_remote_get('https://api.sendsms.ro/json?action=address_book_contact_add&username=' . urlencode($username) . '&password=' . urlencode($password) . '&group_id=' . urlencode($group_id) . '&phone_number=' . urlencode($phone_number) . '&first_name=' . urlencode($first_name) . '&last_name=' . urlencode($last_name))), true);
        return $results;
    }

    /**
     * Delete a contact from sendsms
     * 
     * @since 1.0.0
     */
    function delete_contact($id)
    {
        $this->functions->get_auth($username, $password, $label);
        $results = json_decode(wp_remote_retrieve_body(wp_remote_get('https://api.sendsms.ro/json?action=address_book_contact_delete&username=' . urlencode($username) . '&password=' . urlencode($password) . '&contact_id=' . urlencode($id))), true);
        return $results;
    }
}
