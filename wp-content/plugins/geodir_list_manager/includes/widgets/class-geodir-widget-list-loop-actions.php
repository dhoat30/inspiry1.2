<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDirectory Search widget.
 *
 * @since 1.0.0
 */
class GeoDir_Widget_List_Loop_Actions extends WP_Super_Duper {

	/**
	 * Register the advanced search widget with WordPress.
	 *
	 */
	public function __construct() {

		$options = array(
			'textdomain'    => GEODIRECTORY_TEXTDOMAIN,
			'block-icon'    => 'list-view',
			'block-category'=> 'geodirectory',
			'block-keywords'=> "['list loop','lists','geodir']",
			'class_name'    => __CLASS__,
			'base_id'       => 'gd_list_loop_actions',
			'name'          => __( 'GD > List Loop Actions', 'gd-lists' ),
			'widget_ops'    => array(
				'classname'   => 'geodir-list-loop-actions-container' . ( geodir_design_style() ? ' bsui' : '' ),
				'description' => esc_html__( 'Shows the actions available to the user on a list page, like the author actions like edit and delete list.', 'gd-lists' ),
				'geodirectory' => true,
			),
		);

		parent::__construct( $options );
	}

	/**
	 * The Super block output function.
	 *
	 * @param array $args
	 * @param array $widget_args
	 * @param string $content
	 *
	 * @return mixed|string|void
	 */
	public function output( $args = array(), $widget_args = array(), $content = '' ) {
		global $post;

		ob_start();
		
		if ( is_single() && isset( $post->post_type ) && $post->post_type == 'gd_list' ) {
			do_action( 'geodir_lists_before_loop_actions' );
			do_action( 'geodir_lists_loop_actions' );
			do_action( 'geodir_lists_after_loop_actions' );
		}

		return ob_get_clean();
	}

}