<?php
class SendSMSSubscriber extends WP_Widget
{
    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'sendssms_dashboard_subscriber',
            'description' => __('Use this widget so anyone can subscribe to your SMS newsletter. This widget complies with gdpr standards', 'sendsms-dashboard'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('sendssms_dashboard_subscriber', 'SendSMS Subscription', $widget_ops);
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        include plugin_dir_path(dirname(__FILE__)) . 'partials/sendsms-dashboard-subscription-widget.php';
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form($instance)
    {
        include plugin_dir_path(dirname(__FILE__)) . 'partials/sendsms-dashboard-subscription-widget-admin-form.php';
    }


    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['gdpr_link'] = (!empty($new_instance['gdpr_link'])) ? sanitize_text_field($new_instance['gdpr_link']) : '';

        return $instance;
    }
}
