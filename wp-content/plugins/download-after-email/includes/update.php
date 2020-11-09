<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', 'dae_update_actions' );
function dae_update_actions() {
	
	$update_version = get_option( 'dae_update_version' );
	
	/** Update action to version 1.9 */
	
	if ( version_compare( DAE_VERSION, '1.9', '>=' ) && ( empty( $update_version ) || version_compare( $update_version, '1.9', '<' ) ) ) {
		
		$upload_dir = wp_upload_dir();
		global $wpdb;
		
		if ( ! empty( $upload_dir['basedir'] ) && ! empty( $upload_dir['path'] ) ) {
			
			$dirname = $upload_dir['basedir'] . '/dae-uploads';
			
			$old_upload_dir = $upload_dir['path'] . '/dae-uploads';
			$exp_upload_dir = $dirname;
			
			if ( ! file_exists( $exp_upload_dir ) && file_exists( $old_upload_dir ) ) {
				
				if ( rename( $old_upload_dir, $exp_upload_dir ) ) {
					
					$attached_files = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT * FROM wp_postmeta WHERE meta_key = %s AND meta_value LIKE %s",
							array( '_wp_attached_file', '%dae-uploads%' )
						)
					);
					
					foreach ( $attached_files as $attached_file ) {
						
						$expected_path = strchr( $attached_file->meta_value, 'dae-uploads' );
						
						if ( $expected_path != $attached_file->meta_value ) {
							
							update_attached_file( $attached_file->post_id, $expected_path );
							
						}
						
					}
					
				}
				
			}
			
			if ( ! file_exists( $dirname ) ) {
				
				if ( wp_mkdir_p( $dirname ) ) {
					
					$file_path = $dirname . '/.htaccess';
					
					$marker = 'DAE deny access download files';
					
					$insertion = '
						<IfModule mod_authz_core.c>
						Require all denied
						</IfModule>
						<IfModule ! mod_authz_core.c>
						Order Allow,Deny
						Deny from all
						</IfModule>
					';
					
					insert_with_markers( $file_path, $marker, $insertion );
					
				}
				
			}
			
		}
		
		$new_update_version = '1.9';
		
	}

	/** Update action to version 2.0 */

	if ( version_compare( DAE_VERSION, '2.0', '>=' ) && ( empty( $update_version ) || version_compare( $update_version, '2.0', '<' ) ) ) {

		$download_ids = get_posts( array(
			'post_type'		=> 'dae_download',
			'post_status'   => array( 'publish', 'draft' ),
			'fields'		=> 'ids'
		) );

		if ( ! empty( $download_ids ) && is_array( $download_ids ) ) {

			foreach ( $download_ids as $download_id ) {

				$dae_settings = get_post_meta( $download_id, 'dae_settings', true );

				if ( ! empty( $dae_settings['checkbox_text'] ) && ! empty( $dae_settings['submit_message_color'] ) ) {

					$checkbox_text = isset( $checkbox_text ) ? $checkbox_text : $dae_settings['checkbox_text'];

					$dae_settings['submit_success_message_color'] = $dae_settings['submit_message_color'];
					$dae_settings['submit_error_message_color'] = $dae_settings['submit_message_color'];

					update_post_meta( $download_id, 'dae_settings', $dae_settings );

				}

			}

			if ( isset( $checkbox_text ) ) {

				$dae_messages = get_option( 'dae_messages' );
				$dae_messages['required_checkbox'] = 'enabled';
				$dae_messages['required_checkbox_text'] = $checkbox_text;

				update_option( 'dae_messages', $dae_messages, false );

			}

		}

		$new_update_version = '2.0';

	}

	/** Update action to version 2.0.5 */

	if ( version_compare( DAE_VERSION, '2.0.5', '>=' ) && ( empty( $update_version ) || version_compare( $update_version, '2.0.5', '<' ) ) ) {

		$download_ids = get_posts( array(
			'post_type'		=> 'dae_download',
			'post_status'   => array( 'publish', 'draft' ),
			'fields'		=> 'ids'
		) );

		if ( ! empty( $download_ids ) && is_array( $download_ids ) ) {

			foreach ( $download_ids as $download_id ) {

				$dae_settings = get_post_meta( $download_id, 'dae_settings', true );

				if ( ! empty( $dae_settings ) ) {

					$dae_settings_old = $dae_settings;

					if ( empty( $dae_settings['file_image_width_wide'] ) && empty( $dae_settings['file_image_width_small'] ) ) {
						$dae_settings['file_image_width_wide'] = '';
						$dae_settings['file_image_width_small'] = '';
					}

					if ( empty( $dae_settings['alignment_wide'] ) && empty( $dae_settings['alignment_small'] ) ) {
						$dae_settings['alignment_wide'] = 'center';
						$dae_settings['alignment_small'] = 'center';
					}
					
					if ( $dae_settings != $dae_settings_old ) {
						update_post_meta( $download_id, 'dae_settings', $dae_settings );
					}

				}

			}

		}

		$new_update_version = '2.0.5';

	}

	/** Save new update version */

	if ( isset( $new_update_version ) ) {
		update_option( 'dae_update_version', $new_update_version, false );
	}
	
}

?>