
<?php 
    get_header(); 
    $post_id = get_theID(); 
    echo get_favorites_button($post_id);
    get_footer(); 
?>