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
    <tr class="sendsms-dashboard-edit-form">
        <td>
            <!-- TODO: Add a token to check if the phone number is corect -->
            <input type="hidden" id="sendsms_dashboard_edit_old_phone">
            <th><input id="sendsms_dashboard_edit_phone_number" type="tel"></th>
            <th><input id="sendsms_dashboard_edit_name" type="text"></th>
            <th><input id="sendsms_dashboard_edit_date" type="datetime-local"></th>
            <th><input id="sendsms_dashboard_edit_ip_address" type="text"></th>
            <th><textarea id="sendsms_dashboard_edit_browser" type="text"></th> 
        </td>
    </tr>
</table>