<?php 
 //add nav menu
 function inspiry_config(){ 
    register_nav_menus( 
       array(
           "top-navbar" => "Top Navbar (under logo)",
          "inspiry_main_menu" => "Inspiry Main Menu",
          "inspiry_footer_menu" => "Inspiry Footer Menu", 
          "footer-trade-menu" => "Footer Trade Menu", 
          "footer-help-info" => "Footer Help & info", 
          "footer-ideas-inspiration" => "Footer Ideas & Inspiration", 
          "footer-store" => "Footer Store", 
          "footer-ways-to-shop" => "Footer Ways To Shop"
       )
       );  

       add_theme_support( "title-tag");

         add_post_type_support( "gd_list", "thumbnail" );      
  }
 
  add_action("after_setup_theme", "inspiry_config", 0);
?>