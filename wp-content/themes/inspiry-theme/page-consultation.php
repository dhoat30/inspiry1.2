<?php 
get_header(); 
  ?>
  <section class="consultation-page">

  
<?php 
    while(have_posts()){ 
        the_post(); 
        the_content();
    }
?>
</section>

<?php
get_footer();
?>