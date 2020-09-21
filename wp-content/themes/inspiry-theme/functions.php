<?php 
/**
 * Inspiry functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package inspiry

 */

 //enqueue scripts
 function inspiry_scripts(){ 
   wp_enqueue_style( 'inspiry-style', get_theme_file_uri('/style.css' ), array(), filemtime(get_template_directory().'/style.css'), 'all' ); //delete version 
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

       add_theme_support( 'title-tag');
  }
 
  add_action('after_setup_theme', 'inspiry_config', 0);

  //admin bar
  if ( ! current_user_can( 'manage_options' ) ) {
   show_admin_bar( false );
}
//sidebar
add_action( 'widgets_init', 'mat_widget_areas' );
function mat_widget_areas() {
    register_sidebar( array(
        'name' => 'Theme Sidebar',
        'id' => 'mat-sidebar',
        'description' => 'The main sidebar shown on the right in our awesome theme',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
    ));
}




