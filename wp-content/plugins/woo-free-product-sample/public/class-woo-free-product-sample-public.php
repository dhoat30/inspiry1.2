<?php
/**
 *
 * @link       https://www.thewpnext.com/
 * @since      1.0.0
 *
 * @package    Woo_Free_Product_Sample
 * @subpackage Woo_Free_Product_Sample/public
 */

class Woo_Free_Product_Sample_Public {

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      string 
	 */
	private $plugin_name;

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version 
	 */
	private $version;

	/**
	 * The option of this plugin.
	 *
	 * @since    2.0.0
	 * @param    string 
	 */
	public $_optionName  = 'woo_free_product_sample_settings';
	
	/**
	 * The option group of this plugin.
	 *
	 * @since    2.0.0
	 * @param    string 
	 */	
	public $_optionGroup = 'woo-free-product-sample-options-group';
	
	/**
	 * The default option of this plugin.
	 *
	 * @since    2.0.0
	 * @param    array 
	 */	
	public $_defaultOptions = array(
		'button_label'          => 'Order a Sample',
		'max_qty_per_order'		=> 5 
	);
	
	public $_total = 1;

	/**
	 * @since    1.0.0
	 * @param    string    $plugin_name
	 * @param    string    $version
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name 	= $plugin_name;
		$this->version 		= $version;	
	}

	/**
	 *
	 * @since    1.0.0
	 */
	public function wfps_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-free-product-sample-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Return sample price
	 * 
	 * @since    2.0.0
	 * @param    none
	 */
	public static function wfps_price( $product_id ) {	
		//update_option( 'current_id', $product_id );	
		return apply_filters( 'woo_free_product_sample_price', 0.00, $product_id );
	}
	
	/**
	 * Retrive button label	
	 * 
	 * @since    2.0.0
	 * @param    none
	 */	
	public function wfps_button_text() {
		$setting_options   = wp_parse_args( get_option($this->_optionName),$this->_defaultOptions );
		return isset( $setting_options['button_label'] ) ? esc_html__( $setting_options['button_label'], 'woo-free-product-sample' ) : esc_html__( 'Order a Free Sample', 'woo-free-product-sample' );
	}

	/**
	 * Check product type in product details page
	 * 
	 * @since    2.0.0
	 * @param    none
	 */	
	public static function wfps_product_type() {
		global $product;
		if( $product->is_type( 'simple' ) ) {
			return 'simple';
		} else if( $product->is_type( 'variable' ) ) {
			return 'variable';
		} else {
			return NULL;
		}
	}

	/**
	 * Display sample button
	 * 
	 * @since  	2.0.0
	 * @param  	none  
	 * @return 	html
	 */
	public function wfps_button() {

		global $product;
		if ( $product->is_in_stock() ) {

			switch ( self::wfps_product_type() ) {
				case "simple":
					$button = '<button type="submit" name="simple-add-to-cart" value="'.get_the_ID().'" class="woo-free-sample-button">'.$this->wfps_button_text().'</button>';
					break;
				case "variable":
					$button = '<button type="submit" name="variable-add-to-cart" value="'.get_the_ID().'" class="woo-free-sample-button">'.$this->wfps_button_text().'</button>';
					break;			
				default:
					$button = '';
			} 
			echo apply_filters( 
						'woo_free_product_sample_button',
						$button
					);								
		}

	}	

	/**
	 * Handle add to cart
	 *
	 * @since 2.0.0
	 * @param string
	 */
	public static function wfps_add_to_cart_action( $url = false ) {

		if ( 
			! isset( $_REQUEST['simple-add-to-cart'] ) || 
			! is_numeric( wp_unslash( $_REQUEST['simple-add-to-cart'] ) )
		)			 
		{
			return;
		}

		wc_nocache_headers();

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( wp_unslash( $_REQUEST['simple-add-to-cart'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
		$was_added_to_cart = false;
		$adding_to_cart    = wc_get_product( $product_id );

		if ( ! $adding_to_cart ) {
			return;
		}
		
		$add_to_cart_handler = apply_filters( 'woocommerce_add_to_cart_handler', $adding_to_cart->get_type(), $adding_to_cart );

		if ( 'variable' === $add_to_cart_handler || 'variation' === $add_to_cart_handler ) {
			$was_added_to_cart = self::wfps_add_to_cart_handler_variable( $product_id );
		} else {
			$was_added_to_cart = self::wfps_add_to_cart_handler_simple( $product_id );
		}

		// If we added the product to the cart we can now optionally do a redirect.
		if ( $was_added_to_cart && 0 === wc_notice_count( 'error' ) ) {
			$url = apply_filters( 'woocommerce_add_to_cart_redirect', $url, $adding_to_cart );

			if ( $url ) {
				wp_safe_redirect( $url );
				exit;
			} elseif ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wp_safe_redirect( wc_get_cart_url() );
				exit;
			}
		}
	}

	/**
	 * Handle adding simple products to the cart.
	 *
	 * @since 2.4.6 Split from add_to_cart_action.
	 * @param int $product_id Product ID to add to the cart.
	 * @return bool success or not
	 */
	private static function wfps_add_to_cart_handler_simple( $product_id ) {

		$quantity          = 1; // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );	

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) ) {
			wc_add_to_cart_message( array( $product_id => $quantity ), true );
			return true;
		}
		return false;
	}
	
