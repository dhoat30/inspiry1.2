<?php
/**
 * GeoDirectory Admin
 *
 * @class    GeoDir_Admin
 * @author   AyeCode
 * @category Admin
 * @package  GeoDirectory/Admin
 * @version  2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * GeoDir_Location_Admin class.
 */
class GeoDir_Location_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'current_screen', array( $this, 'conditional_includes' ) );
		add_action( 'admin_init', array( $this, 'buffer' ), 1 );
		add_action( 'admin_init', array( $this, 'admin_redirects' ) );
		add_filter( 'geodir_get_settings_pages', array( $this, 'load_settings_page' ), 10.2, 1 );
		add_filter( 'geodir_load_gomap_script', array( $this, 'load_gomap_script' ), 10, 1 );
		add_filter( 'geodir_get_sections_general', array( $this, 'hide_default_location_setting' ), 10, 1 );

		add_action( 'geodir_clear_version_numbers' ,array( $this, 'clear_version_number'));
		add_action( 'geodir_address_extra_admin_fields', 'geodir_location_address_extra_admin_fields', 1, 2 );
		add_filter( 'geodir_uninstall_options', 'geodir_location_uninstall_settings', 10, 1 );
		add_filter( 'geodir_setup_wizard_default_location_saved', 'geodir_location_setup_wizard_default_location', 10, 1 );
		add_action( 'admin_init', array( $this, 'add_custom_notice' ), 20 );
		add_filter( 'geodir_debug_tools' , 'geodir_location_diagnostic_tools', 20 );
	}

	/**
	 * Deletes the version number from the DB so install functions will run again.
	 */
	public function clear_version_number(){
		delete_option( 'geodir_location_version' );
	}

	/**
	 * Output buffering allows admin screens to make redirects later on.
	 */
	public function buffer() {
		ob_start();
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
		include_once( dirname( __FILE__ ) . '/admin-functions.php' );
		include_once( dirname( __FILE__ ) . '/class-geodir-location-admin-assets.php' );
		include_once( dirname( __FILE__ ) . '/class-geodir-location-admin-import-export.php' );
		include_once( dirname( __FILE__ ) . '/class-geodir-location-admin-dashboard.php' );
	}

	/**
	 * Include admin files conditionally.
	 */
	public function conditional_includes() {
		if ( ! $screen = get_current_screen() ) {
			return;
		}

		switch ( $screen->id ) {
			case 'dashboard' :
			break;
			case 'options-permalink' :
			break;
			case 'users' :
			case 'user' :
			case 'profile' :
			case 'user-edit' :
			break;
			case 'customize':
			case 'widgets' :
			break;
		}
	}

	/**
	 * Handle redirects to setup/welcome page after install and updates.
	 *
	 * For setup wizard, transient must be present, the user must have access rights, and we must ignore the network/bulk plugin updaters.
	 */
	public function admin_redirects() {
		// Nonced plugin install redirects (whitelisted)
		if ( ! empty( $_GET['geodir-location-install-redirect'] ) ) {
			$plugin_slug = geodir_clean( $_GET['geodir-location-install-redirect'] );

			$url = admin_url( 'plugin-install.php?tab=search&type=term&s=' . $plugin_slug );

			wp_safe_redirect( $url );
			exit;
		}

		// Setup wizard redirect
		if ( get_transient( '_geodir_location_activation_redirect' ) ) {
			delete_transient( '_geodir_location_activation_redirect' );
		}
	}
	
	public static function load_settings_page( $settings_pages ) {
		$post_type = ! empty( $_REQUEST['post_type'] ) ? sanitize_title( $_REQUEST['post_type'] ) : 'gd_place';
		if ( !( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == $post_type.'-settings' ) ) {
			$settings_pages[] = include( GEODIR_LOCATION_PLUGIN_DIR . 'includes/admin/settings/class-geodir-location-settings-locations.php' );
		}

		return $settings_pages;
	}
	
	public static function load_gomap_script( $load ) {
		$tab 		= ! empty( $_GET['tab'] ) ? $_GET['tab'] : '';
		$section 	= ! empty( $_GET['section'] ) ? $_GET['section'] : '';

		if ( $tab == 'locations' ) {
			if ( $section == 'add_location' ) {
				$load = true;
			} else if ( $section == 'neighbourhoods' && ! empty( $_GET['add_neighbourhood'] ) ) {
				$load = true;
			}
		}

		return $load;
	}
	
	public static function hide_default_location_setting( $sections ) {
		if ( empty( $_GET['tab'] ) || (! empty( $_GET['tab'] ) && $_GET['tab'] == 'general') ) {
			if ( isset( $sections['location'] ) ) {
				unset( $sections['location'] );
			}
		}
		return $sections;
	}

	public static function add_custom_notice() {
		global $geodirectory;

		$page = ! empty( $_GET['page'] ) ? $_GET['page'] : '';
		$tab = ! empty( $_GET['tab'] ) ? $_GET['tab'] : '';

		if ( wp_doing_ajax() ) {
			return;
		}

		if ( ! empty( $page ) && in_array( $page, array( 'geodirectory', 'gd-settings', 'gd-status' ) ) && $tab != 'fix_duplicate_location_slugs' ) {
			// Check location duplicate slugs
			if ( ! GeoDir_Admin_Notices::has_notice( 'geodir_location_duplicate_slug_error' ) && $geodirectory->location->has_duplicate_slugs() ) {
				GeoDir_Admin_Notices::add_custom_notice(
					'geodir_location_duplicate_slug_error',
					wp_sprintf(
						__( 'There are duplicate slugs found for some locations. Go to GoeDirectory > Status > Tools & run a tool <a href="%1$s">Fix location duplicate slugs</a> to fix duplicate slugs.', 'geodirlocation' ),
						esc_url( admin_url( 'admin.php?page=gd-status&tab=tools' ) )
					)
				);
			}
		}
	}
}