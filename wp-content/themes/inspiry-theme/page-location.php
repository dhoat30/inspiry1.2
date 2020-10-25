<?php 
get_header(); 

  while(have_posts()){
    the_post(); 
    ?>

    <div class="trade-listing">

    <h1 class="center-align section-ft-size margin-elements"><?php the_title();?></h1>
    <div class="location-page-map">
      <?php echo do_shortcode('[gd_map width="100%" height="425px" maptype="ROADMAP" zoom="0" map_type="auto" post_settings="1"]');?>
    </div>
      <div class='row-container white-bc row-padding'>
     
      <?php the_content();?>
        <div class="geo-directory-archive-flex">
          <div class="filters"> 
          <?php echo do_shortcode('[gd_categories post_type="0" max_level="1" max_count="all" max_count_child="all" title_tag="h4" sort_by="count"]');?>

          </div>
          <div class="cards">
            <?php echo do_shortcode('[gd_listings post_type="gd_place" post_limit="20" add_location_filter="1" sort_by="az" title_tag="h3" layout="2" with_pagination="1" bottom_pagination="1"]');?>
          </div>
          
        </div>
        
      </div>
    </div>
    
    <?php
}

get_footer();
?>