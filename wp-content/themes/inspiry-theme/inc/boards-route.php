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
        "callback" => "deleteBoard"
    ));
}

function createBoard($data){ 

   if(is_user_logged_in()){
      $boardName = sanitize_text_field($data["board-name"]);
      $existQuery = new WP_Query(array(
        'author' => get_current_user_id(), 
        'post_type' => 'boards', 
        's' => $boardName
    )); 
     if($existQuery->found_posts == 0){ 
        return wp_insert_post(array(
            "post_type" => "boards", 
            "post_status" => "private", 
            "post_title" => $boardName
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

