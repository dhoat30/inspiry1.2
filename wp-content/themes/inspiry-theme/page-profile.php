<?php 
get_header(); 

  while(have_posts()){
    the_post(); 
    ?>
    <div class="body-contaienr">
      <div class="row-container">
        <p><?php the_content();?></p>
      </div>
    </div>
    <?php
}

get_footer();
?>