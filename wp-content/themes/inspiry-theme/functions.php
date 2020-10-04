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
   wp_enqueue_script("jquery");
   wp_enqueue_style( "inspiry-style", get_theme_file_uri("/style.css" ), array(), filemtime(get_template_directory()."/style.css"), "all" ); //delete version 
   wp_enqueue_style("google-fonts", "https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Work+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap", false);

   wp_enqueue_script("main", get_template_directory_uri() . "/js/scripts.js", array("jquery"), "1.0", true);

   wp_localize_script("main", "inspiryData", array(
      "root_url" => get_site_url(),
      "nonce" => wp_create_nonce("wp_rest")
    ));
}
add_action( "wp_enqueue_scripts", "inspiry_scripts" ); 


 //add nav menu
 function inspiry_config(){ 
    register_nav_menus( 
       array(
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
      "supports" => array("title", "page-attributes"), 
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

//routes

add_action("rest_api_init", "inspiry_board_route");

function inspiry_board_route(){ 
    register_rest_route("inspiry/v1/", "manageBoard", array(
        "methods" => "POST",
        "callback" => "createBoard"
    ));

    register_rest_route("inspiry/v1/", "addToBoard", array(
      "methods" => "POST",
      "callback" => "addProjectToBoard"
  ));

    register_rest_route("inspiry/v1/", "manageBoard", array(
        "methods" => "DELETE",
        "callback" => "deleteBoard"
    ));
}

function createBoard($data){ 
   if(is_user_logged_in()){
      $boardName = sanitize_text_field($data["board-name"]);
      
      return wp_insert_post(array(
         "post_type" => "boards", 
         "post_status" => "private", 
         "post_title" => $boardName
  )); 


   }
   else{
      die("Only logged in users can create a board");
   }
   
  
}

function addProjectToBoard($data){ 
  
   
   if(is_user_logged_in()){
     
      $projectID = sanitize_text_field($data["post-id"]);
      $boardID = sanitize_text_field($data["board-id"]);
      $postTitle = sanitize_text_field($data["post-title"]);
      
      return wp_insert_post(array(
         "post_type" => "boards", 
         "post_status" => "private", 
         "post_title" => $postTitle,
         "post_parent" => $boardID, 
         "meta_input" => array(
            "saved_project_id" => $projectID
         )
  )); 


   }
   else{
      die("Only logged in users can create a board");
   }
   
}

function deleteBoard($data){ 
   $pinID = sanitize_text_field($data["pin-id"] ); 

   if(get_current_user_id() == get_post_field("post_author", $pinID) AND get_post_type($pinID)=="boards"){
      wp_delete_post($pinID, true); 
      return "congrats, like deleted"; 
   }
   else{ 
      die("you do not have permission to delete a pin");
   }
}

//shortcodes
function bp_custom_user_nav_item() {
   global $bp;

   $args = array(
           "name" => __("Custom Design Board", "buddypress"),
           "slug" => "design-board-2",
           "default_subnav_slug" => "portfolio",
           "position" => 50,
           "show_for_displayed_user" => false,
           "screen_function" => "bp_custom_user_nav_item_screen",
           "item_css_id" => "portfolio"
   );

   bp_core_new_nav_item( $args );
}
add_action( "bp_setup_nav", "bp_custom_user_nav_item", 99 );



function bp_custom_user_nav_item_screen() {
   add_action( "bp_template_content", "bp_custom_screen_content" );
   bp_core_load_template( apply_filters( "bp_core_template_plugin", "members/single/plugins" ) );
}
function bp_custom_screen_content() {
 
   echo '<div class="body-container">
   <div class="row-container board-page">
       <div>
     
       <?php 
           $boardLoop = new WP_Query(array(
               ""post_type"" => "boards", 
               "post_parent" => 0
           ));

           while($boardLoop->have_posts()){
               $boardLoop->the_post(); 
               ?>  
                   

                   <div class="board-card">
                       <a class="rm-txt-dec" href="<?php the_permalink(); ?>">   
                       
                           <?php 
                           //GET THE CHILD ID
                               //Instead of calling and passing query parameter differently, we"re doing it exclusively
                               $all_locations = get_pages( array(
                                   "post_type"         => "boards", //here"s my CPT
                                   "post_status"       => array( "private", "pending", "publish") //my custom choice
                               ) );

                               //Using the function
                               $parent_id =get_the_id();
                               $inherited_locations = get_page_children( $parent_id, $all_locations );
                               $pinCount = count($inherited_locations);
                               $child_id = $inherited_locations[0]->ID;
                               $childThumbnail = get_field("saved_project_id", $child_id); 
                               ?>
                           <div class="img-div"><?php echo get_the_post_thumbnail($childThumbnail);?></div>
                           <h5 class="font-s-med"><?php the_title();?></h5>
                           <div class="pin-count"><?php echo $pinCount;
                               if($pinCount <= 1){ 
                                   echo " Pin";
                               }
                               else{
                                   echo " Pins";
                               }
                           ?></div>
                       </a>

                   </div>
               <?php
           }
           wp_reset_query()
       ?>


       </div>
   </div>
</div>';
 
}