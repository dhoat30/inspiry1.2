<?php 
get_header(); 
?>
    <div class="row-container margin-row">
        <h1 class="playfair-fonts regular center-align lg-font-sz">Log In</h1>
        <?php
        echo do_shortcode("[bigcommerce_signin_form]");
        ?>
    </div>  

<?php
 get_footer(); 
?>