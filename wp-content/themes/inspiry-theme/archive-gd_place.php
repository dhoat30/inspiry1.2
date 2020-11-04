<?php 
get_header(); 
?>
<div class="facetwp-template">
<?php
  while(have_posts()){
    the_post(); 
    
    ?>
              <?php  echo do_shortcode('[facetwp template="trade_template"]');?>

    <div class="body-contaienr">
      <div class="row-container">
        <h1 class="center-align section-ft-size playfair-fonts"><?php the_title();?></h1>
        <h1>hello</h1>
        <?php echo do_shortcode('[facetwp facet="new_facet"]');?>
        <div><?php the_content();?></div>
      </div>
    </div>
    <?php
}
?>
</div>
<?php

get_footer();
?>