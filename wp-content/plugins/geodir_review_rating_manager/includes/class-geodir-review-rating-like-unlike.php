<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDirectory GeoDir_Review_Rating_Like_Unlike.
 *
 * Class for templates.
 *
 * @class    GeoDir_Review_Rating_Like_Unlike
 * @category Class
 * @author   AyeCode
 */
class GeoDir_Review_Rating_Like_Unlike{

    public function __construct(){

    }

    /**
     * Adds CSS and JS for comments like / unlike
     *
     * @since 2.0.0
     *
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @global object $wpdb WordPress Database object.
     *
     * @param int $comment_id Comment ID.
     * @param bool $echo Print HTML? Default true.
     * @param bool $login_alert Whether to check and alert the user to login? Default false.
     * @return void|string HTML.
     */
    public static function geodir_reviewrating_comments_like_unlike($comment_id, $echo = true, $login_alert = false) {
        $has_liked = self::geodir_reviewrating_has_comment_liked($comment_id);
        $get_total_likes = self::geodir_reviewrating_get_total_liked($comment_id);

        $script = false;
        if ($login_alert && !get_current_user_id()) {
            $script = '<script type="text/javascript">alert("' . esc_attr(__('You must be logged-in to like a review comment.', 'geodir_reviewratings')) . '")</script>';
        }

        $like_action = 'like';
        $like_class = '';
        $like_text = wp_sprintf(__('%d people like this.', 'geodir_reviewratings'), $get_total_likes);
        if ($has_liked) {
            $like_action = 'unlike';
            $like_class = ' gdrr-liked';
            if ($get_total_likes > 1) {
                $like_text = $get_total_likes == 2 ? __('You and 1 other like this.', 'geodir_reviewratings') : wp_sprintf(__('You and %d others like this.', 'geodir_reviewratings'), ($get_total_likes - 1));
            } else {
                $like_text = __('You like this.', 'geodir_reviewratings');
            }
        }

        $like_button = '<i class="fa fa-thumbs-o-up gdrr-btn-like"></i>';
        /**
         * Filter the like/useful button html.
         *
         * @since 1.2.8
         * @package GeoDirectory_Review_Rating_Manager
         *
         * @param string $like_button Like/useful button content.
         * @param string $like_action Action to handle on button click (like or unlike).
         * @param bool $has_liked True if current user has liked this, otherwise false.
         */
        $like_button = apply_filters('geodir_reviewrating_like_unlike_button', $like_button, $like_action, $has_liked);

        $html = '<div class="comments_review_likeunlike' . $like_class . '" data-comment-id="' . $comment_id . '" data-like-action="' . $like_action . '" data-wpnonce="' . esc_attr(wp_create_nonce('gd-like-' . (int)$comment_id)) . '"><span class="like_count">' . $like_button . $like_text . '</span>' . $script . '</div>';

        if ($echo)
            echo $html;
        else
            return $html;
    }

    /**
     * Check the comment is liked or not.
     *
     * @since 1.2.8
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @global object $wpdb WordPress Database object.
     *
     * @param int $comment_id The comment ID.
     * @return bool True if liked. Otherwise false.
     */
    public static function geodir_reviewrating_has_comment_liked($comment_id) {
        global $wpdb;

        if (!(int)$comment_id > 0) {
            return false;
        }

        $user_id = get_current_user_id();
        if (!$user_id > 0) {
            return false;
        }

        $query = $wpdb->prepare("SELECT COUNT(like_id) FROM `" . GEODIR_COMMENTS_REVIEWS_TABLE . "` WHERE comment_id = %d AND user_id = %d", array($comment_id, $user_id));
        $liked = $wpdb->get_var($query);

        if ($liked) {
            return true;
        }

        return false;
    }

    /**
     * Rating manager save comment like and dislike.
     *
     * @since 2.0.0
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @param int $comment_id The Comment ID.
     * @param string $like_action Like or dislike action.
     */
    public static function geodir_reviewrating_save_like_unlike($comment_id, $like_action) {
        if ($comment_id > 0 && ($like_action == 'like' || $like_action == 'unlike')) {
            $return = self::geodir_reviewrating_handle_like_unlike($comment_id, $like_action);
        }
        self::geodir_reviewrating_comments_like_unlike($comment_id, true, true);
        exit;
    }

    /**
     * Handle and save like/unlike value.
     *
     * @since 2.0.0
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @global object $wpdb WordPress Database object.
     *
     * @param int $comment_id The comment ID.
     * @param string $action The comment usefull action (like or unlike). Default 'like'.
     * @return bool True on success. False on fail.
     */
    public static function geodir_reviewrating_handle_like_unlike($comment_id, $action = 'like') {
        global $wpdb;

        if (!(int)$comment_id > 0) {
            return false;
        }

        $user_id = get_current_user_id();
        if (!$user_id > 0) {
            return false;
        }
        $has_liked = self::geodir_reviewrating_has_comment_liked($comment_id);

        if ($action == 'like') {
            if (!$has_liked) {
                $ip = geodir_get_ip();
                $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
                $like_date = date_i18n('Y-m-d H:i:s', current_time('timestamp'));

                $query = $wpdb->prepare("INSERT INTO `" . GEODIR_COMMENTS_REVIEWS_TABLE . "` SET comment_id = %d, ip = %s, user_id = %d, like_unlike = %d, user_agent = %s, like_date = %s", array($comment_id, $ip, $user_id, 1, $user_agent, $like_date));

                if ($wpdb->query($query)) {
                    $like_id = $wpdb->insert_id;

                    $query = $wpdb->prepare("UPDATE `" . GEODIR_REVIEWRATING_POSTREVIEW_TABLE . "` SET wasthis_review = wasthis_review + 1 WHERE comment_id = %d", array($comment_id));
                    $wpdb->query($query);

                    do_action('geodir_reviewrating_comment_liked', $like_id);

                    return true;
                }
            }
        } else {
            if ($has_liked) {
                $query = $wpdb->prepare("DELETE FROM `" . GEODIR_COMMENTS_REVIEWS_TABLE . "` WHERE comment_id = %d AND user_id = %d", array($comment_id, $user_id));

                if ($wpdb->query($query)) {
                    $query = $wpdb->prepare("UPDATE `" . GEODIR_REVIEWRATING_POSTREVIEW_TABLE . "` SET wasthis_review = wasthis_review - 1 WHERE comment_id = %d AND wasthis_review > 0", array($comment_id));
                    $wpdb->query($query);

                    do_action('geodir_reviewrating_comment_unliked', $comment_id, $user_id);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the total number of likes for comment.
     *
     * @since 2.0.0
     * @package GeoDirectory_Review_Rating_Manager
     *
     * @global object $wpdb WordPress Database object.
     *
     * @param int $comment_id The comment ID.
     * @return int Total likes for comment.
     */
    public static function geodir_reviewrating_get_total_liked($comment_id) {
        global $wpdb;

        $liked = 0;

        if (!(int)$comment_id > 0) {
            return $liked;
        }

        $query = $wpdb->prepare("SELECT wasthis_review FROM `" . GEODIR_REVIEWRATING_POSTREVIEW_TABLE . "` WHERE comment_id = %d", array($comment_id));
        $liked = (int)$wpdb->get_var($query);

        return $liked;
    }

}

new GeoDir_Review_Rating_Like_Unlike();