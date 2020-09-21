<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $wpdb, $cat_display, $post_cat;
?>
<div id="geodir-add-rating-div">
    <?php if ( empty( $rating['id'] ) ) { ?>
        <h2 class="gd-settings-title "><?php _e( 'Add Rating', 'geodir_reviewratings' ); ?></h2>
    <?php } else { ?>
        <h2 class="gd-settings-title "><?php echo __( 'Edit Rating:', 'geodir_reviewratings' ) . ' #' . $rating['id']; ?></h2>
    <?php } ?>
    <table class="form-table">
        <tbody>
        <?php
        $options = array(
            array(
                'name'       => __( 'Select multirating style', 'geodir_reviewratings' ),
                'id'         => 'geodir_rating_style_dl',
                'type'       => 'select',
                'class'      => 'geodir-select',
                'default'    => $rating['category_id'],
                'options'    => geodir_review_rating_style_dl(),
                'custom_attributes' => array(
                    'required' => 'required'
                ),
            ),
            array(
                'name' => __('Rating title', 'geodir_reviewratings'),
                'id' => 'rating_title',
                'type' => 'text',
                'default' => $rating['title'],
                'custom_attributes' => array(
                    'required' => 'required'
                ),
            ),
            array(
                'name'       => __( 'Showing method', 'geodir_reviewratings' ),
                'id'         => 'show_star',
                'type'       => 'radio',
                'options'    => array(
                    '1'      => __('Show Star', 'geodir_reviewratings'),
                    '0'      => __('Show Dropdown', 'geodir_reviewratings'),
                ),
                'default'    => $rating['check_text_rating_cond'] == "" || $rating['check_text_rating_cond'] == 1 ? 1 : 0,
                'custom_attributes' => array(
                    'required' => 'required'
                ),
            ),
            array(
                'type' => 'number',
                'id' => 'display_order',
                'name' => __( 'Display Order', 'geodir_reviewratings' ),
                'default' => ( isset( $rating['display_order'] ) ? absint( $rating['display_order'] ) : '' ),
                'custom_attributes' => array(
                    'min'     => '0',
                    'step'    => '1',
                )
            )
        );

        GeoDir_Admin_Settings::output_fields($options);

        ?>
        <tr valign="top" class="">
            <th scope="row" class="titledesc">
                <label for="geodir_rating_style_dl"><?php _e('Select post type', 'geodir_reviewratings'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <?php

                $rating_cat_id = isset($rating['id']) ? (int)$rating['id'] : '';
                if ($rating_cat_id) {
                    $sqlquery = $wpdb->prepare("SELECT * FROM ".GEODIR_REVIEWRATING_CATEGORY_TABLE." WHERE id = %d", array($rating_cat_id));
                    $qry_result = $wpdb->get_row($sqlquery);
                }

                $post_arr = array();
                if(isset($qry_result->post_type) && $qry_result->post_type!='')
                    $post_arr = explode(',',$qry_result->post_type);

                $geodir_post_types = geodir_get_option('post_types');
                $geodir_posttypes = geodir_get_posttypes();

                $i = 1;
                foreach ($geodir_posttypes as $p_type) {
                    $geodir_posttype_info = $geodir_post_types[$p_type];
                    $listing_slug = $geodir_posttype_info['labels']['singular_name'];
                    $checked = !empty($post_arr) && in_array($p_type, $post_arr) ? 1 : 0;
                    $display = !$checked ? 'display:none' : '';
                    ?>
                    <input type="checkbox" name="post_type<?php echo $i; ?>" id="_<?php echo $i; ?>" value="<?php echo $p_type;?>" class="rating_checkboxs" <?php checked($checked, 1)?> /><b>&nbsp;<?php echo geodir_ucwords($listing_slug);?>&nbsp;</b>
                    <?php
                    $cat_display = 'select';
                    $post_cat = isset($qry_result->category) ? $qry_result->category : '';
                    ?>
                    <select id="categories_type_<?php echo $i; ?>" name="categories_type_<?php echo $i; ?>[]"  multiple="multiple" style="<?php echo $display;?>">
                        <?php
                        if (class_exists('SitePress')) {
                            global $sitepress;
                            $sitepress->switch_lang('all', true);
                        }

                        echo geodir_custom_taxonomy_walker($p_type . 'category');

                        if (class_exists('SitePress')) {
                            global $sitepress;
                            $active_lang = ICL_LANGUAGE_CODE;
                            $sitepress->switch_lang($active_lang, true);
                        }

                        ?>
                    </select>
                    <?php
                    $i++;
                }
                ?>
                <input type="hidden" value="<?php echo $i -= 1; ?>" name="number_of_post" />
                </td>
        </tr>

        <tr valign="top">
            <td class="forminp" colspan="2">
            </td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="rating_id" id="geodir_rating_id" value="<?php echo $rating['id']; ?>" />
    <input type="hidden" name="security" id="geodir_save_rating_nonce" value="<?php echo wp_create_nonce( 'geodir-save-rating' ); ?>" />
    <?php submit_button( __( 'Save Rating', 'geodir_reviewratings' ), 'primary', 'save_rating' ); ?>
</div>