<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GeoDirectory Custom Post Types Widgets class
 *
 * @class       GeoDir_CP_Widgets
 * @version     2.0.0
 * @package     GeoDir_Custom_Posts/Widgets
 * @category    Class
 * @author      AyeCode Ltd
 */
class GeoDir_CP_Widgets {

	public static function init() {
		add_filter( 'wp_super_duper_arguments', array( __CLASS__, 'super_duper_arguments' ), 1, 3 );
	}

	public static function super_duper_arguments( $arguments, $options, $instance = array() ) {
		if ( ! empty( $options['textdomain'] ) && $options['textdomain'] == GEODIRECTORY_TEXTDOMAIN ) {
			if ( $options['base_id'] == 'gd_listings' || $options['base_id'] == 'gd_linked_posts' ) {
				if ( ! empty( $arguments['category'] ) && ! empty( $instance['post_type'] ) ) {
					$arguments['category']['options'] = geodir_category_options( $instance['post_type'] );
				}
				if ( ! empty( $arguments['sort_by'] ) && ! empty( $instance['post_type'] ) ) {
					$arguments['sort_by']['options'] = geodir_sort_by_options( $instance['post_type'] );
				}
			}
		}
		return $arguments;
	}
}
