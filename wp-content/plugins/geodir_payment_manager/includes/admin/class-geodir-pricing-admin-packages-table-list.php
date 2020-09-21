<?php
/**
 * Pricing Manager Admin Packages Table List.
 *
 * @since 2.5.0
 * @package GeoDir_Pricing_Manager
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * GeoDir_Pricing_Admin_Packages_Table_List class.
 */
class GeoDir_Pricing_Admin_Packages_Table_List extends WP_List_Table {

	/**
	 * Initialize the webhook table list.
	 */
	public function __construct() {
		parent::__construct( array(
			'singular' => 'package',
			'plural'   => 'packages',
			'ajax'     => false,
		) );
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'            => '<input type="checkbox" />',
			'name'   		=> __( 'Name', 'geodir_pricing' ),
			'amount'   		=> __( 'Price', 'geodir_pricing' ),
			'lifetime'   	=> __( 'Life Time', 'geodir_pricing' ),
			'is_default' 	=> __( 'Default', 'geodir_pricing' ),
			'status'   		=> __( 'Status', 'geodir_pricing' ),
			'display_order' => __( 'Order', 'geodir_pricing' ),
			'id'   			=> __( 'ID', 'geodir_pricing' ),
		);

		return apply_filters( 'geodir_pricing_admin_list_packages_columns', $columns );
	}

	function get_sortable_columns() {
        $sortable_columns = array(
			'name'       		=> array( 'name', false ),
			'amount'       		=> array( 'amount', false ),
			'is_default'       	=> array( 'is_default', true ),
            'status' 			=> array( 'status', true ),
			'display_order' 	=> array( 'display_order', false ),
			'id' 				=> array( 'id', false )
        );
        return apply_filters( 'geodir_pricing_admin_list_packages_sortable_columns', $sortable_columns );
    }

	public function column_cb( $item ) {
		$cb = '<input type="hidden" class="gd-has-id" data-delete-nonce="' . esc_attr( wp_create_nonce( 'geodir-delete-package-' . $item['id'] ) ) . '" data-set-default-nonce="' . esc_attr( wp_create_nonce( 'geodir-set-default-' . $item['id'] ) ) . '" data-package-id="' . $item['id'] . '" value="' . $item['id'] . '" />';
		$cb .= '<input type="checkbox" name="package[]" value="' . $item['id'] . '" />';
		return $cb;
	}

	public function column_id( $item ) {
		return $item['id'];
	}

	public function column_name( $item ) {
		$edit_link = esc_url(
            add_query_arg(
                array(
                    'post_type' => $item['post_type'],
					'page' => $item['post_type'] . '-settings',
					'tab' => 'cpt-package',
					'section' => 'add-package',
					'id' => $item['id'],
                ),
                admin_url( 'edit.php' )
            )
        );

		$actions = array();
        $actions[ 'edit' ] = sprintf( '<a href="%s">%s</a>', $edit_link, __( 'Edit', 'geodir_pricing' ) );
		if ( empty( $item['is_default'] ) ) {
			$actions[ 'delete' ] = sprintf( '<a href="javascript:void(0)" class="geodir-delete-package geodir-act-delete">%s</a>', __( 'Delete', 'geodir_pricing' ) );
		}
		$actions = apply_filters( 'geodir_pricing_admin_list_packages_column_actions', $actions, $item );
		
		return sprintf(
            '<strong><a href="%s" class="row-package">%s</strong>%s',
            $edit_link,
            $item['name'],
            $this->row_actions( $actions )
        );
	}
	
	public function column_amount( $item ) {
		return $item['amount'] > 0 ? geodir_pricing_price( $item['amount'] ) : __( 'Free', 'geodir_pricing' );
	}

	public function column_lifetime( $item ) {
		$value = geodir_pricing_display_lifetime( $item['time_interval'], $item['time_unit'] );
		if ( ! empty( $item['recurring'] ) && ( $item['recurring_limit'] > 1 || empty( $item['recurring_limit'] ) ) ) {
			$value .= ' x ' . ( empty( $item['recurring_limit'] ) ? '&infin;' : $item['recurring_limit'] );
		}
		return $value;
	}

	public function column_is_default( $item ) {
		return '<input ' . checked( true, ! empty( $item['is_default'] ), false ) . ' value="' . $item['id'] . '" name="default_package" id="gd_package_default" class="geodir-package-set-default" type="radio" title="' . esc_attr__( 'Set Default', 'geodir_pricing' ) . '">';
	}

	public function column_display_order( $item ) {
		return $item['display_order'];
	}

	public function column_status( $item ) {
		$status = ! empty( $item['status'] ) ? '<i class="fas fa-check" aria-hidden="true"></i>' : '<i class="fas fa-times" aria-hidden="true"></i>';;
		return '<span class="package-status">' . $status . '</span>';
	}

	public function column_default( $item, $column_name ) {
		ob_start();
		do_action( 'geodir_pricing_admin_list_packages_column', $item, $column_name );
		$value = ob_get_contents();
		ob_end_clean();
		return $value;
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		return array(
			//'delete' => __( 'Delete', 'geodir_pricing' ),
		);
	}

	/**
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {
		if ( 'top' !== $which ) {
			return;
		}
		ob_start();

		do_action( 'geodir_pricing_restrict_manage_packages', $which );

		$actions = ob_get_clean();

		if ( trim( $actions ) == '' ) {
			return;
		}
		?>
		<div class="alignleft actions">
		<?php
			echo $actions;

			submit_button( __( 'Filter' ), '', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
		?>
		</div>
		<?php
	}

	/**
	 * Prepare table list items.
	 */
	public function prepare_items() {
		global $wpdb;

		$post_type = ! empty( $_REQUEST['post_type'] ) && geodir_is_gd_post_type( $_REQUEST['post_type'] ) ? sanitize_text_field( $_REQUEST['post_type'] ) : 'gd_place';
		$per_page = apply_filters( 'geodir_pricing_packages_settings_items_per_page', 10 );
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();

		// Column headers
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		$where = array( $wpdb->prepare( "post_type = %s", $post_type ) );

		if ( ! empty( $_REQUEST['s'] ) ) {
			$where[] = "name LIKE '%" . esc_sql( $wpdb->esc_like( geodir_clean( $_REQUEST['s'] ) ) ) . "%' OR title LIKE '%" . esc_sql( $wpdb->esc_like( geodir_clean( $_REQUEST['s'] ) ) ) . "%'";
		}

		$where = "WHERE " . implode( ' AND ', $where );

		$orderby = ! empty( $_REQUEST['orderby'] ) ? geodir_clean( $_REQUEST['orderby'] ) : '';
		if ( ! isset( $sortable[ $orderby ] ) ) {
			$orderby = 'id';
		}
		$order = ! empty( $_REQUEST['order'] ) && $_REQUEST['order'] == 'desc' ? 'DESC' : 'ASC';
		$orderby = "ORDER BY {$orderby} {$order}";

		$results = $wpdb->get_results(
			"SELECT id FROM " . GEODIR_PRICING_PACKAGES_TABLE . " {$where} {$orderby} " .
			$wpdb->prepare( "LIMIT %d OFFSET %d;", $per_page, $offset ), ARRAY_A
		);

		$items = array();
		if ( ! empty( $results ) ) {
			foreach ( $results as $key => $row ) {
				$items[ $key ] = GeoDir_Pricing_Package::get_package( $row['id'], ARRAY_A );
			}
		}

		$count = $wpdb->get_var( "SELECT COUNT(id) FROM " . GEODIR_PRICING_PACKAGES_TABLE . " {$where};" );

		$this->items = $items;

		// Set the pagination
		$this->set_pagination_args( array(
			'total_items' => $count,
			'per_page'    => $per_page,
			'total_pages' => ceil( $count / $per_page ),
		) );
	}

	public function single_row( $item ) {
        static $row_class = '';
        $row_class = ( $row_class == '' ? 'alternate' : '' );

        printf('<tr class="%s geodir-package-row geodir-package-status-%s " data-status="' . $item['status'] . '" data-post-type="' . $item['post_type'] . '" data-default="' . $item['is_default'] . '">', $row_class, $item['status']);
        $this->single_row_columns( $item );
        echo '</tr>';
    }
}
