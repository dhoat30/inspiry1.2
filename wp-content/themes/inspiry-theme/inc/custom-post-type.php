<?php 
//custom post register

//custom post register


add_post_type_support( "sliders", "thumbnail" ); 

add_post_type_support( "loving", "thumbnail" ); 
add_post_type_support( "blogs", "thumbnail" );
add_post_type_support( "shop-my-fav", "thumbnail" );
add_post_type_support( "shop_by_brand", "thumbnail" );

function register_custom_type2(){ 

   //sliders psot type
   register_post_type("sliders", array(
      "supports" => array("title", "page-attributes", 'editor'), 
      "public" => true, 
      "show_ui" => true, 
      "hierarchical" => true,
      "labels" => array(
         "name" => "Sliders", 
         "add_new_item" => "Add New Slider", 
         "edit_item" => "Edit Slider", 
         "all_items" => "All Sliders", 
         "singular_name" => "Slider"
      ), 
      "menu_icon" => "dashicons-slides",
      'taxonomies'          => array('category')
   )
   ); 

   //loving post type
   register_post_type("loving", array(
      "supports" => array("title", "page-attributes", 'editor'), 
      "public" => true, 
      "show_ui" => true, 
      "hierarchical" => true,
      "labels" => array(
         "name" => "Lovings", 
         "add_new_item" => "Add New Loving", 
         "edit_item" => "Edit Loving", 
         "all_items" => "All Lovings", 
         "singular_name" => "Loving"
      ), 
      "menu_icon" => "dashicons-welcome-widgets-menus",
      'taxonomies'          => array('category')
   )
   );

   //blogs post type
   register_post_type("blogs", array(
      'show_in_rest' => true,
      "supports" => array("title", "page-attributes", 'editor'), 
      "public" => true, 
      "show_ui" => true, 
      "hierarchical" => true,
      "labels" => array(
         "name" => "Blogs", 
         "add_new_item" => "Add New Blog", 
         "edit_item" => "Edit Blog", 
         "all_items" => "All Blogs", 
         "singular_name" => "Blog"
      ), 
      "menu_icon" => "dashicons-welcome-write-blog",
      'taxonomies'          => array('category')
   )
   );

   //loving post type
   register_post_type("shop-my-fav", array(
      "supports" => array("title", "page-attributes"), 
      "public" => true, 
      "show_ui" => true, 
      "hierarchical" => true,
      "labels" => array(
         "name" => "Shop My Favs", 
         "add_new_item" => "Add New Shop My Fav", 
         "edit_item" => "Edit Shop My Fav", 
         "all_items" => "All Shop My Favs", 
         "singular_name" => "Shop My Fav"
      ), 
      "menu_icon" => "dashicons-welcome-write-blog"
   )
   );
   
   //shop by brand page post type
   register_post_type("shop_by_brand", array(
      "supports" => array("title", "page-attributes"), 
      "public" => true, 
      "show_ui" => true, 
      "hierarchical" => true,
      "labels" => array(
         "name" => "Brands", 
         "add_new_item" => "Add New Brand", 
         "edit_item" => "Edit Brand", 
         "all_items" => "All Brands", 
         "singular_name" => "Brand"
      ), 
      "menu_icon" => "dashicons-shield"
   )
   );

  
}

add_action("init", "register_custom_type2"); 



//custom taxonomy
function wpdocs_register_private_taxonomy() {
   $args = array(
       'label'        => __( 'favorite', 'textdomain' ),
       'public'       => true,
       'rewrite'      => true,
       'hierarchical' => true
   );
    
   register_taxonomy( 'favorite', 'shop-my-fav', $args );
}
add_action( 'init', 'wpdocs_register_private_taxonomy', 0 );




