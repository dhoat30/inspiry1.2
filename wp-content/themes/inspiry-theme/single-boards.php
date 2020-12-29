<?php
get_header(); 
?>
<div class="body-container">
    <div class="row-container board-loop-page single-board box-shadow">
        <div class="back-icon-container">
            <a href="<?php echo get_site_url(); ?>/boards">
                <i class="fal fa-arrow-left"></i>
            </a>
        </div>
        <h1 class="lg-font-sz playfair-fonts regular light-grey"> <?php echo get_the_title($parentID);?></h1>
        <div class='action-btn-container'>
            <button class="share btn btn-dk-green-border font-s-regular"><i class="fal fa-share-alt"></i> Share</button>
            <div class="share-icons box-shadow">
                <i class="fal fa-times"></i>
                <h2 class="roboto-font font-s-medium medium">Share this board</h2>
                <div class="underline underline-bg margin-elements"></div>
                <div>
                    <?php echo do_shortcode('[Sassy_Social_Share]');?>
                </div>

            </div>
        </div>
        <div class="board-flex">

            <?php 
            $boardLoop = new WP_Query(array(
                'post_type' => 'boards', 
                'post_parent' => get_the_id(),
                'posts_per_page' => -1
            ));

            while($boardLoop->have_posts()){
                $boardLoop->the_post(); 
                $parentID =  wp_get_post_parent_id(get_the_id()); 
                ?>


            <div class="board-card">

                <i class="fas fa-ellipsis-h option-icon"></i>
                <div class="pin-options-container box-shadow">
                    <ul class="dark-grey">
                        <li class="share-btn"><i class="fas fa-share-alt"></i> Share</li>
                        <!-- <li class="website-btn"><a class='rm-txt-dec' target="_blank" href='<?php// echo do_shortcode('[gd_post_meta key="website" id="7345" show="value-raw" no_wrap="1"]');?>'><i class="fas fa-globe"></i> Website</a></li>-->
                        <li class="delete-btn" data-pinid='<?php the_ID();?>'><i class="far fa-trash-alt"></i> Delete
                        </li>

                    </ul>
                    

                </div>
                
                    <div class="share-icon-container box-shadow">
                            <div class="roboto-font regular font-s-med"> Share this pin </div>
                            <div class="underline"></div>
                            <div>
                                <?php echo do_shortcode('[Sassy_Social_Share url="<?php echo get_the_permalink(get_field("saved_project_id")); ?>"]');?>
                            </div>
                            <span class="close-icon">X</span>
                        </div>
                
           
                <a href="<?php echo get_the_permalink(get_field('saved_project_id')); ?>">
                    <div class="thumbnail">
                        <?php echo get_the_post_thumbnail( get_field('saved_project_id'), 'post-thumbnail');?>
                    </div>
                    <div class="title font-s-regular rm-txt-dec">
                        <?php echo get_the_title(get_field('saved_project_id')); ?></div>

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