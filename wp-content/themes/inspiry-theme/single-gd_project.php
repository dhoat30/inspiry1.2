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
                       <h2> 
                           <?php 
                            $postID = get_the_ID();
                            $value = geodir_get_post_meta($postID,'gd_place',true);
                            ?> 
                            <a class="prof-title rm-txt-dec work-sans-fonts regular font-s-med" href='<?php echo get_the_permalink($value);?>'> <?php 
                            
                                 echo get_the_title($value);
                            ?>
                                <div class="tooltip-container">
                                    <p class='tooltip'>
                                        Please click this
                                    </p>
                                </div>
                            </a>
                       </h2>
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

                            <div class="design-board-save-btn-container" data-tracking-data='{"post_id":"<?php the_id();?>","name":"<?php echo get_the_title(get_the_id()); ?>"}' <?php echo $link_attributes; ?>>
                                <i data-exists='<?php echo $existStatus?>' class="fal fa-plus open-board-container" ></i>
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
                <div class="project-content work-sans-fonts grey font-s-regular thin">
                    <?php 
                        echo do_shortcode( '[gd_post_meta key="post_content" show="value-raw" no_wrap="1"]');
                    ?>
                </div>
                
            </div>

            <div class="row-container">
                <div class="project-gallery">
                    <h3 class="margin-row column-s-font small-margin-bottom .regular">Gallery</h3>
                    <?php echo do_shortcode('[gd_post_images type="gallery" ajax_load="1" slideshow="1" show_title="1" animation="slide" controlnav="1" link_to="lightbox"]'); ?>
                </div>
                
            </div>
         
        </div>



        
    </div>
    <?php
}
?>

                                    

<?php
get_footer();
?>