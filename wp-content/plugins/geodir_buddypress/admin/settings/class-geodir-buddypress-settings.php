<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'GeoDir_BuddyPress_Settings', false ) ) :

	class GeoDir_BuddyPress_Settings extends GeoDir_Settings_Page {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id    = 'gd-buddypress';
			$this->label = __( 'Buddypress', 'geodir_buddypress' );

			add_filter( 'geodir_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'geodir_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'geodir_sections_' . $this->id, array( $this, 'output_toggle_advanced' ) );

			add_action( 'geodir_settings_save_' . $this->id, array( $this, 'save' ) );
			add_action( 'geodir_sections_' . $this->id, array( $this, 'output_sections' ) );

            add_filter( 'geodir_uninstall_options', array($this, 'geodir_buddypress_uninstall_options'), 10, 1);
		}

		/**
		 * Get sections.
		 *
		 * @return array
		 */
		public function get_sections() {
            $sections = array(
                '' => __( 'General', 'geodir_buddypress' ),
            );

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

            $settings = apply_filters( 'geodir_buddypress_general_options', array(
                array(
                    'name' => __( 'General Settings', 'geodir_buddypress' ),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'buddypress_settings'
                ),
                array(
                    'name' => __( 'Review Rating Settings', 'geodir_buddypress' ),
                    'type' => 'sectionstart',
                    'id' => 'buddypress_settings'
                ),
                array(
                    'name' => __( 'Redirect GD dashboard my listing link to BuddyPress profile', 'geodir_buddypress' ),
                    'desc' => __( 'If this option is selected, the my listing link from GD dashboard will redirect to listings tab of BuddyPress profile.', 'geodir_buddypress' ),
                    'id'   => 'geodir_buddypress_link_listing',
                    'type' => 'checkbox',
                    'advanced' => false
                ),
                array(
                    'name' => __( 'Redirect GD dashboard favorite link to BuddyPress profile', 'geodir_buddypress' ),
                    'desc' => __( 'If this option is selected, the favorite link from GD dashboard will redirect to favorites tab of BuddyPress profile.', 'geodir_buddypress' ),
                    'id'   => 'geodir_buddypress_link_favorite',
                    'type' => 'checkbox',
                    'advanced' => false
                ),
                array(
                    'name' => __( 'Link blog author link to the BuddyPress profile link', 'geodir_buddypress' ),
                    'desc' => __( 'If this option is selected, the blog author page links to the BuddyPress profile page.', 'geodir_buddypress' ),
                    'id'   => 'geodir_buddypress_link_author',
                    'type' => 'checkbox',
                    'advanced' => false
                ),
                array(
                    'name' => __( 'Show featured image in activity', 'geodir_buddypress' ),
                    'desc' => __( 'If this option is selected, the featured image is displayed in activity for new listing submitted.', 'geodir_buddypress' ),
                    'id'   => 'geodir_buddypress_show_feature_image',
                    'type' => 'checkbox',
                    'advanced' => false
                ),
                array(
                    'name'       => __( 'Show listings in BuddyPress dashboard', 'geodir_buddypress' ),
                    'desc'       => __( 'Choose the post types to show listing type tab under listings tab in BuddyPress dashboard', 'geodir_buddypress' ),
                    'id'         => 'geodir_buddypress_tab_listing',
                    'default'    => array('gd_place'),
                    'type'       => 'multiselect',
                    'desc_tip'   => true,
                    'class'      => 'geodir-select',
                    'options'    => geodir_get_posttypes('options'),
                    'advanced' => true
                ),
                array(
                    'name'       => __( 'Show reviews in BuddyPress dashboard', 'geodir_buddypress' ),
                    'desc'       => __( 'Choose the post types to show listing type tab under reviews tab in BuddyPress dashboard', 'geodir_buddypress' ),
                    'id'         => 'geodir_buddypress_tab_review',
                    'default'    => array('gd_place'),
                    'type'       => 'multiselect',
                    'desc_tip'   => true,
                    'class'      => 'geodir-select',
                    'options'    => geodir_get_posttypes('options'),
                    'advanced' => true
                ),
                array(
                    'name' => __('Number of listings', 'geodir_buddypress'),
                    'desc' => __('Enter number of listings to display in the member dashboard listings tab.', 'geodir_buddypress'),
                    'id' => 'geodir_buddypress_listings_count',
                    'type' => 'number',
                    'desc_tip' => true,
                    'default'  => '5',
                    'advanced' => false
                ),
                array(
                    'name'       => __( 'Track new listing activity in BuddyPress', 'geodir_buddypress' ),
                    'desc'       => __( 'Choose the post types to track new listing submission in BuddyPress activity', 'geodir_buddypress' ),
                    'id'         => 'geodir_buddypress_activity_listing',
                    'default'    => array('gd_place'),
                    'type'       => 'multiselect',
                    'desc_tip'   => true,
                    'class'      => 'geodir-select',
                    'options'    => geodir_get_posttypes('options'),
                    'advanced' => true
                ),
                array(
                    'name'       => __( 'Track new review activity in BuddyPress', 'geodir_buddypress' ),
                    'desc'       => __( 'Choose the post types to track new review submission in BuddyPress activity', 'geodir_buddypress' ),
                    'id'         => 'geodir_buddypress_activity_review',
                    'default'    => array('gd_place'),
                    'type'       => 'multiselect',
                    'desc_tip'   => true,
                    'class'      => 'geodir-select',
                    'options'    => geodir_get_posttypes('options'),
                    'advanced' => true
                ),

                array( 'type' => 'sectionend', 'id' => 'buddypress_settings' ),
            ));

			return apply_filters( 'geodir_get_settings_' . $this->id, $settings, $current_section );
		}

        public static function geodir_buddypress_uninstall_options($settings){
            array_pop($settings);
            $settings[] = array(
                'name'     => __( 'Buddypress Integration', 'geodir_buddypress' ),
                'desc'     => __( 'Check this box if you would like to completely remove all of its data when plugin is deleted.', 'geodir_buddypress' ),
                'id'       => 'uninstall_geodir_buddypress_manager',
                'type'     => 'checkbox',
            );
            $settings[] = array( 'type' => 'sectionend', 'id' => 'uninstall_options' );

            return $settings;
        }

	}

endif;

return new GeoDir_BuddyPress_Settings();
