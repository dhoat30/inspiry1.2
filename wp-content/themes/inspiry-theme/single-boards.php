<?php 
get_header(); 
?>
<div class="body-container">
    <div class="row-container board-loop-page">
        <div class="board-flex">
      
        <?php 
            $boardLoop = new WP_Query(array(
                'post_type' => 'boards', 
                'post_parent' => get_the_id()
            ));

            while($boardLoop->have_posts()){
                $boardLoop->the_post(); 
                ?>  
                    

                    <div class="board-card">
                        <div class="delete-icon" data-pinid='<?php the_ID();?>'>
                            <i class="far fa-trash-alt"></i>
                            <div>Delete</div>
                        </div>
                        <a href="<?php echo get_the_permalink(get_field('saved_project_id')); ?>">    
                            <div class="thumbnail">
                                <?php echo get_the_post_thumbnail( get_field('saved_project_id'));?>
                            </div>
                            <div class="title font-s-regular rm-txt-dec"><?php echo get_the_title(get_field('saved_project_id')); ?></div>
                        </a> 
                    </div>
                <?php
            }
        ?>

        
        </div>
    </div>
</div>

<div class="pin-options-container">
    <ul>
        <li><i class="fas fa-share-alt"></i> Share</li>
        <li><i class="far fa-trash-alt"></i> Delete</li>
        <li><i class="fas fa-globe"></i> Website</li>
    </ul>
</div>
    

<?php 
    get_footer();
?>

