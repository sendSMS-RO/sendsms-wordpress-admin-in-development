<?php
require_once(plugin_dir_path(dirname(dirname(__FILE__))) . 'lib' . DIRECTORY_SEPARATOR . 'functions.php');
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

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

    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function column_phone($issue)
    {
        $nonce = wp_create_nonce('sendsms-dashboard-subscribers-bulk-actions');
        $actions = array(
            'delete'    => sprintf('<a href="?page=%s&action=%s&phone=%s&_wpnonce=%s">Delete</a>', $_REQUEST['page'], 'delete', $issue['phone'], $nonce),
        );

        return sprintf('%1$s %2$s', $issue['phone'], $this->row_actions($actions));
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="sendsms_dashboard_%1$s[]" value="%2$s" />',
            /*$1%s*/
            $this->_args['singular'],
            /*$2%s*/
            $item['phone']
        );
    }

    function get_columns()
    {
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'phone'            => __('Phone', 'sendsms-dashboard'),
            'name'         => __('Name', 'sendsms-dashboard'),
            'date'         => __('Date', 'sendsms-dashboard'),
            'ip_address'         => __('IP Address', 'sendsms-dashboard'),
            'browser'         => __('Browser', 'sendsms-dashboard')
        );
        return $columns;
    }


    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'phone' => array('phone', false),
            'name' => array('name', false),
            'date' => array('date', false),
            'ip_address' => array('ip_address', false),
            'browser' => array('browser', false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete-bulk'    => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        switch ($this->current_action()) {
            case 'delete':
                if (!wp_verify_nonce($_GET['_wpnonce'], 'sendsms-dashboard-subscribers-bulk-actions')) {
                    die();
                }
                $phone = $this->functions->clear_phone_number($_GET['phone']);
                $this->functions->remove_subscriber_db($phone);
                break;
            case 'delete-bulk':
                if (!wp_verify_nonce($_POST['_wpnonce'], 'bulk-' . $this->_args['plural'])) {
                    die();
                }
                foreach ($_POST['sendsms_dashboard_subscriber'] as $phone) {
                    $phone = $this->functions->clear_phone_number($phone);
                    $this->functions->remove_subscriber_db($phone);
                }
                break;
            default:
                break;
        }
    }

    function prepare_items()
    {
        $per_page = 10;
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $table_name = $this->wpdb->prefix . 'sendsms_dashboard_subscribers';

        // Column headers
        $this->_column_headers = array($columns, $hidden, $sortable, 'phone');
        $this->process_bulk_action();

        $current_page = $this->get_pagenum();
        if (1 < $current_page) {
            $offset = $per_page * ($current_page - 1);
        } else {
            $offset = 0;
        }

        $search = '';

        if (!empty($_REQUEST['s'])) {
            $search = "AND phone LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR name LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR date LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR ip_address LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR browser LIKE '%" . esc_sql($this->wpdb->esc_like($_REQUEST['s'])) . "%' ";
        }

        if (isset($_GET['orderby']) && isset($columns[$_GET['orderby']])) {
            if (!wp_verify_nonce($_POST['_wpnonce'], 'bulk-' . $this->_args['plural'])) {
                die();
            }
            $orderBy = sanitize_text_field($_GET['orderby']);
            if (isset($_GET['order'])) {
                if (in_array(strtolower($_GET['order']), array('asc', 'desc'))) {
                    $order = sanitize_text_field($_GET['order']);
                }
            } else {
                $order = 'ASC';
            }
        } else {
            $orderBy = 'date';
            $order = 'DESC';
        }

        $items = $this->wpdb->get_results(
            "SELECT phone, name, date, ip_address, browser FROM $table_name WHERE 1 = 1 {$search}" .
                $this->wpdb->prepare("ORDER BY `$orderBy` $order LIMIT %d OFFSET %d;", $per_page, $offset),
            ARRAY_A
        );

        $count = $this->wpdb->get_var("SELECT COUNT(date) FROM $table_name WHERE 1 = 1 {$search};");

        $this->items = $items;

        // Set the pagination
        $this->set_pagination_args(array(
            'total_items' => $count,
            'per_page'    => $per_page,
            'total_pages' => ceil($count / $per_page)
        ));
    }
}
