<?php
/**
 * Display the page to manage import/export locations + cpt description.
 *
 * @since 2.0.0
 * @package GeoDirectory_Location_Manager
 */

global $wpdb;

wp_enqueue_script( 'jquery-ui-progressbar' );
 
$nonce = wp_create_nonce( 'geodir_import_export_nonce' );
$chunksize_options = geodir_location_chunksizes_options( 1000 );

$total_countries = (int)geodir_location_imex_count_locations( 'country' );
$total_regions = (int)geodir_location_imex_count_locations( 'region' );
$total_cities = (int)geodir_location_imex_count_locations();
?>
<div class="inner_content_tab_main gd-import-export">
	<div class="gd-content-heading">
		<?php /**
		 * Contains template for import/export requirements.
		 *
		 * @since 2.0.0
		 */
		include_once( GEODIRECTORY_PLUGIN_DIR . 'includes/admin/views/html-admin-settings-import-export-reqs.php' );
		?>
		<div id="gd_ie_imcpt_locations" class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<div id="gd_ie_im_cpt_locations" class="postbox gd-hndle-pbox">
					<button class="handlediv button-link" type="button"><span class="screen-reader-text"><?php _e( 'Toggle panel - Locations + CPT Description: Import CSV', 'geodirlocation' );?></span><span aria-hidden="true" class="toggle-indicator"></span></button>
					<h3 class="hndle gd-hndle-click"><span style='vertical-align:top;'><?php echo __( 'Locations + CPT Description: Import CSV', 'geodirlocation' );?></span></h3>
					<div class="inside">
						<table class="form-table">
							<tbody>
							<tr>
								<td class="gd-imex-box">
									<div class="gd-im-choices">
										<p><?php _e( 'Export csv from Locations + CPT Description and update descriptions in exported csv file then import csv here. CPT description updated to matching location slugs.' ); ?></p>
									</div>
									<div class="plupload-upload-uic hide-if-no-js" id="gd_im_cpt_locationplupload-upload-ui">
										<input type="text" readonly="readonly" name="gd_im_cpt_location_file" class="gd-imex-file gd_im_cpt_location_file" id="gd_im_cpt_location" onclick="jQuery('#gd_im_cpt_locationplupload-browse-button').trigger('click');" />
										<input id="gd_im_cpt_locationplupload-browse-button" type="button" value="<?php esc_attr_e( 'Select & Upload CSV', 'geodirlocation' ); ?>" class="gd-imex-cupload button-primary" />
										<span class="ajaxnonceplu" id="ajaxnonceplu<?php echo wp_create_nonce( 'gd_im_cpt_locationpluploadan' ); ?>"></span>
										<div class="filelist"></div>
									</div>
									<span id="gd_im_cpt_locationupload-error" style="display:none"></span>
									<span class="description"></span>
									<div id="gd_importer" style="display:none">
										<input type="hidden" id="gd_total" value="0"/>
										<input type="hidden" id="gd_prepared" value="continue"/>
										<input type="hidden" id="gd_processed" value="0"/>
										<input type="hidden" id="gd_created" value="0"/>
										<input type="hidden" id="gd_updated" value="0"/>
										<input type="hidden" id="gd_invalid" value="0"/>
										<input type="hidden" id="gd_terminateaction" value="continue"/>
									</div>
									<div class="gd-import-progress" id="gd-import-progress" style="display:none">
										<div class="gd-import-file"><b><?php _e("Import Data Status :", 'geodirlocation');?> </b><font
												id="gd-import-done">0</font> / <font id="gd-import-total">0</font>&nbsp;( <font
												id="gd-import-perc">0%</font> )
											<div class="gd-fileprogress"></div>
										</div>
									</div>
									<div class="gd-import-msg" id="gd-import-msg" style="display:none">
										<div id="message" class="message fade"></div>
									</div>
									<div class="gd-imex-btns" style="display:none;">
										<input type="hidden" class="geodir_import_file" name="geodir_import_file" value="save"/>
										<input onclick="geodir_location_prepare_import(this, 'cpt_location')" type="button" value="<?php esc_attr_e( 'Import data now', 'geodirlocation' ); ?>" id="gd_import_data" class="button-primary" />
										<input onclick="geodir_location_resume_import(this, 'cpt_location')" type="button" value="<?php _e( "Continue Import Data", 'geodirlocation' ); ?>" id="gd_continue_data" class="button-primary" style="display:none"/>
										<input type="button" value="<?php _e("Terminate Import Data", 'geodirlocation');?>" id="gd_stop_import" class="button-primary" name="gd_stop_import" style="display:none" onclick="geodir_location_terminate_import(this, 'cpt_location')"/>
										<div id="gd_process_data" style="display:none">
											<span class="spinner is-active" style="display:inline-block;margin:0 5px 0 5px;float:left"></span><?php _e("Wait, processing import data...", 'geodirlocation');?>
										</div>
									</div>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="gd_ie_excpt_locations" class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<div id="gd_ie_ex_cpt_locations" class="postbox gd-hndle-pbox">
					<button class="handlediv button-link" type="button"><span class="screen-reader-text"><?php _e( 'Toggle panel - Locations: Export CSV', 'geodirlocation' );?></span><span aria-hidden="true" class="toggle-indicator"></span></button>
					<h3 class="hndle gd-hndle-click"><span style='vertical-align:top;'><?php echo __( 'Locations + CPT Description: Export CSV', 'geodirlocation' );?></span></h3>
					<div class="inside">
						<table class="form-table">
							<tbody>
							<tr>
							  <td class="fld"><label for="gd_loc_type">
								<?php _e( 'Location Type:', 'geodirlocation' );?>
								</label></td>
							  <td><select id="gd_loc_type" name="gd_imex[loc_type]" class="regular-text">
								<option value="country" data-total="<?php echo $total_countries; ?>"><?php echo __( 'Countries', 'geodirlocation' ) . ' (' . $total_countries . ')'; ?></option>
								<option value="region" data-total="<?php echo $total_regions; ?>"><?php echo __( 'Regions', 'geodirlocation' ) . ' (' . $total_regions . ')'; ?></option>
								<option value="city" data-total="<?php echo $total_cities; ?>"><?php echo __( 'Cities', 'geodirlocation' ) . ' (' . $total_cities . ')'; ?></option>
								</select></td>
						   </tr>
							<tr>
								<td class="fld" style="vertical-align:top"><label for="gd_chunk_size"><?php _e( 'Max entries per csv file:', 'geodirlocation' );?></label></td>
								<td><select name="gd_chunk_size" id="gd_chunk_size" class="regular-text"><?php echo $chunksize_options;?></select><span class="description"><?php _e( 'Please select the maximum number of entries per csv file (defaults to 1000, you might want to lower this to prevent memory issues on some installs)', 'geodirlocation' );?></span></td>
							</tr>
							<tr>
								<td class="fld" style="vertical-align:top"><label><?php _e( 'Progress:', 'geodirlocation' );?></label></td>
								<td><div id='gd_progressbar_box'><div id="gd_progressbar" class="gd_progressbar"><div class="gd-progress-label"></div></div></div><p style="display:inline-block"><?php _e( 'Elapsed Time:', 'geodirlocation' );?></p>&nbsp;&nbsp;<p id="gd_timer" class="gd_timer">00:00:00</p></td>
							</tr>
							<tr class="gd-ie-actions">
								<td style="vertical-align:top">
									<input data-export="cpt_locations" type="submit" value="<?php echo esc_attr( __( 'Export CSV', 'geodirlocation' ) );?>" class="button-primary" name="gd_start_export" id="gd_start_export">
								</td>
								<td id="gd_ie_ex_files" class="gd-ie-files"></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?php
		/**
		 * Allows you to add more setting to the GD > Import & Export page.
		 *
		 * @param array $gd_posttypes GD post types.
		 * @param array $gd_chunksize_options File chunk size options.
		 * @param string $nonce Wordpress security token for GD import & export.
		 */
		do_action( 'geodir_location_import_export_locations', $nonce );
		?>
	</div>
</div>
<?php GeoDir_Settings_Import_Export::get_import_export_js( $nonce ); ?>
<?php GeoDir_Location_Admin_Import_Export::get_import_export_js( $nonce ); ?>