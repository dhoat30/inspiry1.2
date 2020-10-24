<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class GeoDir_Widget_List_Compare
 *
 * @since 1.0.0
 */
class GeoDir_Widget_Compare_Button extends WP_Super_Duper {

    public $arguments;

    /**
     * Main class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

        $options = array(
            'textdomain'            => 'geodir-compare',
            'block-icon'            => 'admin-site',
            'block-category'        => 'common',
            'block-keywords'        => "['compare','listings','geodirectory']",
            'class_name'            => __CLASS__,
            'base_id'               => 'gd_compare_button',
            'name'                  => __('GD > Compare Button','geodir-compare'),
            'widget_ops'            => array(
                'classname'         => 'geodir-listing-compare-container bsui',
                'description'       => esc_html__('Allows the user to compare two or more listings.','geodir-compare'),
                'geodirectory'      => true,
                'gd_wgt_showhide'   => 'show_on',
                'gd_wgt_restrict'   => array( 'gd-detail' ),
            ),
        );

        parent::__construct( $options );
    }

    /**
     * Set widget arguments.
     *
     * @since 1.0.0
     * @return array
     */
    public function set_arguments() {

	    $design_style = geodir_design_style();

        $arguments                  = array(
            'badge'                 => array(
                'type'              => 'text',
                'title'             => __('Button Text', 'geodir-compare'),
                'desc'              => __('The text used by the compare listing button.', 'geodir-compare'),
                'placeholder'       => __('Compare', 'geodir-compare'),
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'icon_class'            => array(
                'type'              => 'text',
                'title'             => __('Button Icon', 'geodir-compare'),
                'desc'              => __('Enter a FontAwesome icon class here and it will be displayed in the button.', 'geodir-compare'),
                'placeholder'       => 'far fa-square',
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'badge_after'           => array(
                'type'              => 'text',
                'title'             => __('Added To Compare Button Text', 'geodir-compare'),
                'desc'              => __('The text used by the compare listing button when the listing has already been added to the comparison list.', 'geodir-compare'),
                'placeholder'       => __('Added To Compare', 'geodir-compare'),
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'icon_class_after'      => array(
                'type'              => 'text',
                'title'             => __('Added To Compare Button Icon', 'geodir-compare'),
                'desc'              => __('Enter a FontAwesome icon class here and it will be displayed in the button after a user has added the listing to a comparison list.', 'geodir-compare'),
                'placeholder'       => 'far fa-check-square',
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'bg_color'              => array(
                'type'              => 'color',
                'title'             => __('Button Background Color:','geodir-compare'),
                'desc'              => __('What color should be used as the button background?.','geodir-compare'),
                'placeholder'       => '',
                'default'           => '#0073aa',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'txt_color'             => array(
                'type'              => 'color',
                'title'             => __('Button Text Color:','geodir-compare'),
                'desc'              => __('Color for the button text.','geodir-compare'),
                'placeholder'       => '',
                'desc_tip'          => true,
                'default'           => '#ffffff',
                'advanced'          => true
            ),
            'size'                  => array(
                'type'              => 'select',
                'title'             => __('Button size:','geodir-compare'),
                'desc'              => __('Size of the button.','geodir-compare'),
                'options'           =>  array(
                    "small"         => __('Small','geodir-compare'),
                    ""              => __('Normal','geodir-compare'),
                    "medium"        => __('Medium','geodir-compare'),
                    "large"         => __('Large','geodir-compare'),
                    "extra-large"   => __('Extra Large','geodir-compare'),
                ),
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => true
            ),
            'alignment'             => array(
                'type'              => 'select',
                'title'             => __('Alignment:','geodir-compare'),
                'desc'              => __('How the item should be positioned on the page.','geodir-compare'),
                'options'           =>  array(
                    ""              => __('None','geodir-compare'),
                    "left"          => __('Left','geodir-compare'),
                    "center"        => __('Center','geodir-compare'),
                    "right"         => __('Right','geodir-compare'),
                ),
                'default'           => '',
                'desc_tip'          => true,
                'advanced'          => false,
                'group'     => __("Positioning","geodirectory")
            ),
        );

	    if($design_style) {
		    $arguments['tooltip_text']  = array(
			    'type' => 'text',
			    'title' => __('Tooltip text', 'geodirectory'),
			    'desc' => __('Reveals some text on hover. Enter some text or use %%input%% to use the input value of the field or the field key for any other info %%email%%. (this can NOT be used with popover text)', 'geodirectory'),
			    'placeholder' => '',
			    'default' => '',
			    'desc_tip' => true,
			    'group'     => __("Hover Action","geodirectory")
		    );

		    $arguments['tooltip_text_show']  = array(
			    'type' => 'text',
			    'title' => __('Tooltip text', 'geodirectory'),
			    'desc' => __('Reveals some text on hover. Enter some text or use %%input%% to use the input value of the field or the field key for any other info %%email%%. (this can NOT be used with popover text)', 'geodirectory'),
			    'placeholder' => '',
			    'default' => '',
			    'desc_tip' => true,
			    'group'     => __("Hover Action","geodirectory")
		    );

		    $arguments['type'] = array(
			    'title' => __('Type', 'geodirectory'),
			    'desc' => __('Select the badge type.', 'geodirectory'),
			    'type' => 'select',
			    'options'   =>  array(
				    "" => __('Badge', 'geodirectory'),
				    "pill" => __('Pill', 'geodirectory'),
				    "button" => __('Button', 'geodirectory'),
			    ),
			    'default'  => '',
			    'desc_tip' => true,
			    'advanced' => false,
			    'group'     => __("Design","geodirectory")
		    );

		    $arguments['shadow'] = array(
			    'title' => __('Shadow', 'geodirectory'),
			    'desc' => __('Select the shadow badge type.', 'geodirectory'),
			    'type' => 'select',
			    'options'   =>  array(
				    "" => __('None', 'geodirectory'),
				    "small" => __('small', 'geodirectory'),
				    "medium" => __('medium', 'geodirectory'),
				    "large" => __('large', 'geodirectory'),
			    ),
			    'default'  => '',
			    'desc_tip' => true,
			    'advanced' => false,
			    'group'     => __("Design","geodirectory")
		    );

		    $arguments['color'] = array(
			    'title' => __('Color', 'geodirectory'),
			    'desc' => __('Select the the color.', 'geodirectory'),
			    'type' => 'select',
			    'options'   =>  array(
				                    "" => __('Custom colors', 'geodirectory'),
			                    )+geodir_aui_colors(true, true, true),
			    'default'  => '',
			    'desc_tip' => true,
			    'advanced' => false,
			    'group'     => __("Design","geodirectory")
		    );


		    $arguments['bg_color']  = array(
			    'type' => 'color',
			    'title' => __('Background color:', 'geodirectory'),
			    'desc' => __('Color for the background.', 'geodirectory'),
			    'placeholder' => '',
			    'default' => '#0073aa',
			    'desc_tip' => true,
			    'group'     => __("Design","geodirectory"),
			    'element_require' => $design_style ?  '[%color%]==""' : '',
		    );
		    $arguments['txt_color']  = array(
			    'type' => 'color',
//			'disable_alpha'=> true,
			    'title' => __('Text color:', 'geodirectory'),
			    'desc' => __('Color for the text.', 'geodirectory'),
			    'placeholder' => '',
			    'desc_tip' => true,
			    'default'  => '#ffffff',
			    'group'     => __("Design","geodirectory"),
			    'element_require' => $design_style ?  '[%color%]==""' : '',
		    );
		    $arguments['size']  = array(
			    'type' => 'select',
			    'title' => __('Badge size', 'geodirectory'),
			    'desc' => __('Size of the badge.', 'geodirectory'),
			    'options' =>  array(
				    "" => __('Default', 'geodirectory'),
				    "h6" => __('XS (badge)', 'geodirectory'),
				    "h5" => __('S (badge)', 'geodirectory'),
				    "h4" => __('M (badge)', 'geodirectory'),
				    "h3" => __('L (badge)', 'geodirectory'),
				    "h2" => __('XL (badge)', 'geodirectory'),
				    "h1" => __('XXL (badge)', 'geodirectory'),
				    "btn-lg" => __('L (button)', 'geodirectory'),
				    "btn-sm" => __('S (button)', 'geodirectory'),
			    ),
			    'default' => '',
			    'desc_tip' => true,
			    'group'     => __("Design","geodirectory"),
		    );


		    $arguments['mt']  = geodir_get_sd_margin_input('mt');
		    $arguments['mr']  = geodir_get_sd_margin_input('mr');
		    $arguments['mb']  = geodir_get_sd_margin_input('mb');
		    $arguments['ml']  = geodir_get_sd_margin_input('ml');
	    }

        return $arguments;
    }

    /**
     * Display Widget output.
     *
     * @since 1.0.0
     *
     * @param array $args Get Arguments.
     * @param array $widget_args Get widget arguments.
     * @param string $content Get widget content.
     * @return string
     *
     */
    public function output( $args = array(), $widget_args = array(),$content = '' ){
        global $gd_post;

        //Set current listing id
        $post_id = ! empty( $gd_post->ID ) ? absint( $gd_post->ID ) : '';

        //Button text
        $button = '';

        $defaults = array(
            'badge'             => __('Compare', 'geodir-compare'),
            'icon_class'        => 'fas fa-square',
            'badge_after'       => __('Compare', 'geodir-compare'),
            'icon_class_after'  => 'fas fa-check-square',
            'bg_color'          => '#0073aa',
            'txt_color'         => '#ffffff',
            'type'           => 'badge',
            'tooltip_text'  => __('Add to compare list', 'geodir-compare'),
            'tooltip_text_show'  => __('View comparison list', 'geodir-compare'),
//            'hover_content'  => '',
//            'hover_icon'  => '',
            'shadow'           => '',
            'color'           => '',
            'size'           => '',
            'position'           => '',
            'mt'    => '',
            'mb'    => '',
            'mr'    => '',
            'ml'    => '',
        );
        $args = shortcode_atts($defaults, $args, 'gd_compare_button' );

        // set some defaults
        if(empty($args['badge'])){$args['badge'] = $defaults['badge'];}
        if(empty($args['icon_class'])){$args['icon_class'] = $defaults['icon_class'];}
        if(empty($args['badge_after'])){$args['badge_after'] = $defaults['badge_after'];}
        if(empty($args['icon_class_after'])){$args['icon_class_after'] = $defaults['icon_class_after'];}
        if(empty($args['bg_color'])){$args['bg_color'] = $defaults['bg_color'];}
        if(empty($args['txt_color'])){$args['txt_color'] = $defaults['txt_color'];}
	    if(!$args['tooltip_text']){$args['tooltip_text'] = $defaults['tooltip_text'];}
	    if(!$args['tooltip_text_show']){$args['tooltip_text_show'] = $defaults['tooltip_text_show'];}

        //If this is a listings page, display the button
        if( $post_id || $this->is_preview() ){

	        // add required script
	        add_action( 'wp_footer', array($this,'script'), 200 );

            //Add custom css class
            $design_style = geodir_design_style();
            $args['css_class'] = $design_style ? 'geodir-compare-button c-pointer' : 'geodir-compare-button';
            
            //Ensure label is provided
            if( empty( $args['badge'] ) ) {
                $args['badge'] = __('Compare', 'geodir-compare');
            }

            //Onclick handler
            $post_type       = $gd_post->post_type;
            $args['onclick'] = "geodir_compare_add('$post_id', '$post_type');return false;";

            // make it act like a link
            $args['link'] = '#compare';

            //Extra attributes
            $compare_text  = !empty($args['badge'])                ? __( $args['badge'],'geodir-compare')          : $defaults['badge'];
            $compare_icon  = !empty($args['icon_class'])           ? esc_attr( $args['icon_class'])                : $defaults['icon_class'];
            $compared_text = !empty($args['badge_after'])          ? __( $args['badge_after'],'geodir-compare')    : $defaults['badge_after'];
            $compared_icon = !empty($args['icon_class_after'])     ? esc_attr( $args['icon_class_after'])          : $defaults['icon_class_after'];

            $args['extra_attributes']  = ' data-geodir-compare-text="'.$compare_text.'"';
            $args['extra_attributes'] .= ' data-geodir-compared-text="'.$compared_text.'"';
            $args['extra_attributes'] .= ' data-geodir-compare-icon="'.$compare_icon.'"';
            $args['extra_attributes'] .= ' data-geodir-compared-icon="'.$compared_icon.'"';
            $args['extra_attributes'] .= ' data-geodir-compare-post_type="'.$post_type.'"';
            $args['extra_attributes'] .= ' data-geodir-compare-post_id="'.$post_id.'"';

	        // tooltips
	        if( $design_style){

		        // margins
		        if ( !empty( $args['mt'] ) ) { $args['css_class'] .= " mt-".sanitize_html_class($args['mt'])." "; }
		        if ( !empty( $args['mr'] ) ) { $args['css_class'] .= " mr-".sanitize_html_class($args['mr'])." "; }
		        if ( !empty( $args['mb'] ) ) { $args['css_class'] .= " mb-".sanitize_html_class($args['mb'])." "; }
		        if ( !empty( $args['ml'] ) ) { $args['css_class'] .= " ml-".sanitize_html_class($args['ml'])." "; }

		        if(!empty($args['size'])){
			        switch ($args['size']) {
				        case 'small':
					        $args['size'] = $design_style ? '' : 'small';
					        break;
				        case 'medium':
					        $args['size'] = $design_style ? 'h4' : 'medium';
					        break;
				        case 'large':
					        $args['size'] = $design_style ? 'h2' : 'large';
					        break;
				        case 'extra-large':
					        $args['size'] = $design_style ? 'h1' : 'extra-large';
					        break;
				        case 'h6': $args['size'] = 'h6';break;
				        case 'h5': $args['size'] = 'h5';break;
				        case 'h4': $args['size'] = 'h4';break;
				        case 'h3': $args['size'] = 'h3';break;
				        case 'h2': $args['size'] = 'h2';break;
				        case 'h1': $args['size'] = 'h1';break;
				        case 'btn-lg': $args['size'] = ''; $args['css_class'] = 'btn-lg';break;
				        case 'btn-sm':$args['size'] = '';  $args['css_class'] = 'btn-sm';break;
				        default:
					        $args['size'] = '';

			        }

		        }



		        $args['extra_attributes'] .= ' data-toggle="tooltip" ';
		        $args['extra_attributes'] .= ' title="'.esc_attr($args['tooltip_text']).'" ';
		        $args['extra_attributes'] .= ' data-add-title="'.esc_attr($args['tooltip_text']).'" ';
		        $args['extra_attributes'] .= ' data-view-title="'.esc_attr($args['tooltip_text_show']).'" ';
	        }

            $button =  geodir_get_post_badge( $post_id, $args );
        }

        return $button;

    }


	/**
	 * Add the compare script if the widget is used.
	 */
	public function script(){

			?>
		<script>
//			GD_Compare = {};
			GD_Compare.loader = null;
			GD_Compare.addPopup = null;

			/**
			 * Fetches items from the server
			 *
			 *
			 * @param $items The items to fetch from the server
			 */
			function geodir_compare_fetch(data) {

				//Close any instance of the popup
				if ( GD_Compare.addPopup ) {
					GD_Compare.addPopup.close()
				}

				//Show loading screen
				GD_Compare.loader = aui_modal();

				//Fetch the items from the server
				jQuery.get(geodir_params.ajax_url, data)
					.done(function(html) {
						GD_Compare.addPopup = aui_modal('',html,'','','','modal-xl');
						// set the title
						setTimeout(function(){
							//do what you need here
						}, 50);
					})
					.fail(function() {
						GD_Compare.addPopup = aui_modal(' ',GD_Compare.ajax_error,'',true);
					})
					.always(function() {
						jQuery('.aui-modal').modal('hide')
					})
			}

			/**
			 * Adds an item to the comparison list
			 *
			 *
			 * @param $post_id The id of the listing to add to the comparison table
			 */
			function geodir_compare_add($post_id, $post_type) {
				if ($post_id) {

					var items = {},
						in_compare= false //True if this item is already in a comparison list

					//Are there any items saved in the localstorage?
					if (localStorage.GD_Compare_Items) {
						items = JSON.parse( localStorage.GD_Compare_Items )
					}

					if(! items[$post_type] ) {
						items[$post_type] = {}
					}

					//Ajax data
					var data = {
						action: 'geodir_compare_get_items',
						post_type: $post_type,
						added: $post_id
					}

					if( items[$post_type][$post_id] == 1 ) {
						data.exists = '1'
						in_compare = true
					} else {
						items[$post_type][$post_id] = 1
					}

					localStorage.GD_Compare_Items = JSON.stringify( items )
					data.items = localStorage.GD_Compare_Items

					//Only display a lightbox if the user clicks an item that is already in the comparison list
					if( in_compare ) {
						geodir_compare_fetch(data)
					}

				}

				//Update the buttons on the page
				geodir_compare_update_states($post_id);
			}

			/**
			 * Removes an item from the comparison list
			 *
			 *
			 * @param $post_id The id of the listing to remove from the comparison table
			 */
			function geodir_compare_remove($post_id, $post_type) {
				if ($post_id) {

					var items = {}
					//Are there any items saved in the localstorage?
					if (localStorage.GD_Compare_Items) {
						items = JSON.parse( localStorage.GD_Compare_Items )
					}

					//Are there any items saved in the localstorage?
					if (items[$post_type]) {
						delete items[$post_type][$post_id];
						localStorage.GD_Compare_Items = JSON.stringify( items )
					}

					var data = {
						removed: $post_id,
						action: 'geodir_compare_get_items',
						items: localStorage.GD_Compare_Items,
						post_type: $post_type
					}

					//Update the buttons on the page
					geodir_compare_update_states()

					geodir_compare_fetch(data)

				}
			}

			/**
			 * Removes an item from the comparison table and list
			 *
			 *
			 * @param $post_id The id of the listing to remove from the comparison table
			 */
			function geodir_compare_remove_from_table($post_id, $post_type) {
				if ($post_id) {

					var items = {},
						is_comparison_page = jQuery(".geodir-compare-page").length,
						listing_ids = []

					//Are there any items saved in the localstorage?
					if (localStorage.GD_Compare_Items) {
						items = JSON.parse( localStorage.GD_Compare_Items )
					}

					//is this saved in local storage
					if (items[$post_type] && items[$post_type][$post_id]) {
						delete items[$post_type][$post_id];
						listing_ids = Object.keys(items[$post_type])
						localStorage.GD_Compare_Items = JSON.stringify( items )
					}

					//Remove it from the table
					jQuery('.geodir-compare-' + $post_id).hide()

					//Trigger resize to recalculate image widths
					jQuery(window).trigger('resize')

					//Update the buttons on the page
					geodir_compare_update_states()

					//Change the window location
					var urlQueryString   = document.location.search,
						base_url = [location.protocol, '//', location.host, location.pathname].join(''),
						compareParams = 'compareids=' + listing_ids.join()
					urlQueryString = urlQueryString.replace( new RegExp( "\\bcompareids=[^&;]+[&;]?", "gi" ), compareParams );

					// remove any leftover crud
					urlQueryString = urlQueryString.replace( /[&;]$/, "" );

					//Reload the page, unless the shortcode items are hardcoded
					if( is_comparison_page && listing_ids.length ) {
						window.location = base_url + urlQueryString
					}

				}
			}

			//If we are on the comparison page and there is nothing to compare, e.g if the user visited the comparison page directly, redirect
			if( jQuery(".geodir-compare-page .geodir-compare-page-empty-list").length ) {

				var url   = window.location.href
				var items = {}

				//Are there any items saved in the localstorage?
				if (localStorage.GD_Compare_Items) {
					items = JSON.parse( localStorage.GD_Compare_Items )
				}

				//Ensure there are compare ids
				if(window.location.href.indexOf('compareids=') == -1 && Object.keys(items).length){
					var post_type    = Object.keys(items)[0],
						params 			 = 'compareids=' + Object.keys(items[post_type]).join(),
						base_url	     = [location.protocol, '//', location.host, location.pathname].join(''),
						urlQueryString   = document.location.search;

					// If the "search" string exists, then build params from it
					if (urlQueryString) {
						params = urlQueryString + '&' + params;
					} else {
						params = '?' + params
					}

					window.location = base_url + params
				}

			}

			/**
			 * Retrieves meta relating to a listing
			 *
			 *
			 * @param el The el of the compare button
			 */
			function geodir_compare_get_meta( el ){
				return {
					post_type : jQuery( el ).data('geodir-compare-post_type'),
					post_id : jQuery( el ).data('geodir-compare-post_id'),
					text  : jQuery( el ).data('geodir-compare-text'),
					icon  : jQuery( el ).data('geodir-compare-icon'),
					text2 : jQuery( el ).data('geodir-compared-text'),
					icon2 : jQuery( el ).data('geodir-compared-icon'),
					add_title : jQuery( el ).data('add-title'),
					view_title : jQuery( el ).data('view-title')
				}
			}

			/**
			 * Updates the states of the comparison button
			 *
			 *
			 */
			function geodir_compare_update_states($post_id){

				//Abort early if the comparison list is empty
				if (!localStorage.GD_Compare_Items) {
					return;
				}

				var items = JSON.parse( localStorage.GD_Compare_Items );

				//Loop through each button...
				jQuery('.geodir-compare-button').each( function() {
					var meta = geodir_compare_get_meta( this );

					//If this listing has already been added to local storage...
					if( items[meta.post_type] && items[meta.post_type][meta.post_id] ) {

						//Opacity
						jQuery( this ).css( 'opacity', '0.8');

						//Change the icon
						jQuery( this ).find('i').removeClass( meta.icon ).addClass( meta.icon2 );

						//Change the text
						jQuery( this ).find('.gd-secondary').text( meta.text2 );

						// change the title
						jQuery( this ).attr("data-original-title", meta.view_title).attr("title", meta.view_title);
					} else {

						//Opacity
						jQuery( this ).css( 'opacity', '1');

						//Change the icon
						jQuery( this ).find('i').removeClass( meta.icon2 ).addClass( meta.icon );

						//Change the text
						jQuery( this ).find('.gd-secondary').text( meta.text );

						// change the title
						jQuery( this ).attr("data-original-title", meta.add_title).attr("title", meta.add_title);

					}
				});

				// force the tooltip to show new value without mouseout
				if($post_id){
					jQuery('[data-geodir-compare-post_id~="'+$post_id+'"]').tooltip('show');
				}

			}

			//Update the buttons on the page
			geodir_compare_update_states();
			</script>
		<?php


	}
}