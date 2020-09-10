<?php

get_header(); 
echo "<h1>extra wishlist</h1>";
// Wish list id
$post_id = get_the_ID();
echo do_shortcode( "[wishlist_single id=$post_id]" );

get_footer();
?>