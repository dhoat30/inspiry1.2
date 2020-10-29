<?php 
get_header(); 
?>


<?php 
  while(have_posts()){
    the_post(); 
    ?>
    <div class="project-detail-page">
        
        <div class="project-hero">
                    <?php  echo do_shortcode('[gd_post_images type="image" ajax_load="1" slideshow="1" show_title="1" animation="slide" controlnav="1" types="post_images" fallback_types="logo" image_size="1536x1536"]');  ?>              
        </div>

        <div class="full-width-paragraph-container">
            <div class="row-container margin-elements">
                
                <div class="project-header-section">
                    <div class="header-middle-column">
                        <?php
                        if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
                        }
                        ?>
                       
                            
                            
                        <div class="header-title">
                            <h2 class="section-ft-size board-heading-post-id" data-postid='<?php echo get_the_id()?>'><?php echo do_shortcode( '[gd_post_meta key="post_title" show="value-raw" no_wrap="1"]');?></h2>
                        </div>
                       
                        <div class="trade-info-section">
                            
                            <div class="share-icon">
                                Share: <?php echo do_shortcode('[Sassy_Social_Share]');?>

                            </div>
                            <!--custom board post ui-->
                            <?php 
                                $existStatus = 'no'; 

                                if(is_user_logged_in( )){ 
                                    $existQuery = new WP_Query(array(
                                        'author' => get_current_user_id(), 
                                        'post_type' => 'boards', 
                                        'meta_query' => array(
                                            array(
                                                'key' => 'saved_project_id', 
                                                'compare' => '=', 
                                                'value' => get_the_id()
                                            )
                                        )
                                    )); 
    
                                    if($existQuery->found_posts){ 
                                        $existStatus = 'yes'; 
                                    }
                                    wp_reset_postdata(  );
                                }

                               
                            ?>

                            <div class="design-board-save-btn-container">
                                <i data-exists='<?php echo $existStatus?>' class="fal fa-plus open-board-container" ></i>
                            </div>
                            

                            <div class="choose-board-container">
                                    <div class="choose-board">Choose Board</div>
                                    <div class="close-icon">X</div>
                                    <ul class="board-list">
                                        <?php 
                                        
                                        //wp query to get parent title of boards 
                                        
                                        $boardLoop = new WP_Query(array(
                                            'post_type' => 'boards', 
                                            'post_parent' => 0
                                        ));
                                        
                                        while($boardLoop->have_posts()){
                                            $boardLoop->the_post(); 
                                            
                                          
                                        }
                                    
                                            while($boardLoop->have_posts()){ 
                                                $boardLoop->the_post(); 
                                                ?>
                                                        <li class="board-list-item" data-boardID='<?php echo get_the_id(); ?>'>
                                                        
                                                        <?php 
                                                            
                                                        the_title();?>
                                                        <div class="loader"></div>

                                                        </li>

                                                <?php
                                                wp_reset_postdata(  );
                                            }
                                        ?>
                                    </ul>
                                    <div class="create-new-board"><span>+</span> Create New Board</div>
                                </div>

                        </div>
                        
                        <!--
                        <div class="header-reviews">
                        <?php //echo do_shortcode("[gd_post_rating]"); ?>  
                        </div>
                        -->
                    
                    </div>  

                    <div class="header-contact">
                        <div class="header-contact-btn">
                                <?php echo do_shortcode('[gd_ninja_forms form_id="2" text="Contact Form" post_contact="1" output="button"]'); ?>
                        </div>
                        <!--
                        <div class="header-contact-details">
                                <a class="work-sans-fonts font-s-med rm-txt-dec " href="tel:<? //php echo do_shortcode( '[gd_post_meta key="phone" show="value-raw" no_wrap="1"]');?>">
                                    <i class="fas fa-phone-alt"></i>
                                    <?php //echo do_shortcode( '[gd_post_meta key="phone" show="value-raw" no_wrap="1"]');?>
                                </a>
                                <a class="work-sans-fonts font-s-med rm-txt-dec " href=" <? //php echo do_shortcode( '[gd_post_meta key="website" show="value-raw" no_wrap="1"]');?>" target="_blank">
                                    <i class="fas fa-globe"></i>
                                    Website
                                </a>
                        </div>
                    -->
                </div>
                </div>   
                
                
            </div>
            
            
        
            <div class="row-container project-main-row">
                <div class="project-content work-sans-fonts font-s-med grey">
                    <?php 
                        echo do_shortcode( '[gd_post_meta key="post_content" show="value-raw" no_wrap="1"]');
                    ?>
                </div>
                
            </div>

            <div class="row-container">
                <div class="project-gallery">
                    <h3 class="margin-row column-s-font small-margin-bottom">Gallery</h3>
                    <?php echo do_shortcode('[gd_post_images type="gallery" ajax_load="1" slideshow="1" show_title="1" animation="slide" controlnav="1" link_to="lightbox"]'); ?>
                </div>
                
            </div>
         
        </div>



        
    </div>
    <?php
}
?>
<div class="project-save-form-section">
   
    <div class="project-save-form-container"> 
        <div class="work-sans-fonts regular form-title font-s-med">Create Board</div>
        <div class="form-underline"></div>
        <div class="form">
            <form id="new-board-form">
                <label for="name">Give your board a title*</label>
                <input type="text" name="board-name" id="board-name">
                <label for="description">Description</label>
                <textarea name="board-description" id="board-description" cols="30" rows="10"></textarea>
                
                <div class="btn-container">
                    <button type="button" class="cancel-btn btn"> Cancel</button>
                    <button type="submit" class="save-btn btn btn-dk-green"> Save</button>
                  
                    <div class="loader"></div>
                </div>
            </form>
        </div>
    </div>
</div>
                                           

<?php
get_footer();
?>