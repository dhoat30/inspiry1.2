<?php 

add_action('rest_api_init', 'inspiry_board_route');

function inspiry_like_route(){ 
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