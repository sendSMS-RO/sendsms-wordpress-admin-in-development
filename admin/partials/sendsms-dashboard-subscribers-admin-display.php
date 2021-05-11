<?php
require_once(plugin_dir_path(dirname(__FILE__)) . 'extension' . DIRECTORY_SEPARATOR . 'sendsms-dashboard-subscribers.php');
$table = new Sendsms_Dashboard_Subscribers();
$table->prepare_items();

?>
<div class="wrap">
    <h2><?= __('SendSMS - Subscribers', 'sendsms-dashboard') ?></h2>
    <form id="sendsms-form" method="POST">
        <?php
        $table->search_box(__('Search', 'sendsms-dashboard'), 'key');
        $table->display();
        ?>
    </form>
</div>

<!-- This will be the editing form for a subscriber
    This will be taken as a copy and then inserted in the
    place of subscriber's data -->
<table>
    <tr class="sendsms-dashboard-edit-form"></tr>
    <tr class="sendsms-dashboard-edit-form sendsms-container">
        <td colspan="6">
            <div class="sendsms-edit-container">
                <p class="sendsms-edit-title"><?= __("Edit", "sendsms-dashboard") ?></p>
                <input type="hidden" id="sendsms_dashboard_edit_old_phone">
                <fieldset class="sendsms-edit-fieldset">
                    <label><?= __("Phone number", "sendsms-dashboard") ?></label>
                    <input id="sendsms_dashboard_edit_phone_number" type="tel">
                </fieldset>
                <fieldset class="sendsms-edit-fieldset">
                    <label><?= __("Name", "sendsms-dashboard") ?></label>
                    <input id="sendsms_dashboard_edit_name" type="text">
                </fieldset>
                <fieldset class="sendsms-edit-fieldset">
                    <label><?= __("Subscription date", "sendsms-dashboard") ?></label>
                    <input id="sendsms_dashboard_edit_date" type="datetime-local" step="1">
                </fieldset>
                <fieldset class="sendsms-edit-fieldset">
                    <label><?= __("IP Adress", "sendsms-dashboard") ?></label>
                    <input id="sendsms_dashboard_edit_ip_address" type="text">
                </fieldset>
                <fieldset class="sendsms-edit-fieldset">
                    <label><?= __("Browser", "sendsms-dashboard") ?></label>
                    <textarea id="sendsms_dashboard_edit_browser" type="text"></textarea>
                </fieldset>
                <div class="submit inline-save">
                    <button type="button" class="button button-primary alignleft sendsms-dashboard-subscribers-update"><?= __("Submit", "sendsms-dashboard") ?></button>
                    <button type="button" class="button alignright" onclick="cancelEdit()"><?= __("Cancel", "sendsms-dashboard") ?></button>
                </div>
            </div>
        </td>
    </tr>
</table>