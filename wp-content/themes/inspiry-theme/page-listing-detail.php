<?php 
get_header(); 
    echo "<h1>hello</h1>"; 
        do_shortcode( '[gd_notifications]'); 
            do_shortcode( '[gd_post_images type="slider" ajax_load="true" slideshow="true" show_title="true" animation="slide" controlnav="1" ]'); 


        do_shortcode('[gd_single_taxonomies]');         
        do_shortcode('[gd_single_tabs]'); 
        do_shortcode('[gd_single_next_prev]'); 
       
get_footer();
?>