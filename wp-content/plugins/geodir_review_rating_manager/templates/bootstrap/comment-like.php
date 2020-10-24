<?php
/**
 * Comment Like.
 *
 * @ver 1.0.0
 *
 * @var int $comment_id The comment id.
 * @var bool $login_alert If the login alert should show.
 * @var int $get_total_likes The total number of likes.
 * @var int $get_total_likes The total number of likes.
 * @var bool $has_liked If the current user has liked.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$script = false;
if ($login_alert && !get_current_user_id()) {
	$script = '<script type="text/javascript">alert("' . esc_attr(__('You must be logged-in to like a review comment.', 'geodir_reviewratings')) . '")</script>';
}

$like_action = $has_liked ? 'unlike' : 'like';
$like_class = '';
$get_total_likes = GeoDir_Review_Rating_Like_Unlike::format_like_count($get_total_likes);
$like_text = $has_liked ? __('Liked', 'geodir_reviewratings') : __('Like', 'geodir_reviewratings');
$like_text .= $get_total_likes ? ' <span class="badge badge-light">' .$get_total_likes.'</span>' : '';

$like_button = $has_liked ? '<i class="fas fa-check gdrr-btn-like"></i>' : '<i class="fa fa-thumbs-o-up gdrr-btn-like"></i>';

$like_class = $has_liked ? ' btn btn-primary' : ' btn btn-outline-primary';

$html = '<span class="geodir-comment-like">';
$html .= '<div class="comments_review_likeunlike' . $like_class . $like_class . ' d-inline-block c-pointer px-3" data-comment-id="' . $comment_id . '" data-like-action="' . $like_action . '" data-wpnonce="' . esc_attr(wp_create_nonce('gd-like-' . (int)$comment_id)) . '"><span class="like_count">' . $like_button .' '. $like_text . '</span>' . $script . '</div>';
$html .= '</span>';

echo $html;