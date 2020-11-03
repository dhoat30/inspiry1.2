<?php 
//custom post register

//custom post register


add_post_type_support( "sliders", "thumbnail" ); 
function register_custom_type2(){ 
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

}

add_action("init", "register_custom_type2"); 
?>