	/**
	 * Handle adding variable products to the cart.
	 *
	 * @since 2.4.6 Split from add_to_cart_action.
	 * @throws Exception If add to cart fails.
	 * @param int $product_id Product ID to add to the cart.
	 * @return bool success or not
	 */
	private static function wfps_add_to_cart_handler_variable( $product_id ) {
		try {
			$variation_id       = empty( $_REQUEST['variation_id'] ) ? '' : absint( wp_unslash( $_REQUEST['variation_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			$quantity           = empty( $_REQUEST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_REQUEST['quantity'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
			$missing_attributes = array();
			$variations         = array();
			$adding_to_cart     = wc_get_product( $product_id );

			if ( ! $adding_to_cart ) {
				return false;
			}				

			// If the $product_id was in fact a variation ID, update the variables.
			if ( $adding_to_cart->is_type( 'variation' ) ) {
				$variation_id   = $product_id;
				$product_id     = $adding_to_cart->get_parent_id();
				$adding_to_cart = wc_get_product( $product_id );

				if ( ! $adding_to_cart ) {
					return false;
				}
			}

			// Gather posted attributes.
			$posted_attributes = array();

			foreach ( $adding_to_cart->get_attributes() as $attribute ) {
				if ( ! $attribute['is_variation'] ) {
					continue;
				}
				$attribute_key = 'attribute_' . sanitize_title( $attribute['name'] );

				if ( isset( $_REQUEST[ $attribute_key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
					if ( $attribute['is_taxonomy'] ) {
						// Don't use wc_clean as it destroys sanitized characters.
						$value = sanitize_title( wp_unslash( $_REQUEST[ $attribute_key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
					} else {
						$value = html_entity_decode( wc_clean( wp_unslash( $_REQUEST[ $attribute_key ] ) ), ENT_QUOTES, get_bloginfo( 'charset' ) ); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
					}

					$posted_attributes[ $attribute_key ] = $value;
				}
			}

			// If no variation ID is set, attempt to get a variation ID from posted attributes.
			if ( empty( $variation_id ) ) {
				$data_store   = WC_Data_Store::load( 'product' );
				$variation_id = $data_store->find_matching_product_variation( $adding_to_cart, $posted_attributes );
			}

			// Do we have a variation ID?
			if ( empty( $variation_id ) ) {
				throw new Exception( __( 'Please choose product options&hellip;', 'woocommerce' ) );
			}

			// Check the data we have is valid.
			$variation_data = wc_get_product_variation_attributes( $variation_id );

			foreach ( $adding_to_cart->get_attributes() as $attribute ) {
				if ( ! $attribute['is_variation'] ) {
					continue;
				}

				// Get valid value from variation data.
				$attribute_key = 'attribute_' . sanitize_title( $attribute['name'] );
				$valid_value   = isset( $variation_data[ $attribute_key ] ) ? $variation_data[ $attribute_key ] : '';

				/**
				 * If the attribute value was posted, check if it's valid.
				 *
				 * If no attribute was posted, only error if the variation has an 'any' attribute which requires a value.
				 */
				if ( isset( $posted_attributes[ $attribute_key ] ) ) {
					$value = $posted_attributes[ $attribute_key ];

					// Allow if valid or show error.
					if ( $valid_value === $value ) {
						$variations[ $attribute_key ] = $value;
					} elseif ( '' === $valid_value && in_array( $value, $attribute->get_slugs(), true ) ) {
						// If valid values are empty, this is an 'any' variation so get all possible values.
						$variations[ $attribute_key ] = $value;
					} else {
						/* translators: %s: Attribute name. */
						throw new Exception( sprintf( __( 'Invalid value posted for %s', 'woocommerce' ), wc_attribute_label( $attribute['name'] ) ) );
					}
				} elseif ( '' === $valid_value ) {
					$missing_attributes[] = wc_attribute_label( $attribute['name'] );
				}
			}
			if ( ! empty( $missing_attributes ) ) {
				/* translators: %s: Attribute name. */
				throw new Exception( sprintf( _n( '%s is a required field', '%s are required fields', count( $missing_attributes ), 'woocommerce' ), wc_format_list_of_items( $missing_attributes ) ) );
			}
		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
			return false;
		}

		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) ) {
			wc_add_to_cart_message( array( $product_id => $quantity ), true );
			return true;
		}

		return false;
	}	
	 
	/**
	 * Set sample price in the cart
	 * 
	 * @since      2.0.0     
	 * @param      string, string    	 
	 */
	public function wfps_store_id( $cart_item ) {

		if( isset( $_REQUEST['simple-add-to-cart'] ) || isset( $_REQUEST['variable-add-to-cart'] ) ) {
			$cart_item['free_sample']  = isset( $_REQUEST['simple-add-to-cart'] ) ? sanitize_text_field( $_REQUEST['simple-add-to-cart'] ) : sanitize_text_field( $_REQUEST['variable-add-to-cart'] );
			$product_id = isset( $_REQUEST['simple-add-to-cart'] ) ? sanitize_text_field( $_REQUEST['simple-add-to-cart'] ) : sanitize_text_field( $_REQUEST['variable-add-to-cart'] );
			$cart_item['sample_price'] = self::wfps_price( $product_id );
			$cart_item['line_subtotal']= self::wfps_price( $product_id );
			$cart_item['line_total']   = self::wfps_price( $product_id );				
		}			
		return $cart_item; 
	}	

	/**
	 * Set sample price in session
	 * 
	 * @since      2.0.0
	 * @param      array, array    
	 */
	public function wfps_get_cart_items_from_session( $cart_item, $values ) {
	
		if ( isset( $values['simple-add-to-cart'] ) || isset( $values['variable-add-to-cart'] ) ) {
			$cart_item['free_sample'] 		= isset( $values['simple-add-to-cart'] ) ? $values['simple-add-to-cart'] : $values['variable-add-to-cart'];
			$product_id 					= isset( $_REQUEST['simple-add-to-cart'] ) ? sanitize_text_field( $_REQUEST['simple-add-to-cart'] ) : sanitize_text_field( $_REQUEST['variable-add-to-cart'] );
			$cart_item['line_subtotal'] 	= self::wfps_price( $product_id );
			$cart_item['line_total'] 	  	= self::wfps_price( $product_id );	
		}    

		return $cart_item;
	}
	 
	/**
	 * Add product meta for sample to indentity in the admin order details
	 * 
	 * @since      2.0.0
	 * @param      int, array    	 
	 */
	public function wfps_save_posted_data_into_order( $itemID, $values ) {

		if ( isset( $values['free_sample'] ) ) {
			wc_add_order_item_meta( $itemID, 'PRODUCT_TYPE', 'Sample' );
			wc_add_order_item_meta( $itemID, 'SAMPLE_PRICE', $values['sample_price'] );
		}
		
	}

	/**
	 * Return plugin directory
	 *
	 * @since      2.0.0
	 * @param      none
	 */
	public static function wfps_get_plugin_path(){		
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Return WooCommerce template path
	 * 
	 * @since      2.0.0
	 * @param      none
	 */
	public function wfps_set_locate_template( $template, $template_name, $template_path ) {

		global $woocommerce;
		$_template = $template;
		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}		

	  	$plugin_path  = self::wfps_get_plugin_path() . '/partials/woocommerce/';
	  	$template = locate_template(
	    	array(
	      		$template_path . $template_name,
	      		$template_name
	    	)
	  	);

	  	if ( ! $template && file_exists( $plugin_path . $template_name ) )
	    	$template = $plugin_path . $template_name;

	  	if ( ! $template )
	    	$template = $_template;

		return $template;		
		  
	}

	/**
	 * Set sample price in the order meta
	 * 
	 * @since      2.0.0
	 * @param      object, array     	 
	 */
    public function wfps_apply_sample_price_to_cart_item( $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;

		// Avoiding hook repetition (when using price calculations for example)
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
		return;	
	
		foreach ( $cart->get_cart() as $key => $value ) {
			if( isset( $value["sample_price"] ) ) {
				$value['data']->set_price($value["sample_price"]);				
			}				

		}   
	}

	/**
	 * Display validation message when order a product sample 
	 *
	 * @since      2.0.0
	 * @param      int, array 
	 */		
	public function wfps_set_limit_per_order( $valid, $product_id ) {
	
		global $woocommerce;
		$setting_options   = wp_parse_args( get_option($this->_optionName), $this->_defaultOptions );
		$notice_type 	   = isset( $setting_options['limit_per_order'] ) ? $setting_options['limit_per_order'] : null;
		$disable_limit 	   = isset( $setting_options['disable_limit_per_order'] ) ? $setting_options['disable_limit_per_order'] : null;

		if( ! isset( $disable_limit ) ) :
			foreach( $woocommerce->cart->get_cart() as $key => $val ) :
				
				if( 'product' == $notice_type ) {

					if( ( isset( $val['free_sample'] ) && $product_id == $val['free_sample'] ) && ( $setting_options['max_qty_per_order'] <= $val['quantity'] ) && ( isset( $_REQUEST['simple-add-to-cart'] ) || isset( $_REQUEST['variable-add-to-cart'] ) ) ) {
						if( get_locale() == 'ja' ) {
							wc_add_notice( esc_html__( 'この商品を注文できます '.$setting_options['max_qty_per_order'].' 注文あたりの数量。', 'woo-free-product-sample' ), 'error' );
						} else {
							wc_add_notice( esc_html__( 'You can order this product '.$setting_options['max_qty_per_order'].' quantity per order.', 'woo-free-product-sample' ), 'error' );
						}						
						exit( wp_redirect( get_permalink($product_id) ) );						
					}	

				} else if( 'all' == $notice_type ) {

					if( ( isset( $val['free_sample'] ) ) && ( $setting_options['max_qty_per_order'] <= $this->wfps_cart_total() ) && ( isset( $_REQUEST['simple-add-to-cart'] ) || isset( $_REQUEST['variable-add-to-cart'] ) ) ) {
						if( get_locale() == 'ja' ) {
							wc_add_notice( esc_html__( 'サンプル商品を最大で注文できます '.$setting_options['max_qty_per_order'].' 注文あたりの数量。', 'woo-free-product-sample' ), 'error' );
						} else {
							wc_add_notice( esc_html__( 'You can order sample product maximum '.$setting_options['max_qty_per_order'].' quantity per order.', 'woo-free-product-sample' ), 'error' );
						}						
						exit( wp_redirect( get_permalink($product_id) ) );						
					}

				}
			endforeach; 
		endif; 
		return $valid;

	}

	/**
	 * Show validation message in the cart page for maximum order
	 * 
	 * @since      2.0.0
	 * @param      boolean, array, array, int 
	 */
	public function wfps_cart_update_limit_order( $passed, $cart_item_key, $values, $updated_quantity ) {

		$setting_options   = wp_parse_args( get_option($this->_optionName), $this->_defaultOptions );
		$notice_type 	   = isset( $setting_options['limit_per_order'] ) ? $setting_options['limit_per_order'] : null;
		$disable_limit 	   = isset( $setting_options['disable_limit_per_order'] ) ? $setting_options['disable_limit_per_order'] : null;

		if( ! isset( $disable_limit ) ) :

			if( 'product' == $notice_type ) {

				if( ( $values['free_sample'] == $values['product_id'] ) && ( $setting_options['max_qty_per_order'] < $updated_quantity ) ) {			
				
					$product = wc_get_product( $values['product_id'] );				
					
					if( get_locale() == "ja" ) {
						wc_add_notice( esc_html__( '注文できます '.$product->get_name().' 最大 '.$setting_options['max_qty_per_order'].'  注文ごと。', 'woo-free-product-sample' ), 'error' );
					} else {
						wc_add_notice( esc_html__( 'You can order '.$product->get_name().' maximum  '.$setting_options['max_qty_per_order'].'  per order.', 'woo-free-product-sample' ), 'error' );
					}
					
					$passed = false;
				}

			} else if( 'all' == $notice_type ) {

				if( ( isset( $values['free_sample'] ) ) && ( $setting_options['max_qty_per_order'] <= $this->wfps_cart_total() ) ) {
					if( get_locale() == 'ja' ) {
						wc_add_notice( esc_html__( 'サンプル商品を最大で注文できます '.$setting_options['max_qty_per_order'].' 注文あたりの数量。', 'woo-free-product-sample' ), 'error' );
					} else {
						wc_add_notice( esc_html__( 'You can order sample product maximum '.$setting_options['max_qty_per_order'].' quantity per order.', 'woo-free-product-sample' ), 'error' );
					}					
				}
			}

		endif; 
		return $passed;

	}	

	public function wfps_cart_total( ) {

		global $woocommerce;
		$total = 0;
		foreach( $woocommerce->cart->get_cart() as $key => $val ) {
			if( isset( $val['free_sample'] ) ) {				
				$total += $val['quantity'];
			}
		}
		return $total;		

	}		

	/**
	 * Sample product added in the cart message
	 *
	 * @since      2.0.0
	 * @param      int, array 
	 */	
	public function wfps_add_to_cart_message ( $message, $products ) {

		$titles = '';
		if( isset( $_REQUEST['simple-add-to-cart'] ) || isset( $_REQUEST['variable-add-to-cart'] ) ) {
			
			$count = 0;
			$titles = array();
			foreach ( $products as $product_id => $qty ) {
				if( get_locale() == "ja" ) {
					$sample =  esc_html__( 'サンプル - ', 'woo-free-product-sample' );
				} else {
					$sample =  esc_html__( 'Sample - ', 'woo-free-product-sample' );
				}
				
				$titles[] = apply_filters( 'woocommerce_add_to_cart_qty_html', ( $qty > 1 ? absint( $qty ) . ' &times; ' : '' ), $product_id ) . apply_filters( 'woocommerce_add_to_cart_item_name_in_quotes', sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'woocommerce' ), strip_tags( $sample . get_the_title( $product_id ) ) ), $product_id );
				$count   += $qty;
			}
			
			$titles = array_filter( $titles );
			/* translators: %s: product name */
			$added_text = sprintf( _n( '%s has been added to your cart.', '%s have been added to your cart.', $count, 'woocommerce' ), wc_format_list_of_items( $titles ) );		
	
			// Output success messages.
			$message = sprintf( '<a href="%s" tabindex="1" class="button wc-forward">%s</a> %s', esc_url( wc_get_cart_url() ), esc_html__( 'View cart', 'woocommerce' ), esc_html( $added_text ) );
			return $message;
	
		} 
	
		return $message;

	}

	/**
	 * Add sample label before the product 
	 *
	 * @since      2.0.0
	 * @param      string, array, array 
	 */	
	public function wfps_alter_item_name ( $product_name, $cart_item, $cart_item_key ) {

		$product 			= $cart_item['data']; // Get the WC_Product Object
		$sample_price 		= self::wfps_price( $cart_item['product_id'] );
		$sample_price 		= str_replace( ",",".", $sample_price );
		$prod_price 		= str_replace( ",",".", $product->get_price() );	
		if( $sample_price == $prod_price ) {
			if( get_locale() == 'ja' ) {
				$product_name   = esc_html__( 'サンプル - ', 'woo-free-product-sample' ).$product_name;		
			} else {
				$product_name   = esc_html__( 'Sample - ', 'woo-free-product-sample' ).$product_name;
			}
			
		}

		return $product_name;
	}

   	/**
	 * Set sample price instead real price
	 * 
	 * @since      2.0.0
	 * @param      int, array, array 
	 */
    public function wfps_cart_item_price_filter( $price, $cart_item, $cart_item_key ) {
	
		$product 			= $cart_item['data']; // Get the WC_Product Object
		$sample_price 		= self::wfps_price( $cart_item['product_id'] );
		$set_price 			= str_replace( ",", ".", $sample_price );
		if( isset( $cart_item['sample_price'] ) ) {
			$item_price 	= str_replace( ",", ".", $cart_item['sample_price'] );	
			if( $item_price == $set_price ) {
				$price      = wc_price( $item_price );		
			}
		}
		
		return $price;
	}
	
	public function wfps_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
		
		if( isset( $cart_item['sample_price'] ) ) {
			$newsubtotal = wc_price( $cart_item['sample_price'] * $cart_item['quantity'] ); 		 
			$subtotal = $newsubtotal; 			
		}
		 
		return $subtotal;
	}
	
}