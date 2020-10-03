<?php 
get_header(); 
?>
<div class="body-container">
    <div class="row-container board-page">
        <div>
      
        <?php 
            $boardLoop = new WP_Query(array(
                'post_type' => 'boards', 
                'post_parent' => get_the_id()
            ));

            while($boardLoop->have_posts()){
                $boardLoop->the_post(); 
                ?>  
                    

                    <div>
                        <a href="<?php echo get_the_permalink(get_field('saved_project_id')); ?>">    
                            <div>
                                <?php echo get_the_post_thumbnail( get_field('saved_project_id'));?>
                            </div>
                            <h5 ><?php the_title();?></h5>
                        </a> 
                    </div>
                <?php
            }
        ?>

        
        </div>
    </div>
</div>
    

<?php 
    get_footer();
?>

