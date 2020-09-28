<?php 
get_header(); 



  while(have_posts()){
    the_post(); 
    ?>
    <div class="body-container project-detail-page">
        
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
                            <h2 class="section-ft-size"><?php echo do_shortcode( '[gd_post_meta key="post_title" show="value-raw" no_wrap="1"]');?></h2>
                        </div>

                        <div class="trade-info-section">
                            <div class="place-title">
                                <?php echo do_shortcode('[gd_linked_posts link_type="to" post_type="gd_place" sort_by="az" title_tag="h3" layout="1" post_limit="1" view_all_link="0"]'); ?>
                            </div>
                            <div class="save-button">
                                <?php echo do_shortcode('[gd_list_save save_icon_class="fas fa-thumbtack" saved_icon_class="fas fa-thumbtack" bg_color="#495a54" txt_color="#ffffff" size="medium"]'); ?>
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

                                           

<?php
get_footer();
?>