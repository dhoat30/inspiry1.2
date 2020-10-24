<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDirectory Search widget.
 *
 * @since 1.0.0
 */
class GeoDir_Widget_List_Loop extends WP_Super_Duper {

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
			'base_id'       => 'gd_list_loop', // this us used as the widget id and the shortcode id.
			'name'          => __( 'GD > List Loop', 'gd-lists' ), // the name of the widget.
			'widget_ops'    => array(
				'classname'    => 'geodir-list-loop-container' . ( geodir_design_style() ? ' bsui' : '' ),
				'description'  => esc_html__( 'Shows the current posts saved to a list.', 'gd-lists' ), // widget description
				'geodirectory' => true,
			)
		);

		parent::__construct( $options );
	}

	/**
	 * Set widget arguments.
	 */
	public function set_arguments() {
		$design_style = geodir_design_style();

		$arguments = array();

		$arguments['layout'] = array(
			'title' => __( 'Layout:', 'gd-lists' ),
			'desc' => __( 'How the listings should laid out by default.', 'gd-lists' ),
			'type' => 'select',
			'options' => geodir_get_layout_options(),
			'default' => '2',
			'desc_tip' => true,
			'advanced' => true
		);

		if ( $design_style ) {
			$arguments['row_gap'] = array(
				'title' => __( 'Card row gap', 'gd-lists' ),
				'desc' => __('This adjusts the spacing between the cards horizontally.', 'gd-lists' ),
				'type' => 'select',
				'options' => array(
					'' => __( 'Default', 'geodirectory' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				),
				'default' => '',
				'desc_tip' => false,
				'advanced' => false,
				'group' => __( 'Card Design', 'geodirectory' )
			);

			$arguments['column_gap'] = array(
				'title' => __( 'Card column gap', 'gd-lists' ),
				'desc' => __('This adjusts the spacing between the cards vertically.', 'gd-lists' ),
				'type' => 'select',
				'options' => array(
					'' => __( 'Default', 'geodirectory' ),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				),
				'default' => '',
				'desc_tip' => false,
				'advanced' => false,
				'group' => __( 'Card Design', 'geodirectory' )
			);

			$arguments['card_border'] = array(
				'title' => __( 'Card border', 'gd-lists' ),
				'desc' => __('Set the border style for the card.', 'gd-lists' ),
				'type' => 'select',
				'options' => array(
					'' => __( 'Default', 'geodirectory' ),
					'none' => __( 'None', 'geodirectory' ),
				) + geodir_aui_colors(),
				'default' => '',
				'desc_tip' => false,
				'advanced' => false,
				'group' => __( 'Card Design', 'geodirectory' )
			);

			$arguments['card_shadow'] = array(
				'title' => __( 'Card shadow', 'gd-lists' ),
				'desc' => __('Set the card shadow style.', 'gd-lists' ),
				'type' => 'select',
				'options' => array(
					'' => __( 'None', 'geodirectory' ),
					'small' => __( 'Small', 'geodirectory' ),
					'medium' => __( 'Medium', 'geodirectory' ),
					'large' => __( 'Large', 'geodirectory' ),
				),
				'default' => '',
				'desc_tip' => false,
				'advanced' => false,
				'group' => __( 'Card Design', 'geodirectory' )
			);

			// margins
			$arguments['mt'] = geodir_get_sd_margin_input( 'mt' );
			$arguments['mr'] = geodir_get_sd_margin_input( 'mr' );
			$arguments['mb'] = geodir_get_sd_margin_input( 'mb' );
			$arguments['ml'] = geodir_get_sd_margin_input( 'ml' );

			// padding
			$arguments['pt'] = geodir_get_sd_padding_input( 'pt' );
			$arguments['pr'] = geodir_get_sd_padding_input( 'pr' );
			$arguments['pb'] = geodir_get_sd_padding_input( 'pb' );
			$arguments['pl'] = geodir_get_sd_padding_input( 'pl' );
		}

		return $arguments;
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
		global $wp_query, $post, $geodir_is_widget_listing, $gd_layout_class;

		$design_style = geodir_design_style();

		ob_start();

		if ( is_single() && isset( $post->post_type ) && $post->post_type == 'gd_list' ) {
			// Check if we have listings
			$data = new GeoDir_Lists_Data();
			$posts = $data->get_posts();

			$widget_args = wp_parse_args( $args, array(
				'layout' => '',
				// AUI settings
				'column_gap' => '',
				'row_gap' => '',
				'card_border' => '',
				'card_shadow' => '',
			) );

			$gd_layout_class = geodir_convert_listing_view_class( $widget_args['layout'] );

			// Card border class
			$card_border_class = '';
			if ( ! empty( $widget_args['card_border'] ) ) {
				if ( $widget_args['card_border'] == 'none' ) {
					$card_border_class = 'border-0';
				} else {
					$card_border_class = 'border-' . sanitize_html_class( $widget_args['card_border'] );
				}
			}

			// Card shadow class
			$card_shadow_class = '';
			if ( ! empty( $widget_args['card_shadow'] ) ) {
				if ( $widget_args['card_shadow'] == 'small' ) {
					$card_shadow_class = 'shadow-sm';
				} elseif ( $widget_args['card_shadow'] == 'medium' ) {
					$card_shadow_class = 'shadow';
				} elseif ( $widget_args['card_shadow'] == 'large' ) {
					$card_shadow_class = 'shadow-lg';
				}
			}

			// Wrap class
			$wrap_class = geodir_build_aui_class( $widget_args );

			if ( $wrap_class ) { 
				echo '<div class="' . $wrap_class . '">';
			}

			// Check if we have listings or if we are faking it
			if ( $wp_query->post_count == 1 && empty( $wp_query->posts ) ) {
				geodir_no_listings_found();
			} elseif ( geodir_is_page('search') && ! isset( $_REQUEST['geodir_search'] ) ) {
				geodir_no_listings_found();
			} else {
				// Check we are not inside a template builder container
				if ( isset( $wp_query->posts[0] ) && $wp_query->posts[0]->post_type == 'page' ) {
					// Reset the query count so the correct number of listings are output.
					rewind_posts();

					// Reset the proper loop content
					global $wp_query, $gd_temp_wp_query;

					$wp_query->posts = $gd_temp_wp_query;
				}

				if ( isset( $post ) ) {
					$reset_post = $post;
				}

				if ( isset( $gd_post ) ) {
					$reset_gd_post = $gd_post;
				}

				$geodir_is_widget_listing = true;

				$template = $design_style ? $design_style . '/content-widget-listing.php' : 'content-widget-listing.php';

				echo geodir_get_template_html( $template, array(
					'widget_listings' => $posts,
					'column_gap_class' => ! empty( $widget_args['column_gap'] ) ? 'mb-' . absint( $widget_args['column_gap'] ) : 'mb-4',
					'row_gap_class' => ! empty( $widget_args['row_gap'] ) ? 'px-' . absint( $widget_args['row_gap'] ) : '',
					'card_border_class' => $card_border_class,
					'card_shadow_class' => $card_shadow_class,
				) );

				$geodir_is_widget_listing = false;

				if ( isset( $reset_post ) ) {
					if ( ! empty( $reset_post ) ) {
						setup_postdata( $reset_post );
					}
					$post = $reset_post;
				}

				if ( isset( $reset_gd_post ) ) {
					$gd_post = $reset_gd_post;
				}
			}

			if ( $wrap_class ) { 
				echo '</div>';
			}
		} else {
			_e( "This list is empty at the moment, check back later.", "gd-lists" );
		}

		return ob_get_clean();
	}

	/**
	 * Filter to close the comments for archive pages after the GD loop.
	 * 
	 * @param $open
	 * @param $post_id
	 *
	 * @return bool
	 */
	public static function comments_open($open, $post_id){

		global $post;
		if(isset($post->ID) && $post->ID==$post_id){
			$open = false;
		}

		return $open;
	}

}