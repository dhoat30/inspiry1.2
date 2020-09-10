<?php 
get_header(); 
?>
    <div class="row-container margin-row">
        <h1 class="playfair-fonts regular center-align page-title-sz"> Cart</h1>
        <?php
        echo do_shortcode("[bigcommerce_cart]");
        ?>
    </div>  

<?php
 get_footer(); 
?>