<?php
require_once(plugin_dir_path(dirname(__FILE__)) . 'extension' . DIRECTORY_SEPARATOR . 'sendsms-dashboard-subscribers.php');
$table = new Sendsms_Dashboard_Subscribers();

?>
<div class="wrap">
    <h2><?= __('SendSMS - Subscribers', 'sendsms-dashboard') ?></h2>
    <form method="get">
        <?php
        $table->prepare_items();
        echo '<input type="hidden" name="page" value="sendsms-dashboard_subscribers" />';
        $table->views();
        $table->search_box(__('Search', 'sendsms-dashboard'), 'key');
        $table->display();
        ?>
    </form>
</div>