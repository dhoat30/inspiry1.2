<?php 
//custom post register

//custom post register


add_post_type_support( "sliders", "thumbnail" ); 

add_post_type_support( "loving", "thumbnail" ); 
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
   

}

add_action("init", "register_custom_type2"); 
?>