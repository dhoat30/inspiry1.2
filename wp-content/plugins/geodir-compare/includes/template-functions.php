<?php
/**
 * Template functions
 *
 * @since 2.0.0.4
 * @package GeoDir_Location_Manager
 */

/**
 * Content to display when no compare are found.
 *
 */
function geodir_no_compare_found() {

	$design_style = geodir_design_style();

	$template_args = array();
	if( $design_style ) {
		geodir_get_template( $design_style . '/loop/no-compare-found.php', $template_args, '', plugin_dir_path( GEODIR_COMPARE_PLUGIN_FILE ). "/templates/" );
	} else {
		geodir_get_template( 'loop/no-compare-found.php', $template_args, '', plugin_dir_path( GEODIR_COMPARE_PLUGIN_FILE ). "/templates/" );	
	}
}