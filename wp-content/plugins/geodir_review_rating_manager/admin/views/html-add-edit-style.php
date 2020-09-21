<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div id="geodir-add-style-div">
    <?php if ( empty( $style['id'] ) ) { ?>
        <h2 class="gd-settings-title "><?php _e( 'Add Rating Style', 'geodir_reviewratings' ); ?></h2>
    <?php } else { ?>
        <h2 class="gd-settings-title "><?php echo __( 'Edit Rating Style:', 'geodir_reviewratings' ) . ' #' . $style['id']; ?></h2>
    <?php } ?>
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="multi_rating_category"><?php _e( 'Title', 'geodir_reviewratings' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <input name="multi_rating_category" id="multi_rating_category" value="<?php echo esc_attr( $style['name'] ); ?>" class="regular-text" type="text" required>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="style_count"><?php _e( 'Rating score (default 5)', 'geodir_reviewratings' ); ?></label>
            </th>
            <td class="forminp forminp-text">
                <?php
                $default_star_lables = GeoDir_Comments::rating_texts_default();
                $default_star_lables = array_values($default_star_lables);
                $star_lables = !empty($style['star_lables']) ? $style['star_lables'] : serialize($default_star_lables);
                $style_serialized = $star_lables != '' && is_serialized($star_lables) ? 1 : 0;
                ?>
                <input type="hidden" id="hidden-style-text" value='<?php echo $star_lables; ?>' />
                <input type="hidden" id="hidden-style-serialized" value="<?php echo $style_serialized; ?>" />
                <input name="style_count" id="style_count" value="<?php echo esc_attr( $style['star_number'] ); ?>" class="regular-text" type="number" min="3" max="10" onBlur="style_the_text_box()" required>
            </td>
        </tr>
        <?php
        $values = isset($style['star_lables']) ? $style['star_lables'] : '';
        $arr = array();
        $arr = geodir_reviewrating_star_lables_to_arr($values, 0, true);
        echo '</table><table id="style_texts" class="form-table">';
        if (count($arr) > 0) {
            $i = 1;
            foreach ($arr as $value) {
                $value = stripslashes($value);
                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="star_rating_text"><?php echo $i . ' ' .__( 'Star Text', 'geodir_reviewratings' ); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <input name="star_rating_text[]" value="<?php echo $value; ?>" class="regular-text" type="text" required>
                    </td>
                </tr>
                <?php
                $i++;
            }
        } else {
            for ($k = 1; $k <= 5; $k++) {
                ?>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="star_rating_text"><?php echo $k . ' ' .__( 'Star Text', 'geodir_reviewratings' ); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <input name="star_rating_text[]" value="" class="regular-text" type="text" required>
                    </td>
                </tr>
                <?php
            }
        }
        echo '</table><table class="form-table">';
        $options = array(
            array(
                'id' => 's_rating_type',
                'type' => 'select',
                'name' => __('Rating type', 'geodir_reviewratings'),
                'class' => 'geodir-select',
                'options' => array(
                    'font-awesome'  => __( 'Font Awesome', 'geodir_reviewratings' ),
                    'image'  => __( 'Transparent Image', 'geodir_reviewratings' ),
                ),
                'default' => !empty($style['s_rating_type']) ? $style['s_rating_type'] : 'font-awesome',
                'desc_tip' => true,
                'advanced' => true,
            ),
            array(
                'id'   => 's_rating_icon',
                'name' => __( 'Rating icon', 'geodir_reviewratings' ),
                'class' => 'geodir-select',
                'default' => 'fas fa-star',
                'value' => !empty($style['s_rating_icon']) ? $style['s_rating_icon'] : 'fas fa-star',
                'type' => 'font-awesome',
                'desc_tip' => true,
                'advanced' => true,
                'custom_attributes' => array(
                    'data-fa-icons' => true,
                    'data-fa-color' => !empty($style['star_color']) ? $style['star_color'] : '#ff9900',
                )
            ),
            array(
                'name' => __('Rating image', 'geodir_reviewratings'),
                'desc' => '',
                'id' => 's_file_off',
                'type' => 'image',
                'default' => $style['s_img_off'],
                'desc_tip' => true,
            ),
            array(
                'id'   => 'star_color',
                'name' => __( 'Rating color', 'geodir_reviewratings' ),
                'desc' => '',
                'value' => !empty($style['star_color']) ? $style['star_color'] : '#ff9900',
                'default' => '#ff9900',
                'type' => 'color',
            ),
            array(
                'id'   => 'star_color_off',
                'name' => __( 'Rating color off', 'geodir_reviewratings' ),
                'desc' => '',
                'value' => !empty($style['star_color_off']) ? $style['star_color_off'] : '#afafaf',
                'default' => '#afafaf',
                'type' => 'color',
            )
        );

        GeoDir_Admin_Settings::output_fields($options);
        ?>
        <tr valign="top">
            <td class="forminp" colspan="2">
            </td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="style_id" id="geodir_style_id" value="<?php echo $style['id']; ?>" />
    <input type="hidden" name="security" id="geodir_save_style_nonce" value="<?php echo wp_create_nonce( 'geodir-save-style' ); ?>" />
    <?php submit_button( __( 'Save Style', 'geodir_reviewratings' ), 'primary', 'save_style' ); ?>
</div>