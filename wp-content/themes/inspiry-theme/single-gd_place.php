<?php 
get_header(); 



  while(have_posts()){
    the_post(); 
    ?>
    <div class="body-contaienr">
        

        <div class="row-container">
            
            <div class="trade-hero-image">
                <?php  the_post_thumbnail('full');?>     
            </div>

            <div class="trade-header-section">
                <div class="trade-profile-img">
                    <?php 
                    //striping the value at the end of the img url
                    $img_url = do_shortcode( '[gd_post_meta key="logo" show="value-raw" no_wrap="1"]');
                    $profile_img = substr($img_url, 0, strpos($img_url,".jpg"));
                    ?>
                    <img src="<?php echo $profile_img.'.jpg'?>" alt="">
                </div>

                <div class="header-title">
                    <h2 class="column-s-font"><?php echo do_shortcode( '[gd_post_meta key="post_title" show="value-raw" no_wrap="1"]');?></h2>
                </div>
                <div class="header-reviews">
                    <?php echo do_shortcode("[gd_post_rating]"); ?>
                </div>
                <div class="header-address">
                    <?php echo do_shortcode('[gd_post_address show="value" address_template="%%city%%"]'); ?>
                </div>
            </div>   
            
            <div class="header-contact">
                <div class="header-contact-btn">
                        <?php echo do_shortcode('[gd_ninja_forms form_id="2" text="Contact Form" post_contact="1" output="button"]'); ?>
                </div>

                <div class="header-contact-details">
                        <a class="work-sans-fonts font-s-med rm-txt-dec " href="tel:<?php echo do_shortcode( '[gd_post_meta key="phone" show="value-raw" no_wrap="1"]');?>">
                            <i class="fas fa-phone-alt"></i>
                            <?php echo do_shortcode( '[gd_post_meta key="phone" show="value-raw" no_wrap="1"]');?>
                        </a>
                        <a class="work-sans-fonts font-s-med rm-txt-dec " href=" <?php echo do_shortcode( '[gd_post_meta key="website" show="value-raw" no_wrap="1"]');?>" target="_blank">
                            <i class="fas fa-globe"></i>
                            Website
                        </a>
                </div>
            </div>
            
            <?php 
              
                echo "<br>";
                echo get_the_title();
                echo get_the_content(  ); 
                echo do_shortcode( '[gd_single_taxonomies]' );
                echo do_shortcode( '[gd_single_tabs]');
                
                
                $testing_val = get_post_custom_values('phone', get_the_ID()); 

                echo $testing_val;
                
                

               
            ?>



        </div>
        
    </div>
    <?php
}
?>
<div id='site-sidebar'>
            <?php get_sidebar(); ?>
        </div>
<?php
get_footer();
?>