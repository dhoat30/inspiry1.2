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

function geodir_compare_list_init_js() {
	global $geodir_compare_list_init_js;
	if ( $geodir_compare_list_init_js ) {
		return;
	}
	$geodir_compare_list_init_js = true;
?>
<script type="text/javascript">/* <![CDATA[ */ 
	jQuery(function($) {console.log('112');
		// Load flexslider if not loaded
		if (!$.flexslider) {
			$.getScript("<?php echo geodir_plugin_url(); ?>/assets/js/jquery.flexslider.min.js?ver=<?php echo GEODIRECTORY_VERSION; ?>", function(data, textStatus, jqxhr) {
				init_read_more();
				geodir_init_lazy_load();
				geodir_refresh_business_hours();
				try { 
					geodir_init_flexslider();
				} catch(e) {
					console.log(e.message);
				}
			});
		} else {
			init_read_more();
			geodir_init_lazy_load();
			geodir_refresh_business_hours();
			try { 
				geodir_init_flexslider();
			} catch(e) {
				console.log(e.message);
			}
		}
	});
/* ]]> */</script>
<?php
}