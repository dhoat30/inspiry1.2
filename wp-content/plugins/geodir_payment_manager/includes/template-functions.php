<?php
/**
 * Pricing Manager Template Functions.
 *
 * @since 2.5.0
 * @package GeoDir_Pricing_Manager
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Widgets.
 *
 * @since 2.5.0
 */
function geodir_pricing_register_widgets() {

	if ( get_option( 'geodir_pricing_version' ) ) {
		// Non Widgets
		new GeoDir_Pricing_Widget_Single_Expired_Text();
	}
}

function geodir_pricing_params() {
	$params = array();

    return apply_filters( 'geodir_pricing_params', $params );
}

/**
 * Filters the list of body classes for the current post.
 *
 * @since 2.0.0
 *
 * @global object $post The current post object.
 * @global object $wp_query WP_Query object.
 * @global object $gd_post The current GeoDirectory post object.
 * 
 * @param array $classes Class array.
 * @return array Modified class array.
 */
function geodir_pricing_body_class( $classes ) {
    global $post, $wp_query, $gd_post;

    if ( !empty( $post->ID ) && !empty( $wp_query->is_expired ) && $post->ID == $wp_query->is_expired && is_single() ) {
        $classes[] = 'gd-expired';
    }

	// Add post package id to body class.
	if ( ! empty( $gd_post ) && isset( $gd_post->package_id ) && ( geodir_is_page( 'detail' ) || geodir_is_page( 'preview' ) ) ) {
		$classes[] = 'gd-pkg-id-' . $gd_post->package_id;
	}

    return $classes;
}

/**
 * Filters the list of CSS classes for the current post.
 *
 * @since 2.5.0
 *
 * @param array $classes An array of post classes.
 * @param array $class   An array of additional classes added to the post.
 * @param int   $post_id The post ID.
 */
function geodir_pricing_post_class( $classes, $class, $post_ID ) {
	if ( ! empty( $post_ID ) && geodir_is_gd_post_type( get_post_type( $post_ID ) ) ) {
		$package_id = (int) geodir_get_post_meta( $post_ID, 'package_id', true );
		$classes[] = 'gd-post-pkg-' . $package_id;
	}
	return $classes;
}

/**
 * get_templates_dir function.
 *
 * The function is return templates dir path.
 *
 * @since 2.0.0
 *
 * @return string Templates dir path.
 */
function geodir_pricing_get_templates_dir() {
    return GEODIR_PRICING_PLUGIN_DIR . 'templates';
}

/**
 * get_templates_url function.
 *
 * The function is return templates dir url.
 *
 * @since 2.0.0
 *
 * @return string Templates dir url.
 */
function geodir_pricing_get_templates_url() {
    return GEODIR_PRICING_PLUGIN_URL . '/templates';
}

/**
 * get_theme_template_dir_name function.
 *
 * The function is return theme template dir name.
 *
 * @since 2.0.0
 *
 * @return string Theme template dir name.
 */
function geodir_pricing_theme_templates_dir() {
    return untrailingslashit( apply_filters( 'geodir_pricing_templates_dir', 'geodir_payment_manager' ) );
}

function geodir_pricing_locate_template( $template, $template_name, $template_path = '' ) {
	if ( file_exists( $template ) ) {
		return $template;
	}

	$template_path = geodir_pricing_theme_templates_dir();
	$default_path = geodir_pricing_get_templates_dir();
	$default_template = untrailingslashit( $default_path ) . '/' . $template_name;

	if ( ! file_exists( $default_template ) ) {
		return $template;
	}

    // Look within passed path within the theme - this is priority.
    $template = locate_template(
        array(
            untrailingslashit( $template_path ) . '/' . $template_name,
            $template_name,
        )
    );

    // Get default template
    if ( ! $template ) {
        $template = $default_template;
    }

	return $template;
}

/**
 * post_expired_text function.
 *
 * The function is use for display post expired text content.
 *
 * Check if $echo is true then echo post expired text html content
 * else return post expired text html content.
 *
 * @since 2.5.0
 *
 * @param object $post Post object.
 * @param bool $echo Optional. Default true.
 * @return string Post expired text.
 */
