<?php 
get_header(); 
?>
<div class="body-container general-page-template">
    <div class="row-container">
        <?php 
            while(have_posts()){
                the_post(); 
                ?>
                    <h1><?php the_title();?></h1>
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

