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
        <?php //check if the post status is publish 
                            if(get_post_status() == 'publish'){
                                ?>
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
        <?php
                            }
                        ?>
        <?php //check if the post status is private 
                            if(get_post_status() == 'private'){
                                ?>
        <div class="image-upload-container" data-parentid=<?php echo get_the_id();?>>
            <button class="img-upload share btn btn-dk-green-border font-s-regular"> <i class="far fa-arrow-to-top"></i> Upload</button>

            <div>
                <div class="project-save-form-container">
                    <div class="roboto-font regular form-title font-s-med">Upload</div>
                    <div class="form-underline"></div>
                    <div class="form">
                        <form action="{{admin_url}}" method="POST" enctype="multipart/form-data" id="upload-image">
                            
                            
                            
    
 
                                <input type="hidden" name="action" value="my_file_upload" id="action"/>
                                <label for="image">Select file:</label>
                                <input type="file" id="image" name="my_file_field" accept="image/*"/>
                            


                            <div class="btn-container">
                                <button type="button" class="cancel-btn btn"> Cancel</button>
                                <button type="submit" class="save-btn btn btn-dk-green archive-save-btn"> Upload</button>

                                <div class="loader"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
                            }
                        ?>



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
                        <?php 
                            if(get_post_status() == 'publish'){
                                ?>
                        <li class="share-btn"><i class="fas fa-share-alt"></i> Share</li>
                        <?php
                            }
                        ?>

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

                        <?php 
                        //check if the image id exists
                        if(get_field('saved_image_id')){
                            $img =  wp_get_attachment_image_src(get_field('saved_image_id'), 'large');
                            ?>
                                <img src="<?php echo $img[0] ?>" alt="<?php echo get_the_title();?>">
                            <?php
                        }
                        else{
                            echo get_the_post_thumbnail( get_field('saved_project_id'), 'post-thumbnail');

                        }
                        ?>
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
<div>
   
</div>


<?php 
get_footer(); 
?>