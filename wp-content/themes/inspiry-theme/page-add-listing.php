<?php 
get_header(); 
  ?>
  <div class="add-listing-page">
    <div class="row-container">
      <?php
        while(have_posts()){
        the_post(); 
      ?>
      <h1 class="section-ft-size center-align"><?php the_title();?></h1>
      <div class="add-listing-form"><?php the_content();?></div>

    </div>
  </div>
  
    
    <?php
}

get_footer();
?>