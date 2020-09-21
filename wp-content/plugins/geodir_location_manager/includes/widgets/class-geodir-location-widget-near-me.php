<?php

/**
 * GeoDir_Location_Widget_Near_Me class.
 *
 * @since 2.0.0
 */
class GeoDir_Location_Widget_Near_Me extends WP_Super_Duper {

	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'textdomain'     => 'geodirlocation',
			'block-icon'     => 'location-alt',
			'block-category' => 'common',
			'block-keywords' => "['geodirlocation','location','near me']",
			'class_name'     => __CLASS__,
			'base_id'        => 'gd_location_near_me',
			'name'           => __( 'GD > Near Me Button', 'geodirlocation' ),
			'widget_ops'     => array(
				'classname'     => 'geodir-lm-popular-locations',
				'description'   => esc_html__( 'Displays near me button to share geo position.', 'geodirlocation' ),
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
            ),
			'button_title'  => array(
                'title' => __('Button title:', 'geodirlocation'),
                'desc' => __('Near me button title.', 'geodirlocation'),
                'type' => 'text',
                'default'  => '',
				'placeholder' => __( 'Near Me', 'geodirlocation' ),
                'desc_tip' => true,
                'advanced' => false
            ),
			'button_class'  => array(
                'title' => __('Button css class:', 'geodirlocation'),
                'desc' => __('Near me button css class.', 'geodirlocation'),
                'type' => 'text',
                'default'  => '',
                'desc_tip' => true,
                'advanced' => true
            ),
		);

		return $arguments;
	}

	public function output( $args = array(), $widget_args = array(), $content = '' ) {
		extract( $widget_args, EXTR_SKIP );

		$button_title = empty( $args['button_title'] ) ? __( 'Near Me', 'geodirlocation' ) : apply_filters( 'geodir_location_widget_near_me_button_title', __( $args['button_title'], 'geodirlocation' ), $args, $this->id_base );
		$button_class = empty( $args['button_class'] ) ? '' : apply_filters( 'geodir_location_widget_near_me_button_class', $args['button_class'], $args, $this->id_base );

		$output = '<button type="button" class="geodir-location-near-me ' . esc_attr( $button_class ) . '" onclick="gd_get_user_position(gdlm_ls_near_me);">' . $button_title . '</button>';

		return $output;
	}
	
}

