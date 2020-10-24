<?php
/**
 * Pricing Manager Package Functions.
 *
 * @since 2.5.0
 * @package GeoDir_Pricing_Manager
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function geodir_pricing_default_package_id( $post_type, $create = true ) {
	return GeoDir_Pricing_Package::get_default_package_id( $post_type, $create );
}

function geodir_pricing_default_package( $post_type, $create = true ) {
	return GeoDir_Pricing_Package::get_default_package_id( $post_type, $create );
}

function geodir_pricing_get_packages( $args = array() ) {
	return GeoDir_Pricing_Package::get_packages( $args );
}

function geodir_pricing_field_packages( $field ) {
	return GeoDir_Pricing_Package::get_field_packages( $field );
}

function geodir_pricing_get_package( $package = null, $output = OBJECT, $filter = 'raw' ) {
	return GeoDir_Pricing_Package::get_package( $package, $output, $filter );
}

function geodir_pricing_get_meta( $package_id, $meta_key = '', $single = false ) {
	$meta = GeoDir_Pricing_Package::get_meta( $package_id, $meta_key, $single );

	$meta = apply_filters( 'geodir_pricing_get_meta', $meta, (int) $package_id, $meta_key, $single );

	if ( $meta_key && is_scalar( $meta_key ) ) {
		$meta = apply_filters( 'geodir_pricing_get_meta_' . $meta_key, $meta, (int) $package_id, $single );
	}

	return $meta;
}

function geodir_pricing_update_meta( $package_id, $meta_key, $meta_value, $prev_value = '' ) {
	return GeoDir_Pricing_Package::update_meta( $package_id, $meta_key, $meta_value, $prev_value );
}

function geodir_pricing_package_name( $package ) {
	return GeoDir_Pricing_Package::get_name( $package );
}

function geodir_pricing_package_title( $package ) {
	return GeoDir_Pricing_Package::get_title( $package );
}

function geodir_pricing_package_post_type( $package ) {
	return GeoDir_Pricing_Package::get_post_type( $package );
}

function geodir_pricing_package_post_status( $package = 0 ) {
	return GeoDir_Pricing_Package::get_post_status( $package );
}

function geodir_pricing_package_alive_days( $package, $trial = false ) {
	return GeoDir_Pricing_Package::get_alive_days( $package, $trial );
}

function geodir_pricing_package_desc_limit( $package ) {
	return GeoDir_Pricing_Package::get_desc_limit( $package );
}

function geodir_pricing_has_upgrades( $package_id ) {
	return (int) geodir_pricing_get_meta( (int) $package_id, 'has_upgrades', true );
}

function geodir_pricing_disable_html_editor( $package_id ) {
	$disable_html_editor = (bool) geodir_pricing_get_meta( (int) $package_id, 'disable_editor', true );

	return apply_filters( 'geodir_pricing_disable_html_editor', $disable_html_editor, (int) $package_id );
}

function geodir_pricing_category_limit( $package_id ) {
	return (int) geodir_pricing_get_meta( (int) $package_id, 'category_limit', true );
}

function geodir_pricing_exclude_category( $package_id ) {
	return (array) geodir_pricing_get_meta( (int) $package_id, 'exclude_category', true );
}

function geodir_pricing_tag_limit( $package_id ) {
	return (int) geodir_pricing_get_meta( (int) $package_id, 'tag_limit', true );
}

function geodir_pricing_is_featured( $package_id ) {
	$post_type = geodir_pricing_package_post_type( $package_id );

	if ( ! GeoDir_Post_types::supports( $post_type, 'featured' ) ) {
		return false;
	}

	return (bool) GeoDir_Pricing_Package::check_field_visibility( true, 'featured', $package_id, $post_type );
}

function geodir_pricing_has_files( $package_id, $file_type = 'post_images' ) {
	return (bool) GeoDir_Pricing_Package::check_field_visibility( true, $file_type, $package_id );
}

function geodir_pricing_is_recurring( $package ) {
	return GeoDir_Pricing_Package::is_recurring( $package );
}

function geodir_pricing_add_listing_url( $package ) {
	return GeoDir_Pricing_Package::add_listing_url( $package );
}