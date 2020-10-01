<?php 
get_header(); 
?>
<div class="body-container">
    <div class="row-container board-page">
        <div>
      
        <?php 
            $boardLoop = new WP_Query(array(
                'post_type' => 'boards', 
                'post_parent' => 0
            ));

            while($boardLoop->have_posts()){
                $boardLoop->the_post(); 
                ?>  
                    

                    <div>
                    <a href="<?php the_permalink(); ?>">    <h5 ><?php the_title();?></h5></a>
                        <?php the_content();?>
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

                            // echo what we get back from WP to the browser (@bhlarsen's part :) )
                            $child_id = $inherited_locations[0]->ID;
                            
                            ?>
                        <div><?php echo get_the_post_thumbnail($child_id);?></div>
                       
                        

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

