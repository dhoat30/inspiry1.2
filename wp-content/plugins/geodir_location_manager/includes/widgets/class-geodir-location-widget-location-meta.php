<?php
/**
 * Location Meta widget
 *
 * @package GeoDir_Location_Manager
 * @since 2.0.0.25
 */

/**
 * GeoDir_Location_Widget_Location_Meta class.
 */
class GeoDir_Location_Widget_Location_Meta extends WP_Super_Duper {

	/**
	 * Sets up a widget instance.
	 */
	public function __construct() {
		$options = array(
			'textdomain'    => 'geodirlocation',
			'block-icon'    => 'location-alt',
			'block-category'=> 'geodirectory',
			'block-keywords'=> "['location meta','meta','description']",
			'class_name'    => __CLASS__,
			'base_id'       => 'gd_location_meta',
			'name'          => __( 'GD > Location Meta', 'geodirevents' ),
			'widget_ops'    => array(
				'classname'   => 'geodir-location-meta-container bsui',
				'description' => esc_html__( 'Displays the meta title, meta description, location description, image for location.', 'geodirevents' ),
				'geodirectory' => true,
				'gd_wgt_showhide' => 'show_on',
				'gd_wgt_restrict' => array(),
			),
			'arguments' => array(
				'title' => array(
					'type' => 'text',
					'title' => __( 'Title:', 'geodirlocation' ),
					'desc' => __( 'Extra main title if needed.', 'geodirlocation' ),
					'placeholder' => '',
					'default' => '',
					'desc_tip' => true,
					'advanced' => false
				),
				'type' => array(
					'type' => 'select',
					'title' => __( 'Location Type:', 'geodirlocation' ),
					'desc' => __( 'Select location type.', 'geodirlocation' ),
					'placeholder' => '',
					'options' => array(
						'' => __( 'Auto', 'geodirlocation' ),
						'country' => __( 'Country', 'geodirlocation' ),
						'region' => __( 'Region', 'geodirlocation' ),
						'city' => __( 'City', 'geodirlocation' ),
						'neighbourhood' => __( 'Neighbourhood', 'geodirlocation' ),
					),
					'desc_tip' => true,
					'default' => '',
					'advanced' => false
				),
				'key' => array(
					'type' => 'select',
					'title' => __( 'Key:', 'geodirlocation' ),
					'desc' => __( 'This is the location meta field key.', 'geodirlocation' ),
					'placeholder' => '',
					'options' => array(
						'location_name' => __( 'Location Name', 'geodirlocation' ),
						'location_description' => __( 'Location Description', 'geodirlocation' ),
						'location_meta_title' => __( 'Location Meta Title', 'geodirlocation' ),
						'location_meta_description' => __( 'Location Meta Description', 'geodirlocation' ),
						'location_image' => __( 'Location Image', 'geodirlocation' ),
						'location_image_tagline' => __( 'Location Image Tagline', 'geodirlocation' ),
					),
					'desc_tip' => true,
					'default' => '',
					'advanced' => false
				),
				'fallback_image' => array(
					'type' => 'checkbox',
					'title' => __( "Show post image as a fallback?", 'geodirlocation' ),
					'desc' => __( "If location image not available then show last post image added under this location.", 'geodirlocation' ),
					'desc_tip' => true,
					'value'  => '1',
					'default'  => '0',
					'advanced' => true,
					'element_require' => '[%key%]=="location_image"',
				),
				'image_size' => array(
					'type' => 'select',
					'title' => __( 'Image size:', 'geodirectory' ),
					'desc' => __( 'The WP image size as a text string.', 'geodirectory' ),
					'options' => self::get_image_sizes(),
					'desc_tip' => true,
					'value' => '',
					'default' => '',
					'advanced' => true,
					'element_require' => '[%key%]=="location_image"',
				),
				'no_wrap' => array(
					'type' => 'checkbox',
					'title' => __( 'No Wrap:', 'geodirectory' ),
					'desc' => __( 'Remove wrapping div.', 'geodirectory' ),
					'default' => '0',
					'advanced' => true
				),
				'alignment' => array(
					'type' => 'select',
					'title' => __( 'Alignment:', 'geodirectory' ),
					'desc' => __( 'How the item should be positioned on the page.', 'geodirectory' ),
					'options' => array(
						"" => __( 'None', 'geodirectory' ),
						"block" => __( 'Block', 'geodirectory' ),
						"left" => __( 'Left', 'geodirectory' ),
						"center" => __( 'Center', 'geodirectory' ),
						"right" => __( 'Right', 'geodirectory' ),
					),
					'desc_tip' => true,
					'advanced' => true
				),
				'text_alignment' => array(
					'type' => 'select',
					'title' => __( 'Text Align:', 'geodirectory' ),
					'desc' => __( 'How the text should be aligned.', 'geodirectory' ),
					'options' => array(
						"" => __( 'None', 'geodirectory' ),
						"left" => __( 'Left', 'geodirectory' ),
						"center" => __( 'Center', 'geodirectory' ),
						"right" => __( 'Right', 'geodirectory' ),
					),
					'desc_tip' => true,
					'advanced' => true
				),
				'css_class' => array(
					'type' => 'text',
					'title' => __( 'Extra class:', 'geodirectory' ),
					'desc' => __( 'Give the wrapper an extra class so you can style things as you want.', 'geodirectory' ),
					'placeholder' => '',
					'default' => '',
					'desc_tip' => true,
					'advanced' => true,
				),
			)
		);

		parent::__construct( $options );
	}


