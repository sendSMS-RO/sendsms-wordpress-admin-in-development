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
    <h1 class="sendsms-text-center"><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="sendsms-containter-grid-settings">
        <div class="sendsms-item-input-1 sendsms-left-panel-settings">
            <img class="sendsms-image-center-xs" src=<?php echo plugin_dir_url(dirname(__FILE__)) . 'img' . DIRECTORY_SEPARATOR . 'logo-test-area.png'; ?>>
            <p><?= __('If you don\'t have an account, you can register <a href="https://hub.sendsms.ro/register" target="_blank">here</a>', 'sendsms-dashboard') ?></p>
            <ul class="sendsms-setting-list">
                <li class="sendsms-setting-section-title"><a href=<?php echo add_query_arg(array('settings-updated' => false, 'tab' => 'general')); ?>>General</a></li>
                <li class="sendsms-setting-section-title"><a href=<?php echo add_query_arg(array('settings-updated' => false, 'tab' => 'user')); ?>>User</a></li>
                <li class="sendsms-setting-section-title"><a href=<?php echo add_query_arg(array('settings-updated' => false, 'tab' => 'subscription')); ?>>Subscription</a></li>
            </ul>
        </div>
        <form class="sendsms-item-input-1" action="options.php" method="post">
            <?php
            settings_fields('sendsms_dashboard_plugin_settings');
            ?>
            <?php
            $page = sanitize_text_field($_GET['tab']);
            if (empty($page))
                $page = 'general';
            do_settings_sections("sendsms_dashboard_plugin_$page");
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
</div>