<?php get_header(); 
?>
<section class="row-container">
    <div></div>
    <div class="flex">
        <?php 

                    $argsBlog = array(
                        'post_type' => 'blogs',
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
                    $Blog = new WP_Query( $argsBlog );

                    while($Blog->have_posts()){ 
                        $Blog->the_post(); 

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
</section>
<?php 
    get_footer(); 
?>