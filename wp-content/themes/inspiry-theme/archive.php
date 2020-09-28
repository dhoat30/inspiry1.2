<?php 
get_header(); 

  while(have_posts()){
    the_post(); 
    ?>
    <div class="body-contaienr">
      <div class="row-container">
        <h1 class="center-align section-ft-size playfair-fonts"><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
        <div><?php the_content();?></div>
      </div>
    </div>
    <?php
}

get_footer();
?>