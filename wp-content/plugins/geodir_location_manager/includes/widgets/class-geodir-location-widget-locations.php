<?php

/**
 * GeoDir_Location_Widget_Locations class.
 *
 * @since 2.0.0
 */
class GeoDir_Location_Widget_Locations extends WP_Super_Duper {

	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'textdomain'     => 'geodirlocation',
			'block-icon'     => 'location-alt',
			'block-category' => 'common',
			'block-keywords' => "['geodirlocation','location','locations']",
			'class_name'     => __CLASS__,
			'base_id'        => 'gd_locations',
			'name'           => __( 'GD > Locations', 'geodirlocation' ),
			'widget_ops'     => array(
				'classname'     => 'geodir-lm-locations',
				'description'   => esc_html__( 'Displays the locations.', 'geodirlocation' ),
				'gd_wgt_restrict' => '',
                'geodirectory' => true,
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
            ),
			'what' => array(
				'type' => 'select',
				'title' => __( 'Show Locations:', 'geodirlocation' ),
				'desc' => __( 'Select which locations to show in a list. Default: Cities', 'geodirlocation' ),
				'placeholder' => '',
				'default' => 'city',
				'options' =>  array(
					"city" => __( 'Cities', 'geodirlocation' ),
					"region" => __( 'Regions', 'geodirlocation' ),
					"country" => __( 'Countries', 'geodirlocation' ),
					"neighbourhood" => __( 'Neighbourhoods', 'geodirlocation' ),
				),
				'desc_tip' => true,
				'advanced' => false,
			),
			'output_type'  => array(
				'type' => 'select',
				'title' => __('Output type', 'geodirlocation'),
				'desc' => __('This determines the style of the output list.', 'geodirlocation'),
				'placeholder' => '',
				'default' => '',
				'options' =>  array(
					"" => __('List', 'geodirlocation'),
					"grid" => __('Image Grid', 'geodirlocation'),
				),
				'desc_tip' => true,
				'advanced' => true,
			),
            'fallback_image' => array(
				'type' => 'checkbox',
                'title' => __( "Show post image as a fallback?", 'geodirlocation' ),
				'desc' => __( "If location image not available then show last post image added under this location.", 'geodirlocation' ),
                'desc_tip' => true,
                'value'  => '1',
                'default'  => '0',
                'advanced' => true,
				'element_require' => '[%output_type%]=="grid"',
            ),
			'per_page'  => array(
				'type' => 'number',
				'title' => __('Number of locations:', 'geodirlocation'),
				'desc' => __('Number of locations to be shown on each page. Use 0(zero) or ""(blank) to show all locations.', 'geodirlocation'),
				'placeholder' => '',
				'default' => '',
				'desc_tip' => true,
				'advanced' => false
			),
            'pagi_t'  => array(
                'title' => __("Show pagination on top?", 'geodirlocation'),
                'type' => 'checkbox',
                'desc_tip' => false,
                'value'  => '1',
                'default'  => '0',
                'advanced' => true
            ),
            'pagi_b'  => array(
                'title' => __("Show pagination at bottom?", 'geodirlocation'),
                'type' => 'checkbox',
                'desc_tip' => false,
                'value'  => '1',
                'default'  => '0',
                'advanced' => true
            ),
			'pagi_info'  => array(
				'type' => 'select',
				'title' => __('Show advanced pagination details:', 'geodirlocation'),
				'desc' => __('This will add extra pagination info like "Showing locations x-y of z" after/before pagination.', 'geodirlocation'),
				'placeholder' => '',
				'default' => '',
                'options' =>  array(
                    "" => __('Never Display', 'geodirlocation'),
                    "after" => __('After pagination', 'geodirlocation'),
                    "before" => __('Before pagination', 'geodirlocation')
                ),
				'desc_tip' => true,
				'advanced' => true,
			),
            'no_loc'  => array(
                'title' => __("Disable location filter?", 'geodirlocation'),
				'desc' => __("Don't filter results for current location.", 'geodirlocation'),
                'type' => 'checkbox',
                'desc_tip' => true,
                'value'  => '1',
                'default'  => '0',
                'advanced' => false
            ),
			'show_current' => array(
				'title' => __( 'Show current location only', 'geodirlocation' ),
				'desc' => __( 'Tick to show only current country / region / city / neighbourhood when location filter is active & country / region / city / neighbourhood is set.', 'geodirlocation' ),
				'type' => 'checkbox',
				'desc_tip' => true,
				'value' => '1',
				'default' => '0',
				'advanced' => false,
				'element_require' => '( ! ( ( typeof form != "undefined" && jQuery( form ).find( "[data-argument=no_loc]" ).find( "input[type=checkbox]" ).is( ":checked" ) ) || ( typeof props == "object" && props.attributes && props.attributes.no_loc ) ) )',
			),
            'country' => array(
                'type' => 'text',
                'title' => __( 'Country slug', 'geodirlocation' ),
                'desc' => __( 'Filter the locations by country slug when location filter enabled. Default: current country.', 'geodirlocation' ),
                'placeholder' => '',
                'desc_tip' => true,
                'value' => '',
                'default' => '',
                'advanced' => true,
				'element_require' => '[%what%]!="country"',
            ),
            'region' => array(
                'type' => 'text',
                'title' => __( 'Region slug', 'geodirlocation' ),
                'desc' => __( 'Filter the locations by region slug when location filter enabled. Default: current region.', 'geodirlocation' ),
                'placeholder' => '',
                'desc_tip' => true,
                'value' => '',
                'default' => '',
                'advanced' => true,
				'element_require' => '[%what%]=="city" || [%what%]=="neighbourhood"',
            ),
            'city' => array(
                'type' => 'text',
                'title' => __( 'City slug', 'geodirlocation' ),
                'desc' => __( 'Filter the locations by city slug when location filter enabled. Default: current city.', 'geodirlocation' ),
                'placeholder' => '',
                'desc_tip' => true,
                'value' => '',
                'default' => '',
                'advanced' => true,
				'element_require' => '[%what%]=="neighbourhood"',
            )
		);

		return $arguments;
	}

	public function output( $args = array(), $widget_args = array(), $content = '' ) {
		extract( $widget_args, EXTR_SKIP );

		/**
		 * Filter the widget title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title The widget title. Default empty.
		 * @param array  $args An array of the widget's settings.
		 * @param mixed  $id_base The widget ID.
		 */
		$title = apply_filters('geodir_popular_location_widget_title', !empty($args['title']) ? $args['title'] : '', $args, $this->id_base);
		
		/**
		 * Filter the no. of locations to shows on each page.
		 *
		 * @since 1.5.0
		 *
		 * @param int   $per_page No. of locations to be displayed.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['per_page'] = apply_filters('geodir_popular_location_widget_per_page', !empty($args['per_page']) ? absint($args['per_page']) : '', $args, $this->id_base);
		
		/**
		 * Whether to show pagination on top of widget content.
		 *
		 * @since 1.5.0
		 *
		 * @param bool  $pagi_t If true then pagination displayed on top. Default false.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['pagi_t'] = apply_filters('geodir_popular_location_widget_pagi_top', !empty($args['pagi_t']) ? true : false, $args, $this->id_base);
		
		/**
		 * Whether to show pagination on bottom of widget content.
		 *
		 * @since 1.5.0
		 *
		 * @param bool  $pagi_b If true then pagination displayed on bottom. Default false.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['pagi_b'] = apply_filters('geodir_popular_location_widget_pagi_bottom', !empty($args['pagi_b']) ? true : false, $args, $this->id_base);
		
		/**
		 * Filter the position to display advanced pagination info.
		 *
		 * @since 1.5.0
		 *
		 * @param string  $pagi_info Position to display advanced pagination info.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['pagi_info'] = apply_filters('geodir_popular_location_widget_pagi_info', !empty($args['pagi_info']) ? $args['pagi_info'] : '', $args, $this->id_base);
		
		/**
		 * Whether to disable filter results for current location.
		 *
		 * @since 1.5.0
		 *
		 * @param bool  $no_loc If true then results not filtered for current location. Default false.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['no_loc'] = apply_filters('geodir_popular_location_widget_no_location_filter', !empty($args['no_loc']) ? true : false, $args, $this->id_base);

		/**
		 * Whether to show current country / region / city / neighbourhood only.
		 *
		 * @since 2.0.0.24
		 *
		 * @param bool  $show_current If true then it will show only current location. Default false.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['show_current'] = apply_filters( 'geodir_popular_location_widget_show_current_filter', ! empty( $args['show_current'] ) ? true : false, $args, $this->id_base );

		/**
		 * Whether to disable filter results for current location.
		 *
		 * @since 1.5.0
		 *
		 * @param bool  $output_type If true then results not filtered for current location. Default false.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['output_type'] = apply_filters('geodir_popular_location_widget_output_type_filter', !empty($args['output_type']) ? $args['output_type'] : 'list', $args, $this->id_base);

		/**
		 * Whether to show post image as a fallback image.
		 *
		 * @since 2.0.0.25
		 *
		 * @param bool  $fallback_image If true then show post image when location image not available. Default false.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['fallback_image'] = apply_filters( 'geodir_popular_location_widget_fallback_image_filter', ( ! empty( $args['fallback_image'] ) ? true : false ), $args, $this->id_base );
		
		$what = ! empty( $args['what'] ) && in_array( $args['what'], array( 'country', 'region', 'city', 'neighbourhood' ) ) ? $args['what'] : 'city';
		/**
		 * Filter which location to show in a list.
		 *
		 * @since 2.0.0.22
		 *
		 * @param string $what The locations to show. Default city.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['what'] = apply_filters( 'geodir_popular_location_widget_what_filter', $what, $args, $this->id_base );
		
		/**
		 * Filter the locations by country.
		 *
		 * @since 2.0.0.22
		 *
		 * @param string $country The country.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['country'] = apply_filters( 'geodir_popular_location_widget_country_filter', ( ! empty( $args['country'] ) ? $args['country'] : '' ), $args, $this->id_base );

		/**
		 * Filter the locations by region.
		 *
		 * @since 2.0.0.22
		 *
		 * @param string $region The region.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['region'] = apply_filters( 'geodir_popular_location_widget_region_filter', ( ! empty( $args['region'] ) ? $args['region'] : '' ), $args, $this->id_base );

		/**
		 * Filter the locations by city.
		 *
		 * @since 2.0.0.22
		 *
		 * @param string $city The city.
		 * @param array $args An array of the widget's settings.
		 * @param mixed $id_base The widget ID.
		 */
		$params['city'] = apply_filters( 'geodir_popular_location_widget_city_filter', ( ! empty( $args['city'] ) ? $args['city'] : '' ), $args, $this->id_base );

		$params['widget_atts'] = $params;

		ob_start();
		?>
		<div class="geodir-category-list-in clearfix geodir-location-lity-type-<?php echo esc_attr( $params['output_type'] ); ?>">
		    <?php geodir_popular_location_widget_output( $params ); ?>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}	
}

