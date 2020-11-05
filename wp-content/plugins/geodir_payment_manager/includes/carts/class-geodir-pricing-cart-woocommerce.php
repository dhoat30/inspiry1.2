<?php
/**
 * Pricing Manager & WooCommerce integration class.
 *
 * @since 2.5.0
 * @package GeoDir_Pricing_Manager
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDir_Pricing_Cart_WooCommerce class.
 */
class GeoDir_Pricing_Cart_WooCommerce extends GeoDir_Pricing_Cart {

	/**
	 * Product meta key.
	 *
	 * @var string
	 */
	const GEODIR_PRICING_WC_PRODUCT_ID = 'woocommerce_product_id';

	/**
	 * Category meta key.
	 *
	 * @var string
	 */
	const GEODIR_PRICING_WC_CATEGORY_ID = 'woocommerce_category_id';

	/**
	 * Card name.
	 *
	 * @var string
	 */
	public static $name = 'WooCommerce';

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'admin_init', array( __CLASS__, 'geodir_integration' ) );
			add_action( 'geodir_pricing_admin_list_packages_columns', array( __CLASS__, 'admin_list_packages_columns' ), 10, 1 );
			add_action( 'geodir_pricing_admin_list_packages_column', array( __CLASS__, 'admin_list_packages_column' ), 10, 2 );
			add_action( 'geodir_pricing_admin_list_packages_column_actions', array( __CLASS__, 'admin_list_packages_column_actions' ), 10, 2 );
			add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ), 10, 2 );
		}

		add_action( 'geodir_pricing_save_package', array( __CLASS__, 'on_save_package' ), 10, 3 );
		add_action( 'woocommerce_before_product_object_save', array( __CLASS__, 'before_product_object_save' ), 100, 2 );
		add_action( 'woocommerce_product_supports', array( __CLASS__, 'supports' ), 9999, 3 );
		add_action( 'woocommerce_product_add_to_cart_url', array( __CLASS__, 'add_to_cart_url' ), 9999, 2 );
		add_action( 'woocommerce_add_to_cart_form_action', array( __CLASS__, 'add_to_cart_form_action' ), 9999, 1 );
		add_action( 'woocommerce_product_add_to_cart_text', array( __CLASS__, 'add_to_cart_text' ), 9999, 2 );
		add_action( 'woocommerce_product_single_add_to_cart_text', array( __CLASS__, 'add_to_cart_text' ), 9999, 2 );
		add_action( 'woocommerce_after_add_to_cart_button', array( __CLASS__, 'after_add_to_cart_button' ), 20 );
		add_action( 'woocommerce_new_order', array( __CLASS__, 'new_order' ), 10, 2 );
		add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'order_completed' ), 100, 2 );
		add_action( 'woocommerce_order_status_completed_to_cancelled', array( __CLASS__, 'order_cancelled' ), 100, 2 );
		add_action( 'woocommerce_order_status_refunded', array( __CLASS__, 'order_refunded' ), 100, 2 );
		add_action( 'geodir_pricing_wc_save_product', array( __CLASS__, 'on_save_product' ), 100, 2 );
		add_action( 'woocommerce_subscription_renewal_payment_complete', array( __CLASS__, 'subscription_renewed' ), 100, 2 );

		if ( ! get_current_user_id() ) {
			add_action( 'wp_restore_post_revision', array( __CLASS__, 'restore_post_revision' ), 9, 2 );
		}
	}

	/**
	 * Hook for subscription renewals.
	 *
	 * @param WC_Subscription $subscription
	 * @param WC_Order $order
	 */
	public static function subscription_renewed( $subscription, $order ) {

		// Ensure all details are available.
		if ( empty( $subscription ) || empty( $order ) ) {
			return false;
		}

		// Retrieve the items of the subscription.
		$items = GeoDir_Pricing_Post_Package::get_items( array( 'invoice_id' => $subscription->get_parent_id() ) );

		// Maybe abort early.
		if ( empty( $items ) ) {
			return;
		}

		// When was it paid for?
		$order_date = $order->get_date_paid();
		if ( empty( $order_date ) ) {
			$order_date = $order->get_date_created();
		}
		$order_date = wc_format_datetime( $order_date, 'Y-m-d H:i:s' );

		foreach ( $items as $item ) {

			$revision_id = '';
			$data = array(
				'id'     => $item->id,
				'status' => 'publish',
				'date'   => $order_date,
				'task'   => 'renew',
				'meta' => maybe_serialize( array( 'task' => 'renew' ) ),
			);

			// If its is revision post then we need to swap the post id.
			if ( $post_id = wp_is_post_revision( $item->post_id ) ) {
				$revision_id = $item->post_id;
				$data['post_id'] = $post_id;
				$item->post_id = $post_id;
			}

			GeoDir_Pricing_Post_Package::save( $data );
			do_action( 'geodir_pricing_post_package_payment_completed', $item,$revision_id  );

		}

	}

	public static function geodir_integration() {
		if ( ! wp_doing_ajax() && ! isset( $_REQUEST['do_update_geodirectory'] ) && get_option( 'geodir_pricing_version' ) ) {
			// Merge price packages
			self::merge_packages_to_items();
		}
	}

	public static function add_meta_boxes( $post_type, $post ) {
		if ( geodir_is_gd_post_type( $post_type ) ) {
			$add_meta_box = apply_filters( 'geodir_pricing_add_meta_boxes', current_user_can( 'manage_options' ), $post_type, $post );

			if ( $add_meta_box ) {
				add_meta_box( 'gd-wc-post-invoices', __( 'Listing Orders', 'geodir_pricing' ), array( __CLASS__, 'display_meta_box_post_orders' ), $post_type );
			}
		}
	}

	public static function before_product_object_save( $product, $data_store ) {
		global $geodir_set_wc_to_pm_sync, $geodir_pm_to_wc_sync;

		if ( ! empty( $geodir_pm_to_wc_sync ) ) {
			return;
		}

		if ( empty( $geodir_set_wc_to_pm_sync ) ) {
			$geodir_set_wc_to_pm_sync = array();
		}

		$product_id = $product->get_id();

		if ( in_array( $product_id, $geodir_set_wc_to_pm_sync ) || ( defined( 'WP_LOAD_IMPORTERS' ) && WP_LOAD_IMPORTERS ) ) {
			return;
		}

		$post = get_post( $product_id );

		if ( $post->post_type != 'product' || wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) ) {
			return;
		}

		$package_id = (int) self::get_package_id( $product_id );

		if ( empty( $package_id ) ) {
			$geodir_set_wc_to_pm_sync = array();
		}

		if ( ! apply_filters( 'geodir_pricing_wc_add_package_to_product_sync', true, $product, $data_store ) ) {
			return;
		}

		$geodir_pm_to_wc_sync[] = $product_id;

		wp_schedule_single_event( time() + 10, 'geodir_pricing_wc_save_product', array( $product_id, uniqid() ) );
	}

	public function currency_code() {
		$currency_code = get_woocommerce_currency();

		return apply_filters( 'geodir_pricing_wc_currency_code', $currency_code );
	}

	public function currency_sign( $currency = '' ) {
		$currency_sign = get_woocommerce_currency_symbol( $currency );

		return apply_filters( 'geodir_pricing_wc_currency_sign', $currency_sign, $currency );
	}

	public function currency_position() {
		$currency_position = get_option( 'woocommerce_currency_pos' );

		return apply_filters( 'geodir_pricing_wc_currency_position', $currency_position );
	}

	public function thousand_separator() {
		$thousand_separator = get_option( 'woocommerce_price_thousand_sep' );

		return apply_filters( 'geodir_pricing_wc_thousand_separator', $thousand_separator );
	}

	public function decimal_separator() {
		$decimal_separator = get_option( 'woocommerce_price_decimal_sep' );

		return apply_filters( 'geodir_pricing_wc_decimal_separator', $decimal_separator );
	}

	public function decimals() {
		$decimals = wc_get_price_decimals();

		return apply_filters( 'geodir_pricing_wc_decimals', $decimals );
	}

	/**
	 * Format decimal numbers ready for DB storage.
	 *
	 * Sanitize, remove decimals, and optionally round + trim off zeros.
	 *
	 * This function does not remove thousands - this should be done before passing a value to the function.
	 *
	 * @since 2.5.0.0
	 *
	 * @param  float|string $number Expects either a float or a string with a decimal separator only (no thousands)
	 * @param  mixed $dp number of decimal points to use, blank to use geodir_get_price_decimals, or false to avoid all rounding.
	 * @param  bool $trim_zeros from end of string
	 * @return string
	 */
	public function format_decimal( $number, $dp = false, $trim_zeros = false ) {
		$format_decimal = wc_format_decimal( $number, $dp, $trim_zeros );

		return apply_filters( 'geodir_pricing_wc_format_decimal', $format_decimal, $number, $dp, $trim_zeros );
	}

	/**
	 * Get the price format depending on the currency position.
	 *
	 * @return string
	 */
	public function price_format() {
		$price_format = get_woocommerce_price_format();

		return apply_filters( 'geodir_pricing_wc_price_format', $price_format );
	}

	public function price( $price, $args = array() ) {
		$price = wc_price( $price, $args );

		return apply_filters( 'geodir_pricing_wc_price', $price, $price, $args );
	}

	public static function get_package_id( $product ) {
		global $wpdb;

		if ( is_object( $product ) ) {
			$product_id = $product->get_id();
		} else if ( is_int( $product ) ) {
			$product_id = $product;
		} else {
			$product_id = 0;
		}

		$package_id = wp_cache_get( 'geodir_pricing_wc_product_package_id-' . $product_id, 'geodir_pricing_wc' );

		if ( $package_id !== false ) {
			return $package_id;
		}

		$package_id = $wpdb->get_var( $wpdb->prepare( "SELECT p.id FROM " . GEODIR_PRICING_PACKAGES_TABLE . " AS p LEFT JOIN " . GEODIR_PRICING_PACKAGE_META_TABLE . " AS pm ON pm.package_id = p.id WHERE pm.meta_key = %s AND pm.meta_value = %s ORDER BY `pm`.`meta_id` ASC", array( self::GEODIR_PRICING_WC_PRODUCT_ID , $product_id ) ) );

		$package_id = (int) apply_filters( 'geodir_pricing_wc_get_package_id', $package_id, $product_id );

		wp_cache_set( 'geodir_pricing_wc_product_package_id-' . $product_id, $package_id, 'geodir_pricing_wc' );

		return $package_id;
	}

	public static function get_category_id( $package = 0 ) {
		$package_id = 0;

		if ( is_int( $package ) ) {
			$package_id = $package;
		} else if ( is_object( $package ) && ! empty( $package->id ) ) {
			$package_id = $package->id;
		}

		if ( $package_id ) {
			$category_id = geodir_pricing_get_meta( $package_id, self::GEODIR_PRICING_WC_CATEGORY_ID, true );
		} else {
			$category_id = 0;
		}

		return apply_filters( 'geodir_pricing_wc_get_category_id', $category_id, $package_id );
	}

	public static function get_product_id( $package ) {
		$package_id = 0;

		if ( is_int( $package ) ) {
			$package_id = $package;
		} else if ( is_object( $package ) && ! empty( $package->id ) ) {
			$package_id = $package->id;
		}

		if ( empty( $package_id ) ) {
			return NULL;
		}

		$product_id = geodir_pricing_get_meta( $package_id, self::GEODIR_PRICING_WC_PRODUCT_ID, true );

		return (int) apply_filters( 'geodir_pricing_wc_get_product_id', $product_id, $package_id );
	}

	public static function get_product( $package ) {
		$product_id = self::get_product_id( $package );

		$product = $product_id ? wc_get_product( $product_id ) : NULL;

		return apply_filters( 'geodir_pricing_wc_get_product', $product, $package );
	}

	public static function on_save_package( $package_id, $package, $update = false ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return self::sync_package_to_product( $package );
	}

	public static function merge_packages_to_items( $force = false ) {
		if ( ( $merged = geodir_get_option( 'pm_wc_merge_packages' ) ) && ! $force ) {
			return true;
		}

		$packages = geodir_pricing_get_packages();
		
		foreach ( $packages as $key => $package ) {
			self::sync_package_to_product( $package );
		}

		if ( ! $merged ) {
			geodir_update_option( 'pm_wc_merge_packages', 1 );
		}

		return true;
	}

	public static function sync_package_to_product( $package ) {
		global $geodir_pm_to_wc_sync;

		if ( empty( $package ) ) {
			return false;
		}

		$package = is_int( $package ) ? geodir_pricing_get_package( $package ) : $package;

		if ( empty( $package->id ) ) {
			return false;
		}

		if ( apply_filters( 'geodir_pricing_wc_skip_package_to_product_sync', false, $package ) ) {
			return false;
		}

		$geodir_pm_to_wc_sync = $package->id;

		$sync = self::sync_product( $package );
		
		unset( $geodir_pm_to_wc_sync );

		return $sync;
	}

	public static function sync_product( $package, $wp_error = false ) {
		$id = self::get_product_id( $package );
		$is_new_product = empty( $id ) ? true : false;

		$request = array(
			'id'                => $id,
			'name'              => $package->name,
			'status'            => ! empty( $package->status ) ? 'publish' : 'draft',
			'description'       => $package->description,
			'short_description' => $package->title,
			'price'             => (float)$package->amount,
			'regular_price'     => (float)$package->amount,
			'sku'               => $package->post_type . '-package-' . $package->id,
			'type'              => 'simple',
		);

		if ( ! empty( $package->recurring ) ) {
			$request['type'] = 'subscription';
			$request['_subscription_price'] = (float)$package->amount;

			$periods = array(
				'D' => 'day',
				'W' => 'week',
				'M' => 'month',
				'Y' => 'year'
			);

			$request['_subscription_period'] = 'day';
			if ( isset( $periods[ $package->time_unit ] ) ) {
				$request['_subscription_period'] = $periods[ $package->time_unit ];
			}

			$request['_subscription_period_interval'] = absint( $package->time_interval );
			$request['_subscription_length'] = absint( $package->recurring_limit );

			$trial_interval = absint( $package->trial_interval );
			if ( $trial_interval ) {
				$request['_subscription_trial_length'] = $trial_interval;
				$request['_subscription_trial_period'] = 'day';
				if ( isset( $periods[ $package->trial_unit ] ) ) {
					$request['_subscription_trial_period'] = $periods[ $package->trial_unit ];
				}
			}
		}

		$request = apply_filters( 'geodir_pricing_wc_sync_product_data', $request, $package );

		try {
			$product = self::save_product( $request, $package, $is_new_product );

			if ( is_wp_error( $product ) ) {
				if ( $is_new_product ) {
					geodir_error_log( sprintf( 'Error creating product for package ID %s: %s', $package->id, $product->get_error_message() ), 'Pricing -> WooCommerce' );
				} else {
					geodir_error_log( sprintf( 'Error updating product #%s for package ID %s: %s', $id, $package->id, $product->get_error_message() ), 'Pricing -> WooCommerce' );
				}
				if ( $wp_error ) {
					return $product;
				} else {
					return false;
				}
			} else if ( ! empty( $product ) ) {
				if ( $is_new_product ) {
					geodir_error_log( sprintf( 'Product #%d created for package ID %s', $product, $package->id ), 'Pricing -> WooCommerce' );
				}
				
				// Update meta.
				geodir_pricing_update_meta( $package->id, self::GEODIR_PRICING_WC_PRODUCT_ID, $product );

				do_action( 'geodir_pricing_wc_sync_product_done', $product, $package, $is_new_product );

				return $product;
			}
		} catch ( Exception $e ) {
			if ( $is_new_product ) {
				geodir_error_log( sprintf( 'Error creating product for package ID %s: %s', $package->id, $e->getMessage() ), 'Pricing -> WooCommerce' );
			} else {
				geodir_error_log( sprintf( 'Error updating product #%s for package ID %s: %s', $id, $package->id, $e->getMessage() ), 'Pricing -> WooCommerce' );
			}

			if ( $wp_error ) {
				return new WP_Error(
					"geodir_pricing_wc_sync_package_error", $e->getMessage(), array(
						'status' => 404,
					)
				);
			} else {
				return false;
			}
		}

		return false;
	}

	public static function save_product( $request, $package, $creating = false ) {
		$product = self::prepare_product_for_save( $request, $package );
		return $product->save();
	}

	public static function prepare_product_for_save( $request, $package, $creating = false ) {

		if ( empty( $request['type'] ) ) {
			$request['type'] = 'simple';
		}

		$request['virtual'] = true;
		$request['parent_id'] = 0;
		$request['manage_stock'] = false;
		$request['sold_individually'] = true;
		$request['downloadable'] = false;

		$id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;

		if ( isset( $request['type'] ) ) {
			$classname = WC_Product_Factory::get_classname_from_product_type( $request['type'] );

			if ( ! class_exists( $classname ) ) {
				$classname = 'WC_Product_Simple';
			}

			$product = new $classname( $id );
		} elseif ( isset( $request['id'] ) ) {
			$product = wc_get_product( $id );
		} else {
			$product = new WC_Product_Simple();
		}

		if ( 'variation' === $product->get_type() ) {
			return new WP_Error(
				"geodir_pricing_wc_rest_invalid_product_id", __( 'To manipulate product variations you should use the /products/&lt;product_id&gt;/variations/&lt;id&gt; endpoint.', 'geodir_pricing' ), array(
					'status' => 404,
				)
			);
		}

		// Post title.
		if ( isset( $request['name'] ) ) {
			$product->set_name( wp_filter_post_kses( $request['name'] ) );
		}

		// Post content.
		if ( isset( $request['description'] ) ) {
			$product->set_description( wp_filter_post_kses( $request['description'] ) );
		}

		// Post excerpt.
		if ( isset( $request['short_description'] ) ) {
			$product->set_short_description( wp_filter_post_kses( $request['short_description'] ) );
		}

		// Post status.
		if ( isset( $request['status'] ) ) {
			$product->set_status( get_post_status_object( $request['status'] ) ? $request['status'] : 'draft' );
		}

		// Post slug.
		if ( isset( $request['slug'] ) ) {
			$product->set_slug( $request['slug'] );
		}

		// Menu order.
		if ( isset( $request['menu_order'] ) ) {
			$product->set_menu_order( $request['menu_order'] );
		}

		// Comment status.
		if ( isset( $request['reviews_allowed'] ) ) {
			$product->set_reviews_allowed( $request['reviews_allowed'] );
		}

		// Virtual.
		if ( isset( $request['virtual'] ) ) {
			$product->set_virtual( true );
		}

		// Tax status.
		if ( isset( $request['tax_status'] ) ) {
			$product->set_tax_status( $request['tax_status'] );
		}

		// Tax Class.
		if ( isset( $request['tax_class'] ) ) {
			$product->set_tax_class( $request['tax_class'] );
		}

		// Catalog Visibility.
		if ( isset( $request['catalog_visibility'] ) ) {
			$product->set_catalog_visibility( $request['catalog_visibility'] );
		}

		// Purchase Note.
		if ( isset( $request['purchase_note'] ) ) {
			$product->set_purchase_note( wp_kses_post( wp_unslash( $request['purchase_note'] ) ) );
		}

		// Featured Product.
		if ( isset( $request['featured'] ) ) {
			$product->set_featured( $request['featured'] );
		}

		// Shipping data.
		$product->set_weight( '' );
		$product->set_height( '' );
		$product->set_length( '' );
		$product->set_width( '' );

		// SKU.
		if ( isset( $request['sku'] ) ) {
			$product->set_sku( wc_clean( $request['sku'] ) );
		}

		// Attributes.
		if ( isset( $request['attributes'] ) ) {
			$attributes = array();

			foreach ( $request['attributes'] as $attribute ) {
				$attribute_id   = 0;
				$attribute_name = '';

				// Check ID for global attributes or name for product attributes.
				if ( ! empty( $attribute['id'] ) ) {
					$attribute_id   = absint( $attribute['id'] );
					$attribute_name = wc_attribute_taxonomy_name_by_id( $attribute_id );
				} elseif ( ! empty( $attribute['name'] ) ) {
					$attribute_name = wc_clean( $attribute['name'] );
				}

				if ( ! $attribute_id && ! $attribute_name ) {
					continue;
				}

				if ( $attribute_id ) {

					if ( isset( $attribute['options'] ) ) {
						$options = $attribute['options'];

						if ( ! is_array( $attribute['options'] ) ) {
							// Text based attributes - Posted values are term names.
							$options = explode( WC_DELIMITER, $options );
						}

						$values = array_map( 'wc_sanitize_term_text_based', $options );
						$values = array_filter( $values, 'strlen' );
					} else {
						$values = array();
					}

					if ( ! empty( $values ) ) {
						// Add attribute to array, but don't set values.
						$attribute_object = new WC_Product_Attribute();
						$attribute_object->set_id( $attribute_id );
						$attribute_object->set_name( $attribute_name );
						$attribute_object->set_options( $values );
						$attribute_object->set_position( isset( $attribute['position'] ) ? (string) absint( $attribute['position'] ) : '0' );
						$attribute_object->set_visible( ( isset( $attribute['visible'] ) && $attribute['visible'] ) ? 1 : 0 );
						$attribute_object->set_variation( ( isset( $attribute['variation'] ) && $attribute['variation'] ) ? 1 : 0 );
						$attributes[] = $attribute_object;
					}
				} elseif ( isset( $attribute['options'] ) ) {
					// Custom attribute - Add attribute to array and set the values.
					if ( is_array( $attribute['options'] ) ) {
						$values = $attribute['options'];
					} else {
						$values = explode( WC_DELIMITER, $attribute['options'] );
					}
					$attribute_object = new WC_Product_Attribute();
					$attribute_object->set_name( $attribute_name );
					$attribute_object->set_options( $values );
					$attribute_object->set_position( isset( $attribute['position'] ) ? (string) absint( $attribute['position'] ) : '0' );
					$attribute_object->set_visible( ( isset( $attribute['visible'] ) && $attribute['visible'] ) ? 1 : 0 );
					$attribute_object->set_variation( ( isset( $attribute['variation'] ) && $attribute['variation'] ) ? 1 : 0 );
					$attributes[] = $attribute_object;
				}
			}
			$product->set_attributes( $attributes );
		}

		// Regular Price.
		if ( isset( $request['regular_price'] ) ) {
			$product->set_regular_price( $request['regular_price'] );
		}

		// Sale Price.
		if ( isset( $request['sale_price'] ) ) {
			$product->set_sale_price( $request['sale_price'] );
		}

		if ( isset( $request['date_on_sale_from'] ) ) {
			$product->set_date_on_sale_from( $request['date_on_sale_from'] );
		}

		if ( isset( $request['date_on_sale_from_gmt'] ) ) {
			$product->set_date_on_sale_from( $request['date_on_sale_from_gmt'] ? strtotime( $request['date_on_sale_from_gmt'] ) : null );
		}

		if ( isset( $request['date_on_sale_to'] ) ) {
			$product->set_date_on_sale_to( $request['date_on_sale_to'] );
		}

		if ( isset( $request['date_on_sale_to_gmt'] ) ) {
			$product->set_date_on_sale_to( $request['date_on_sale_to_gmt'] ? strtotime( $request['date_on_sale_to_gmt'] ) : null );
		}

		// Product parent ID for groups.
		if ( isset( $request['parent_id'] ) ) {
			$product->set_parent_id( $request['parent_id'] );
		}

		// Stock status.
		if ( isset( $request['in_stock'] ) ) {
			$stock_status = true === $request['in_stock'] ? 'instock' : 'outofstock';
		} else {
			$stock_status = $product->get_stock_status();
		}

		// Don't manage stock.
		$product->set_manage_stock( 'no' );
		$product->set_stock_quantity( '' );
		$product->set_stock_status( $stock_status );

		// Sold individually.
		if ( isset( $request['sold_individually'] ) ) {
			$product->set_sold_individually( $request['sold_individually'] );
		}

		// Product categories.
		if ( isset( $request['categories'] ) && is_array( $request['categories'] ) ) {
			$product = self::save_taxonomy_terms( $product, $request['categories'] );
		}

		// Product tags.
		if ( isset( $request['tags'] ) && is_array( $request['tags'] ) ) {
			$product = self::save_taxonomy_terms( $product, $request['tags'], 'tag' );
		}

		// Downloadable.
		if ( isset( $request['downloadable'] ) ) {
			$product->set_downloadable( $request['downloadable'] );
		}

		// Check for featured/gallery images, upload it and set it.
		if ( isset( $request['images'] ) ) {
			$product = $product->set_product_images( $product, $request['images'] );
		}

		// Subscriptions.
		if ( isset( $request['_subscription_price'] ) ) {
			$product->update_meta_data( '_subscription_price', $request['_subscription_price'] );
		}

		if ( isset( $request['_subscription_period'] ) ) {
			$product->update_meta_data( '_subscription_period', $request['_subscription_period'] );
		}

		if ( isset( $request['_subscription_period_interval'] ) ) {
			$product->update_meta_data( '_subscription_period_interval', $request['_subscription_period_interval'] );
		}

		if ( isset( $request['_subscription_length'] ) ) {
			$product->update_meta_data( '_subscription_length', $request['_subscription_length'] );
		}

		if ( isset( $request['_subscription_trial_length'] ) ) {
			$product->update_meta_data( '_subscription_trial_length', $request['_subscription_trial_length'] );
		}

		if ( isset( $request['_subscription_trial_period'] ) ) {
			$product->update_meta_data( '_subscription_trial_period', $request['_subscription_trial_period'] );
		}

		// Allow set meta_data.
		if ( isset( $request['meta_data'] ) && is_array( $request['meta_data'] ) ) {
			foreach ( $request['meta_data'] as $meta ) {
				$product->update_meta_data( $meta['key'], $meta['value'], isset( $meta['id'] ) ? $meta['id'] : '' );
			}
		}

		return apply_filters( "geodir_pricing_wc_pre_insert_product", $product, $request, $package, $creating );
	}

	public static function save_taxonomy_terms( $product, $terms, $taxonomy = 'cat' ) {
		$term_ids = wp_list_pluck( $terms, 'id' );

		if ( 'cat' === $taxonomy ) {
			$product->set_category_ids( $term_ids );
		} elseif ( 'tag' === $taxonomy ) {
			$product->set_tag_ids( $term_ids );
		}

		return $product;
	}

	public static function on_save_product( $product_id ) {
		if ( ! ( current_user_can( 'manage_options' ) || wp_doing_cron() ) ) {
			return false;
		}

		return self::sync_product_to_package( $product_id );
	}

	public static function sync_product_to_package( $product_id ) {
		global $geodir_wc_to_pm_sync;

		if ( empty( $product_id ) ) {
			return false;
		}

		$package_id = self::get_package_id( $product_id );
		if ( empty( $package_id ) ) {
			return false;
		}

		$product = wc_get_product( $product_id );

		if ( empty( $product ) ) {
			return false;
		}

		if ( apply_filters( 'geodir_pricing_wc_skip_product_to_package_sync', false, $product ) ) {
			return false;
		}

		$geodir_wc_to_pm_sync = $product_id;

		$sync = self::sync_package( $product );
		
		unset( $geodir_wc_to_pm_sync );

		return $sync;
	}

	/**
	 * @param WC_Product|WC_Product_Simple|WC_Product_Subscription $product
	 */
	public static function sync_package( $product, $wp_error = false ) {
		$product_id = $product->get_id();
		$package_id = self::get_package_id( $product );
		if ( empty( $package_id ) ) {
			if ( $wp_error ) {
				return new WP_Error(
					"geodir_pricing_wc_rest_invalid_package_id", __( 'No package linked to this product.', 'geodir_pricing' ), array(
						'status' => 404,
					)
				);
			} else {
				return false;
			}
		}

		$request = array(
			'id' => $package_id,
			'name' => $product->get_name( 'db' ),
			'status' => $product->get_status( 'db') == 'publish' ? 1 : 0,
			'description' => $product->get_description( 'db'),
			'title' => $product->get_short_description( 'db'),
			'amount' => $product->get_regular_price( 'db'),
		);
		
		$request = apply_filters( 'geodir_pricing_wc_sync_package_data', $request, $product );

		if ( $product->is_type('subscription') ) {
			$request['recurring'] = 1;
			$request['amount']    = $product->get_meta( '_subscription_price' );

			$periods = array_flip( array(
				'D' => 'day',
				'W' => 'week',
				'M' => 'month',
				'Y' => 'year'
			));

			$period = $product->get_meta( '_subscription_period' );
			if ( isset( $periods[ $period ] ) ) {
				$period = $periods[ $period ];
			} else {
				$period = 'D';
			}
			$request['time_unit'] = $period;
			$request['_subscription_period'] = 'day';

			$request['time_interval'] = absint( $product->get_meta( '_subscription_period_interval' ) );
			$request['recurring_limit'] = absint( $product->get_meta( '_subscription_length' ) );

			$request['subscription_trial_length'] = (int) $product->get_meta( '_subscription_trial_length' );
			$request['trial'] = $request['subscription_trial_length'] ? 1 : 0;
			$period = $product->get_meta( '_subscription_trial_period' );
			if ( isset( $periods[ $period ] ) ) {
				$period = $periods[ $period ];
			} else {
				$period = 'D';
			}

		
			$request['trial_unit'] = $period;

		} else {
			$request['recurring'] = 0;
		}
	
		$package = self::save_package( $request, $product );

		if ( is_wp_error( $product ) ) {
			geodir_error_log( sprintf( 'Error updating package #%s for product ID %s: %s', $package_id, $product_id, $package->get_error_message() ), 'Pricing -> WooCommerce' );

			if ( $wp_error ) {
				return $package;
			} else {
				return false;
			}
		} else if ( ! empty( $package ) ) {
			do_action( 'geodir_pricing_wc_sync_package_done', $package, $product );

			return $package;
		}

		return false;
	}
	
	public static function save_package( $request, $product ) {
		$data = GeoDir_Pricing_Package::prepare_data_for_save( $request );

		$data = apply_filters( "geodir_pricing_wc_prepare_package_for_save", $data, $request, $product );

		return GeoDir_Pricing_Package::update_package( $data, true );
	}

	public static function supports( $supports, $feature, $product ) {
		if ( $supports && $feature == 'ajax_add_to_cart' && ( $package_id = self::get_package_id( $product ) ) ) {
			$supports = false;
		}
		return $supports;
	}

	public static function add_listing_url( $package_id ) {
		$post_type = geodir_pricing_package_post_type( $package_id );
		$url = geodir_get_addlisting_link( $post_type );
		$url = add_query_arg( array( 'package_id' => $package_id ), $url );
		return $url;
	}

	public static function add_to_cart_url( $url, $product ) {
		if ( $package_id = self::get_package_id( $product ) ) {
			$post_type = geodir_pricing_package_post_type( $package_id );

			$url = self::add_listing_url( $package_id );

			$url = apply_filters( 'geodir_pricing_wc_product_add_to_cart_url', $url, $package_id, $product, $post_type );
		}
		return $url;
	}

	public static function add_to_cart_form_action( $url ) {
		global $product;

		if ( ! empty( $product ) ) {
			$url = self::add_to_cart_url( $url, $product );
		}
		return $url;
	}

	public static function add_to_cart_text( $text, $product ) {
		if ( $package_id = self::get_package_id( $product ) ) {
			$post_type = geodir_pricing_package_post_type( $package_id );

			if ( $product->is_purchasable() && $product->is_in_stock() ) {
				$text = $post_type ? wp_sprintf( __( 'Add %s', 'geodir_pricing' ), geodir_post_type_singular_name( $post_type, true ) ) : __( 'Add Listing', 'geodir_pricing' );
			} else {
				$text = __( 'Read more', 'geodir_pricing' );
			}

			$text = apply_filters( 'geodir_pricing_wc_product_add_to_cart_text', $text, $package_id, $product, $post_type );
		}
		return $text;
	}

	public static function after_add_to_cart_button() {
		global $product;

		if ( ! empty( $product ) && ( $url = self::add_to_cart_url( '', $product ) ) ) {
			$identifier = 'geodir_cart_btn_' . uniqid();
			?>
			<div id="<?php echo $identifier; ?>" style="display:none!important"></div>
			<script type="text/javascript">
				jQuery(function($) {
					var $form = $('#<?php echo $identifier; ?>').closest('form.cart');
					if ($form.length) {
						$($form).attr('action', "javascript:void(0)");
						$('[name="add-to-cart"]', $form).val('');
						$('[name="add-to-cart"]', $form).attr('onclick', "window.location.href='<?php echo $url; ?>'");
					}
				});
			</script>
			<?php
		}
	}

	public function ajax_post_saved( $post_data ) {

		// first save the submitted data
		$result = GeoDir_Post_Data::auto_save_post( $post_data );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$has_invoice = false;
		// get current status
		$post_status = get_post_status( $post_data['ID'] );
		$parent_post_id = !empty($post_data['post_parent']) ? $post_data['post_parent'] : $post_data['ID'];

		// new post
		if ( geodir_pricing_is_new( $post_data ) ) {
			$task = 'new';

			// if its new and an auto-draft then we save it as pending
			$post_status = get_post_status( $parent_post_id );
			if(empty($post_data['post_parent']) && $post_status=='auto-draft'){
				wp_update_post( array('ID'=> $post_data['ID'],'post_status'=>'pending'));
			}
			// if its new and a revision then just save it
			elseif(!empty($post_data['post_parent'])){
				wp_restore_post_revision( $post_data['ID'] );
				$post_data['ID'] = $parent_post_id;
			}

			$has_invoice = self::has_invoice($post_data['ID'],'new');

			//Check if its a logged out user and if we have details to register the user
			$post_data = GeoDir_Post_Data::check_logged_out_author($post_data);
		} else if ( geodir_pricing_is_upgrade( $post_data ) ) { // upgrade
			$task = 'upgrade';
			$has_invoice = self::has_invoice($post_data['ID']);
		} else if ( geodir_pricing_is_renew( $post_data ) ) { // renewal
			$task = 'renew';
			$has_invoice = self::has_invoice($post_data['ID']);
		} else { // update
			wp_restore_post_revision( $post_data['ID'] );
			$task = 'update';
		}

		if ( $task != 'update' && ! empty( $post_data['package_id'] ) && ( $product_id = self::get_product_id( (int)$post_data['package_id'] ) ) ) {

			$product_id = apply_filters( 'geodir_pricing_wc_cart_product_id', $product_id, $task, (int) $post_data['ID'], (int) $post_data['package_id'], $post_data );

			try {
				// Remove cart.
				WC()->cart->empty_cart();

				if ( self::add_to_cart($product_id) && ( $cart_key = self::get_cart_key( $product_id ) ) ) {
					$data = array(
						'post_id' => $post_data['ID'],
						'package_id' => $post_data['package_id'],
						'product_id' => $product_id,
						'cart' => 'woocommerce',
						'task' => $task,
						'status' => '',
						'meta' => maybe_serialize( array( 'task' => $task, 'cart_key' => $cart_key ) ),
						'date' => date_i18n( 'Y-m-d H:i:s' )
					);

					// if we have a package id then we just update it.
					if(!empty($has_invoice->id)){
						$data['id'] = $has_invoice->id;
					}

					$data = apply_filters( 'geodir_pricing_wc_post_package_data', $data, (int) $post_data['ID'], (int) $post_data['package_id'], $post_data );

					GeoDir_Pricing_Post_Package::save( $data );

					$needs_payment = (bool) WC()->cart->needs_payment();

					if ( ! $needs_payment ) {
						$order = wc_create_order();
						$order->add_product( wc_get_product( $product_id ), 1 );
						update_post_meta( $order->get_id(), '_customer_user', get_current_user_id() );
						$order->calculate_totals();
						$order->update_status( 'completed', __( 'Free order', 'geodir_pricing' ), true );  

						// Remove cart.
						WC()->cart->empty_cart();

						// if its free then send the standard message
						$post_data['ID'] = $parent_post_id;
						$post_data['post_status'] = geodir_pricing_package_post_status( $post_data['package_id'] );
						return GeoDir_Post_Data::ajax_save_post_message($post_data);
						
					}else{

						//echo '###'.wc_get_checkout_url();exit;
						$checkout_url = apply_filters( 'geodir_pricing_manager_wc_new_listing_redirect_url', wc_get_checkout_url() );
						return self::ajax_save_post_message( $task, $post_data, $checkout_url );
					}
				}
			} catch ( Exception $e ) {
				geodir_error_log( $e->getMessage(), 'Pricing -> WC' );
			}
		}
	}



	public static function get_cart_key( $product_id ) {
		try {
			if ( isset( WC()->cart ) && ! is_null( WC()->cart ) && WC()->cart ) {
				$cart = WC()->cart->get_cart();
			} else {
				$cart = array();
			}
		} catch ( Exception $e ) {
			$cart = array();
		}

		$cart_key = '';

		if ( ! empty( $cart ) ) {
			foreach ( $cart as $key => $cart_item ) {
				if ( $cart_item['product_id'] == $product_id ) {
					$cart_key = $cart_item['key'];
				}
			}
		}

		return $cart_key;
	}

	public static function add_to_cart($product_id, $quantity = '') {
		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_id ) );
		$product           = wc_get_product( $product_id );
		$quantity          = empty( $quantity ) ? 1 : wc_stock_amount( $quantity );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );
		$variation_id      = 0;
		$variation         = array();

		if ( $product && 'variation' === $product->get_type() ) {
			$variation_id = $product_id;
			$product_id   = $product->get_parent_id();
			$variation    = $product->get_variation_attributes();
		}

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {
			ob_start();
			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			ob_get_clean();

			return true;
		}
		return false;
	}

	public static function new_order( $order_id, $order = array() ) {
		global $wpdb;

		if ( empty( $order_id ) ) {
			return;
		}

		try {
			if ( isset( WC()->cart ) && ! is_null( WC()->cart ) && WC()->cart ) {
				$cart = WC()->cart->get_cart();
			} else {
				$cart = array();
			}
		} catch ( Exception $e ) {
			$cart = array();
		}

		if ( empty( $cart ) ) {
			return;
		}

		$order = ! empty( $order ) ? $order : wc_get_order( $order_id );
		if ( empty( $order ) ) {
			return;
		}

		foreach ( $cart as $cart_key => $cart_item ) {
			$row = $wpdb->get_row( "SELECT id, meta FROM " . GEODIR_PRICING_POST_PACKAGES_TABLE . " WHERE meta LIKE '%\"cart_key\";s:32:\"{$cart_key}\"%' AND invoice_id = 0 AND product_id = " . (int)$cart_item['product_id'] . " ORDER BY id DESC LIMIT 1" );
			if ( ! empty( $row ) ) {
				$date = $order->get_date_paid();
				if ( empty( $date ) ) {
					$date = $order->get_date_created();
				}
				$date = wc_format_datetime( $date, 'Y-m-d H:i:s' );
				$meta = maybe_unserialize( $row->meta );
				unset( $meta['cart_key'] );
				$meta = $meta ? maybe_serialize( $meta ) : '';

				$wpdb->update( GEODIR_PRICING_POST_PACKAGES_TABLE, array( 'invoice_id' => $order_id, 'date' => $date, 'status' => $order->get_status(), 'meta' => $meta ), array( 'id' => $row->id ) );
			}
		}
	}

	public static function order_completed( $order_id, $order ) {
		$items = GeoDir_Pricing_Post_Package::get_items( array( 'invoice_id' => $order->get_id() ) );

		if ( ! empty( $items ) ) {
			$order_date = $order->get_date_paid();
			if ( empty( $order_date ) ) {
				$order_date = $order->get_date_created();
			}
			$order_date = wc_format_datetime( $order_date, 'Y-m-d H:i:s' );

			foreach ( $items as $key => $item ) {
				$revision_id = '';
				$data = array(
					'id'     => $item->id,
					'status' => $order->get_status(),
					'date'   => $order_date
				);

				// If its is revision post then we need to swap the post id.
				if ( $post_id = wp_is_post_revision( $item->post_id ) ) {
					$revision_id = $item->post_id;
					$data['post_id'] = $post_id;
					$item->post_id = $post_id;
				}

				GeoDir_Pricing_Post_Package::save( $data );

				do_action( 'geodir_pricing_post_package_payment_completed', $item, $revision_id  );
			}
		}
	}

	public static function order_cancelled( $order_id, $order ) {
		$items = GeoDir_Pricing_Post_Package::get_items( array( 'invoice_id' => $order->get_id() ) );

		if ( ! empty( $items ) ) {
			$order_date = $order->get_date_paid();
			if ( empty( $order_date ) ) {
				$order_date = $order->get_date_modified();
			}
			$order_date = wc_format_datetime( $order_date, 'Y-m-d H:i:s' );

			foreach ( $items as $key => $item ) {
				$data = array(
					'id' => $item->id,
					'status' => $order->get_status(),
					'date' => $order_date
				);
				GeoDir_Pricing_Post_Package::save( $data );

				do_action( 'geodir_pricing_post_package_payment_cancelled', $item );
			}
		}
	}

	public static function order_refunded( $order_id, $order ) {
		$items = GeoDir_Pricing_Post_Package::get_items( array( 'invoice_id' => $order->get_id() ) );

		if ( ! empty( $items ) ) {
			$order_date = $order->get_date_paid();
			if ( empty( $order_date ) ) {
				$order_date = $order->get_date_modified();
			}
			$order_date = wc_format_datetime( $order_date, 'Y-m-d H:i:s' );

			foreach ( $items as $key => $item ) {
				$data = array(
					'id' => $item->id,
					'status' => $order->get_status(),
					'date' => $order_date
				);
				GeoDir_Pricing_Post_Package::save( $data );

				do_action( 'geodir_pricing_post_package_payment_refunded', $item );
			}
		}
	}

	public static function admin_list_packages_columns( $columns = array() ) {
		$replacement = array(
			'product_id' => __( 'WooCommerce Product', 'geodir_pricing' )
		);

		$position = array_search( 'lifetime', array_keys( $columns ) );
		$position = false === $position ? count( $columns ) : $position + 1;
		$columns = array_merge( array_slice( $columns, 0, $position ), $replacement, array_slice( $columns, $position ) );

		return $columns;
	}

	public static function admin_list_packages_column_actions( $actions, $item ) {
		$actions['sync'] = '<a href="javascript:void(0)" class="geodir-sync-package geodir-act-sync" title="' . esc_attr( 'Synchronize package to WooCommerce product' ) . '" data-sync-nonce="' . esc_attr( wp_create_nonce( 'geodir-sync-package-' . $item['id'] ) ) . '"><i class="fas fa-sync"></i></a>';

		return $actions;
	}

	public static function admin_list_packages_column( $item = array(), $column_name = '' ) {
		switch ( $column_name ) {
			case 'product_id':
				$product_id = self::get_product_id( $item['id'] );
				if ( ! empty( $product_id ) ) {
					$edit_link = get_edit_post_link( $product_id );
				
					echo sprintf( '<a href="%s">%d</a>', $edit_link, $product_id );
				} else {
					echo '<a href="javascript:void(0)" class="geodir-sync-package geodir-act-sync" title="' . esc_attr( 'Synchronize package to WooCommerce item' ) . '" data-sync-nonce="' . esc_attr( wp_create_nonce( 'geodir-sync-package-' . $item['id'] ) ) . '" data-reload="1"><i class="fas fa-sync"></i></a>';
				}
				break;
		}
	}

	public static function sync_package_to_cart_item( $package_id ) {
		return self::sync_package_to_product( (int) $package_id );
	}

	public static function get_post_orders( $post_id = 0 ) {
		global $wpdb;

		if ( ! $post_id ) {
			return false;
		}

		$invoices = GeoDir_Pricing_Post_Package::get_items( array( 'post_id' => $post_id, 'order_by' => 'id DESC' ) );

		return $invoices;
	}

	public static function display_meta_box_post_orders( $post ) {
		$post_invoices = self::get_post_orders( $post->ID );

		if ( empty( $post_invoices ) ) {
			_e( '<p>No order(s) found for this listing.</p>' , 'geodir_pricing' );
			return;
		}
		?>
		<table class="wp-list-table widefat fixed striped posts gd-listing-invoices">
			<thead>
				<th class="column-wpi_number"><?php _e('Order', 'geodir_pricing'); ?></th>
				<th class="column-wpi_status"><?php _e('Date', 'geodir_pricing'); ?></th>
				<th class="column-wpi_amount"><?php _e('Status', 'geodir_pricing'); ?></th>
				<th class="column-wpi_invoice_date"><?php _e('Total', 'geodir_pricing'); ?></th>
			</thead>
			<tbody>
		<?php
			foreach ( $post_invoices as $key => $item ) {
				$invoice_id = $item->invoice_id;
				if ( ! $invoice_id ) {
					continue;
				}
				$invoice = wc_get_order( $invoice_id );
				if ( empty( $invoice ) ) {
					continue;
				}
				if ( $invoice->get_billing_first_name() || $invoice->get_billing_last_name() ) {
					/* translators: 1: first name 2: last name */
					$buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $invoice->get_billing_first_name(), $invoice->get_billing_last_name() ) );
				} elseif ( $invoice->get_billing_company() ) {
					$buyer = trim( $invoice->get_billing_company() );
				} elseif ( $invoice->get_customer_id() ) {
					$user  = get_user_by( 'id', $invoice->get_customer_id() );
					$buyer = ucwords( $user->display_name );
				}

				$order_timestamp = $invoice->get_date_created()->getTimestamp();

				// Check if the order was created within the last 24 hours, and not in the future.
				if ( $order_timestamp > strtotime( '-1 day', current_time( 'timestamp', true ) ) && $order_timestamp <= current_time( 'timestamp', true ) ) {
					$show_date = sprintf(
						/* translators: %s: human-readable time difference */
						_x( '%s ago', '%s = human-readable time difference', 'woocommerce' ),
						human_time_diff( $invoice->get_date_created()->getTimestamp(), current_time( 'timestamp', true ) )
					);
				} else {
					$show_date = $invoice->get_date_created()->date_i18n( apply_filters( 'woocommerce_admin_order_date_format', __( 'M j, Y', 'woocommerce' ) ) );
				}

				$status  = wc_get_order_status_name( $invoice->get_status() );
				if ( $payment_method_title = $invoice->get_payment_method_title() ) {
					$status .= '<br><small class="meta gateway">' . wp_sprintf( __( 'Via %s', 'geodir_pricing' ), $payment_method_title ) . '</small>';
				}
			?>
			<tr>
				<td class="column-wpi_number"><?php echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $invoice->get_id() ) ) . '&action=edit' ) . '" class="order-view"><strong>#' . esc_attr( $invoice->get_order_number() ) . ' ' . esc_html( $buyer ) . '</strong></a>'; ?></td>
				<td class="column-wpi_invoice_date">
				<?php printf(
					'<time datetime="%1$s" title="%2$s">%3$s</time>',
					esc_attr( $invoice->get_date_created()->date( 'c' ) ),
					esc_html( $invoice->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ),
					esc_html( $show_date )
				); ?>
				</td>
				<td class="column-wpi_status"><?php echo $status; ?></td>
				<td class="column-wpi_amount"><?php echo wp_kses_post( $invoice->get_formatted_order_total() ); ?></td>
			</tr>
		<?php
		}
		?>
		</tbody>
		</table><?php
	}

	/**
	 * Create a invoice for claiming a listing.
	 *
	 * @param $post_id
	 * @param $package_id
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public static function create_claim_invoice( $post_id, $package_id, $user_id ) {
		$task = 'claim';
		$post_data = (array) geodir_get_post_info( $post_id );

		$product_id = apply_filters( 'geodir_pricing_wc_cart_product_id', self::get_product_id( (int)$package_id ), $task, (int) $post_id, (int) $package_id, $post_data );
		if ( empty( $product_id ) ) {
			return false;
		}

		try {
			// Remove cart.
			WC()->cart->empty_cart();

			if ( self::add_to_cart( $product_id ) && ( $cart_key = self::get_cart_key( $product_id ) ) ) {
				$data = array(
					'post_id' => $post_id,
					'package_id' => $package_id,
					'product_id' => $product_id,
					'cart' => 'woocommerce',
					'task' => $task,
					'status' => '',
					'meta' => maybe_serialize( array( 'task' => $task, 'cart_key' => $cart_key ) ),
					'date' => date_i18n( 'Y-m-d H:i:s' )
				);

				$data = apply_filters( 'geodir_pricing_wc_post_package_data', $data, (int) $post_id, (int) $package_id, $post_data );

				$payment_id = GeoDir_Pricing_Post_Package::save( $data );

				return $payment_id;
			}
		} catch ( Exception $e ) {
			geodir_error_log( $e->getMessage(), 'Pricing -> WC' );
		}

		return '';
	}

	/**
	 * Get the checkout url (without adding to cart)
	 * 
	 * @param string $payment_id
	 *
	 * @return mixed
	 */
	public static function get_checkout_url($payment_id = ''){
		return wc_get_checkout_url();// cart specific
	}

	public static function claim_submit_success_message( $message, $claim, $post_id ) {
		if ( ! empty( $claim->payment_id ) && ( $payment = GeoDir_Pricing_Post_Package::get_item( $claim->payment_id ) ) ) {
			if ( $cart_key = self::get_cart_key( $payment->product_id ) ) {
				$needs_payment = (bool) WC()->cart->needs_payment();

				if ( ! $needs_payment ) {
					$order = wc_create_order();
					$order->add_product( wc_get_product( $payment->product_id ), 1 );
					update_post_meta( $order->get_id(), '_customer_user', get_current_user_id() );
					$order->calculate_totals();
					$order->update_status( 'completed', __( 'Free order', 'geodir_pricing' ), true );  

					// Remove cart.
					WC()->cart->empty_cart();

					if ( geodir_get_option( 'claim_auto_approve_on_payment', 1 ) ) {
						$message = wp_sprintf( __( 'Your request to claim the listing has been approved. View the listing %shere%s.', 'geodir_pricing' ), '<a href="' . get_permalink( $post_id ) . '">', '</a>' );
					}
				} else {
					$message = wp_sprintf( __( 'Your claim requires a payment to complete.  %sCheckout%s', 'geodir_pricing' ), '<a href="' .  wc_get_checkout_url() . '" class="gd-noti-button" target="_top"><i class="fas fa-shopping-cart"></i> ', '</a>' );
				}
			}
		}

		return $message;
	}

	/**
	 * Set post author to handle restore post revisions on IPN requests.
	 *
	 * @since 2.5.0.13
	 *
	 * @param int $post_id Post ID.
	 * @param int $revision_id Post revision ID.
	 */
	public static function restore_post_revision( $post_id, $revision_id ) {
		global $geodir_post_author;

		if ( geodir_is_gd_post_type( get_post_type( $post_id ) ) ) {
			$geodir_post_author = geodir_get_listing_author( $revision_id );
		}
	}

}
