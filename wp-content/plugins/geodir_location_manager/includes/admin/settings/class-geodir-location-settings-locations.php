<?php
/**
 * GeoDirectory Location Manager Settings
 *
 * @author   AyeCode
 * @category Admin
 * @package  GeoDir_Location_Manager/Admin
 * @version  2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'GeoDir_Location_Settings_Locations', false ) ) :

	/**
	 * GeoDir_Location_Settings_Locations.
	 */
	class GeoDir_Location_Settings_Locations extends GeoDir_Settings_Page {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id    = 'locations';
			$this->label = __( 'Locations', 'geodirlocation' );

			add_filter( 'geodir_settings_tabs_array', array( $this, 'add_settings_page' ), 21 );
			add_action( 'geodir_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'geodir_sections_' . $this->id, array( $this, 'output_toggle_advanced' ) );

			add_action( 'geodir_settings_save_' . $this->id, array( $this, 'save' ) );
			add_action( 'geodir_sections_' . $this->id, array( $this, 'output_sections' ) );

			// Yoast SEO settings
			add_filter( 'geodir_locations_options', array( $this, 'yoast_seo_options' ) );

			// Add/edit location
			add_action( 'geodir_admin_field_add_location', array( $this, 'add_location' ) );

			// Countries
			add_action( 'geodir_admin_field_countries_page', array( $this, 'countries_page' ) );

			// Regions
			add_action( 'geodir_admin_field_regions_page', array( $this, 'regions_page' ) );

			// Cities
			add_action( 'geodir_admin_field_cities_page', array( $this, 'cities_page' ) );

			// Neighbourhoods
			add_action( 'geodir_admin_field_neighbourhoods_page', array( $this, 'neighbourhoods_page' ) );

			add_action( 'geodir_settings_form_method_tab_' . $this->id, array( $this, 'form_method' ) );

			// Location filter
			add_action( 'geodir_location_restrict_manage_locations', array( $this, 'locations_filter_actions' ), 10, 2 );
		}

		/**
		 * Get sections.
		 *
		 * @return array
		 */
		public function get_sections() {
			$sections = array(
				''					=> __( 'Settings', 'geodirlocation' ),
				'add_location'  	=> __( 'Add Location', 'geodirlocation' ),
				'countries'  		=> __( 'Countries', 'geodirlocation' ),
				'regions' 			=> __( 'Regions', 'geodirlocation' ),
				'cities' 			=> __( 'Cities', 'geodirlocation' )
			);
			if ( GeoDir_Location_Neighbourhood::is_active() ) {
				$sections['neighbourhoods'] = __( 'Neighbourhoods', 'geodirlocation' );
			}

			return apply_filters( 'geodir_get_sections_' . $this->id, $sections );
		}

		/**
		 * Output the settings.
		 */
		public function output() {
			global $current_section;

			$settings = $this->get_settings( $current_section );

			GeoDir_Admin_Settings::output_fields( $settings );
		}

		/**
		 * Save settings.
		 */
		public function save() {
			global $current_section;

			$settings = $this->get_settings( $current_section );

			GeoDir_Admin_Settings::save_fields( $settings );
		}

		/**
		 * Get settings array.
		 *
		 * @return array
		 */
		public function get_settings( $current_section = '' ) {
			if ( 'add_location' == $current_section ) {
				$settings = apply_filters( 'geodir_location_add_location_options', 
					array(
						array( 
							'name' => __( 'Countries', 'geodirlocation' ),
							'type' => 'add_location',
							'desc' => '',
							'id' => 'geodir_location_add_location_settings',
							'advanced' => false
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_add_location_settings'
						)
					)
				);
			} elseif ( 'countries' == $current_section ) {
				$settings = apply_filters( 'geodir_location_countries_page_options', 
					array(
						array( 
							'name' => __( 'Countries', 'geodirlocation' ), 
							'type' => 'countries_page', 
							'desc' => '', 
							'id' => 'geodir_location_countries_page_settings',
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_countries_page_settings'
						)
					)
				);
			} elseif ( 'regions' == $current_section ) {
				$settings = apply_filters( 'geodir_location_regions_page_options', 
					array(
						array( 
							'name' => __( 'Regions', 'geodirlocation' ), 
							'type' => 'regions_page', 
							'desc' => '', 
							'id' => 'geodir_location_regions_page_settings' 
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_regions_page_settings'
						)
					)
				);
			} elseif ( 'cities' == $current_section ) {
				$settings = apply_filters( 'geodir_location_cities_page_options', 
					array(
						array( 
							'name' => __( 'Cities', 'geodirlocation' ), 
							'type' => 'cities_page', 
							'desc' => '', 
							'id' => 'geodir_location_cities_page_settings' 
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_cities_page_settings'
						)
					)
				);
			} elseif ( 'neighbourhoods' == $current_section ) {
				$settings = apply_filters( 'geodir_location_neighbourhoods_page_options', 
					array(
						array( 
							'name' => __( 'Neighbourhoods', 'geodirlocation' ), 
							'type' => 'neighbourhoods_page', 
							'desc' => '', 
							'id' => 'geodir_location_neighbourhoods_page_settings' 
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_neighbourhoods_page_settings'
						)
					)
				);
			} else {
				$selected_regions = geodir_get_option( 'lm_selected_regions' );
				$selected_cities = geodir_get_option( 'lm_selected_cities' );
				if ( ! empty( $selected_regions ) && is_array( $selected_regions ) ) {
					$selected_regions = array_combine( $selected_regions, $selected_regions );
				} else {
					$selected_regions = array();
				}
				if ( ! empty( $selected_cities ) && is_array( $selected_cities ) ) {
					$selected_cities = array_combine( $selected_cities, $selected_cities );
				} else {
					$selected_cities = array();
				}
				$settings = apply_filters( 'geodir_locations_options', 
					array(
						array( 
							'name' => __( 'URL Settings', 'geodirlocation' ),
							'type' => 'title', 
							'desc' => '', 
							'id' => 'geodir_location_home_url_settings' 
						),
						array(
							'type'       => 'radio',
							'id'         => 'lm_home_go_to',
							'name'       => __( 'Home page should go to', 'geodirlocation' ),
							'desc'       => '',
							'default'    => 'root',
							'options'    => array(
								'root' => __('Site root (ex: mysite.com/)', 'geodirlocation'),
								'location' => __('Current location page (ex: mysite.com/location/glasgow/)', 'geodirlocation')
							),
							'desc_tip'   => false,
							'advanced' 	 => false
						),
						array(
							'type'       => 'radio',
							'id'         => 'lm_url_filter_archives',
							'name'       => __( 'Archive urls', 'geodirlocation' ),
							'desc'       => '',
							'default'    => '',
							'options'    => array(
								'' => __('Add current url location to the archive page urls', 'geodirlocation'),
								'disable' => __('Disable', 'geodirlocation')
							),
							'desc_tip'   => false,
							'advanced' 	 => true
						),
						array(
							'type'       => 'radio',
							'id'         => 'lm_url_filter_archives_on_single',
							'name'       => __( 'Archive urls on details page', 'geodirlocation' ),
							'desc'       => __('The details page is unique as its url can contain partial locations or none at all so it must be set here.','geodirlocation'),
							'default'    => 'city',
							'options'    => array(
								'city' => __('Add the listings city location to the urls', 'geodirlocation'),
								'region' => __('Add the listings region location to the urls', 'geodirlocation'),
								'country' => __('Add the listings country location to the urls', 'geodirlocation'),
								'disable' => __('Disable', 'geodirlocation'),
							),
							'desc_tip'   => false,
							'advanced' 	 => true
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_home_url_settings'
						),
						array( 
							'name' => __( 'Enable locations', 'geodirlocation' ), 
							'type' => 'title', 
							'desc' => '', 
							'id' => 'geodir_location_enable_locations_settings' 
						),
						array(
							'type' => 'radio',
							'id' => 'lm_default_country',
							'name' => __( 'Country', 'geodirlocation' ),
							'desc' => '',
							'default' => 'multi',
							'options' => array(
								'default' => __('Enable default country (country drop-down will not appear on add listing and location switcher).', 'geodirlocation'),
								'multi' => __('Enable Multi Countries', 'geodirlocation'),
								'selected' => __('Enable Selected Countries', 'geodirlocation')
							),
							'desc_tip' => false,
							'advanced' => false
						),
						array(
							'type' => 'multiselect',
							'id' => 'lm_selected_countries',
							'name' => __( 'Select Countries', 'geodirlocation' ),
							'desc' => __( 'Only selected countries will appear in country drop-down on add listing page and location switcher. Make sure to have default country in your selected countries list for proper site functioning.', 'geodirlocation' ),
							'class' => 'geodir-select',
							'css' => 'width:100%',
							'default'  => '',
							'placeholder' => __( 'Select Countries', 'geodirlocation' ),
							'options' => geodir_get_countries(),
							'desc_tip' => true,
							'advanced' => false,
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_hide_country_part',
							'name' => '',
							'desc' => __( 'Hide country part of url for LISTING, CPT and LOCATION pages?', 'geodirlocation' ),
							'default' => '0',
							'advanced' => false
						),
						array(
							'type' => 'radio',
							'id' => 'lm_default_region',
							'name' => __( 'Region', 'geodirlocation' ),
							'desc' => '',
							'default' => 'multi',
							'options' => array(
								'default' => __('Enable default region (region drop-down will not appear on add listing and location switcher).', 'geodirlocation'),
								'multi' => __('Enable multi regions', 'geodirlocation'),
								'selected' => __('Enable selected regions', 'geodirlocation')
							),
							'desc_tip' => false,
							'advanced' => false
						),
						array(
							'type' => 'multiselect',
							'id' => 'lm_selected_regions',
							'name' => __( 'Select Regions', 'geodirlocation' ),
							'desc' => __( 'Only selected regions will appear in region drop-down on add listing page and location switcher. Make sure to have default region in your selected regions list for proper site functioning.', 'geodirlocation' ),
							'class' => 'geodir-region-search',
							'css' => 'width:100%',
							'default'  => '',
							'placeholder' => __( 'Search for a region...', 'geodirlocation' ),
							'options' => $selected_regions,
							'desc_tip' => true,
							'advanced' => false,
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_hide_region_part',
							'name' => '',
							'desc' => __( 'Hide region part of url for LISTING, CPT and LOCATION pages?', 'geodirlocation' ),
							'default' => '0',
							'advanced' => false
						),
						array(
							'type' => 'radio',
							'id' => 'lm_default_city',
							'name' => __( 'City', 'geodirlocation' ),
							'desc' => '',
							'default' => 'multi',
							'options' => array(
								'default' => __('Enable default city (City drop-down will not appear on add listing and location switcher).', 'geodirlocation'),
								'multi' => __('Enable multi cities', 'geodirlocation'),
								'selected' => __('Enable selected cities', 'geodirlocation')
							),
							'desc_tip' => false,
							'advanced' => false
						),
						array(
							'type' => 'multiselect',
							'id' => 'lm_selected_cities',
							'name' => __( 'Select Cities', 'geodirlocation' ),
							'desc' => __( 'Only selected cities will appear in city drop-down on add listing page and location switcher. Make sure to have default city in your selected cities list for proper site functioning.', 'geodirlocation' ),
							'class' => 'geodir-city-search',
							'css' => 'width:100%',
							'default'  => '',
							'placeholder' => __( 'Search for a city...', 'geodirlocation' ),
							'options' => $selected_cities,
							'desc_tip' => true,
							'advanced' => false,
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_enable_neighbourhoods',
							'name' => __( 'Enable neighbourhoods?', 'geodirlocation' ),
							'desc' => __( 'Select the option if you wish to enable neighbourhood options.', 'geodirlocation' ),
							'default' => '0',
							'advanced' => false
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_enable_locations_settings'
						),
						array( 
							'name' => __( 'Add listing form', 'geodirlocation' ), 
							'type' => 'title', 
							'desc' => '', 
							'id' => 'geodir_location_add_listing_settings' 
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_location_address_fill',
							'name' => __( 'Disable address autocomplete?', 'geodirlocation' ),
							'desc' => __( 'This will stop the address suggestions when typing in address box on add listing page.', 'geodirlocation' ),
							'default' => '0',
							'advanced' => false
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_location_dropdown_all',
							'name' => __( 'Show all locations in dropdown?', 'geodirlocation' ),
							'desc' => __( ' This is useful if you have a small directory but can break your site if you have many locations', 'geodirlocation' ),
							'default' => '0',
							'advanced' => true
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_set_address_disable',
							'name' => __( 'Disable set address on map from changing address fields', 'geodirlocation' ),
							'desc' => __( ' This is useful if you have a small directory and you have custom locations or your locations are not known by the Google API and they break the address. (highly recommended not to enable this)', 'geodirlocation' ),
							'default' => '0',
							'advanced' => true
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_set_pin_disable',
							'name' => __( 'Disable move map pin from changing address fields', 'geodirlocation' ),
							'desc' => __( 'This is useful if you have a small directory and you have custom locations or your locations are not known by the Google API and they break the address. (highly recommended not to enable this)', 'geodirlocation' ),
							'default' => '0',
							'advanced' => true
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_add_listing_settings'
						),


//						array(// todo move to LM
//							'type' => 'title',
//							'id' => 'lm_redirect_settings',
//							'name' => __( 'Redirect Settings', 'geodiradvancesearch' ),
//						),
//						array(
//							'type' => 'radio',
//							'id' => 'lm_first_load_redirect',
//							'name' => __( 'Home page should go to', 'geodiradvancesearch' ),
//							'desc' => '',
//							'default' => 'no',
//							'options' => array(
//								'no' => __( 'No redirect', 'geodiradvancesearch' ),
//								'nearest' => __( 'Redirect to nearest location <i>(on first time load users will be auto geolocated and redirected to nearest geolocation found)</i>', 'geodiradvancesearch' ),
//								'location' => __( 'Redirect to default location <i>(on first time load users will be redirected to default location</i>', 'geodiradvancesearch' ),
//							),
//						),
//						array(
//							'type' => 'sectionend',
//							'id' => 'lm_redirect_settings'
//						),
//						array(
//							'type' => 'title',
//							'id' => 'lm_geolocation_settings',
//							'name' => __( 'GeoLocation Settings', 'geodiradvancesearch' ),
//						),
//						array(// todo move to LM
//							'type' => 'checkbox',
//							'id' => 'lm_autolocate_ask',
//							'name' => __( 'Ask user if they wish to be geolocated?', 'geodiradvancesearch' ),
//							'desc' => __( 'If this option is selected, users will be asked if they with to be geolocated via a popup.', 'geodiradvancesearch' ),
//							'std' => '0',
//						),
//						array(
//							'type' => 'sectionend',
//							'id' => 'lm_geolocation_settings'
//						),


						array( 
							'name' => __( 'Other', 'geodirlocation' ), 
							'type' => 'title', 
							'desc' => '', 
							'id' => 'geodir_location_other_settings' 
						),
						array(
							'type' => 'number',
							'id'   => 'lm_location_no_of_records',
							'name' => __( 'Load more limit', 'geodirlocation' ),
							'desc' => __( 'Load no of locations by default in [gd_location_switcher] shortcode and then add load more.', 'geodirlocation' ),
							'css'  => 'min-width:300px;',
							'default'  => '50',
							'desc_tip' => true,
							'advanced' => true
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_enable_search_autocompleter',
							'name' => __( 'Enable location search autocompleter?', 'geodirlocation' ),
							'desc' => __( 'This will enable location autocomplete search on the location search bar.', 'geodirlocation' ),
							'default' => '1',
							'advanced' => false
						),
						array(
							'type' => 'number',
							'id' => 'lm_autocompleter_min_chars',
							'name' => __( 'Min chars needed to trigger location autocomplete', 'geodirlocation' ),
							'desc' => __( 'Enter the minimum characters users need to be typed to trigger location autocomplete. Ex: 3.', 'geodirlocation' ),
							'placeholder' => '',
							'default' => '0',
							'custom_attributes' => array(
								'min' => '0',
								'step' => '1',
							),
							'desc_tip' => true
						),
						array(
							'type' => 'checkbox',
							'id' => 'lm_disable_nearest_cities',
							'name' => __( 'Disable nearest cities?', 'geodirlocation' ),
							'desc' => __( 'In location switcher and search form first time focus to location search input shows nearest city results based on user IP. Tick to disable this nearest city results on first time focus to input.', 'geodirlocation' ),
							'default' => '0',
							'advanced' => false
						),
						array(
							'type' => 'checkbox',
							'id'   => 'lm_disable_term_auto_count',
							'name' => __( 'Disable term auto count?', 'geodirlocation' ),
							'desc' => __( 'On shared hosting with lots of listings, saving a listing may take a long time because of auto term counts, if you disable them here you should manually run the GD Tools > Location category counts, often until you can upgrade your hosting and re-enable it here, otherwise your location term and review counts can be wrong.', 'geodirlocation' ),
							'default' => '0',
							'advanced' => false
						),
						array(
							'type' => 'sectionend', 
							'id' => 'geodir_location_other_settings'
						),
					)
				);
			}

			return apply_filters( 'geodir_get_settings_' . $this->id, $settings, $current_section );
		}

		public static function yoast_seo_options( $settings  ) {
			if ( function_exists( 'wpseo_init' ) ) {
				$yoast_seo_options = array(
					array( 
						'name' => __( 'Yoast SEO sitemap', 'geodirlocation' ), 
						'type' => 'title', 
						'desc' => '', 
						'id' => 'geodir_location_sitemap_settings' 
					),
					array(
						'type' => 'checkbox',
						'id'   => 'lm_sitemap_exclude_location',
						'name' => __( 'Exclude location pages in xml sitemap', 'geodirlocation' ),
						'desc' => __( 'Please check the box if you do NOT want to include location pages in your xml sitemap.', 'geodirlocation' ),
						'css'  => 'min-width:300px;',
						'default'  => '0',
					),
					array(
						'type' => 'checkbox',
						'id'   => 'lm_sitemap_exclude_post_types',
						'name' => __( 'Exclude post types location pages in xml sitemap', 'geodirlocation' ),
						'desc' => __( 'Please check the box if you do NOT want to include post types location pages in your xml sitemap.', 'geodirlocation' ),
						'default' => '0',
						'advanced' => true
					),
					array(
						'type' => 'checkbox',
						'id'   => 'lm_sitemap_exclude_cats',
						'name' => __( 'Exclude categories location pages in xml sitemap', 'geodirlocation' ),
						'desc' => __( 'Please check the box if you do NOT want to include categories location pages in your xml sitemap.', 'geodirlocation' ),
						'default' => '0',
						'advanced' => true
					),
					array(
						'type' => 'checkbox',
						'id'   => 'lm_sitemap_exclude_tags',
						'name' => __( 'Exclude tags location pages in xml sitemap', 'geodirlocation' ),
						'desc' => __( 'Please check the box if you do NOT want to include tags location pages in your xml sitemap.', 'geodirlocation' ),
						'default' => '1',
						'advanced' => true
					),
					array(
						'type' => 'sectionend', 
						'id' => 'geodir_location_sitemap_settings'
					),
				);

				$settings = array_merge( $settings, $yoast_seo_options );
			}
			return $settings;
		}
		
		public static function countries_page( $option ) {
			GeoDir_Location_Admin_Countries::page_output();
		}

		public static function regions_page( $option ) {
			GeoDir_Location_Admin_Regions::page_output();
		}

		public static function cities_page( $option ) {
			GeoDir_Location_Admin_Cities::page_output();
		}

		public static function neighbourhoods_page( $option ) {
			GeoDir_Location_Admin_Neighbourhoods::page_output();
		}
		
		/**
		 * Form method.
		 *
		 * @param  string $method
		 *
		 * @return string
		 */
		public function form_method( $method ) {
			global $current_section;

			if ( 'countries' == $current_section || 'regions' == $current_section || 'cities' == $current_section || ( 'neighbourhoods' == $current_section && empty( $_REQUEST['add_neighbourhood'] ) ) ) {

				return 'get';
			}

			return 'post';
		}

		public function add_location() {
			// Hide the save button
			$GLOBALS['hide_save_button'] 		= true;

			$location_id 	= isset( $_GET['location_id'] ) ? absint( $_GET['location_id'] ) : 0;
			$location 		= self::get_location_data( $location_id );

			add_filter( 'geodir_add_listing_map_restrict', '__return_false' );

			include( GEODIR_LOCATION_PLUGIN_DIR . 'includes/admin/views/html-add-edit-location.php' );
		}

		/**
		 * Get key data.
		 *
		 * @param  int $key_id
		 * @return array
		 */
		private static function get_location_data( $id ) {
			global $wpdb;

			$empty = array(
				'location_id'		=> 0,
				'country'			=> '',
				'region'			=> '',
				'city'				=> '',
				'country_slug'		=> '',
				'region_slug'		=> '',
				'city_slug'			=> '',
				'latitude'			=> '',
				'longitude'			=> '',
				'is_default'		=> '',
				'meta_title'		=> '',
				'meta_description'	=> '',
				'description'		=> '',
				'image'		        => '',
				'image_tagline'		=> '',
				'cpt_desc'			=> array()
			);

			if ( empty( $id ) ) {
				return $empty;
			}

			$row = (array)geodir_get_location_by_id( '' , $id );

			if ( empty( $row ) ) {
				return $empty;
			}
			
			$seo = GeoDir_Location_SEO::get_seo_by_slug( $row['city_slug'], 'city', $row['country_slug'], $row['region_slug'] );

			$row['meta_title'] = ! empty( $seo->meta_title ) ? $seo->meta_title : '';
			$row['meta_description'] = ! empty( $seo->meta_desc ) ? $seo->meta_desc : '';
			$row['description'] = ! empty( $seo->location_desc ) ? $seo->location_desc : '';
			$row['image'] = ! empty( $seo->image ) ? $seo->image : 0;
			$row['image_tagline'] = isset( $seo->image_tagline ) ? $seo->image_tagline : '';
			$row['cpt_desc'] = ! empty( $seo->cpt_desc ) ? json_decode( stripslashes( $seo->cpt_desc ), true ) : array();

			return $row;
		}

		public function locations_filter_actions( $type, $which ) {
			if ( in_array( $type, array( 'region', 'city', 'neighbourhood' ) ) ) {
				$this->country_filter( $type, $which );

				if ( in_array( $type, array( 'city', 'neighbourhood' ) ) ) {
					$this->region_filter( $type, $which );

					if ( $type == 'neighbourhood' ) {
						$this->city_filter( $type, $which );
					}
				}
			}
		}

		public function country_filter( $type, $which ) {
			global $wpdb;

			$country = isset( $_REQUEST['country'] ) ? sanitize_text_field( $_REQUEST['country'] ) : '';

			// Get the results
			$results = $wpdb->get_results( "SELECT DISTINCT `country` FROM " . GEODIR_LOCATIONS_TABLE . " ORDER BY country ASC" );
			?>
			<label for="filter-by-country" class="screen-reader-text"><?php _e( 'Filter by country', 'geodirlocation' ); ?></label>
			<select name="country" id="filter-by-country">
				<option value=""><?php _e( 'All countries', 'geodirlocation' ); ?></option>
				<?php if ( ! empty( $results ) ) { ?>
					<?php foreach ( $results as $row ) { ?>
						<option value="<?php echo esc_attr( $row->country ); ?>" <?php selected( stripslashes( $country ), stripslashes( $row->country ) ); ?>><?php echo __( $row->country, 'geodirectory' ); ?></option>
					<?php } ?>
				<?php } ?>
			</select>
			<?php
		}

		public function region_filter( $type, $which ) {
			global $wpdb;

			$country = isset( $_REQUEST['country'] ) ? sanitize_text_field( $_REQUEST['country'] ) : '';
			$region = isset( $_REQUEST['region'] ) ? sanitize_text_field( $_REQUEST['region'] ) : '';

			if ( ! empty( $country ) ) {
				$where = array();
				if ( ! empty( $_REQUEST['country'] ) ) {
					$where[] = "country LIKE '" . sanitize_text_field( $_REQUEST['country'] ) . "'";
				}
				$where = ! empty( $where ) ? "WHERE " . implode( ' AND ', $where ) : '';

				// Get the results
				$results = $wpdb->get_results( "SELECT DISTINCT `region` FROM " . GEODIR_LOCATIONS_TABLE . " {$where} ORDER BY region ASC" );
				$disabled = '';
			} else {
				$results = array();
				$disabled = 'disabled="disabled"';
			}
			?>
			<label for="filter-by-region" class="screen-reader-text"><?php _e( 'Filter by region', 'geodirlocation' ); ?></label>
			<select name="region" id="filter-by-region" <?php echo $disabled; ?>>
				<option value=""><?php _e( 'All regions', 'geodirlocation' ); ?></option>
				<?php if ( ! empty( $results ) ) { ?>
					<?php foreach ( $results as $row ) { ?>
						<option value="<?php echo esc_attr( $row->region ); ?>" <?php selected( stripslashes( $region ), stripslashes( $row->region ) ); ?>><?php echo $row->region; ?></option>
					<?php } ?>
				<?php } ?>
			</select>
			<?php
		}

		public function city_filter( $type, $which ) {
			global $wpdb;

			$country = isset( $_REQUEST['country'] ) ? sanitize_text_field( $_REQUEST['country'] ) : '';
			$region = isset( $_REQUEST['region'] ) ? sanitize_text_field( $_REQUEST['region'] ) : '';
			$city = isset( $_REQUEST['city'] ) ? sanitize_text_field( $_REQUEST['city'] ) : '';

			if ( ! empty( $region ) ) {
				$where = array();
				if ( ! empty( $_REQUEST['country'] ) ) {
					$where[] = "country LIKE '" . sanitize_text_field( $_REQUEST['country'] ) . "'";
				}
				if ( ! empty( $_REQUEST['region'] ) ) {
					$where[] = "region LIKE '" . sanitize_text_field( $_REQUEST['region'] ) . "'";
				}
				$where = ! empty( $where ) ? "WHERE " . implode( ' AND ', $where ) : '';

				// Get the results
				$results = $wpdb->get_results( "SELECT DISTINCT `city` FROM " . GEODIR_LOCATIONS_TABLE . " {$where} ORDER BY city ASC" );
				$disabled = '';
			} else {
				$results = array();
				$disabled = 'disabled="disabled"';
			}
			?>
			<label for="filter-by-city" class="screen-reader-text"><?php _e( 'Filter by city', 'geodirlocation' ); ?></label>
			<select name="city" id="filter-by-city" <?php echo $disabled; ?>>
				<option value=""><?php _e( 'All cities', 'geodirlocation' ); ?></option>
				<?php if ( ! empty( $results ) ) { ?>
					<?php foreach ( $results as $row ) { ?>
						<option value="<?php echo esc_attr( $row->city ); ?>" <?php selected( stripslashes( $city ), stripslashes( $row->city ) ); ?>><?php echo $row->city; ?></option>
					<?php } ?>
				<?php } ?>
			</select>
			<?php
		}

		public function neighbourhood_filter( $type, $which ) {
			$country = isset( $_REQUEST['country'] ) ? sanitize_text_field( $_REQUEST['country'] ) : '';
			$region = isset( $_REQUEST['region'] ) ? sanitize_text_field( $_REQUEST['region'] ) : '';
			$city = isset( $_REQUEST['city'] ) ? sanitize_text_field( $_REQUEST['city'] ) : '';
			$neighbourhood = isset( $_REQUEST['neighbourhood'] ) ? sanitize_text_field( $_REQUEST['neighbourhood'] ) : '';

			?>
			<label for="filter-by-neighbourhood" class="screen-reader-text"><?php _e( 'Filter by neighbourhood', 'geodirlocation' ); ?></label>
			<select name="neighbourhood" id="filter-by-neighbourhood">
				<option value=""><?php _e( 'All neighbourhoods', 'geodirlocation' ); ?></option>
			</select>
			<?php
		}
	}

endif;

return new GeoDir_Location_Settings_Locations();
