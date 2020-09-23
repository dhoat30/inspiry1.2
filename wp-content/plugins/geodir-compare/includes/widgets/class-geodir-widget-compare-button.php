<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class GeoDir_Widget_List_Compare
 *
 * @since 1.0.0
 */
class GeoDir_Widget_Compare_Button extends WP_Super_Duper {

    public $arguments;

    /**
     * Main class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

        $options = array(
            'textdomain'            => 'geodir-compare',
            'block-icon'            => 'admin-site',
            'block-category'        => 'common',
            'block-keywords'        => "['compare','listings','geodirectory']",
            'class_name'            => __CLASS__,
            'base_id'               => 'gd_compare_button',
            'name'                  => __('GD > Compare Button','geodir-compare'),
            'widget_ops'            => array(
                'classname'         => 'geodir-listing-compare-container',
                'description'       => esc_html__('Allows the user to compare two or more listings.','geodir-compare'),
                'geodirectory'      => true,
                'gd_wgt_showhide'   => 'show_on',
                'gd_wgt_restrict'   => array( 'gd-detail' ),
            ),
        );

        parent::__construct( $options );
    }

    /**
     * Set widget arguments.
     *
     * @since 1.0.0
     * @return array
     */
    public function set_arguments() {
        $arguments                  = array(
            'badge'                 => array(
                'type'              => 'text',
                'title'             => __('Button Text', 'geodir-compare'),
                'desc'              => __('The text used by the compare listing button.', 'geodir-compare'),
                'placeholder'       => __('Compare', 'geodir-compare'),
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'icon_class'            => array(
                'type'              => 'text',
                'title'             => __('Button Icon', 'geodir-compare'),
                'desc'              => __('Enter a FontAwesome icon class here and it will be displayed in the button.', 'geodir-compare'),
                'placeholder'       => 'far fa-square',
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'badge_after'           => array(
                'type'              => 'text',
                'title'             => __('Added To Compare Button Text', 'geodir-compare'),
                'desc'              => __('The text used by the compare listing button when the listing has already been added to the comparison list.', 'geodir-compare'),
                'placeholder'       => __('Added To Compare', 'geodir-compare'),
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'icon_class_after'      => array(
                'type'              => 'text',
                'title'             => __('Added To Compare Button Icon', 'geodir-compare'),
                'desc'              => __('Enter a FontAwesome icon class here and it will be displayed in the button after a user has added the listing to a comparison list.', 'geodir-compare'),
                'placeholder'       => 'far fa-check-square',
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'bg_color'              => array(
                'type'              => 'color',
                'title'             => __('Button Background Color:','geodir-compare'),
                'desc'              => __('What color should be used as the button background?.','geodir-compare'),
                'placeholder'       => '',
                'default'           => '#0073aa',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'txt_color'             => array(
                'type'              => 'color',
                'title'             => __('Button Text Color:','geodir-compare'),
                'desc'              => __('Color for the button text.','geodir-compare'),
                'placeholder'       => '',
                'desc_tip'          => true,
                'default'           => '#ffffff',
                'advanced'          => true
            ),
            'size'                  => array(
                'type'              => 'select',
                'title'             => __('Button size:','geodir-compare'),
                'desc'              => __('Size of the button.','geodir-compare'),
                'options'           =>  array(
                    "small"         => __('Small','geodir-compare'),
                    ""              => __('Normal','geodir-compare'),
                    "medium"        => __('Medium','geodir-compare'),
                    "large"         => __('Large','geodir-compare'),
                    "extra-large"   => __('Extra Large','geodir-compare'),
                ),
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'alignment'             => array(
                'type'              => 'select',
                'title'             => __('Alignment:','geodir-compare'),
                'desc'              => __('How the item should be positioned on the page.','geodir-compare'),
                'options'           =>  array(
                    ""              => __('None','geodir-compare'),
                    "left"          => __('Left','geodir-compare'),
                    "center"        => __('Center','geodir-compare'),
                    "right"         => __('Right','geodir-compare'),
                ),
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
        );

        return $arguments;
    }

    /**
     * Display Widget output.
     *
     * @since 1.0.0
     *
     * @param array $args Get Arguments.
     * @param array $widget_args Get widget arguments.
     * @param string $content Get widget content.
     * @return string
     *
     */
    public function output( $args = array(), $widget_args = array(),$content = '' ){
        global $gd_post;

        //Set current listing id
        $post_id = ! empty( $gd_post->ID ) ? absint( $gd_post->ID ) : '';

        //Button text
        $button = '';

        $defaults = array(
            'badge'    => __('Compare', 'geodir-compare'),
            'icon_class'    => 'far fa-square',
            'badge_after'    => __('Compare', 'geodir-compare'),
            'icon_class_after'    => 'far fa-check-square',
            'bg_color'    => '#0073aa',
            'txt_color'    => '#ffffff',
        );
        $args = shortcode_atts($defaults, $args, 'gd_compare_button' );

        // set some defaults
        if(empty($args['badge'])){$args['badge'] = $defaults['badge'];}
        if(empty($args['icon_class'])){$args['icon_class'] = $defaults['icon_class'];}
        if(empty($args['badge_after'])){$args['badge_after'] = $defaults['badge_after'];}
        if(empty($args['icon_class_after'])){$args['icon_class_after'] = $defaults['icon_class_after'];}
        if(empty($args['bg_color'])){$args['bg_color'] = $defaults['bg_color'];}
        if(empty($args['txt_color'])){$args['txt_color'] = $defaults['txt_color'];}

        //If this is a listings page, display the button
        if( $post_id ){

            //Add custom css class
            $args['css_class']        = 'geodir-compare-button';

            //Ensure label is provided
            if( empty( $args['badge'] ) ) {
                $args['badge'] = __('Compare', 'geodir-compare');
            }

            //Onclick handler
            $post_type       = $gd_post->post_type;
            $args['onclick'] = "geodir_compare_add('$post_id', '$post_type');return false;";

            // make it act like a link
            $args['link'] = '#';

            //Extra attributes
            $compare_text  = !empty($args['badge'])                ? __( $args['badge'],'geodir-compare')          : $defaults['badge'];
            $compare_icon  = !empty($args['icon_class'])           ? esc_attr( $args['icon_class'])                : $defaults['icon_class'];
            $compared_text = !empty($args['badge_after'])          ? __( $args['badge_after'],'geodir-compare')    : $defaults['badge_after'];
            $compared_icon = !empty($args['icon_class_after'])     ? esc_attr( $args['icon_class_after'])          : $defaults['icon_class_after'];

            $args['extra_attributes']  = ' data-geodir-compare-text      ="'.$compare_text.'"';
            $args['extra_attributes'] .= ' data-geodir-compared-text     ="'.$compared_text.'"';
            $args['extra_attributes'] .= ' data-geodir-compare-icon      ="'.$compare_icon.'"';
            $args['extra_attributes'] .= ' data-geodir-compared-icon     ="'.$compared_icon.'"';
            $args['extra_attributes'] .= ' data-geodir-compare-post_type ="'.$post_type.'"';
            $args['extra_attributes'] .= ' data-geodir-compare-post_id   ="'.$post_id.'"';

            $button =  geodir_get_post_badge( $post_id, $args );
        }


        return $button;

    }
}