<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sendsms_Dashboard
 * @subpackage Sendsms_Dashboard/includes
 * @author     sendSMS <support@sendsms.ro>
 */
class Sendsms_Dashboard_i18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'sendsms-dashboard',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
