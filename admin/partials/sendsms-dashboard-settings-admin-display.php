<?php
if (!current_user_can('manage_options')) {
    return;
}

// add error/update messages
if (isset($_GET['settings-updated'])) {
    // add settings saved message with the class of "updated"
    add_settings_error('sendsms-dashboard_messages', 'sendsms-dashboard_messages', __('Settings Saved', 'sendsms-dashboard'), 'updated');
}

// show error/update messages
settings_errors('sendsms-dashboard_messages');
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php
        settings_fields('sendsms_dashboard_general');
        do_settings_sections('sendsms_dashboard_general');
        // output save settings button
        submit_button('Save Settings');
        ?>
    </form>
</div>
