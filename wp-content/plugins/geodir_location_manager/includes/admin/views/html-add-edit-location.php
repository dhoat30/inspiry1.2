<?php
/**
 * Admin View: Add/edit location
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $mapzoom;
$prefix 	= 'location_';
$map_title 	= __( "Set Address On Map", 'geodirlocation' );
if ( ! empty( $location['location_id'] ) ) {
    $mapzoom = 10;
	$country = $location['country'];
	$region = $location['region'];
	$city = $location['city'];
	$lat = $location['latitude'];
	$lng = $location['longitude'];
}
?>
<div id="geodir-add-location-div">
	<?php if ( empty( $location['location_id'] ) ) { ?>
	<h2 class="gd-settings-title "><?php _e( 'Add Location', 'geodirlocation' ); ?></h2>
	<?php } else { ?>
	<h2 class="gd-settings-title "><?php echo __( 'Edit Location:', 'geodirlocation' ) . ' #' . $location['location_id']; ?></h2>
	<?php } ?>
	<table class="form-table">
		<tbody>
			<?php if ( ! empty( $location['city_slug'] ) ) { ?>
			<tr valign="top" class="formlabel">
				<th scope="row" class="titledesc">
					<label for="location_city_slug"><?php _e( 'Slug', 'geodirlocation' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<?php echo esc_attr( $location['city_slug'] ); ?>
				</td>
			</tr>
			<?php } ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_city"><?php _e( 'City', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The location city name.', 'geodirlocation' ); ?>"></span>
				</th>
				<td class="forminp forminp-text">
					<input name="location_city" id="location_city" value="<?php echo esc_attr( $location['city'] ); ?>" class="regular-text" type="text" required>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_region"><?php _e( 'Region', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The location region name.', 'geodirlocation' ); ?>"></span>	
				</th>
				<td class="forminp forminp-text">
					<input name="location_region" id="location_region" value="<?php echo esc_attr( $location['region'] ); ?>" class="regular-text" type="text" required>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_country"><?php _e( 'Country', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The location country name.', 'geodirlocation' ); ?>"></span>
				</th>
				<td class="forminp">
					<select id="location_country" name="location_country" data-placeholder="<?php esc_attr_e( 'Choose a country...', 'geodirlocation' ); ?>" class="regular-text geodir-select" required>
						<?php geodir_get_country_dl( $location['country'] ); ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<td class="forminp" colspan="2">
					<?php include( GEODIRECTORY_PLUGIN_DIR . 'templates/map.php' ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_latitude"><?php _e( 'Latitude', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The latitude of the location.', 'geodirlocation' ); ?>"></span>
				</th>
				<td class="forminp forminp-number">
					<input name="location_latitude" id="location_latitude" value="<?php echo esc_attr( $location['latitude'] ); ?>" class="regular-text" min="-90" max="90" step="any" type="number" lang="EN" required>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_longitude"><?php _e( 'Longitude', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The longitude of the location.', 'geodirlocation' ); ?>"></span>
				</th>
				<td class="forminp forminp-number">
					<input name="location_longitude" id="location_longitude" value="<?php echo esc_attr( $location['longitude'] ); ?>" class="regular-text" min="-180" max="180" step="any" type="number" lang="EN" required>
				</td>
			</tr>
			<?php if ( ! empty( $location['is_default'] ) ) {
				if ( function_exists( 'geodir_timezone_choice' ) ) {
				$locale = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_timezone_string"><?php _e( 'Timezone', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Select a city/timezone.', 'geodirlocation' ); ?>"></span>
				</th>
				<td class="forminp forminp-text">
					<select id="location_timezone_string" name="location_timezone_string" class="regular-text geodir-select" data-placeholder="<?php esc_attr_e( 'Select a city/timezone&hellip;', 'geodirlocation' ); ?>" data-allow_clear="true"><?php echo geodir_timezone_choice( geodir_get_option( 'default_location_timezone_string' ), $locale ) ;?></select>
				</td>
			</tr>
			<?php } else { /* @todo remove after GD v2.0.0.96 */ ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_timezone"><?php _e( 'Timezone', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Set the site timezone. Ex: +5:30 or GMT+5:30 or UTC+5:30.', 'geodirlocation' ); ?>"></span>
				</th>
				<td class="forminp forminp-text">
					<input name="location_timezone" id="location_timezone" placeholder="<?php esc_attr_e( geodir_wp_gmt_offset() ); ?>" value="<?php esc_attr_e( geodir_get_option( 'default_location_timezone' ) ); ?>" class="regular-text" type="text">
				</td>
			</tr>
			<?php	}	}	?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_meta_title"><?php _e( 'Meta Title', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The meta title.', 'geodirlocation' ); ?>"></span>
				</th>
				<td class="forminp forminp-textarea">
					<input type="text" name="location_meta_title" id="location_meta_title" value="<?php echo esc_attr( $location['meta_title'] ); ?>" class="regular-text">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_meta_description"><?php _e( 'Meta Description', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The meta description.', 'geodirlocation' ); ?>"></span>	
				</th>
				<td class="forminp forminp-textarea">
					<textarea name="location_meta_description" id="location_meta_description" class="regular-text code"><?php echo esc_attr( $location['meta_description'] ); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_description"><?php _e( 'Location Description', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The location description.', 'geodirlocation' ); ?>"></span>
				</th>
				<td class="forminp forminp-textarea">
					<textarea name="location_description" id="location_description" class="regular-text code"><?php echo esc_attr( $location['description'] ); ?></textarea>
				</td>
			</tr>

			<?php
			
			$image[] = array(
				'name' => __('Featured Image', 'geodirlocation'),
				'desc' => __('This is implemented by some themes to show a location specific image.', 'geodirlocation'),
				'id' => 'location_image',
				'type' => 'image',
				'default' => 0,
				'desc_tip' => true,
				'value' => $location['image']
			);
			GeoDir_Admin_Settings::output_fields($image);
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="location_image_tagline"><?php _e( 'Image Tagline', 'geodirlocation' ); ?></label><span class="gd-help-tip dashicons dashicons-editor-help" title="<?php esc_attr_e( 'The location image tagline.', 'geodirlocation' ); ?>"></span>	
				</th>
				<td class="forminp forminp-textarea">
					<textarea name="location_image_tagline" id="location_image_tagline" class="regular-text code"><?php echo esc_attr( $location['image_tagline'] ); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<td class="forminp" colspan="2">
				</td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="location_id" id="geodir_location_id" value="<?php echo $location['location_id']; ?>" />
	<input type="hidden" name="security" id="geodir_save_location_nonce" value="<?php echo wp_create_nonce( 'geodir-save-location' ); ?>" />
	<?php submit_button( __( 'Save Location', 'geodirlocation' ), 'primary', 'save_location' ); ?>
</div>