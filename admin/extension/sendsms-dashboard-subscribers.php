<?php
class Sendsms_Dashboard_Subscribers extends WP_List_Table
{
    /**
     * Get list columns.
     *
     * @return array
     */
    public function get_columns()
    {
        return array(
            'phone'            => __('Phone', 'sendsms-dashboard'),
            'name'         => __('Name', 'sendsms-dashboard'),
            'date'         => __('Date', 'sendsms-dashboard'),
            'ip_address'         => __('IP Address', 'sendsms-dashboard'),
            'browser'         => __('Browser', 'sendsms-dashboard')
        );
    }

    /**
     * Column cb.
     */
    public function column_cb($issue)
    {
        return '<input type="checkbox" name="sendsms-dashboard_subscribers[]"/>';
    }

    /**
     * Return phone column
     */
    public function column_phone($issue)
    {
        $actions = array(
            'delete'    => sprintf('<a href="?page=%s&action=%s&phone=%s">Delete</a>', $_REQUEST['page'], 'delete', $issue['phone']),
        );

        return sprintf('%1$s %2$s', $issue['phone'], $this->row_actions($actions));
        return $issue['phone'];
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

    /**
     * Get bulk actions.
     *
     * @return array
     */
    protected function get_bulk_actions()
    {
        return array();
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
        global $wpdb;

        $per_page = 10;
        $columns  = $this->get_columns();
        $hidden   = $this->get_hiden_columns();
        $sortable = $this->get_sortable_columns();
        $table_name = $wpdb->prefix . 'sendsms_dashboard_subscribers';

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
            $search = "AND phone LIKE '%" . esc_sql($wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR name LIKE '%" . esc_sql($wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR date LIKE '%" . esc_sql($wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR ip_address LIKE '%" . esc_sql($wpdb->esc_like($_REQUEST['s'])) . "%' ";
            $search .= "OR browser LIKE '%" . esc_sql($wpdb->esc_like($_REQUEST['s'])) . "%' ";
        }


        if (isset($_GET['orderby']) && isset($columns[$_GET['orderby']])) {
            $orderBy = sanitize_text_field($_GET['orderby']);
            if (isset($_GET['order']) && in_array(strtolower($_GET['order']), array('asc', 'desc'))) {
                $order = sanitize_text_field($_GET['order']);
            } else {
                $order = 'ASC';
            }
        } else {
            $orderBy = 'date';
            $order = 'DESC';
        }

        $items = $wpdb->get_results(
            "SELECT phone, name, date, ip_address, browser FROM $table_name WHERE 1 = 1 {$search}" .
                $wpdb->prepare("ORDER BY `$orderBy` $order LIMIT %d OFFSET %d;", $per_page, $offset),
            ARRAY_A
        );

        $count = $wpdb->get_var("SELECT COUNT(date) FROM $table_name WHERE 1 = 1 {$search};");

        $this->items = $items;

        // Set the pagination
        $this->set_pagination_args(array(
            'total_items' => $count,
            'per_page'    => $per_page,
            'total_pages' => ceil($count / $per_page)
        ));
    }
}
