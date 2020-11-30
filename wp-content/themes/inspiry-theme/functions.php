<?php 
/**
 * Inspiry functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package inspiry

 */
require get_theme_file_path('/inc/buddypress-design-boards.php');

require get_theme_file_path('/inc/boards-route.php');
require get_theme_file_path('/inc/custom-post-type.php');

require get_theme_file_path('/inc/nav-registeration.php');


 //enqueue scripts

 function inspiry_scripts(){ 
   wp_enqueue_script("jquery");
   wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/f3cb7ab01f.js', NULL, '1.0', false);
   wp_enqueue_style("google-fonts", "https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap", false);
   
   if (strstr($_SERVER['SERVER_NAME'], 'localhost')) {
      wp_enqueue_script('main', 'http://localhost:3000/bundled.js',  NULL, '1.0', true);
    } else {
      wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/undefined'),  NULL, '1.0', true);

      wp_enqueue_script('main', get_theme_file_uri('/bundled-assets/scripts.b86987f2a8f51e1f05d8.js'), NULL, '1.0', true);
      wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.b86987f2a8f51e1f05d8.css'));
    }
    wp_localize_script("main", "inspiryData", array(
      "root_url" => get_site_url(),
      "nonce" => wp_create_nonce("wp_rest")
    ));
  

  
}
add_action( "wp_enqueue_scripts", "inspiry_scripts" ); 




  //admin bar
  if ( ! current_user_can( "manage_options" ) ) {
   show_admin_bar( false );
}
//sidebar


add_action( "widgets_init", "mat_widget_areas" );
function mat_widget_areas() {
    register_sidebar( array(
        "name" => "Theme Sidebar",
        "id" => "mat-sidebar",
        "description" => "The main sidebar shown on the right in our awesome theme",
        "before_widget" => '<li id="%1$s" class="widget %2$s">',
		"after_widget"  => "</li>",
		"before_title"  => '<h3 class="widget-title">',
		"after_title"   => "</h3>",
    ));
}



//custom post register

add_theme_support("post-thumbnails");
add_post_type_support( "boards", "thumbnail" ); 
function register_custom_type(){ 
   register_post_type("boards", array(
      "supports" => array("title", "page-attributes", 'editor'), 
      "public" => true, 
      "show_ui" => true, 
      "hierarchical" => true,
      "labels" => array(
         "name" => "Boards", 
         "add_new_item" => "Add New Board", 
         "edit_item" => "Edit Board", 
         "all_items" => "All Boards", 
         "singular_name" => "Note"
      ), 
      "menu_icon" => "dashicons-heart"
      
   )
   ); 
}

add_action("init", "register_custom_type"); 

 //make private page parent/child
 add_filter("page_attributes_dropdown_pages_args", "my_attributes_dropdown_pages_args", 1, 1);

function my_attributes_dropdown_pages_args($dropdown_args) {

    $dropdown_args["post_status"] = array("publish","draft", "private");

    return $dropdown_args;
}


// remove "Private: " from titles
function remove_private_prefix($title) {
	$title = str_replace("Private: ", "", $title);
	return $title;
}
add_filter("the_title", "remove_private_prefix");

//facet wp
function fwp_archive_per_page( $query ) {
   if ( is_tax( 'category' ) ) {
       $query->set( 'posts_per_page', 20 );
   }
}
add_filter( 'pre_get_posts', 'fwp_archive_per_page' );


function fwp_home_custom_query( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'post_type', [ 'post', 'product' ] );
        $query->set( 'orderby', 'title' );
        $query->set( 'order', 'ASC' );
    }
}
add_filter( 'pre_get_posts', 'fwp_home_custom_query' );

//navbar
class CSS_Menu_Walker extends Walker {

	var $db_fields = array('parent' => 'menu_item_parent', 'id' => 'db_id');
	
	function start_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul>\n";
	}
	
	function end_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	
		global $wp_query;
		$indent = ($depth) ? str_repeat("\t", $depth) : '';
		$class_names = $value = '';
		$classes = empty($item->classes) ? array() : (array) $item->classes;
		
		/* Add active class */
		if (in_array('current-menu-item', $classes)) {
			$classes[] = 'active';
			unset($classes['current-menu-item']);
		}
		
		/* Check for children */
		$children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $item->ID));
		if (!empty($children)) {
			$classes[] = 'has-sub';
		}
		
		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
		
		$id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';
		
		$output .= $indent . '<li' . $id . $value . $class_names .'>';
		
		$attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
		$attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
		$attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
		$attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';
		
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'><span>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</span></a>';
		$item_output .= $args->after;
		
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
	
	function end_el(&$output, $item, $depth = 0, $args = array()) {
		$output .= "</li>\n";
	}
}