<?php

//adding new page in buddypress

function bp_custom_user_nav_item() {
    global $bp;

    //Design Board 
    $args = array(
            "name" => __("Design Board", "buddypress"),
            "slug" => "design-board-2",
            "default_subnav_slug" => "design-board-2",
            "position" => 50,
            "show_for_displayed_user" => false,
            "screen_function" => "bp_custom_user_nav_item_screen",
            "item_css_id" => "portfolio"
    );

    //shiping address bigcommerce
  
    bp_core_new_nav_item( $args );
 }
 add_action( "bp_setup_nav", "bp_custom_user_nav_item", 99 );
 
 
 
 function bp_custom_user_nav_item_screen() {
    add_action( "bp_template_content", "bp_custom_screen_content" );

    bp_core_load_template( apply_filters( "bp_core_template_plugin", "members/single/plugins" ) );
 }



 function bp_custom_screen_content() {  
             ?>
<div class="body-container">
    <h1 class="center-align section-ft-size margin-elements"><?php the_title();?></h1>

    <div class="row-container board-page">
        <div>
        <?php 
            $boardLoop = new WP_Query(array(
                'post_type' => 'boards', 
                'post_parent' => 0, 
                'posts_per_page' => -1, 
                'author' => get_current_user_id()
            ));

            while($boardLoop->have_posts()){
                $boardLoop->the_post(); 
                ?>  
                    
                
                    <div class="board-card-archive">
                        <i class="fas fa-ellipsis-h option-icon"></i>
                        <div class="pin-options-container box-shadow">
                            <ul class="dark-grey">
                                <li class="delete-board-btn" data-pinid='<?php the_ID();?>'><i class="far fa-trash-alt"></i> Delete</li>
                            </ul>
                        </div>

                        <a class="design-board-card rm-txt-dec" class="rm-txt-dec" href="<?php the_permalink(); ?>">   
                        
                            <?php 
                            //GET THE CHILD ID
                                //Instead of calling and passing query parameter differently, we're doing it exclusively
                                $all_locations = get_pages( array(
                                    'post_type'         => 'boards', //here's my CPT
                                    'post_status'       => array( 'private', 'pending', 'publish') //my custom choice
                                ) );

                                //Using the function
                                $parent_id =get_the_id();
                                $inherited_locations = get_page_children( $parent_id, $all_locations );
                                $pinCount = count($inherited_locations);

                                // echo what we get back from WP to the browser (@bhlarsen's part :) )
                                $child_id = $inherited_locations[0]->ID;
                                $childThumbnail = get_field('saved_project_id', $child_id); 
                                ?>
                            <div class="img-div"><?php echo get_the_post_thumbnail($childThumbnail);?></div>
                            <h5 class="font-s-med"><?php the_title();?></h5>
            
                            <div class="pin-count gray"><?php echo $pinCount;
                                if($pinCount <= 1){ 
                                    echo ' Pin';
                                }
                                else{
                                    echo ' Pins';
                                }
                            ?></div>

                             <div class="work-sans-fonts font-s-regular"><?php if( '' !== get_post()->post_content ) { 
                                   
                                    echo get_the_content();
                                     }
                                ?></div>

                        </a>


                    </div>
                <?php
            }
            wp_reset_query();
        ?>


        </div>
    </div>
</div>
<div class="overlay"></div>                       
<div class="share-icon-container box-shadow">
                            <div class="work-sans-fonts regular font-s-med"> Share this pin </div>
                            <div class="underline"></div>
                            <div>
                                <?php echo do_shortcode('[Sassy_Social_Share  url="http:'.get_the_permalink(get_field('saved_project_id')).'"]');?>
                            </div>
                            <span>X</span>

</div> 

<div class="ajax-result">

</div>


</div>
             <?php
 }
 
 ?>