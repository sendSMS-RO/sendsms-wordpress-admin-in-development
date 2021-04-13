<?php
require_once(plugin_dir_path(dirname(__FILE__)) . 'extension' . DIRECTORY_SEPARATOR . 'sendsms-dashboard-history.php');
$table = new Sendsms_Dashboard_History();

?>
<div class="wrap">
    <h2><?= __('SendSMS - Historic', 'sendsms-dashboard') ?></h2>
    <form method="get">
        <?php
        $table->prepare_items();
        echo '<input type="hidden" name="page" value="sendsms-dashboard_history" />';
        $table->views();
        $table->search_box(__('Search', 'sendsms-dashboard'), 'key');
        $table->display();
        ?>
    </form>
</div>