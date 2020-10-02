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
   wp_enqueue_script('jquery');
   wp_enqueue_style( 'inspiry-style', get_theme_file_uri('/style.css' ), array(), filemtime(get_template_directory().'/style.css'), 'all' ); //delete version 
   wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap', false);

   wp_enqueue_script('main', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', true);

   wp_localize_script('main', 'inspiryData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest')
    ));
}
add_action( 'wp_enqueue_scripts', 'inspiry_scripts' ); 


 //add nav menu
 function inspiry_config(){ 
    register_nav_menus( 
       array(
          'inspiry_main_menu' => 'Inspiry Main Menu',
          'inspiry_footer_menu' => 'Inspiry Footer Menu', 
          'footer-trade-menu' => 'Footer Trade Menu', 
          'footer-help-info' => 'Footer Help & info', 
          'footer-ideas-inspiration' => 'Footer Ideas & Inspiration', 
          'footer-store' => 'Footer Store', 
          'footer-ways-to-shop' => 'Footer Ways To Shop'
       )
       );  

       add_theme_support( 'title-tag');

         add_post_type_support( 'gd_list', 'thumbnail' );      
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



//custom post register

add_theme_support('post-thumbnails');
add_post_type_support( 'boards', 'thumbnail' ); 
function register_custom_type(){ 
   register_post_type('boards', array(
      'supports' => array('title', 'page-attributes'), 
      'public' => true, 
      "show_ui" => true, 
      'hierarchical' => true,
      'labels' => array(
         'name' => 'Boards', 
         'add_new_item' => 'Add New Board', 
         'edit_item' => 'Edit Board', 
         'all_items' => 'All Boards', 
         'singular_name' => 'Note'
      ), 
      'menu_icon' => 'dashicons-heart'
      
   )
   ); 
}

add_action('init', 'register_custom_type'); 

 //make private page parent/child
 add_filter('page_attributes_dropdown_pages_args', 'my_attributes_dropdown_pages_args', 1, 1);

function my_attributes_dropdown_pages_args($dropdown_args) {

    $dropdown_args['post_status'] = array('publish','draft', 'private');

    return $dropdown_args;
}



//routes

add_action('rest_api_init', 'inspiry_board_route');

function inspiry_board_route(){ 
    register_rest_route('inspiry/v1/', 'manageBoard', array(
        'methods' => 'POST',
        'callback' => 'createBoard'
    ));

    register_rest_route('inspiry/v1/', 'manageBoard', array(
        'methods' => 'DELETE',
        'callback' => 'deleteBoard'
    ));
}

function createBoard(){ 
   wp_insert_post(array(
      'post_type' => 'boards', 
      'post_status' => 'private', 
      'post_title' => 'Save Board Post'
  )); 
}

function deleteBoard(){ 
    return 'Thanks for deleting a board';
}

