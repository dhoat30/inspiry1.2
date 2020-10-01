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
                    <a href="<?php the_permalink(); ?>">    <h5 ><?php the_title();?></h5></a>
                        <?php the_content();?>
                        <?php the_post_thumbnail();?>
                        <h5><?php echo get_the_ID();?></h5>
                         
                        
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

