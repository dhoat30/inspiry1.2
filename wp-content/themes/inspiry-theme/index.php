<?php 
get_header(); 

  while(have_posts()){
    the_post(); 
    ?>
    <h1><?php the_title( 'the title ');?></h1>
    <div><?php the_content();?></div>
    <?php
}

get_footer();
?>