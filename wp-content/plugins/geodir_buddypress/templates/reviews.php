<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to twentytwelve_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
?>
<div id="comments" class="geodir-comments-area gdbp-comments-area">

  <?php if ( have_comments() ) : ?>
  <ul class="commentlist">
    <?php
    $callback = apply_filters('geodir_buddypress_comment_callback', array('GeoDir_BuddyPress_Template', 'geodir_buddypress_comment'));
    wp_list_comments( array( 'callback' => $callback, 'style' => 'ol' ) ); ?>
  </ul>

  <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
      <nav id="comment-nav-below" class="navigation" role="navigation">
          <h1 class="assistive-text section-heading"><?php _e('Review navigation', 'geodir_buddypress'); ?></h1>

          <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Reviews', 'geodir_buddypress')); ?></div>
          <div class="nav-next"><?php next_comments_link(__('Newer Reviews &rarr;', 'geodir_buddypress')); ?></div>
      </nav>
  <?php endif; // check for comment navigation ?>

  <?php
  /* If there are no comments and comments are closed, let's leave a note.
   * But we only want the note on posts and pages that had comments in the first place.
   */
  if (!comments_open() && get_comments_number()) : ?>
      <p class="nocomments"><?php _e('Reviews are closed.', 'geodir_buddypress'); ?></p>
  <?php endif; ?>

  <?php endif; // have_comments() ?>
</div>
<!-- #comments .comments-area -->
