<?php
/**
 * CPT Listings widget.
 *
 * @since 2.0.0
 * @package Geodir_Custom_Posts
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDir_CP_Widget_CPT_Listings class.
 */
class GeoDir_CP_Widget_CPT_Listings extends WP_Super_Duper {

	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'textdomain'     => GEODIRECTORY_TEXTDOMAIN,
			'block-icon'     => 'screenoptions',
			'block-category' => 'common',
			'block-keywords' => "['cpt','geodir','geodirectory']",
			'class_name'     => __CLASS__,
			'base_id'        => 'gd_cpt_listings',
			'name'           => __( 'GD > CPT Listings', 'geodir_custom_posts' ),
			'widget_ops'     => array(
				'classname'     => 'geodir-cpt-listings',
				'description'   => esc_html__( 'Displays GeoDirectory post types.', 'geodir_custom_posts' ),
				'geodirectory'  => true,
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
                'title' => __( 'Title:', 'geodir_custom_posts' ),
                'desc' => __( 'The widget title.', 'geodir_custom_posts' ),
                'type' => 'text',
                'default' => '',
                'desc_tip' => true,
                'advanced' => false
            ),
            'cpt_exclude'  => array(
                'title' => __( 'Exclude CPT:', 'geodir_custom_posts' ),
                'desc' => __( 'Tick CPTs to hide from list.', 'geodir_custom_posts' ),
                'type' => 'select',
                'multiple' => true,
                'options' => geodir_get_posttypes( 'options-plural' ),
                'default' => '',
                'desc_tip' => true,
                'advanced' => true
            ),
			'cpt_display' => array(
				'title' => __( 'Display:', 'geodir_custom_posts' ),
				'desc' => __( 'Select display type.', 'geodir_custom_posts' ),
				'type' => 'select',
				'options' =>  array(
					'' => __( 'Default (image & name)', 'geodir_custom_posts' ),
					'image' => __( 'Image only', 'geodir_custom_posts' ),
					'name' => __( 'Name only', 'geodir_custom_posts' )
				),
				'default'  => '',
				'desc_tip' => true,
				'advanced' => false
			),
			'cpt_img_width' => array(
                'title' => __( 'Image Width:', 'geodir_custom_posts' ),
                'desc' => __( 'CPT image width. Ex: 90px, 25%, auto', 'geodir_custom_posts' ),
                'type' => 'text',
                'default' => '',
                'desc_tip' => true,
                'advanced' => true
            ),
			'cpt_img_height' => array(
                 'title' => __( 'Image Height:', 'geodir_custom_posts' ),
                'desc' => __( 'CPT image height. Ex: 90px, 25%, auto', 'geodir_custom_posts' ),
                'type' => 'text',
                'default' => '',
                'desc_tip' => true,
                'advanced' => true
            )
		);

		return $arguments;
	}


	/**
	 * Outputs the cpt listings on the front-end.
	 *
	 * @param array $args
	 * @param array $widget_args
	 * @param string $content
	 *
	 * @return mixed|string|void
	 */
	public function output( $args = array(), $widget_args = array(), $content = '' ) {
		$html = $this->output_html( $widget_args, $args );

        return $html;
	}

	/**
     * Generates widget HTML.
     *
     * @global object $post                    The current post object.
     *
     * @param array|string $args               Display arguments including before_title, after_title, before_widget, and
     *                                         after_widget.
     * @param array|string $instance           The settings for the particular instance of the widget.
	 *
	 * @return bool|string
     */
    public function output_html( $args = array(), $instance = array() ) {
		$post_types = geodir_get_posttypes( 'array' );

		$defaults = array(
            'title' => '',
			'cpt_exclude' => '',
			'cpt_display' => '',
			'cpt_img_width' => '90px',
			'cpt_img_height' => '90px',
        );

		$instance = wp_parse_args( $instance, $defaults );

		if ( is_array( $instance['cpt_exclude'] ) ) {
			$exclude_cpts = $instance['cpt_exclude'];
		} else {
			$exclude_cpts = explode( ',', trim( $instance['cpt_exclude'] ) );
			if ( ! empty( $exclude_cpts ) ) {
				$exclude_cpts = array_map( 'trim', $exclude_cpts );
			}
		}

		$exclude_cpts = apply_filters( 'geodir_cp_widget_cpt_listings_cpt_exclude', $exclude_cpts, $instance, $args, $this->id_base );

		// Exclude CPT to hide from display.
		if ( ! empty( $exclude_cpts ) ) {
			foreach ( $exclude_cpts as $cpt ) {
				if ( isset( $post_types[ $cpt ] ) ) {
					unset( $post_types[ $cpt ] );
				}
			}
		}

		if ( empty( $post_types ) ) {
			return;
		}

		$cpt_display = apply_filters( 'geodir_cp_widget_cpt_listings_cpt_display', $instance['cpt_display'], $instance, $args, $this->id_base );
		$width = apply_filters( 'geodir_cp_widget_cpt_listings_cpt_img_width', $instance['cpt_img_width'], $instance, $args, $this->id_base );
		$height = apply_filters( 'geodir_cp_widget_cpt_listings_cpt_img_height', $instance['cpt_img_height'], $instance, $args, $this->id_base );

		if ( $width !== '' && strpos( $width, '%' ) !== false ) {
			$width = 'calc(' . $width . ' - 2px)';
		} else if ( strpos( $width, 'px' ) !== false ) {
			$width = 'calc(' . $width . ' + 24px)';
		}
		if ( $height !== '' && strpos( $height, '%' ) !== false ) {
			$height = 'calc(' . $height . ' - 2px)';
		}

		$style_width = $width !== '' ? 'width:' . $width . ';' : '';
		$style_height = $height !== '' ? 'height:' . $height . ';' : '';

		ob_start();
		?>
		<ul class="gd-wgt-cpt-list gd-wgt-cpt-list-<?php echo $cpt_display; ?>">
		<?php
		foreach ( $post_types as $post_type => $post_type_arr ) {
			$name = __( $post_type_arr['labels']['name'], 'geodirectory' );
			$url = get_post_type_archive_link( $post_type );

			$display_image = '';
			$display_name = '';
			if ( $cpt_display != 'name' ) {
				if ( ! empty( $post_type_arr['default_image'] ) ) {
					$image_src = wp_get_attachment_image_src( (int) $post_type_arr['default_image'], 'thumbnail' );
					if ( $image_src ) {
						list( $src, $image_width, $image_height ) = $image_src;

						$display_image = '<img src="' . $src . '" class="attachment-thumbnail size-thumbnail" alt="' . esc_attr ( $name ) . '" style="' . esc_attr( $style_height ) . '">';
					}
				}
				if ( empty( $display_image ) && ( $attachment_id = absint( geodir_get_option( 'listing_default_image' ) ) ) ) {
					$image_src = wp_get_attachment_image_src( (int) $attachment_id, 'thumbnail' );
					if ( $image_src ) {
						list( $src, $image_width, $image_height ) = $image_src;

						$display_image = '<img src="' . $src . '" class="attachment-thumbnail size-thumbnail" alt="' . esc_attr ( $name ) . '" style="' . esc_attr( $style_height ) . '">';
					}
				}
				$display_image = apply_filters( 'geodir_cp_widget_cpt_listings_image', $display_image, $post_type );
			}

			$has_image = ! empty( $display_image ) ? '1' : '0';
			if ( $cpt_display == 'image' && empty( $display_image ) ) {	
				$display_image = $name;
			}

			if ( $cpt_display != 'image' ) {	
				$display_name = $name;
			}
			?>
			<li class="gd-cpt-list-row gd-cpt-list-has-img-<?php echo $has_image; ?> gd-cpt-list-<?php echo $post_type; ?>" style="<?php echo esc_attr( $style_width ); ?>">
				<a class="gd-cpt-list-link" href="<?php echo esc_url( $url ); ?>" title="<?php echo esc_attr ( $name ); ?>">
					<?php if ( $display_image ) { ?>
					<span class="gd-cpt-list-img" style="<?php echo esc_attr( $style_height ); ?>"><?php echo $display_image; ?></span>
					<?php } ?>
					<?php if ( $display_name ) { ?>
					<span class="gd-cpt-list-name"><?php echo $display_name; ?></span>
					<?php } ?>
				</a>
			</li>
			<?php
		}
		?></ul>
		<?php
		$html = ob_get_clean();

		return $html;
	}
}

