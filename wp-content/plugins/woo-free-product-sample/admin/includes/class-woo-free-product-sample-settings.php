<?php

/**
 * Register all setting actions and filters for the plugin
 *
 * @link       https://www.thewpnext.com/
 * @since      2.0.0
 *
 * @package    Woo_Free_Product_Sample
 * @subpackage Woo_Free_Product_Sample/includes
 * @author     Mohiuddin Abdul Kader <muhin.cse.diu@gmail.com> 
 */

class Woo_Free_Product_Sample_Settings {

    /**
	 * Initialize the class and set its settings options.
	 *
	 * @since    2.0.0
	 * @param    none 
	 */
    public function __construct() {}

    /**
	 * Define setting options as array
	 *
	 * @since    2.0.0
	 * @param    none
     * @return   array 
	 */
    public static function wfps_setting_fields() {

        $setting_fields = array(                         
            array(
                'name'          => 'button_label',
                'label'         => __( 'Button Label', 'woo-free-product-sample' ),
                'type'          => 'text',
                'class'         => 'widefat',
                'description'   => __( 'Set Button Label', 'woo-free-product-sample' ),
                'placeholder'   => __( 'Set Button Label', 'woo-free-product-sample' ),
            ),
            array(
                'name'          => 'disable_limit_per_order',
                'label'         => __( 'Disable Maximum Limit', 'woo-free-product-sample' ),
                'type'          => 'checkbox',
                'class'         => 'widefat',
                'description'   => __( 'Disable maximum order limit validation', 'woo-free-product-sample' ),
            ),            
            array(
                'name'          => 'limit_per_order',
                'label'         => __( 'Maximum Limit Type', 'woo-free-product-sample' ),
                'type'          => 'select',
                'class'         => 'widefat',
                'description'   => __( 'Maximum Limit Type', 'woo-free-product-sample' ),
                'default'       => array(
                    'product'   => 'Product',
                    'all'       => 'Order',
                ),
				'style'			=> 'class="limit_per_order_area"',
				'position'		=> 'tr'                
            ),            
			array(
                'name'          => 'max_qty_per_order',
                'label'         => __( 'Maximum Quantity Per Order', 'woo-free-product-sample' ),
                'type'          => 'number',
                'class'         => 'widefat',
                'description'   => __( 'Maximum Quantity Per Order', 'woo-free-product-sample' ),
                'placeholder'   => 5,
				'style'			=> 'class="max_qty_per_order_area"',
				'position'		=> 'tr'                
            ),            
		);

		return apply_filters( 'woo_free_product_sample_setting_fields', $setting_fields );
    }
}
