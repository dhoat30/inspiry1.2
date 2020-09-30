<?php 
get_header(); 

while(have_posts()){ 
    the_post(); 
?>  
  <div class="body-container">
    <div class="row-container"></div>
      <h1 class="section-ft-size center-align margin-elements"> <?php the_title(); ?></h1>
      
      <div><?php the_content();?></div>
  </div>
    
  <?php

}



get_footer();

?>