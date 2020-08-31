<?php get_header();?>
   
   <?php 
    while(have_posts()){
        the_post(); 
        ?>
        <h1><?php the_title( );?></h1>
        <div><?php the_content();?></div>
        <?php
    }
   ?>

<?php get_footer();?>