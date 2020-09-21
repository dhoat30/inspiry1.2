<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDirectory GeoDir_Review_Rating_Template.
 *
 * Class for templates.
 *
 * @class    GeoDir_Review_Rating_Template
 * @category Class
 * @author   AyeCode
 */
class GeoDir_Review_Rating_Template{

    public function __construct(){

    }

    /**
     * Adds 'Individually rated for' ratings to the detail page.
     *
     * @since 2.0.0
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @global object $wpdb WordPress Database object.
     * @global object $post The current post object.
     *
     * @param string|array $ratings Individual rating array.
     * @return string Rating HTML.
     */
    public static function geodir_reviewrating_draw_ratings($ratings = '') {
        global $wpdb, $post, $gd_post;

        $post_id = isset($gd_post->ID) ? $gd_post->ID : '';
        if (!empty($post_id) && geodir_cpt_has_rating_disabled((int)$post_id)) {
            return NULL;
        }
        $rating_ids = array(0);
        $format = '%d';
        if (!empty($ratings)) {
            if (array_key_exists('overall',$ratings))
                unset($ratings['overall']);

            if (!empty($ratings))
                $rating_ids = array_keys($ratings);

            $rating_ids_length = count($rating_ids);
            if ($rating_ids_length > 0) {
                $rating_ids_format = array_fill(0, $rating_ids_length, '%d');
                $format = implode(',', $rating_ids_format);
            }

            $styles = $wpdb->get_results($wpdb->prepare("SELECT rt.id as id,
                                    rt.title as title,
                                    rt.post_type as post_type,
                                    rt.category as category,
                                    rt.check_text_rating_cond as check_text_rating_cond,
                                    rt.display_order,
                                    rs.s_rating_type  as s_rating_type,
                                    rs.s_rating_icon  as s_rating_icon,
                                    rs.s_img_off  as s_img_off,
                                    rs.s_img_width as s_img_width,
                                    rs.s_img_height as s_img_height,
                                    rs.star_color as star_color,
                                    rs.star_color_off as star_color_off,
                                    rs.star_lables as star_lables,
                                    rs.star_number as star_number 
                                    FROM " . GEODIR_REVIEWRATING_CATEGORY_TABLE . " rt," . GEODIR_REVIEWRATING_STYLE_TABLE . " rs
                                    WHERE rt.category_id= rs.id and rt.id IN($format) ORDER BY rt.display_order ASC, rt.id", $rating_ids));

            $rating_style = array();

            $_ratings = array();
            foreach($styles as $style){
                $rating_style[$style->id] = $style;

                if ( isset( $ratings[ $style->id ] ) ) {
                    $_ratings[ $style->id ] = $ratings[ $style->id ];
                }
            }
            $ratings = $_ratings;
        }

        $rating_html = '';

        if (!empty($ratings) && !empty($rating_style)) :
            $rating_html .= '<div class="gd_ratings_module_box">
            <!-- <h4>' . __('Individually rated for:', 'geodir_reviewratings') . '</h4> -->
            <div class="gd-rating-box-in clearfix">
                <div class="gd-rating-box-in-right">
                    <div class="gd-rate-category clearfix">';
            foreach ( $ratings as $id => $rating ) {
                if ( isset( $rating_style[$id] ) ) {
                    $rating_style_category = isset( $rating_style[$id]->category ) ? $rating_style[$id]->category : '';
                    $rating_cat = explode( ",", trim( ",", $rating_style_category ) );

                    $post_cat = array();
                    $post_categories = isset( $gd_post->categories ) ? $gd_post->categories : '';
                    $post_cat  = explode( ",", trim( ",", $post_categories ) );
                    $showing_cat = array_intersect( $rating_cat, $post_cat );

                    if ( !empty( $showing_cat ) ) {
                        $title = isset($rating_style[$id]->title) ? __( stripslashes_deep( $rating_style[$id]->title ), 'geodirectory') : '';
                        $rating_style_star_lables = isset($rating_style[$id]->star_lables) ? $rating_style[$id]->star_lables : '';
                        $star_lable = geodir_reviewrating_star_lables_to_arr( $rating_style_star_lables, $rating_style[$id]->star_number, true );

                        if(is_array($rating)){
                            $rating = $rating['c'] > 0 ? $rating['r']/$rating['c'] : 0;
                        }

                        $rating_html .=	'<div class="clearfix gd-rate-cat-in">';

                        $overrides = array(
                            'rating_icon' => esc_attr( $rating_style[$id]->s_rating_icon ),
                            'rating_color' => esc_attr( $rating_style[$id]->star_color ),
                            'rating_color_off' => esc_attr( $rating_style[$id]->star_color_off ),
                            'rating_label' => __( stripslashes_deep( $title ), 'geodirectory'),
                            'rating_texts' => $star_lable,
                            'rating_image' => $rating_style[$id]->s_img_off,
                            'rating_type' => esc_attr( $rating_style[$id]->s_rating_type ),
                            'rating_input_count' => $rating_style[$id]->star_number,
                            'id' => "geodir_rating[".$rating_style[$id]->id."]",
                            'type' => 'output',
                        );

                        $rating_html .= GeoDir_Comments::rating_html($rating, 'output', $overrides);

                        $rating_html .= '</div>';
                    }
                }
            }
            $rating_html .= '</div>
                </div>
            </div>
        </div>';

        endif;

        return apply_filters('geodir_reviewrating_draw_ratings_html',$rating_html, $ratings);
    }

    /**
     * Display reviews in GD settings 'reviews' tab.
     *
     * @since 1.0.0
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @param array $geodir_reviews Reviews array.
     */
    public static function geodir_reviewrating_show_comments($geodir_reviews=array()){

        echo '<ul class="reviews-fields">';

        if(!empty($geodir_reviews)){

            foreach($geodir_reviews as $comment){
                ?>
                <li id="comment-<?php echo $comment->comment_ID; ?>" >
                    <div class="clearfix">
                        <div class="comment-info">
                            <div class="clearfix">
                                <form>
                                    <input name="chk-action[]" type="checkbox" value="<?php echo $comment->comment_ID; ?>" />
                                </form>
                                <div class="post-info">
                                    <h2 class="comment-post-title"><?php echo get_the_title($comment->post_id); ?></h2>

                                    <?php
                                    $comment_ratings = geodir_reviewrating_get_comment_rating_by_id($comment->comment_ID);
                                    $overall_html = geodir_reviewrating_draw_overall_rating($comment_ratings->rating);
                                    echo 	'<span>'.$overall_html.'</span>';
                                    ?>

                                    <p><?php echo wpautop(stripslashes($comment->comment_content)); ?></p>
                                    <div class="post-action clearfix">

                                        <?php
                                        if($comment->comment_approved == '0')
                                        {
                                            ?>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="approvecomment"><a href="javascript:void(0);"><?php _e('Approve', 'geodir_reviewratings');?></a></span>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="spamcomment"><a href="javascript:void(0);"><?php _e('Spam', 'geodir_reviewratings');?></a></span>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="trashcomment"><a href="javascript:void(0);"><?php _e('Trash', 'geodir_reviewratings');?></a></span>
                                            <?php
                                        }elseif($comment->comment_approved == '1')
                                        {
                                            ?>

                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="unapprovecomment"><a href="javascript:void(0);"><?php _e('Unapprove', 'geodir_reviewratings');?></a></span>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="spamcomment"><a href="javascript:void(0);"><?php _e('Spam', 'geodir_reviewratings');?></a></span>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="trashcomment"><a href="javascript:void(0);"><?php _e('Trash', 'geodir_reviewratings');?></a></span>
                                            <?php
                                        }elseif($comment->comment_approved == 'spam')
                                        {
                                            ?>

                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="unspamcomment"><a href="javascript:void(0);"><?php _e('Not Spam', 'geodir_reviewratings');?></a></span>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="deletecomment"><a href="javascript:void(0);"><?php _e('Delete Permanently', 'geodir_reviewratings');?></a></span>
                                            <?php
                                        }elseif($comment->comment_approved == 'trash')
                                        {
                                            ?>

                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="untrashcomment"><a href="javascript:void(0);"><?php _e('Restore', 'geodir_reviewratings');?></a></span>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="deletecomment"><a href="javascript:void(0);"><?php _e('Delete Permanently', 'geodir_reviewratings');?></a></span>
                                            <?php
                                        }?>

                                        <?php

                                        $multirating_over_all = unserialize (($comment->ratings));

                                        if(is_array($multirating_over_all) && array_filter($multirating_over_all)) { /* if all values not empty */  ?>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="ratingshowhide" ><a href="javascript:void(0);"><?php _e('Show Multi Ratings', 'geodir_reviewratings');?></a></span>
                                        <?php }	?>

                                        <?php
                                        if($comment->attachments != '')
                                        {
                                            ?>
                                            <span data-comment-id="<?php echo $comment->comment_ID; ?>" action="commentimages" ><a href="javascript:void(0);"><?php _e('Show Images', 'geodir_reviewratings');?></a></span>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="author-info">
                            <div class="clearfix">
                                <div class="avtar-img">
                                    <?php echo get_avatar( $comment->user_id, 60); ?>
                                </div>
                                <div class="author-name">
                                    <?php echo $comment->comment_author; ?>
                                    <span><?php echo $comment->comment_author_email; ?></span>
                                    <span><?php if(isset($comment->comment_author_IP)){echo $comment->comment_author_IP;} ?></span>
                                </div>
                            </div>
                            <span class="time"><?php _e('Submitted on:', 'geodir_reviewratings');?>
                                <?php if(!function_exists('how_long_ago')){echo get_comment_date('M d, Y',$comment->comment_ID); } else { echo get_comment_time('M d, Y'); } ?>
                        </span>
                        </div>

                    </div>
                    <?php

                    $ratings = @unserialize($comment_ratings->ratings);

                    $ratings_html = GeoDir_Review_Rating_Template::geodir_reviewrating_draw_ratings($ratings);

                    echo '<div class="edit-form-comment-rating" style="display:none;">'.$ratings_html.'</div>';
                    ?>

                    <div class="edit-form-comment-images" style="display:none;">

                        <?php
                        if($comment->attachments != '')
                        {
                            $comment_data = self::geodir_reviewrating_get_comment_images($comment->comment_id,$comment->comment_post_ID);
                            echo $comment_data->html;

                        }
                        ?>
                    </div>
                </li>
            <?php }

        }
        echo '</ul>';
    }

    /**
     * Returns comment images using comment ID.
     *
     * @since 2.0.0
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @global object $wpdb WordPress Database object.
     *
     * @param $comment_id
     * @return object
     */
    public static function geodir_reviewrating_get_comment_images( $comment_id, $post_id ) {
        global $wpdb;
        $comment_imgs = array();
        $comment_img_html = '';

        // we get all comment images for the post as this will be temp cached and reduce DB queries.
        $all_comment_images = GeoDir_Media::get_attachments_by_type($post_id,'comment_images');

        if(!empty($all_comment_images)){
            foreach($all_comment_images as $comment_image){
                if($comment_id == $comment_image->other_id){
                    $comment_imgs[] = $comment_image;
                }
            }
        }

        if ( !empty( $comment_imgs ) ) {
            ob_start();
            ?>
            <div id="place-gallery-<?php echo $comment_id; ?>" class="place-gallery geodir-image-container">
                <div class="clearfix reviews_rating_images_all_images">
                    <ul class="geodir-gallery geodir-images clearfix">
                        <?php
                        $image_size = 'medium';
                        $link_tag_open = "<a href='%s' class='geodir-lightbox-image' data-lity>";
                        $link_tag_close = "<i class=\"fas fa-search-plus\" aria-hidden=\"true\"></i></a>";
                            foreach( $comment_imgs as $image ) {
                                    echo '<li>';
                                    $img_tag = geodir_get_image_tag($image,$image_size );
                                    $meta = isset($image->metadata) ? maybe_unserialize($image->metadata) : '';
                                    $img_tag =  wp_image_add_srcset_and_sizes( $img_tag, $meta , 0 );

                                    // image link
                                    $link = geodir_get_image_src($image, 'large');

                                    // ajaxify images
                                    $img_tag = geodir_image_tag_ajaxify($img_tag);

                                    // output image
                                    echo $link_tag_open ? sprintf($link_tag_open,esc_url($link)) : '';
                                    echo $img_tag;
                                    echo $link_tag_close;

                                    echo '</li>';
                                
                            }

                        ?>
                    </ul>
                </div>
            </div>
            <?php
            $comment_img_html =	ob_get_clean();
        }

        return (object)array( 'images' => $comment_imgs, 'html' => $comment_img_html );
    }

    /**
     * Adds comment image upload form field to the detail page.
     *
     * @since 2.0.0
     * @package GeoDirectory_Review_Rating_Manager
     */
    public static function geodir_reviewrating_rating_img_html($files = ''){

        $image_limit = absint(geodir_get_option('rr_image_limit'));
        if(!$image_limit){
            $image_limit = 10;
        }
        $total_files = 0;
        $allowed_file_types = array( 'jpg','jpe','jpeg','gif','png','bmp','ico');
        $display_file_types = $allowed_file_types != '' ? '.' . implode(", .", $allowed_file_types) : '';
        if(!empty($allowed_file_types)){$allowed_file_types = implode(",",$allowed_file_types);}

        $id = "comment_images";
        $multiple = true;

        // the file upload template
        echo geodir_get_template_html( "file-upload.php", array(
            'id'                  => $id,
            'is_required'         => false,
            'files'	              => $files,
            'image_limit'         => $image_limit,
            'total_files'         => $total_files,
            'allowed_file_types'  => $allowed_file_types,
            'display_file_types'  => $display_file_types,
            'multiple'            => $multiple,
        ) );

    }

    /**
     * Adds comment multi rating form fields to the detail page.
     *
     * @since 1.0.0
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @global object $post The current post object.
     */
    public static function geodir_reviewrating_rating_frm_html(){
        global $post, $gd_post;

        $post_arr = (array)$gd_post;
        if(isset($post_arr['post_category']))
            $post_categories = explode(",",$post_arr['post_category']);
        else
            $post_categories = wp_get_post_categories( $post->ID, array( 'fields' => 'ids') );

        $ratings = geodir_reviewrating_rating_categories();
        if ($ratings) {
            $rating_style_html = '';
            foreach($ratings as $rating){
                if (!in_array($post->post_type, explode(",", $rating->post_type))) {
                    continue;
                }// if not for this post type then skip.
                $rating->title = isset( $rating->title ) && $rating->title != '' ? __( stripslashes_deep( $rating->title ), 'geodirectory' ) : '';
                $star_lable = geodir_reviewrating_star_lables_to_arr( $rating->star_lables, $rating->star_number, true );
                $rating_cat = explode(",",$rating->category);

                // fix id's if wpml is active
                if ( geodir_wpml_is_taxonomy_translated( $post->post_type . 'category' ) ) {
                    if (is_array($rating_cat)) {
                        foreach ($rating_cat as $key => $std_cat) {
                            $rating_cat[$key] = geodir_wpml_object_id($std_cat, $post->post_type.'category', false);
                        }
                    }
                }

                $showing_cat = array_intersect($rating_cat,$post_categories);
                if(!empty($showing_cat)){
                    if($rating->check_text_rating_cond){
                        $overrides = array(
                            'rating_icon' => esc_attr( $rating->s_rating_icon ),
                            'rating_color' => esc_attr( $rating->star_color ),
                            'rating_color_off' => esc_attr( $rating->star_color_off ),
                            'rating_label' => __( stripslashes_deep( $rating->title ), 'geodirectory'),
                            'rating_texts' => $star_lable,
                            'rating_image' => $rating->s_img_off,
                            'rating_type' => esc_attr( $rating->s_rating_type ),
                            'rating_input_count' => $rating->star_number,
                            'id' => "geodir_rating[".$rating->id."]",
                            'type' => 'input',
                        );

                        $rating_style_html .=  GeoDir_Comments::rating_html(0, 'input', $overrides);

                    }else{

                        $rating_style_html .= '<div class="clearfix gd-rate-cat-in">';
                        $rating_style_html .= '<span class="gd-rating-label">'.stripslashes_deep($rating->title).'</span>';
                        $rating_style_html .= '<select name="geodir_rating['.$rating->id.']" > ';
                        for($star=1; $star <= $rating->star_number; $star++){
                            $star_lable_text = isset( $star_lable[$star] ) ? esc_attr( $star_lable[$star] ) : '';
                            $star_lable_text = stripslashes_deep( $star_lable_text );
                            $rating_style_html .= '<option value="'.$star.'">';
                            $rating_style_html .= $star_lable_text;
                            $rating_style_html .= '</option>	';
                        }
                            $rating_style_html .= '</select>';
                        $rating_style_html .= '</div>';

                    }

                }

            }

            if($rating_style_html != ''){
                echo "<div class='gd-extra-ratings'>".$rating_style_html."</div>";
            }
        }
    }

    /**
     * Get image name from image src.
     *
     * @since 2.0.0
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @param string $img_src Image url.
     * @return mixed|string Image name.
     */
    public static function geodir_reviewrating_get_image_name( $img_src ) {
        $comm_img_title = '';
        if ( $img_src != '' ) {
            $comm_img_str = basename( $img_src );
            if ( $comm_img_str != '' ) {
                if ( strpos( $comm_img_str, '.' ) !== false ) {
                    $comm_img_arr = explode( '.', $comm_img_str );
                    if ( !empty( $comm_img_arr ) ) {
                        unset( $comm_img_arr[( count( $comm_img_arr ) - 1 )] );
                        if ( !empty( $comm_img_arr ) ) {
                            $comm_img_str = implode( ".", $comm_img_arr );
                        }
                    }
                }

                if ( strpos( $comm_img_str, '_' ) !== false ) {
                    $comm_img_arr = explode( '_', $comm_img_str );
                    if ( !empty( $comm_img_arr ) ) {
                        unset( $comm_img_arr[( count( $comm_img_arr ) - 1 )] );
                        if ( !empty( $comm_img_arr ) ) {
                            $comm_img_str = implode( "_", $comm_img_arr );
                        }
                    }
                }

                $comm_img_title = preg_replace( '/[_-]/', ' ', $comm_img_str );
            }
        }
        return $comm_img_title;
    }

}

new GeoDir_Review_Rating_Template();