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