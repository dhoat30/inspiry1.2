<?php 
get_header(); 



  while(have_posts()){
    the_post(); 
    ?>
    <div class="body-contaienr">
        
        <div class="trade-hero">
                    <?php  echo do_shortcode('[gd_post_images type="slider" ajax_load="1" slideshow="1" show_title="1" animation="slide" controlnav="1"]');  ?>              

        </div>
        <div class="row-container">
            
            <div class="trade-header-section">
                <div class="trade-profile-img">
                    <?php 
                    //striping the value at the end of the img url
                    $img_url = do_shortcode( '[gd_post_meta key="logo" show="value-raw" no_wrap="1"]');
                    $profile_img = substr($img_url, 0, strpos($img_url,".jpg"));
                    ?>
                    <img src="<?php echo $profile_img.'.jpg'?>" alt="">
                </div>

                <div class="header-middle-column">

                    <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                    yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
                    }
                    ?>
                    <div class="header-title">
                        <h2 class="section-ft-size"><?php echo do_shortcode( '[gd_post_meta key="post_title" show="value-raw" no_wrap="1"]');?></h2>
                    </div>
                    <!--
                    <div class="header-reviews">
                       <?php //echo do_shortcode("[gd_post_rating]"); ?>  
                    </div>
                    -->
                    <div class="header-address regular">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo do_shortcode('[gd_post_address show="value" address_template="%%city%%"]'); ?>
                    </div>
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
        
        
    
        <div class="row-container trade-main-row">
            
            <div class="trade-middle-column">
                <div class="trade-nav-container" id="trade-nav-container">
                    <ul class="nav">
                        <li class="trade-nav-link active-nav">Profile</li>
                        <li class="trade-nav-link">Contact</li>
                        <li class="trade-nav-link">Projects</li>
                    </ul>

                    <div class="trade-about-nav-content work-sans-fonts font-s-regular">
                        <?php 
                        echo do_shortcode( '[gd_post_meta key="post_content" show="value-raw" no_wrap="1"]');
                        ?>
                    </div>

                    <div class="trade-contact-nav-content invisible">
                        <?php 
                          
                            echo do_shortcode('[gd_post_meta key="phone" show="value-raw" no_wrap="1]');
                            echo do_shortcode('[gd_post_meta key="email" show="value-raw" no_wrap="1]');
                            echo do_shortcode('[gd_post_meta key="website" show="value-raw" no_wrap="1]');
                            echo do_shortcode('[gd_post_meta key="address" show="value-raw" no_wrap="1]');
                            echo do_shortcode('[gd_map width="100%" height="425px" maptype="ROADMAP" zoom="0" map_type="auto" post_settings="1"]');

                        ?>
                    </div>

                    <div class="trade-project-nav-content invisible">
                        <?php 
                            echo do_shortcode( '[gd_linked_posts title="" link_type="from" post_type="gd_project" sort_by="az" title_tag="h3" layout="3" post_limit="50" view_all_link="1"]');

                        ?>
                    </div>


                    <script>
                       
                    </script>
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