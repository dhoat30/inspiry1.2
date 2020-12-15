<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', 'dae_update_actions' );
function dae_update_actions() {
	
	$update_version = get_option( 'dae_update_version' );

	/** Save new update version */

	if ( isset( $new_update_version ) ) {
		update_option( 'dae_update_version', $new_update_version, false );
	}
	
}

?>