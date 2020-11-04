<?php 
get_header(); 
  ?>
    <section class="service-page">
        <div class="hero-section"  style='background: url("<?php echo get_site_url(); ?>/wp-content/uploads/2020/11/AdobeStock_171006496.jpg") no-repeat center top/cover;'>
            <div class="hero-overlay"></div>
        </div>    
        <div class="stamp content">
            <i class="fal fa-home-alt"></i>
            <div class="section-ft-size">INSPIRY</div>
            <div class="font-s-med">Interior Design Services</div>
            <a class="rm-txt-dec button btn-dk-green" href="<?php echo get_site_url();?>">MAKE AN APPOINTMENT</a>
        </div>
    </section>

    <div class="services-section row-section margin-row">
        <div class="heading-line-through">
            <div class="underline-dg"></div>

            <div class="lg-font-sz center-align">Our Services</div>
        </div>
        
        <div class="flex">

        <?php 

            $argsLoving = array(
                'post_type' => 'loving',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => array( 'our-services'),
                    )
                    ), 
                    'orderby' => 'date', 
                    'order' => 'ASC'
            );
            $loving = new WP_Query( $argsLoving );

            while($loving->have_posts()){ 
                $loving->the_post(); 

                ?>      
                        <div class="cards">
                            <div>
                                <img src="<?php echo get_the_post_thumbnail_url(null,"full"); ?>" alt="Khroma">                      
                                <div class="column-s-font"><?php the_title(); ?></div>
                                <div class="paragraph center-align"><?php the_content();?></div>
                            </div>
                        </div>
                   
                <?php 

            }
            wp_reset_postdata();
            ?>
            

          
                                
        </div>                                
    </div>

    <section class="service-page">
        <div class="hero-section"  style='background: url("<?php echo get_site_url(); ?>/wp-content/uploads/2020/11/HELP.jpg") no-repeat center top/cover;'>
            <div class="hero-overlay"></div>
        </div>    
        <div class="stamp content">
            <i class="fal fa-home-alt"></i>
            <div class="section-ft-size">INSPIRY</div>
            <div class="font-s-med">Interior Design Services</div>
            <a class="rm-txt-dec button btn-dk-green" href="<?php echo get_site_url();?>">MAKE AN APPOINTMENT</a>
        </div>
    </section>
  

<?php
get_footer();
?>