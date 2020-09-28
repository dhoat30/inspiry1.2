<?php 
get_header(); 
while(have_posts()){ 
    the_post(); 
    the_title(); 
    the_content(); 
   // Wish list id
$post_id = get_the_ID();
echo do_shortcode( "[wishlist_single id=$post_id]" );
}

get_footer(); 
?>

