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
    
    //update board 
    register_rest_route("inspiry/v1/", "updateBoard", array(
      "methods" => "POST",
      "callback" => "updateBoard"
  ));

  //update board 
  register_rest_route("inspiry/v1/", "board", array(
   "methods" => "POST",
   "callback" => "getBoard"
));
}

function getBoard($data){
   $postID = sanitize_text_field($data["id"] ); 

   if(is_user_logged_in()){
   $boards = new WP_Query(array(
      'post_type' => 'boards',
      'post_parent' => 0, 
      'p' => $postID
   )); 

   $boardsResult = array(); 

   while($boards->have_posts()){
      $boards->the_post(); 

      array_push($boardsResult, array(
         'title' => get_the_title(),
         'description' => get_the_content(), 
         'id' => get_the_id(), 
         'status' => get_post_status()

      )); 

      
   }
   return $boardsResult; 
}
else{
   return 'you do not have permission' ;
}
}

function updateBoard($data){
   $parentID = sanitize_text_field($data["board-id"] ); 
   $boardName = sanitize_text_field($data["board-name"] ); 
   $boardDescription = sanitize_text_field($data["board-description"] );
   $publishStatus  = sanitize_text_field($data["status"] );

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
            // Update all the Children of the Parent Page
            foreach($inherited_locations as $post){
               
                wp_insert_post(array(
                  "ID" => $post->ID, 
                  "post_type" => "boards", 
                  "post_status" => $publishStatus,
                  'post_parent'=> $parentID, 
                  "post_title" =>get_the_title($post->ID)


               )); 
            }

        // Update the Parent Page
        wp_insert_post(array(
         "ID" => $parentID, 
         "post_type" => "boards", 
         "post_status" => $publishStatus, 
         "post_title" => $boardName,
         'post_content' => $boardDescription
         )); 

        return 'updation worked. congrats'; 
     }
     else{ 
        die("You do not have permission to update a board");
     }
}
function createBoard($data){ 

   if(is_user_logged_in()){
      $boardName = sanitize_text_field($data["board-name"]);
      $boardDescription = sanitize_text_field($data['board-description']); 
      $publishStatus = sanitize_text_field($data['status']);

      $existQuery = new WP_Query(array(
        'author' => get_current_user_id(), 
        'post_type' => 'boards', 
        's' => $boardName
    )); 
     if($existQuery->found_posts == 0){ 
        return wp_insert_post(array(
            "post_type" => "boards", 
            "post_status" => $publishStatus, 
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
     
      
      $boardID = sanitize_text_field($data["board-id"]);
      $postTitle = sanitize_text_field($data["post-title"]);
      $publishStatus = sanitize_text_field($data['status']);
      $postImageID = sanitize_text_field($data['post-image-id']);

      
      if($postImageID){
         return wp_insert_post(array(
            "post_type" => "boards", 
            "post_status" => $publishStatus, 
            "post_parent" => $boardID, 
            "post_title" => get_the_title($postImageID),
            "meta_input" => array(
               "saved_image_id" => $postImageID
            )
         )); 

      }
      else{
         return wp_insert_post(array(
            "post_type" => "boards", 
            "post_status" => $publishStatus, 
            "post_title" => $postTitle,
            "post_parent" => $boardID, 
            "meta_input" => array(
               "saved_project_id" => $projectID
            )
     )); 
      }
        
     


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