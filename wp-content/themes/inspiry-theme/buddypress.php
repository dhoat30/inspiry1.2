<?php 
get_header(); 
?>
<div class="body-container">
    <div class="row-container">
        <?php 
            while(have_posts()){
                the_post(); 
                ?>
                    <div>
                        <?php the_content();?>
                    </div>

                <?php
            }
        ?>
    </div>
</div>
    

<?php 
    get_footer();
?>