function geodir_pricing_post_expired_text( $post, $echo = true ) {
	if ( ! empty( $post ) && ! empty( $post->post_type ) ) {
		$cpt_name = geodir_strtolower( geodir_post_type_singular_name( $post->post_type ) );
	} else {
		$cpt_name = __( 'business', 'geodir_pricing' );
	}

    ob_start();

    geodir_get_template( 'view/post-expired-text.php', array(
		'cpt_name' => $cpt_name
	) );

    if ( $echo ) {
        echo ob_get_clean();
    } else {
        return ob_get_clean();
    }
}

/**
 * Filter the listing content.
 *
 * @since 1.0.0
 *
 * @global object $post The current post object.
 *
 * @param string $post_desc Post content text.
 * @retrun Post content.
 */
function geodir_pricing_the_content( $post_desc ) {
	global $post;

	if ( $post_desc === '' ) {
		return $post_desc;
	}

	if ( ! ( ! is_admin() && is_object( $post ) && ! empty( $post ) ) ) {
		return $post_desc;
	}

	$post_type = '';
	if ( ! empty( $post->ID ) ) {
		$post_type = get_post_type( $post->ID );
	} else if ( ! empty( $post->pid ) ) {
		$post_type = get_post_type( $post->pid );
	} else if ( ! empty( $post->post_type ) ) {
		$post_type = $post->post_type;
	} else if ( ! empty( $post->listing_type ) ) {
		$post_type = $post->listing_type;
	} else if ( ! empty( $_REQUEST['listing_type'] ) ) {
		$post_type = sanitize_text_field( $_REQUEST['listing_type'] );
	}

	if ( ! geodir_is_gd_post_type( $post_type ) ) {
		return $post_desc;
	}

	if ( isset( $post->ID ) && ! empty( $post->video ) ) {
		if ( strpos( $post_desc, $post->video ) !== false ) {
			return $post_desc;
		}
	}

	if ( ( isset( $post->ID ) || ( ! isset( $post->ID ) && isset( $post->preview ) ) ) && ( $package = geodir_get_post_package( $post ) ) ) {
		$desc_limit = geodir_pricing_package_desc_limit( $package );

		if ( $desc_limit !== NULL ) {
			$post_desc = geodir_excerpt( $post_desc, absint( $desc_limit ) );
		}
	}
	return $post_desc;
}

function geodir_pricing_detail_author_actions() {
	global $gd_post;

	if ( ! empty( $gd_post->ID ) ) {
		// Renew link
		echo geodir_pricing_post_renew_link( $gd_post->ID );
		// Upgrade link
		echo geodir_pricing_post_upgrade_link( $gd_post->ID );
	}
}

function geodir_pricing_post_renew_link( $post_id ) {
	if ( empty( $post_id ) ) {
		return NULL;
	}

	if ( ! geodir_is_gd_post_type( get_post_type( $post_id ) ) ) {
		return NULL;
	}

	$renew_link = '';
	$post_status = get_post_status( $post_id );

	if ( in_array( $post_status, array( 'draft', 'gd-expired' ) ) || ( geodir_pricing_post_has_renew_period( $post_id ) && ! in_array( $post_status, array( 'trash', 'gd-closed', 'pending' ) ) ) ) {
		$renew_url = geodir_pricing_post_renew_url( $post_id );

		if ( $renew_url ) {
			$renew_link .= '<span class="gd_user_action renew_link">';
				$renew_link .= '<i class="fas fa-sync" aria-hidden="true"></i> ';
				$renew_link .= '<a href="' . esc_url( $renew_url ) . '" title="' . esc_attr__( 'Renew Listing', 'geodir_pricing' ) . '">' . __( 'Renew', 'geodir_pricing' ) . '</a>';
			$renew_link .= '</span>';
		}
	}

	return apply_filters( 'geodir_pricing_post_renew_link', $renew_link, $post_id );
}

