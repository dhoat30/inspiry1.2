<?php

//adding new page in buddypress

function bp_custom_user_nav_item_wishlist() {
    global $bp;

    
    //shiping address bigcommerce
    $args2 = array(
        "name" => __("Wishlist", "buddypress"),
        "slug" => "wishlist",
        "default_subnav_slug" => "wishlist",
        "position" => 60,
        "show_for_displayed_user" => false,
        "screen_function" => "wishlist_content",
        "item_css_id" => "address"
);
 
    bp_core_new_nav_item( $args2 );
 }
 add_action( "bp_setup_nav", "bp_custom_user_nav_item_wishlist", 99 );
 
 
 
 function wishlist_content() {
    add_action( "bp_template_content", "bp_custom_wishlist_content" );


    bp_core_load_template( apply_filters( "bp_core_template_plugin", "members/single/plugins" ) );
 }

 //wishlist template
 function bp_custom_wishlist_content(){ 
     ?>
<?php echo do_shortcode('[bigcommerce_wish_lists]');?>

     <?php
 }



 ?>