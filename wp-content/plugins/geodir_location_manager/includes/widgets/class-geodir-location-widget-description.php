<?php

/**
 * GeoDir_Location_Widget_Description class.
 *
 * @since 2.0.0
 */
class GeoDir_Location_Widget_Description extends WP_Super_Duper {

	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'textdomain'     => 'geodirlocation',
			'block-icon'     => 'location-alt',
			'block-category' => 'common',
			'block-keywords' => "['geodirlocation','location','location description']",
			'class_name'     => __CLASS__,
			'base_id'        => 'gd_location_description',
			'name'           => __( 'GD > Location Description', 'geodirlocation' ),
			'widget_ops'     => array(
				'classname'     => 'geodir-lm-location-description',
				'description'   => esc_html__( 'Displays the current location description.', 'geodirlocation' ),
				'geodirectory'  => true,
				'gd_show_pages' => array(),
			)
		);

		parent::__construct( $options );
	}

	/**
	 * Set widget arguments.
	 *
	 */
	public function set_arguments() {
		$arguments = array(
			'title'  => array(
                'title' => __('Title:', 'geodirlocation'),
                'desc' => __('The widget title.', 'geodirlocation'),
                'type' => 'text',
                'default'  => '',
                'desc_tip' => true,
                'advanced' => false
            )
		);

		return $arguments;
	}

	public function output( $args = array(), $widget_args = array(), $content = '' ) {
		global $wpdb, $wp,$geodirectory;

		extract( $widget_args, EXTR_SKIP );

		$location = $geodirectory->location;

		$gd_country = isset( $location->country_slug ) ? $location->country_slug : '';
		$gd_region = isset( $location->region_slug ) ? $location->region_slug : '';
		$gd_city = isset( $location->city_slug ) ? $location->city_slug : '';
		$gd_neighbourhood = isset( $location->neighbourhood_slug ) ? $location->neighbourhood_slug : '';

		$type = !empty($location->type) ? $location->type : '';
		$value = $type && in_array($type,$geodirectory->location->allowed_query_variables()) ? $location->{$location->type."_slug"} : '';


		$location_title = '';
		$location_desc = '';
		if ($gd_neighbourhood) {
			$location_title = isset( $location->neighbourhood ) ? $location->neighbourhood : '';
			$hood_info = GeoDir_Location_Neighbourhood::get_info_by_slug($gd_neighbourhood);
			$location_desc = isset($hood_info->description) ? $hood_info->description : '';
		}elseif ($gd_city) {
			$location_title = geodir_location_get_name( 'city', $gd_city );
			$info = GeoDir_Location_SEO::get_seo_by_slug( $gd_city, 'city', $gd_country, $gd_region );
		} else if (!$gd_city && $gd_region) {
			$location_title = geodir_location_get_name( 'region', $gd_region );
			$info = GeoDir_Location_SEO::get_seo_by_slug( $gd_region, 'region', $gd_country );
		} else if (!$gd_city && !$gd_region && $gd_country) {
			$location_title = geodir_location_get_name( 'country', $gd_country, true );
			$info = GeoDir_Location_SEO::get_seo_by_slug( $gd_country, 'country' );			
		}
		if ( ! empty( $info ) && ! empty( $info->location_desc ) ) {
			$location_desc = $info->location_desc;
		}
		if ( ! empty( $location_desc ) ) {
			$location_desc = stripslashes( __( $location_desc, 'geodirlocation' ) );
		}
		
		/**
		 * Filter location description text..
		 *
		 * @since 1.4.0
		 *
		 * @param string $location_desc The location description text.
		 * @param string $gd_country The current country slug.
		 * @param string $gd_region The current region slug.
		 * @param string $gd_city The current city slug.
		 */
		$location_desc = apply_filters( 'geodir_location_description', $location_desc, $gd_country, $gd_region, $gd_city );
		if ( empty( $location_desc ) ) {
			return NULL;
		}

		$location_desc = str_replace( '%%location%%', $location_title, $location_desc );
		$location_desc = str_replace( '%location%', $location_title, $location_desc );

		$output = '<div class="geodir-location-desc">' . $location_desc . '</div>';

		return $output;
	}	
}

