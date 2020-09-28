<?php 
get_header(); 

  while(have_posts()){
    the_post(); 
    ?>

    <div class="body-container">

    <h1 class="center-align section-ft-size margin-elements"><?php the_title();?></h1>
    <div class="location-page-map">
      <?php
        echo do_shortcode( '[gd_map map_type="directory" width=100% height=300 search_filter=1 cat_filter=1 post_type_filter=1]');
      ?>
      
    </div>
      <div class='row-container white-bc row-padding'>
          <div><?php the_content();?></div>
      </div>
    </div>
    
    <?php

}

get_footer();
?>