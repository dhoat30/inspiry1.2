<?php
/**
 * Claim Listing widget.
 *
 * @since 2.0.0
 * @package Geodir_Claim_Listing
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDir_Claim_Widget_Post_Claim class.
 */
class GeoDir_Claim_Widget_Post_Claim extends WP_Super_Duper {

	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'textdomain'     => GEODIRECTORY_TEXTDOMAIN,
			'block-icon'     => 'businessman',
			'block-category' => 'common',
			'block-keywords' => "['claim','geodir','geodirectory']",
			'class_name'     => __CLASS__,
			'base_id'        => 'gd_claim_post',
			'name'           => __( 'GD > Post Claim', 'geodir-claim' ),
			'widget_ops'     => array(
				'classname'     => 'geodir-post-claim',
				'description'   => esc_html__( 'Displays the button to claim post.', 'geodir-claim' ),
				'geodirectory'  => true,
                'gd_wgt_showhide' => 'show_on',
                'gd_wgt_restrict' => array( 'gd-detail' )
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
			'title' => array(
                'title' => __( 'Title:', 'geodir-claim' ),
                'desc' => __( 'The widget title.', 'geodir-claim' ),
                'type' => 'text',
                'default' => '',
                'desc_tip' => true,
                'advanced' => false
            ),
            'text'  => array(
                'title' => __( 'Text:', 'geodir-claim' ),
                'desc' => __( 'The text shown than opens the lightbox.', 'geodir-claim' ),
                'type' => 'text',
                'default' => __( 'Claim Listing', 'geodir-claim' ),
                'desc_tip' => true,
                'advanced' => false
            ),
            'output'  => array(
                'title' => __( 'Output Type:', 'geodir-claim' ),
                'desc' => __( 'How the link to open the lightbox is displayed.', 'geodir-claim' ),
                'type' => 'select',
                'options' =>  array(
                    'button' => __('Button', 'geodir-claim' ),
                    'link' => __( 'Link', 'geodir-claim' ),
                ),
                'default' => 'button',
                'desc_tip' => true,
                'advanced' => true
            )
		);

		return $arguments;
	}


	/**
	 * Outputs the linked posts on the front-end.
	 *
	 * @param array $args
	 * @param array $widget_args
	 * @param string $content
	 *
	 * @return mixed|string|void
	 */
	public function output( $args = array(), $widget_args = array(), $content = '' ) {
		$html = $this->output_html( $widget_args, $args );

		if ( $html ) {
			$html = '<div class="geodir_post_meta gd-post-claim-wrap">' . $html . '</div>';
		}

        return $html;
	}

	/**
     * Generates claim post widget HTML.
     *
     * @global object $post                    The current post object.
     *
     * @param array|string $args               Display arguments including before_title, after_title, before_widget, and
     *                                         after_widget.
     * @param array|string $instance           The settings for the particular instance of the widget.
	 *
	 * @return bool|string
     */
    public function output_html( $args = '', $instance = '' ) {
        global $post;

		if ( empty( $post ) ) {
			return false;
		}

		if ( ! geodir_claim_show_claim_link( $post->ID ) ) {
			return false;
		}

		$defaults = array(
            'title' => '',
			'text' => __( 'Claim Listing', 'geodir-claim' ),
            'output' => 'button',
        );

        $instance = wp_parse_args( $instance, $defaults );

		$button_text = apply_filters( 'geodir_claim_widget_button_text', $instance['text'], $post->ID );

		if ( ! is_user_logged_in() ) {
			$current_url = remove_query_arg( array( 'gd_do','gd_go' ), geodir_curPageURL() );
			$current_url = geodir_login_url( add_query_arg( array( 'gd_do' => 'claim' ), $current_url ) );
			$current_url = add_query_arg( array( 'gd_go' => 'claim' ), $current_url ) ;
			$current_url = apply_filters( 'geodir_claim_login_to_claim_url', $current_url, $post->ID );
			$target = esc_attr( 'window.location="' . $current_url . '"' );
		} else {
			$target = 'gd_claim_ajax_lightbox(\'geodir_claim_post_form\',\'\',' . absint( $post->ID ) . ',\'\'); return false;';
		}

		if ( ! empty( $instance['output'] ) && $instance['output'] == 'button' ) {
			$output = '<button class="btn btn-default geodir-claim-post-form-link" onclick="' . $target . '">' . esc_attr( $button_text ) . '</button>';
		} else {
			$output = '<a class="geodir-claim-post-form-link" href="#" onclick="' . $target . '">' . esc_attr( $button_text ) . '</a>';
		}

	    // fire on load
	    if(is_user_logged_in() && isset($_REQUEST['gd_do']) && $_REQUEST['gd_do']=='claim'){
		    $output .= "<script>jQuery(function() {jQuery('.geodir-claim-post-form-link').trigger( \"click\" );});</script>";
	    }

		return $output;
	}
}

