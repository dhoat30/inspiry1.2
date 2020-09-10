<?php

/**
 * The file that defines the core plugin class
 *
 *
 * @link       https://www.thewpnext.com/
 * @since      1.0.0
 *
 * @package    Woo_Free_Product_Sample
 * @subpackage Woo_Free_Product_Sample/includes
 * @author     hossain88 <muhin.cse.diu@gmail.com> 
 */

class Woo_Free_Product_Sample_Message {

	/**
	 * The option of this plugin.
	 *
	 * @since    2.0.0
	 * @param    string 
	 */
	public static $_optionName  = 'woo_free_product_sample_settings';

	/**
	 * The option message of this plugin.
	 *
	 * @since    2.0.0
	 * @param    string 
	 */	
	public static $_optionNameMessage  = 'woo_free_product_sample_message';	
	
	/**
	 * The option group of this plugin.
	 *
	 * @since    2.0.0
	 * @param    string 
	 */	
	public static $_optionGroup = 'woo-free-product-sample-options-group';

	/**
	 * The option message group of this plugin.
	 *
	 * @since    2.0.0
	 * @param    string 
	 */	
	public static $_optionGroupMessage = 'woo-free-product-sample-options-message';	
	
	/**
	 * The default option of this plugin.
	 *
	 * @since    2.0.0
	 * @param    array 
	 */	
	public static $_defaultOptions = array(
		'button_label'          => 'Order a Sample',
		'max_qty_per_order'		=> 5 
	);

	/**
	 * The default option of this plugin.
	 *
	 * @since    2.0.0
	 * @param    array 
	 */	
	public static $_defaultMessageOptions = array(
		'qty_validation'      	   => ''	
	);

	/**
	 * Validation message
	 * 
	 * @since    2.0.0
	 * @param    none 
	 */    
    public static function validation_notice( $product_id ){

        $final_msg         = '';
        $setting_options   = wp_parse_args( get_option(self::$_optionName), self::$_defaultOptions );
      
		$setting_message   = wp_parse_args( get_option(self::$_optionNameMessage), self::$_defaultMessageOptions );
        $message 		   = isset( $setting_message['qty_validation'] ) ? $setting_message['qty_validation'] : '';  
        
        $product		   = wc_get_product( $product_id );
        $searchVal         = array("{product}", "{qty}");
        $replaceVal        = array($product->get_name(), $setting_options['max_qty_per_order'] );
        $final_msg         = str_replace($searchVal, $replaceVal, $message);         
        return $final_msg;    
           
    } 
}