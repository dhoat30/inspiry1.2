<?php 
get_header(); 
?>




<?php
    $counter = 1; 
  while(have_posts()){
      $counter2 = $counter++;
    the_post(); 
    ?>
    <div class="body-contaienr">
      <div class="row-container">
        <h3 class=" playfair-fonts"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
        <div><?php the_content();?></div>
       
        <?php echo get_the_post_thumbnail( $id_val[$counter2-1]); ?>
       
        
      </div>

      
    </div>
    <?php
}

?>

<?php 

get_footer();
?>