function geodir_pricing_post_upgrade_link( $post_id ) {
	if ( empty( $post_id ) ) {
		return NULL;
	}

	if ( ! geodir_is_gd_post_type( get_post_type( $post_id ) ) ) {
		return NULL;
	}

	$upgrade_link = '';
	$post_status = get_post_status( $post_id );

	if ( ! in_array( $post_status, array( 'trash', 'gd-closed', 'pending' ) ) && geodir_pricing_has_upgrades( (int) geodir_get_post_meta( $post_id, 'package_id', true ) ) ) {
		$upgrade_url = geodir_pricing_post_upgrade_url( $post_id );

		if ( $upgrade_url ) {
			$upgrade_link = '<span class="gd_user_action upgrade_link">';
				$upgrade_link .= '<i class="fas fa-sync" aria-hidden="true"></i> ';
				$upgrade_link .= '<a href="' . esc_url( $upgrade_url ) . '" title="' . esc_attr__( 'Upgrade Listing', 'geodir_pricing' ) . '">' . __( 'Upgrade', 'geodir_pricing' ) . '</a>';
			$upgrade_link .= '</span>';
		}
	}

	return apply_filters( 'geodir_pricing_post_upgrade_link', $upgrade_link, $post_id );
}

function geodir_pricing_cfi_textarea_attributes( $attributes, $cf ) {
	global $gd_post;

	if ( $cf['name'] == 'post_content' ) {
		$package = geodir_get_post_package( $gd_post, $cf['post_type'] );
		$desc_limit = geodir_pricing_package_desc_limit( $package );

		if ( $desc_limit !== NULL && $desc_limit !== '' ) {
			$attributes[] = 'maxlength="' . $desc_limit . '"';
		}
	}

	return $attributes;
}

function geodir_pricing_tiny_mce_before_init( $mceInit, $editor_id ) {
	global $gd_post, $post;

	$the_post = $gd_post;
	$description_field = 'post_content';
	$textarea_parent = '.geodir_form_row';

	if ( is_admin() && ! wp_doing_ajax() ) {
		$description_field = 'content';
		$textarea_parent = '#wp-content-wrap';

		if ( empty( $the_post ) ) {
			$the_post = $post;
		}
	}

	if ( $editor_id == $description_field && ! empty( $the_post->post_type ) && geodir_is_gd_post_type( $the_post->post_type ) ) {
		$package = geodir_get_post_package( $the_post, $the_post->post_type );
		$desc_limit = geodir_pricing_package_desc_limit( $package );

		if ( $desc_limit !== NULL && $desc_limit !== '' ) {
			$desc_msg = addslashes( wp_sprintf( __( 'For description you can use up to %d characters only for this package.', 'geodir_pricing' ), $desc_limit ) );

			$mceInit['setup'] = 'function(ed){ed.on("keydown",function(e){ob=this;if(ob.id=="' . $editor_id . '"){var content=ed.getContent().replace(/(<[a-zA-Z\/][^<>]*>|\[([^\]]+)\])|(\s+)/ig,"");if(parseInt('.(int)$desc_limit.')-parseInt(content.length)<1&&!(e.keyCode===8||e.keyCode===46))tinymce.dom.Event.cancel(e)}});ed.on("keyup",function(e){ob=this;if(ob.id=="' . $editor_id . '"){var content=ed.getContent();var text=content.replace(/(<[a-zA-Z\/][^<>]*>|\[([^\]]+)\])|(\s+)/ig,"");if(parseInt('.(int)$desc_limit.')<parseInt(text.length)&&!(e.keyCode===8||e.keyCode===46))alert("'.$desc_msg.'")}});jQuery("' . $textarea_parent . ' #' . $editor_id . '").on("keydown",function(e){ob=this;var content=jQuery(ob).val();content=content.replace(/(<[a-zA-Z\/][^<>]*>|\[([^\]]+)\])|(\s+)/ig,"");if(parseInt('.(int)$desc_limit.')-parseInt(content.length)<1&&!(e.keyCode===8||e.keyCode===46)){return false;}});jQuery("' . $textarea_parent . ' #' . $editor_id . '").on("keyup",function(e){ob=this;var content=jQuery(ob).val();content=content.replace(/(<[a-zA-Z\/][^<>]*>|\[([^\]]+)\])|(\s+)/ig,"");if(parseInt('.(int)$desc_limit.')<parseInt(content.length)&&!(e.keyCode===8||e.keyCode===46))alert("'.$desc_msg.'");});}';
		}
	}

	return $mceInit;
}