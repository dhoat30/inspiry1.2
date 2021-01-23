<?php 
get_header(); 

  ?>
    <section class="trade-directory row-container">
      <div class="sidebar">
        <?php //echo do_shortcode('[gd_categories post_type="0" max_level="1" max_count="all" max_count_child="all" title_tag="h4" sort_by="count"]');?>
          <?php  echo do_shortcode('[gd_search]');?>
        <?php echo do_shortcode('[gd_map width="100%" height="425px" maptype="ROADMAP" zoom="0" map_type="auto" post_settings="1"]');?>

      </div>
      <div class="main-cards">
        <!-- <div class="section-ft-size">Project Gallery </div>-->
        <div class="flex">

        <?php 
              $argsTrade = array(
                'post_type' => 'gd_project', 
                'posts_per_page' => -1,
                'facetwp' => true
            );
            $trade = new WP_Query( $argsTrade );

            while($trade->have_posts()){ 
                $trade->the_post(); 
                $postID = get_the_ID();
          ?> 
          
              <div class="card project">
                    <a href="<?php the_permalink(); ?>">
                      <div class="logo">

                              <img src="<?php echo  get_the_post_thumbnail_url( $postID, 'small'); ?>" alt="<?php the_title();?>">
                        </div>
                      </a>
                      <div class="link">
                        <?php 
                          //remove http://
                          $urlStr = geodir_get_post_meta($postID, 'website', true);
                          if(strpos($urlStr, 'https://')){
                            $urlStr = str_replace('https://', "", $urlStr); 
                          }
                          else{
                            $urlStr = str_replace('http://', "", $urlStr);
                          }
                          
                          
                        ?>
                        <a class="rm-txt-dec website-link" target="_blank" href="<?php echo $urlStr;?>">
                        <i class="fal fa-external-link-alt"></i>
                          <?php  echo $urlStr;?> 
                        </a>
                        

                      </div>

                      <div class="design-board-save-btn-container" data-tracking-data='{"post_id":"<?php the_id();?>","name":"<?php echo get_the_title(get_the_id()); ?>"}' <?php echo $link_attributes; ?>>
                                <i data-exists='<?php echo $existStatus?>' class="fal fa-plus open-board-container" ></i>
                      </div>

                      <a class='rm-txt-dec' href="<?php the_permalink(); ?>">

                         <div class="title regular font-s-med"><?php the_title();?></div>
                      </a>

                      <?php 
                      //find the post id of trade proffessional 
                      $placeID = geodir_get_post_meta($postID, 'gd_place', true); 
                      ?>
                      <a href="<?php echo get_the_permalink($placeID); ?>" class='rm-txt-dec font-s-regular thin roboto-font add-txt-dec'>
                          By <?php echo get_the_title($placeID); ?>
                      </a>

              </div>
              
          <?php 
            }
          ?>
        </div>
      </div>
    </section>


    

    <?php


get_footer();
?>