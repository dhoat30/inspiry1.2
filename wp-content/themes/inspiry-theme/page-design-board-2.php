<?php 
get_header(); 
?>
<div class="body-container">
    <div class="row-container board-page">
        <div>
      
        <?php 
            $boardLoop = new WP_Query(array(
                'post_type' => 'boards', 
                'post_parent' => 0, 
                'posts_per_page' => -1
            ));

            while($boardLoop->have_posts()){
                $boardLoop->the_post(); 
                ?>  
                    

                    <div class="board-card">
                        <a class="rm-txt-dec" href="<?php the_permalink(); ?>">   
                        
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
                            <div class="pin-count"><?php echo $pinCount;
                                if($pinCount <= 1){ 
                                    echo ' Pin';
                                }
                                else{
                                    echo ' Pins';
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
</div>
    

<?php 
    get_footer();
?>

