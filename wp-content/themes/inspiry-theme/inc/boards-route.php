<?php
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
        "callback" => "deletePin"
    ));

    register_rest_route("inspiry/v1/", "deleteBoard", array(
        "methods" => "DELETE",
        "callback" => "deleteBoardFunc"
    ));
    
}

function createBoard($data){ 

   if(is_user_logged_in()){
      $boardName = sanitize_text_field($data["board-name"]);
      $boardDescription = sanitize_text_field($data['board-description']); 

      $existQuery = new WP_Query(array(
        'author' => get_current_user_id(), 
        'post_type' => 'boards', 
        's' => $boardName
    )); 
     if($existQuery->found_posts == 0){ 
        return wp_insert_post(array(
            "post_type" => "boards", 
            "post_status" => "private", 
            "post_title" => $boardName,
            'post_content' => $boardDescription
     )); 
     }
     else{ 
         die('Board already exists');
     }
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

function deletePin($data){ 
   $pinID = sanitize_text_field($data["pin-id"] ); 

   if(get_current_user_id() == get_post_field("post_author", $pinID) AND get_post_type($pinID)=="boards"){
      wp_delete_post($pinID, true); 
      return "congrats, like deleted"; 
   }
   else{ 
      die("you do not have permission to delete a pin");
   }
}

function deleteBoardFunc($data){ 
    $parentID = sanitize_text_field($data["board-id"] ); 

    // Delete the Parent Page
    if(get_current_user_id() == get_post_field("post_author", $parentID) AND get_post_type($parentID)=="boards"){

        //Instead of calling and passing query parameter differently, we're doing it exclusively
        $all_locations = get_pages( array(
            'post_type'         => 'boards', //here's my CPT
            'post_status'       => array( 'private', 'pending', 'publish') //my custom choice
        ) );

        //Using the function
        $inherited_locations = get_page_children( $parentID, $all_locations );
        // echo what we get back from WP to the browser (@bhlarsen's part :) )
            // Delete all the Children of the Parent Page
            foreach($inherited_locations as $post){
        
                wp_delete_post($post->ID, true);
            }

        // Delete the Parent Page
        wp_delete_post($parentID, true);

        return 'deletion worked. congrats'; 
     }
     else{ 
        die("you do not have permission to delete a pin");
     }
}

/*function deleteParentBoard(){ 
    $boardID = sanitize_text_field($data["board-id"] ); 

    // Delete the Parent Page
    if(get_current_user_id() == get_post_field("post_author", $boardID) AND get_post_type($boardID)=="boards"){
        wp_delete_post($boardID, true); 
        return "congrats, board deleted"; 
     }
     else{ 
        die("you do not have permission to delete a pin");
     }
}*/