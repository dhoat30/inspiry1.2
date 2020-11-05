<?php 
get_header(); 
?>
<div class="body-container index-page">
    <div class="row-container">
        <?php 
            while(have_posts()){
                the_post(); 
                ?>
                    <h1 class="lg-font-sz regular center-align"><?php the_title();?></h1>
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

