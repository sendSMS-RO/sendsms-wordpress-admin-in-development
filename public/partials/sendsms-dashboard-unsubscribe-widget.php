<?php
echo $args['before_widget'];
if (!empty($instance['title'])) {
    echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
}
wp_nonce_field('sendsms-security-nonce');
?>
<div id="sendsms-widget-unsubscribe-error-message" style="color:red"></div>
<div id="sendsms-widget-unsubscribe-success-message" style="color:green"></div>
<div id="sendsms-widget-unsubscribe-feedback-message"></div>
<div id="sendsms-widget-unsubscribe-add-form">
    <div class="sendsms-widget-unsubscribe-phone-field">
        <label id="sendsms_forPhoneNumber"><?= __('Phone number', 'sendsms-dashboard') ?></label>
        <input id="sendsms_widget_unsubscribe_phone_number" type="number" aria-label="<?= __('Phone number', 'sendsms-dashboard') ?>" aria-describedby="sendsms_forPhoneNumber">
    </div>
    <div class="sendsms-widget-unsubscribe-send-button">
        <button class="button" id="sendsms_widget_unsubscribe_submit" type="button" aria-label="<?= __('Submit', 'sendsms-dashboard') ?>"><?= __('Submit', 'sendsms-dashboard') ?></button>
    </div>
</div>
<div id="sendsms-widget-unsubscribe-verify-form">
    <div id="sendsms-widget-unsubscribe-code-field">
        <label id="sendsms_forValidationField"><?= __('Code', 'sendsms-dashboard') ?></label>
        <input id="sendsms_widget_unsubscribe_validation_field" type="text" aria-label="<?= __('Code', 'sendsms-dashboard') ?>" aria-describedby="sendsms_forValidationField">
    </div>
    <div class="sendsms-widget-unsubscribe-validation-button">
        <button class="button" id="sendsms_widget_unsubscribe_validate" type="button" aria-label="<?= __('Verify', 'sendsms-dashboard') ?>"><?= __('Verify', 'sendsms-dashboard') ?></button>
    </div>
</div>
<?php echo $args['after_widget'];
