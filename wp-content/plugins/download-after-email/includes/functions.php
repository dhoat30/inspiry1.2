<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mckp_create_nonce( $file, $email ) {
	
	$data = $file . '|' . time() . '|' . wp_get_session_token();
	
	$option_name = 'mckp_download_nonce-' . substr( wp_hash( $file . '-' . $email, 'nonce' ), -12, 10 );
	
	$nonce = wp_hash( $data, 'nonce' );
	
	update_option( $option_name, $nonce, false );
	
	return $nonce;
	
}

function mckp_verify_nonce( $file, $email ) {
	
	$option_name = 'mckp_download_nonce-' . substr( wp_hash( $file . '-' . $email, 'nonce' ), -12, 10 );
	$option_value = get_option( $option_name );

	$option_name_old = 'mckp_download_nonce-' . $file . '-' . $email;
	$option_value_old = get_option( $option_name_old );
	
	if ( ! empty( $option_value ) || ! empty( $option_value_old ) ) {
		
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$nonce = sanitize_text_field( $_POST['nonce'] );
		} elseif ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			$nonce = sanitize_text_field( $_GET['nonce'] );
		}

		if ( ! empty( $option_value ) && hash_equals( $option_value, $nonce ) ) {
			return true;
		}

		if ( ! empty( $option_value_old ) && hash_equals( $option_value_old, $nonce ) ) {
			return true;
		}

		return false;
		
	} else {
		
		return false;
		
	}
	
}

function mckp_delete_nonce( $file, $email ) {
	
	$option_name_old = 'mckp_download_nonce-' . $file . '-' . $email;
	delete_option( $option_name_old );

	$option_name = 'mckp_download_nonce-' . substr( wp_hash( $file . '-' . $email, 'nonce' ), -12, 10 );
	delete_option( $option_name );
	
}

function mckp_get_client_ip() {
	
	$ipaddress = '';
	
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	}
	elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	}
	elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	}
	elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	}
	else{
		$ipaddress = 'UNKNOWN';
	}
 
	return $ipaddress;
	
}

function mckp_content_media( $media_id, $media_input_name, $image ) {
	
	if ( $image ) {
		
		if ( ! empty( $media_id ) ) {
			
			$media_url = wp_get_attachment_thumb_url( $media_id );
			$url_parts = explode( '/', $media_url );
			$media_name = end( $url_parts );
			$media_class = 'mk-media';
			$media_class_remove = 'mk-media-remove dashicons dashicons-no';
			
		} else {
			
			$media_url = '';
			$media_name = __( 'No image selected', 'download-after-email' );
			$media_class = 'mk-media dashicons dashicons-format-image';
			$media_class_remove = 'mk-media-remove';
			
		}
		
		?>
		<input type="hidden" name="<?php echo esc_attr( $media_input_name ); ?>" value="<?php echo esc_attr( $media_id ); ?>" />
		<a class="<?php echo esc_attr( $media_class ); ?>" title="<?php echo esc_attr( $media_name ); ?>"><img src="<?php echo esc_url( $media_url ); ?>" /></a>
		<span class="<?php echo esc_attr( $media_class_remove ); ?>"></span>
		<?php
		
	} else {
		
		if ( ! empty( $media_id ) ) {
			
			$file_name = basename( get_attached_file( $media_id, true ) );
			$media_class_remove = 'mk-media-remove dashicons dashicons-no';
			
		} else {
			
			$file_name = __( 'No file selected', 'download-after-email' );
			$media_class_remove = 'mk-media-remove';
			
		}
		
		?>
		<input type="hidden" name="<?php echo esc_attr( $media_input_name ); ?>" value="<?php echo esc_attr( $media_id ); ?>" />
		<span class="<?php echo esc_attr( $media_class_remove ); ?>"></span>
		<span class="mk-media-filename"><?php echo esc_html( $file_name ); ?></span>
		<button class="mk-media button" type="button"><?php esc_html_e( 'Select File', 'download-after-email' ); ?></button>
		<?php
		
	}
	
}

function mckp_sanitize_form_content( $form_content ) {

	$allowed_tags = wp_kses_allowed_html( 'post' );

	$allowed_tags['form'] = array(
		'class'			=> true,
		'id'			=> true,
		'method'		=> true,
		'action'		=> true,
		'novalidate'	=> true,
		'autocomplete'	=> true
	);
	$allowed_tags['input'] = array(
		'type'			=> true,
		'class'			=> true,
		'id'			=> true,
		'name'			=> true,
		'value'			=> true,
		'placeholder'	=> true
	);
	$allowed_tags['select'] = array(
		'class'			=> true,
		'id'			=> true,
		'name'			=> true
	);
	$allowed_tags['option'] = array(
		'class'			=> true,
		'id'			=> true,
		'value'			=> true
	);
	
	return wp_kses( $form_content, $allowed_tags );

}

function mckp_get_links_count( $file_name ) {

	global $wpdb;
	$table_links = $wpdb->prefix . 'dae_links';

	$used_links = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM $table_links WHERE file = %s AND link_used = %s", array( $file_name, 'used' ) ) );
	$unused_links = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM $table_links WHERE file = %s AND link_used = %s", array( $file_name, 'not used' ) ) );

	return array(
		'used'		=> count( $used_links ),
		'unused'	=> count( $unused_links ),
		'total'		=> count( $used_links ) + count( $unused_links )
	);

}

?>