
<?php 
/**
 * Twenty Twenty functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package inspiry

 */

 //enqueue scripts
 function inspiry_scripts(){ 
    
    wp_enqueue_style( 'inspiry-style', get_theme_file_uri('/style.css' ), array(), filemtime(get_template_directory().'/css/style.css'), 'all' ); //delete version 
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap', false);
    wp_enqueue_script('main', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);

    

 }
 add_action( 'wp_enqueue_scripts', 'inspiry_scripts' ); 


 //add na menu
 function inspiry_config(){ 
   register_nav_menus( 
      array(
         'inspiry_main_menu' => 'Inspiry Main Menu',
         'Inspiry_footer_menu' => 'Inspiry Footer Menu'
      )
      );  
 }

 add_action('after_setup_theme', 'inspiry_config', 0);

 //breadcrumb 
 
 