	/**
	 * The widget output.
	 *
	 * @param array $instance
	 * @param array $args
	 * @param string $content
	 *
	 * @return mixed|string|void
	 */
	public function output( $instance = array(), $args = array(), $content = '' ) {
		global $geodirectory, $post, $gd_post;

		$instance = shortcode_atts( 
			array(
				'title' => '',
				'type' => '',
				'key' => 'location_name',
				'fallback_image' => '',
				'image_size' => '',
				'no_wrap' => '',
				'alignment' => '',
				'text_alignment' => '',
				'list_hide' => '',
				'list_hide_secondary' => '',
				'css_class' => '',
				'location' => 'none',
			), 
			$instance, 
			'gd_location_meta' 
		);
		if ( empty( $instance['image_size'] ) ) {
			$instance['image_size'] = 'thumbnail';
		}

		$output = '';
		if ( $this->is_preview() ) {
			return $output;
		}

		$location = $geodirectory->location;
		$country = isset( $location->country_slug ) ? $location->country_slug : '';
		$region = isset( $location->region_slug ) ? $location->region_slug : '';
		$city = isset( $location->city_slug ) ? $location->city_slug : '';
		$neighbourhood = isset( $location->neighbourhood_slug ) ? $location->neighbourhood_slug : '';

		if ( empty( $country ) && empty( $region ) && empty( $city ) && empty( $neighbourhood ) ) {
			return;
		}

		$key = $instance['key'];
		$type = $instance['type'];

		$_type = '';
		$name = '';
		$info = array();

		if ( ( $neighbourhood && empty( $type ) ) || $type == 'neighbourhood' ) {
			if ( ! empty( $neighbourhood ) ) {
				$_type = 'neighbourhood';
				$info = GeoDir_Location_Neighbourhood::get_info_by_slug( $neighbourhood );
				$name = $neighbourhood;

				if ( ! empty( $info ) ) {
					$name = $info->neighbourhood;
					$info->location_desc = $info->description;
					$info->meta_desc = $info->meta_description;
				}
			} else {
				return;
			}
		} elseif ( ( $city && empty( $type ) ) || $type == 'city' ) {
			if ( ! empty( $city ) ) {
				$_type = 'city';
				$info = GeoDir_Location_SEO::get_seo_by_slug( $city, 'city', $country, $region );
				$name = geodir_location_get_name( 'city', $city );
			} else {
				return;
			}
		} elseif ( ( $region && empty( $type ) ) || $type == 'region' ) {
			if ( ! empty( $region ) ) {
				$_type = 'region';
				$info = GeoDir_Location_SEO::get_seo_by_slug( $region, 'region', $country );
				$name = geodir_location_get_name( 'region', $region );
			} else {
				return;
			}
		} elseif ( ( $country && empty( $type ) ) || $type == 'country' ) {
			if ( ! empty( $country ) ) {
				$_type = 'country';
				$info = GeoDir_Location_SEO::get_seo_by_slug( $country, 'country' );
				$name = geodir_location_get_name( 'country', $country );
			} else {
				return;
			}
		}

		if ( empty( $info ) ) {
			if ( 'location_image' != $key ) {
				return;
			}
		} else {
			$info->name = $name;
		}

		// CSS class
		$design_style = geodir_design_style();

		$css_class = 'geodir-location-meta geodir-meta-' . $key;

		if ( $instance['css_class'] != '' ) {
			$css_class .= " " . esc_attr( $instance['css_class'] );
		}

		if ( $instance['text_alignment'] != '' ) {
			$css_class .=  $design_style ? " text-".sanitize_html_class( $instance['text_alignment'] ) : " geodir-text-align" . sanitize_html_class( $instance['text_alignment'] );
		}

		// set alignment class
		if ( $instance['alignment'] != '' ) {
			if($design_style){
				if($instance['alignment']=='block'){$css_class .= " d-block ";}
				elseif($instance['alignment']=='left'){$css_class .= " float-left mr-2 ";}
				elseif($instance['alignment']=='right'){$css_class .= " float-right ml-2 ";}
				elseif($instance['alignment']=='center'){$css_class .= " mw-100 d-block mx-auto ";}
			}else{
				$css_class .= $instance['alignment']=='block' ? " gd-d-block gd-clear-both " : " geodir-align" . sanitize_html_class( $instance['alignment'] );
			}
		}

		$value = '';
		if ( 'location_name' == $key ) {
			if ( ! empty( $info->name ) ) {
				$value = __( stripslashes( $info->name ), 'geodirectory' );
			}
		} elseif ( 'location_description' == $key ) {
			if ( ! empty( $info->location_desc ) ) {
				$value = __( stripslashes( $info->location_desc ), 'geodirectory' );
			}
		} elseif ( 'location_meta_title' == $key ) {
			if ( ! empty( $info->meta_title ) ) {
				$value = __( stripslashes( $info->meta_title ), 'geodirectory' );
			}
		} elseif ( 'location_meta_description' == $key ) {
			if ( ! empty( $info->meta_desc ) ) {
				$value = __( stripslashes( $info->meta_desc ), 'geodirectory' );
			}
		} elseif ( 'location_image' === $key ) {
			if ( ! empty( $info->image ) ) {
				$value = wp_get_attachment_image( $info->image, $instance['image_size'], "", array( "class" => "img-responsive" ) );
			} elseif ( ! empty( $instance['fallback_image'] ) ) {
				$params = array(
					'country' => ( ! empty( $location->country ) ? $location->country : $country ),
					'region' => ( ! empty( $location->region ) ? $location->region : $region ),
					'city' => ( ! empty( $location->city ) ? $location->city : $city ),
					'neighbourhood' => $neighbourhood,
				);
				$attachment = GeoDir_Location_SEO::get_post_attachment( $params );

				if ( ! empty( $attachment ) ) {
					$value = geodir_get_image_tag( $attachment, $instance['image_size'] );
				}
			}
		} elseif ( 'location_image_tagline' == $key ) {
			if ( ! empty( $info->image_tagline ) ) {
				$value = __( stripslashes( $info->image_tagline ), 'geodirectory' );
			}
		}

		$value = apply_filters( 'geodir_location_meta_value', $value, $_type, $info, $instance );
		if ( strpos( $value, '%%' ) !== false ) {
			$value = geodir_replace_location_variables( $value );
		}

		if ( empty( $value ) ) {
			return;
		}

		if ( empty( $instance['no_wrap'] ) ) {
			$output = '<div class="' . $css_class . '">' . $value . '</div>';
		} else {
			$output = $value;
		}

		return apply_filters( 'geodir_location_meta_output', $output, $value, $_type, $info, $instance );
	}

	public static function get_image_sizes() {
		$image_sizes = array( 
			'' => 'default (thumbnail)'
		);

		$available = get_intermediate_image_sizes();
		if ( ! empty( $available ) ) {
			foreach( $available as $size ) {
				$image_sizes[ $size ] = $size;
			}
		}

		$image_sizes['full'] = 'full';

		return $image_sizes;
	}
}
