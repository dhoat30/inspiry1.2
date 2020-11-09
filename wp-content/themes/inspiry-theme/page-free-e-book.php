<?php 
get_header(); 
?>
 <!--second section --> 
 <section class="free-e-book">
    <h1 class="center-align section-ft-size"><?php the_title();?></h1>
    <div class="work-sans-fonts font-s-medium center-align">HOW TO CHANNEL YOUR INNER INTERIOR DESIGNER</div>
    <section class="row-container hero-section">
        <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2020/11/DRAFT-2.jpg" alt="<?php the_title();?>">
    </section>

    <section class="second-section row-container">
        <div>
             <div class="font-s-medium center-align">Find out what defines different popular styles such as mid century modern or scandinavian</div>

        </div>
        <div>
            <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/Cole-Son-Topiary-1.jpg" alt="Cole Son Topiary">
        </div>
    </section>

    <section class="form">
        <div>
            <?php echo do_shortcode('[mc4wp_form id="13731"]'); ?>
        </div>
    </section>
</section>
<?php get_footer(); ?>