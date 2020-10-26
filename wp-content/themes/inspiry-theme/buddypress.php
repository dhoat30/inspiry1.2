<?php 
get_header(); 
?>
<div class="account-page">
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

