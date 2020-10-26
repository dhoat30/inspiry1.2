
<div class="body-container">
    <div class="row-container board-loop-page">
        <div class="board-flex">
      
        <?php 
            $boardLoop = new WP_Query(array(
                'post_type' => 'boards', 
                'post_parent' => get_the_id(),
                'posts_per_page' => -1
            ));

            while($boardLoop->have_posts()){
                $boardLoop->the_post(); 
                ?>  
                    

                    <div class="board-card">
                    
                        <i class="fas fa-ellipsis-h option-icon"></i>
                        <div class="pin-options-container box-shadow">
                            <ul class="dark-grey">
                                <li class="share-btn"><i class="fas fa-share-alt"></i> Share</li>
                                <li class="website-btn"><a class='rm-txt-dec' target="_blank" href='<?php echo do_shortcode('[gd_post_meta key="website" id="7345" show="value-raw" no_wrap="1"]');?>'><i class="fas fa-globe"></i> Website</a></li>
                                <li class="delete-btn" data-pinid='<?php the_ID();?>'><i class="far fa-trash-alt"></i> Delete</li>
                                
                            </ul>
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

 <div class="overlay"></div>                       
<div class="share-icon-container box-shadow">
                            <div class="work-sans-fonts regular font-s-med"> Share this pin </div>
                            <div class="underline"></div>
                            <div>
                                <?php echo do_shortcode('[Sassy_Social_Share  url="http:'.get_the_permalink(get_field('saved_project_id')).'"]');?>
                            </div>
                            <span>X</span>

                        </div>

