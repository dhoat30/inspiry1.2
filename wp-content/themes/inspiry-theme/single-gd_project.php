<?php 
get_header(); 



  while(have_posts()){
    the_post(); 
    ?>
    <div class="body-container">
        
        <div class="project-hero">
                    <?php  echo do_shortcode('[gd_post_images type="image" ajax_load="1" slideshow="1" show_title="1" animation="slide" controlnav="1" types="post_images" fallback_types="logo" image_size="1536x1536"]');  ?>              
        </div>

        <div class="row-container">
            
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
                    <div class="place-title">
                         <?php echo do_shortcode('[gd_linked_posts link_type="to" post_type="gd_place" sort_by="az" title_tag="h3" layout="1" post_limit="1" view_all_link="0"]'); ?>
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
            
            <div class="project-middle-column">
                <div class="project-nav-container" id="project-nav-container">
                    <ul class="nav">
                        <li class="project-nav-link active-nav">Profile</li>
                        <li class="project-nav-link">Contact</li>
                        <li class="project-nav-link">Projects</li>
                        <li class="project-nav-link">Gallery</li>
                    </ul>

                    <div class="project-about-nav-content work-sans-fonts font-s-regular">
                        <?php 
                        echo do_shortcode( '[gd_post_meta key="post_content" show="value-raw" no_wrap="1"]');
                        ?>
                    </div>

                    <div class="project-contact-nav-content">
                        <table class="work-sans-fonts">
                            <tr>
                                <td><i class="fas fa-phone-alt"></i></td>
                                <td><?php echo do_shortcode('[gd_post_meta key="phone" show="value" no_wrap="1"]');?></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-envelope"></i></td>
                                <td><?php echo do_shortcode('[gd_post_meta key="email" show="value" no_wrap="1"]');?></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-globe"></i></td>
                                <td><?php echo do_shortcode('[gd_post_meta key="website" show="value-raw" no_wrap="1]');?></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-map-marker-alt"></i></td>
                                <td><?php echo do_shortcode('[gd_post_meta key="address" show="value-raw" no_wrap="1]');?></td>
                            </tr>
                            <tr class="social-media-icons">
                                
                                <td><a href='<?php echo  do_shortcode( '[gd_post_meta key="facebook" show="value-raw" no_wrap="1"]');?>' target="_blank"><?php echo  do_shortcode( '[gd_post_meta key="facebook" show="icon" no_wrap="1"]');?></a></td>
                                <td><a href='<?php echo  do_shortcode( '[gd_post_meta key="instagram" show="value-raw" no_wrap="1"]');?>' target="_blank"><?php echo  do_shortcode( '[gd_post_meta key="instagram" show="icon" no_wrap="1"]');?></a></td>
                                <td><a href='<?php echo  do_shortcode( '[gd_post_meta key="twitter" show="value-raw" no_wrap="1"]');?>' target="_blank"><?php echo  do_shortcode( '[gd_post_meta key="twitter" show="icon" no_wrap="1"]');?></a></td>
                                <td><a href='<?php echo  do_shortcode( '[gd_post_meta key="twitter" show="value-raw" no_wrap="1"]');?>' target="_blank"><?php echo  do_shortcode( '[gd_post_meta key="twitter" show="icon" no_wrap="1"]');?></a></td>
                            </tr>
                        </table>
                         
                        <?php 
                            echo do_shortcode('[gd_map width="100%" height="425px" maptype="ROADMAP" zoom="0" map_type="auto" post_settings="1"]');

                        ?>
                    </div>

                    <div class="project-project-nav-content">
                        <?php 
                            echo do_shortcode( '[gd_linked_posts title="" link_type="from" post_type="gd_project" sort_by="az" title_tag="h3" layout="3" post_limit="50"]');
                        ?>
                    </div>

                    <div class="project-gallery-nav-content">
                        <?php echo do_shortcode('[gd_post_images type="gallery" ajax_load="1" slideshow="1" show_title="1" animation="slide" controlnav="1" link_to="lightbox"]'); ?>
                    </div>



                </div>
            </div>
            
        </div>

        <div>

            <?php  echo do_shortcode( '[gd_post_fav icon="fas fa-heart" icon_color_off="#ff3333" icon_color_on="#009e20"]');?>
        </div>



        
    </div>
    <?php
}
?>

<?php
get_footer();
?>