<?php 
get_header(); 



  while(have_posts()){
    the_post(); 
    $postID = get_the_ID(); 
    ?>

<div>
        
    <div class="trade-hero">
        <img src="<?php  echo get_the_post_thumbnail_url( $postID, 'full' )  ?> " alt="<?php echo get_the_title();?>">
    </div>

    <div class="row-container">
            
        <div class="trade-header-section">
            <div class="trade-profile-img">
                <?php 
                    $logo = geodir_get_post_meta($postID, 'logo', true);
                ?>
                <img src="<?php echo substr($logo, 0, strpos($logo,".jpg"));?>.jpg" alt="">
            </div>
                
            <div class="header-middle-column">

                <?php
                if ( function_exists('yoast_breadcrumb') ) {
                yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
                }
                ?>
                <div class="header-title">
                    <h2 class="section-ft-size board-heading-post-id" data-postid='<?php echo get_the_id()?>'><?php echo get_the_title();?></h2>
                </div>
            
                <div class="header-address regular">
                    <i class="fal fa-map-marker-alt"></i>
                    <?php echo do_shortcode('[gd_post_address show="value" address_template="%%city%%"]'); ?>         
                </div>

                <div>  <p class="share-section roboto-font regular-text">Share: <?php echo do_shortcode( '[Sassy_Social_Share]' );?></p>
                </div>
            </div>  
                
            <div class="header-contact">
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
                
                <div class="header-contact-btn">
                        <?php echo do_shortcode('[gd_ninja_forms form_id="2" text="Contact Form" post_contact="1" output="button"]'); ?>
                </div>
                <!--
                <div class="header-contact-details">
                        <a class="roboto-font font-s-med rm-txt-dec " href="tel:<? //php echo do_shortcode( '[gd_post_meta key="phone" show="value-raw" no_wrap="1"]');?>">
                            <i class="fas fa-phone-alt"></i>
                            <?php //echo do_shortcode( '[gd_post_meta key="phone" show="value-raw" no_wrap="1"]');?>
                        </a>
                        <a class="roboto-font font-s-med rm-txt-dec " href=" <? //php echo do_shortcode( '[gd_post_meta key="website" show="value-raw" no_wrap="1"]');?>" target="_blank">
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
                    <li class="trade-nav-link">Gallery</li>
                </ul>

                <div class="trade-about-nav-content roboto-font font-s-med">
                    <?php 
                    echo get_the_content();
                    ?>
                </div>

                <div class="trade-contact-nav-content">
                    <table class="roboto-font">
                        <tr>
                            <td><i class="fal fa-phone-alt"></i></td>
                            <td class="font-s-med">
                                <a class="rm-txt-dec thin roboto-font" href="tel:<?php echo geodir_get_post_meta($postID, 'phone', true);?>">
                                    <?php echo geodir_get_post_meta($postID, 'phone', true);?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fal fa-envelope"></i></td>
                            <td class="font-s-med">
                                <a class="rm-txt-dec thin roboto-font" href="mailto:<?php echo geodir_get_post_meta($postID, 'email', true);?>">
                                    <?php echo geodir_get_post_meta($postID, 'email', true);?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fal fa-globe"></i></td>
                            <td class="font-s-med">
                                <a class="rm-txt-dec thin roboto-font" href="<?php echo geodir_get_post_meta($postID, 'website', true);?>"><?php echo geodir_get_post_meta($postID, 'website', true);?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fal fa-map-marker-alt"></i></td>
                            <td class="font-s-med thin roboto-font"><?php echo geodir_get_post_meta($postID, 'address', true);
                            echo geodir_get_post_meta($postID, 'regions_covered', true);?></td>
                        </tr>
                           
                    </table>
                    <table>
                    <tr class="social-media-icons">
                                
                            <td><a href='<?php echo  do_shortcode( '[gd_post_meta key="facebook" show="value-raw" no_wrap="1"]');?>' target="_blank"><?php echo  do_shortcode( '[gd_post_meta key="facebook" show="icon" no_wrap="1"]');?></a></td>
                            <td><a href='<?php echo  do_shortcode( '[gd_post_meta key="instagram" show="value-raw" no_wrap="1"]');?>' target="_blank"><?php echo  do_shortcode( '[gd_post_meta key="instagram" show="icon" no_wrap="1"]');?></a></td>
                            <td><a href='<?php echo  do_shortcode( '[gd_post_meta key="twitter" show="value-raw" no_wrap="1"]');?>' target="_blank"><?php echo  do_shortcode( '[gd_post_meta key="twitter" show="icon" no_wrap="1"]');?></a></td>
                        </tr>
                    </table>
                         
                    <?php 
                        echo do_shortcode('[gd_map width="100%" height="425px" maptype="ROADMAP" zoom="0" map_type="auto" post_settings="1"]');

                    ?>
                </div>

                <div class="trade-project-nav-content">


                    <section class="trade-directory row-container">
                    <?php 
                        $projectID = geodir_get_post_meta($postID, 'gd_project', true);
                        //$projectIDArr = split(',', $projectID); 
      
                        $projectIDArr = explode(',', $projectID);

                        foreach ($projectIDArr as $value) {
                            if($value){
                                ?>

           
                            
                
                
                                            <div class="main-cards">
                                                <!-- <div class="section-ft-size">Project Gallery </div>-->
                                                <div class="flex">

                    
                    
                                                    <div class="card project">
                                                            <a href="<?php the_permalink(); ?>">
                                                            <div class="logo">

                                                                    <img src="<?php echo  get_the_post_thumbnail_url( $value, 'small'); ?>" alt="<?php the_title();?>">
                                                                </div>
                                                            </a>
                                                

                                                            <div class="design-board-save-btn-container" data-tracking-data='{"post_id":"<?php echo $value?>","name":"<?php echo get_the_title($value); ?>"}' <?php echo $link_attributes; ?>>
                                                                        <i data-exists='<?php echo $existStatus?>' class="fal fa-plus open-board-container" ></i>
                                                            </div>

                                                            <a class='rm-txt-dec' href="<?php echo get_the_permalink( $value ); ?>">

                                                                <div class="title regular font-s-med"><?php echo get_the_title( $value );?></div>
                                                            </a>
                                                            <a href="<?php echo get_the_permalink($postID); ?>" class='rm-txt-dec font-s-regular thin roboto-font add-txt-dec'>
                                                              <?php echo get_the_title($postID); ?>
                                                          </a>

                                                    </div>
                        
                    
                                                </div>
                                            </div>
                        
                                <?php
                            }
                            else{
                                
                                echo do_shortcode( '[gd_linked_posts title="" link_type="from" post_type="gd_project" sort_by="az" title_tag="h3" layout="3" post_limit="50"]');

                            }
                            
                        }              
                    ?>
                    </section>
                    
                </div>

                <div class="trade-gallery-nav-content">
                    <?php echo do_shortcode('[gd_post_images type="gallery" ajax_load="0" slideshow="1" show_title="1" animation="slide" controlnav="1" link_to="lightbox"]'); ?>
                        
                </div>



            </div>
        </div>
            
    </div>

    <div>

    </div>



        
</div>















    
    <?php
}
?>
<?php 

?>

<?php
get_footer();
?>

