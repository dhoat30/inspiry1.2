<?php get_header(); 
?>
<section class="inspiry-blogs">
    <!--first section --> 
    <section class="row-container first-section">
        <div class="sidebar">
   
                <?php echo do_shortcode('[facetwp facet="blogs"]');?>
                <?php echo do_shortcode('[facetwp template="blogs"]');?>
        </div>
        <div class="flex">
            <?php 

                            $argsBlog = array(
                            'post_type' => 'blogs',
                            'posts_per_page' => 6,
                            'facetwp' => true,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'category',
                                    'field'    => 'slug',
                                    'terms'    => array('interior-inspiration', 'interior-advice'),
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
                        <a class="rm-txt-dec" href="<?php the_permalink();?>">
                            <div class="overlay"></div>
                            <img src="<?php echo get_the_post_thumbnail_url(null,"full"); ?>" alt="Khroma">                      
                            <div class="font-s-med center-align"><?php the_title(); ?></div>
                        </a>
                       
                    </div>
                </div>
            
                <?php 

                }
                wp_reset_postdata();
                ?>
        
        </div>
    </section>

    <!--second section --> 
    <section class="row-container second-section">
        
        <div class="flex">
            <?php 

                        $argsBlog = array(
                            'post_type' => 'blogs',
                            'posts_per_page' => 2,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'category',
                                    'field'    => 'slug',
                                    'terms'    => array('wallpaper', 'fabric'),
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
                            <div class="font-s-med center-align text-padding"><?php the_title(); ?></div>
                            <div class="font-s-regular work-sans-fonts center-align text-padding"><?php 
                             echo wp_trim_words(get_the_content(), 15) ;?> </div>
                             <a class="button btn-dk-green rm-txt-dec" href="<?php the_permalink();?>">READ THE POST</a>
                    </div>
                </div>
            
                <?php 

                }
                wp_reset_postdata();
                ?>
        
        </div>
    </section>

    <!--third section --> 
    <section class="row-container third-section">
        <div class="section-ft-size">The Latest </div>
        <div class="flex">
            <?php 

                        $argsBlog = array(
                            'post_type' => 'blogs',
                            'posts_per_page' => 4,
                                'orderby' => 'date', 
                                'order' => 'ASC'
                        );
                        $Blog = new WP_Query( $argsBlog );

                        while($Blog->have_posts()){ 
                            $Blog->the_post(); 

            ?>      
                <div class="cards">
                    <div>   
                            
                            <a class="rm-txt-dec" href="<?php the_permalink();?>">  
                                <img src="<?php echo get_the_post_thumbnail_url(null,"full"); ?>" alt="Khroma">                      
                                <div class="font-s-regular"><?php the_title(); ?></div>
                            </a>
                    </div>
                </div>
            
                <?php 

                }
                wp_reset_postdata();
                ?>
        
        </div>
    </section>

    <!--fourth section --> 
    <section class="fourth-section">
        <div class="flex-container">
             <div class="nav-buttons">
                 <button class="border-none button-border">Wallpaper</button>
                 <button class="border-none">Furniture</button>
                 <button class="border-none">Homeware</button>
             </div>   
            <div class="font-s-med">The Latest </div>
            <div class="flex wallpaper --visible-flex">
            
                <?php 

                            $argsBlog = array(
                                'post_type' => 'shop-my-fav',
                                'posts_per_page' => 6,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'favorite',
                                        'field'    => 'slug',
                                        'terms'    => array('wallpaper'),
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
                                
                                <a class="rm-txt-dec" href="<?php echo get_field('link');?>">  
                                    <img src="<?php echo get_the_post_thumbnail_url(null,"full"); ?>" alt="Khroma">                      
                                    <div class="font-s-regular center-align"><?php the_title(); ?></div>
                                    <a class="button btn-dk-green rm-txt-dec" href="<?php echo get_field('link');?>">SHOP NOW</a>

                                </a>
                        </div>
                    </div>
                
                    <?php 

                    }
                    wp_reset_postdata();
                    ?>
            
            </div>
            <div class="flex furniture">
            
                <?php 

                            $argsBlog = array(
                                'post_type' => 'shop-my-fav',
                                'posts_per_page' => 6,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'favorite',
                                        'field'    => 'slug',
                                        'terms'    => array('furniture'),
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
                                
                                <a class="rm-txt-dec" href="<?php echo get_field('link');?>">  
                                    <img src="<?php echo get_the_post_thumbnail_url(null,"full"); ?>" alt="Khroma">                      
                                    <div class="font-s-regular center-align"><?php the_title(); ?></div>
                                    <a class="button btn-dk-green rm-txt-dec" href="<?php echo get_field('link');?>">SHOP NOW</a>

                                </a>
                        </div>
                    </div>
                
                    <?php 

                    }
                    wp_reset_postdata();
                    ?>
            
            </div>
            <div class="flex homeware">
            
                <?php 

                            $argsBlog = array(
                                'post_type' => 'shop-my-fav',
                                'posts_per_page' => 6,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'favorite',
                                        'field'    => 'slug',
                                        'terms'    => array('homeware'),
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
                                
                                <a class="rm-txt-dec" href="<?php echo get_field('link');?>">  
                                    <img src="<?php echo get_the_post_thumbnail_url(null,"full"); ?>" alt="Khroma">                      
                                    <div class="font-s-regular center-align"><?php the_title(); ?></div>
                                    <a class="button btn-dk-green rm-txt-dec" href="<?php echo get_field('link');?>">SHOP NOW</a>

                                </a>
                        </div>
                    </div>
                
                    <?php 

                    }
                    wp_reset_postdata();
                    ?>
            
            </div>
        </div>
        
    </section>
    
    <!--social meda section --> 

    <section class="row-container social-section">
        <div class="font-s-regular center-align">Inspiry On Instagram</div>
            <script src="https://assets.juicer.io/embed.js" type="text/javascript"></script>
        <link href="https://assets.juicer.io/embed.css" media="all" rel="stylesheet" type="text/css" />
        <ul data-per="4" class="juicer-feed" data-feed-id="gdhoat"><h1 class="referral"><a href="https://www.juicer.io"></a></h1></ul>
        
    </section>

     <!--fifth section --> 
     <section class="fifth-section">
        <div class="font-s-regular center-align">More Inspiry Blogs </div>
        <div class="flex">
            <?php 

                        $argsBlog = array(
                            'post_type' => 'blogs',
                            'posts_per_page' => 3,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'category',
                                    'field'    => 'slug',
                                    'terms'    => array('design-inspiration'),
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
                            
                            <a class="rm-txt-dec" href="<?php the_permalink();?>">  
                                <img src="<?php echo get_the_post_thumbnail_url(null,"full"); ?>" alt="Khroma">                      
                                <div class="font-s-regular"><?php the_title(); ?></div>
                            </a>
                    </div>
                </div>
            
                <?php 

                }
                wp_reset_postdata();
                ?>
        
        </div>
    </section>


    

</section>

<?php 
    get_footer(); 
?>