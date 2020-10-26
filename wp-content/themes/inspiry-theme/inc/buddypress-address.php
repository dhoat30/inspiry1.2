<?php

//adding new page in buddypress

function bp_custom_user_nav_item2() {
    global $bp;

    
    //shiping address bigcommerce
    $args2 = array(
        "name" => __("Address", "buddypress"),
        "slug" => "addresses",
        "default_subnav_slug" => "addresses",
        "position" => 60,
        "show_for_displayed_user" => false,
        "screen_function" => "bp_custom_user_nav_item_screen2",
        "item_css_id" => "address"
);
 
    bp_core_new_nav_item( $args2 );
 }
 add_action( "bp_setup_nav", "bp_custom_user_nav_item2", 99 );
 
 
 
 function bp_custom_user_nav_item_screen2() {
    add_action( "bp_template_content", "bp_custom_address_content" );


    bp_core_load_template( apply_filters( "bp_core_template_plugin", "members/single/plugins" ) );
 }

 //address template
 function bp_custom_address_content(){ 
     ?>
<?php echo do_shortcode('[bigcommerce_shipping_address_list]');?>

     <?php
 }



 ?>