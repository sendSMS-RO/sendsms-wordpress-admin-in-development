<?php
echo $args['before_widget'];
if (!empty($instance['title'])) {
    echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
}
wp_nonce_field('sendsms-security-nonce');
?>
<div id="sensms-widget-error-message" style="color:red"></div>
<div id="sensms-widget-success-message" style="color:green"></div>
<div id="sensms-widget-feedback-message"></div>
<div class="sendsms-widget-name-field">
    <label id="sendsms_forName"><?= __('Name', 'sendsms-dashboard') ?></label>
    <input id="sendsms_widget_name" type="text" aria-label="<?= __('Name', 'sendsms-dashboard') ?>" aria-describedby="sendsms_forName">
</div>
<div class="sendsms-widget-phone-field">
    <label id="sendsms_forPhoneNumber"><?= __('Phone number', 'sendsms-dashboard') ?></label>
    <input id="sendsms_widget_phone_number" type="tel" aria-label="<?= __('Phone number', 'sendsms-dashboard') ?>" aria-describedby="forPhoneNumber">
</div>
<div class="sendsms-widget-gdpr-field">
    <input id="sendsms_widget_gdpr" type="checkbox" aria-label="<?= __('I agree with the privacy policy', 'sendsms-dashboard') ?>" aria-describedby="sendsms_forGdpr">
    <label id="sendsms_forGdpr">
        <?php
        _e('I agree with the ', 'sendsms-dashboard');
        ?>
        <a href="<?= !empty($instance['gdpr_link']) ? esc_url($instance['gdpr_link']) : "" ?>"><?= _e('privacy policy', 'sendsms-dashboard') ?></a>
        <?php
        ?>
    </label>
</div>
<div class="sendsms-widget-send-button">
    <button class="button" id="sendsms_subscribe" type="button" aria-label="<?= __('Submit', 'sendsms-dashboard') ?>"><?= __('Submit', 'sendsms-dashboard') ?></button>
</div>
<?php echo $args['after_widget'];
