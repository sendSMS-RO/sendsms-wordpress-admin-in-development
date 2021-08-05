<?php
require_once plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . 'lib' . DIRECTORY_SEPARATOR . 'functions.php';
require_once plugin_dir_path( dirname( dirname( __FILE__ ) ) ) . 'lib' . DIRECTORY_SEPARATOR . 'sendsms.class.php';
if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Sendsms_Dashboard_Subscribers extends WP_List_Table {



	var $table_name;
	var $wpdb;
	var $functions;
	var $api;
	function __construct() {
		global $wpdb;
		$this->wpdb       = $wpdb;
		$this->table_name = $this->wpdb->prefix . 'sendsms_dashboard_subscribers';
		$this->functions  = new SendSMSFunctions();
		$this->api        = new SendSMS();
		// Set parent defaults
		parent::__construct(
			array(
				'singular' => 'subscriber',     // singular name of the listed records
				'plural'   => 'subscribers',    // plural name of the listed records
				'ajax'     => false,        // does this table support ajax?
			)
		);
	}

	function column_default( $item, $column_name ) {
		return '<p>' . esc_html( $item[ $column_name ] ) . '</p>';
	}

	function column_phone( $issue ) {
		// for edition we are using a button to which we append a ajax request
		$nonce   = wp_create_nonce( 'sendsms-dashboard-subscribers-bulk-actions' );
		$actions = array(
			'inline' => sprintf( '<button type="button" data-sendsms-dashboard-phone="%s" class="button-link editinline sendsms-dashboard-subscribers-edit">Edit</button>', $issue['phone'] ),
			'delete' => sprintf(
				'<a href="?page=%s&action=%s&phone=%s&_wpnonce=%s">Delete</a>',
				sanitize_text_field( $_REQUEST['page'] ),
				'delete',
				$issue['phone'],
				$nonce
			),
		);

		return sprintf( '<p>%1$s</p> %2$s', $issue['phone'], $this->row_actions( $actions ) );
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="sendsms_dashboard_%1$s[]" value="%2$s" />',
			/*$1%s*/
			$this->_args['singular'],
			/*$2%s*/
			$item['phone']
		);
	}

	function get_columns() {
		$columns = array(
			'cb'         => '<input type="checkbox" />', // Render a checkbox instead of text
			'phone'      => __( 'Phone', 'sendsms-dashboard' ),
			'first_name' => __( 'First Name', 'sendsms-dashboard' ),
			'last_name'  => __( 'Last Name', 'sendsms-dashboard' ),
			'date'       => __( 'Date', 'sendsms-dashboard' ),
			'ip_address' => __( 'IP Address', 'sendsms-dashboard' ),
			'browser'    => __( 'Browser', 'sendsms-dashboard' ),
		);
		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'phone'      => array( 'phone', false ),
			'first_name' => array( 'first_name', false ),
			'last_name'  => array( 'last_name', false ),
			'date'       => array( 'date', false ),
			'ip_address' => array( 'ip_address', false ),
			'browser'    => array( 'browser', false ),
		);
		return $sortable_columns;
	}

	function get_bulk_actions() {
		$actions = array(
			'delete-bulk' => 'Delete',
		);
		return $actions;
	}

	function process_bulk_action() {
		switch ( $this->current_action() ) {
			case 'delete':
				if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'sendsms-dashboard-subscribers-bulk-actions' ) ) {
					die();
				}
				$phone = $this->functions->clear_phone_number( $_GET['phone'] );
				if ( $this->functions->is_subscriber_db( $phone ) ) {
					$synced = $this->functions->get_subscriber_db( $phone )[0]['synced'];
					if ( ! is_null( $synced ) ) {
						$this->api->delete_contact( $synced );
					}
					$this->functions->remove_subscriber_db( $phone );
				}
				break;
			case 'edit':
				if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'sendsms-dashboard-subscribers-bulk-actions' ) ) {
					die();
				}
				break;
			case 'delete-bulk':
				if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
					die();
				}
				foreach ( $_POST['sendsms_dashboard_subscriber'] as $phone ) {
					$phone = $this->functions->clear_phone_number( $phone );
					if ( $this->functions->is_subscriber_db( $phone ) ) {
						$synced = $this->functions->get_subscriber_db( $phone )[0]['synced'];
						if ( ! is_null( $synced ) ) {
							$this->api->delete_contact( $synced );
						}
						$this->functions->remove_subscriber_db( $phone );
					}
				}
				break;
			default:
				break;
		}
	}

	function prepare_items() {
		$per_page   = 10;
		$columns    = $this->get_columns();
		$hidden     = array();
		$sortable   = $this->get_sortable_columns();
		$table_name = $this->wpdb->prefix . 'sendsms_dashboard_subscribers';

		// Column headers
		$this->_column_headers = array( $columns, $hidden, $sortable, 'phone' );
		$this->process_bulk_action();

		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		$search = '';

		if ( ! empty( $_REQUEST['s'] ) ) {
			if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) {
				die();
			}
			$search  = "AND phone LIKE '%" . esc_sql( $this->wpdb->esc_like( $_REQUEST['s'] ) ) . "%' ";
			$search .= "OR first_name LIKE '%" . esc_sql( $this->wpdb->esc_like( $_REQUEST['s'] ) ) . "%' ";
			$search .= "OR last_name LIKE '%" . esc_sql( $this->wpdb->esc_like( $_REQUEST['s'] ) ) . "%' ";
			$search .= "OR date LIKE '%" . esc_sql( $this->wpdb->esc_like( $_REQUEST['s'] ) ) . "%' ";
			$search .= "OR ip_address LIKE '%" . esc_sql( $this->wpdb->esc_like( $_REQUEST['s'] ) ) . "%' ";
			$search .= "OR browser LIKE '%" . esc_sql( $this->wpdb->esc_like( $_REQUEST['s'] ) ) . "%' ";
		}

		if ( isset( $_GET['orderby'] ) && isset( $columns[ $_GET['orderby'] ] ) ) {
			$orderBy = sanitize_text_field( $_GET['orderby'] );
			if ( isset( $_GET['order'] ) ) {
				if ( in_array( strtolower( $_GET['order'] ), array( 'asc', 'desc' ) ) ) {
					$order = sanitize_text_field( $_GET['order'] );
				}
			} else {
				$order = 'ASC';
			}
		} else {
			$orderBy = 'date';
			$order   = 'DESC';
		}

		$items = $this->wpdb->get_results(
			"SELECT phone, first_name, last_name, date, ip_address, browser FROM $table_name WHERE 1 = 1 {$search}" .
				$this->wpdb->prepare( "ORDER BY `$orderBy` $order LIMIT %d OFFSET %d;", $per_page, $offset ),
			ARRAY_A
		);

		$count = $this->wpdb->get_var( "SELECT COUNT(date) FROM $table_name WHERE 1 = 1 {$search};" );

		$this->items = $items;

		// Set the pagination
		$this->set_pagination_args(
			array(
				'total_items' => $count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $count / $per_page ),
			)
		);
	}
}
