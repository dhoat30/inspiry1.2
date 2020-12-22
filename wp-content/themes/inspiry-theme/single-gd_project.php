<?php 
get_header(); 



  while(have_posts()){
    the_post(); 
    $postID = get_the_ID(); 
    ?>

<div class="single-project-page">
        
    <div class="trade-hero">
        <img src="<?php  echo get_the_post_thumbnail_url( $postID, 'full' )  ?> " alt="<?php echo get_the_title();?>">
    </div>

    <div class="row-container trade-snippet-row">
            
        <div class="trade-header-section">    
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
                    <?php 
                    $tradeID = geodir_get_post_meta($postID, 'gd_place', true);
                    ?>
                    <a href="<?php echo get_the_permalink($tradeID);?>" class="rm-txt-dec add-txt-dec">
                        <i class="fal fa-hammer"></i>
                        <?php   
                            echo get_the_title($tradeID);
                            
                        ?>
                    </a> 
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

                <div>  <p class="share-section roboto-font regular-text">Share: <?php echo do_shortcode( '[Sassy_Social_Share]' );?></p>
                </div>
            </div>  
                
            
        </div>   
            
            
    </div>
        
        
    
    <div class="row-container trade-main-row">
        <div class="trade-middle-column">
            <div class="gallery">
                <?php echo do_shortcode('[gd_post_images ajax_load="1" type="gallery" slideshow="1" controlnav="1" animation="slide" show_title="1" link_to="lightbox"]');?>
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

