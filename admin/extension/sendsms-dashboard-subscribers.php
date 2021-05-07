<?php
require_once(plugin_dir_path(dirname(dirname(__FILE__))) . 'lib' . DIRECTORY_SEPARATOR . 'functions.php');

class Sendsms_Dashboard_Subscribers extends WP_List_Table
{
    var $table_name;
    var $wpdb;
    var $functions;
    function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $this->wpdb->prefix . 'sendsms_dashboard_subscribers';
        $this->functions = new SendSMSFunctions();
        //Set parent defaults
        parent::__construct(array(
            'singular'  => 'subscriber',     //singular name of the listed records
            'plural'    => 'subscribers',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ));
    }
    /**
     * Get list columns.
     *
     * @return array
     */
    public function get_columns()
    {
        return array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'phone'            => __('Phone', 'sendsms-dashboard'),
            'name'         => __('Name', 'sendsms-dashboard'),
            'date'         => __('Date', 'sendsms-dashboard'),
            'ip_address'         => __('IP Address', 'sendsms-dashboard'),
            'browser'         => __('Browser', 'sendsms-dashboard')
        );
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        error_log(json_encode($_GET));
        //Detect when a bulk action is being triggered...
        // if ('delete' === $this->current_action()) {
        //     if (!wp_verify_nonce($_GET['nonce'], 'sendsms-dashboard-subscribers-bulk-actions')) {
        //         die();
        //     }
        //     error_log("SETERGE");
        //     $phone = $this->functions->clear_phone_number($_GET['phone']);
        //     $this->functions->remove_subscriber_db($phone);
        // }
    }

    /**
     * Column cb.
     */
    function column_cb($issue)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/
            $this->_args['singular'],  
            /*$2%s*/
            $issue['phone']                
        );
    }

    /**
     * Return phone column
     */
    public function column_phone($issue)
    {
        $nonce = wp_create_nonce('sendsms-dashboard-subscribers-bulk-actions');
        $actions = array(
            'delete'    => sprintf('<a href="?page=%s&action=%s&phone=%s&nonce=%s">Delete</a>', $_REQUEST['page'], 'delete', $issue['phone'], $nonce),
        );

        return sprintf('%1$s %2$s', $issue['phone'], $this->row_actions($actions));
    }

    /**
     * Return message column
     */
    public function column_name($issue)
    {
        return $issue['name'];
    }

    /**
     * Return status column
     */
    public function column_date($issue)
    {
        return $issue['date'];
    }

    /**
     * Return details column
     */
    public function column_ip_address($issue)
    {
        return $issue['ip_address'];
    }

    /**
     * Return content column
     */
    public function column_browser($issue)
    {
        return $issue['browser'];
    }

    /**
     * Return type column
     */
    public function column_type($issue)
    {
        return $issue['type'];
    }

    /**
     * Return sent_on column
     */
    public function column_sent_on($issue)
    {
        return $issue['sent_on'];
    }

    public function get_sortable_columns()
    {
        return array(
            'phone' => array('phone', true),
            'name' => array('name', true),
            'date' => array('date', true),
            'ip_address' => array('ip_address', true),
            'browser' => array('browser', true)
        );
    }

    public function get_hiden_columns()
    {
        return array();
    }
    /**
     * Prepare table list items.
     */
    public function prepare_items()
    {
        $per_page = 10;
        $columns  = $this->get_columns();
        $hidden   = $this->get_hiden_columns();
        $sortable = $this->get_sortable_columns();

        $this->process_bulk_action();

        // Column headers
        $this->_column_headers = array($columns, $hidden, $sortable);

        $current_page = $this->get_pagenum();
        if (1 < $current_page) {
            $offset = $per_page * ($current_page - 1);
        } else {
            $offset = 0;
        }

        $search = '';

        //die();
        if (!empty($_REQUEST['s'])) {
            $search = "AND phone LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR name LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR date LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR ip_address LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR browser LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
        }


        if (isset($_POST['orderby']) && isset($columns[$_POST['orderby']])) {
            $orderBy = sanitize_text_field($_POST['orderby']);
            if (isset($_POST['order']) && in_array(strtolower($_POST['order']), array('asc', 'desc'))) {
                $order = sanitize_text_field($_POST['order']);
            } else {
                $order = 'ASC';
            }
        } else {
            $orderBy = 'date';
            $order = 'DESC';
        }

        $items = $this->wpdb->get_results(
            "SELECT phone, name, date, ip_address, browser FROM $this->table_name WHERE 1 = 1 {$search}" .
                $this->wpdb->prepare("ORDER BY `$orderBy` $order LIMIT %d OFFSET %d;", $per_page, $offset),
            ARRAY_A
        );

        $count = $this->wpdb->get_var("SELECT COUNT(date) FROM $this->table_name WHERE 1 = 1 {$search};");

        $this->items = $items;

        // Set the pagination
        $this->set_pagination_args(array(
            'total_items' => $count,
            'per_page'    => $per_page,
            'total_pages' => ceil($count / $per_page)
        ));
    }
}
