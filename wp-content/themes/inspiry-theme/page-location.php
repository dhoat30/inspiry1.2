<?php 
get_header(); 

  while(have_posts()){
    the_post(); 
    ?>
    <div class="body-container">
      <div class='row-container white-bc row-padding'>
          <h1 class="center-align section-ft-size"><?php the_title();?></h1>
          <div><?php the_content();?></div>
      </div>
    </div>
    
    <?php
}

get_footer();
?>