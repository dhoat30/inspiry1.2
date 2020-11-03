<?php 
/* Template Name: Places * Template Post Type: post*/ /*The template for displaying full width single posts. */
get_header(); 

?>

    <div class="slider-container">


        <div class="slider">
                        <div class="hero-overlay"></div>
                        
                            
                            <?php 

                                        $args = array(
                                            'post_type' => 'sliders',
                                            'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'category',
                                                    'field'    => 'slug',
                                                    'terms'    => array( 'home-page-hero-slider'),
                                                )
                                                ), 
                                                'orderby' => 'date', 
                                                'order' => 'ASC'
                                        );
                                        $query = new WP_Query( $args );

                                        while($query->have_posts()){ 
                                            $query->the_post(); 

                                            ?>
                                           
                                            <div class="slide"  style='background: url("<?php echo get_the_post_thumbnail_url(null,"full"); ?>") no-repeat
                                        center top/cover;'>
                                                <div class="content">
                                                    <h1 class="lg-font-sz center-align regular"><?php the_title();?></h1>
                                                    <h3 class="work-sans-fonts center-align white section-ft-size regular">
                                                        <?php echo get_field('add_subtitle_');?>
                                                    </h3>
                                                    <a class="rm-txt-dec" href="<?php echo get_field('add_link');?>"><?php echo get_field('add_link_title');?>  <i class="fal fa-arrow-right"></i></a>
                                                </div>
                                         </div>
                                        

                                            <?php

                                       
                                        }
                                        wp_reset_postdata();

                                        ?>
                            
                                
                            
        </div>
                
                    
        <div class="buttons">
                        <button id="prev"><i class="fas fa-arrow-left"></i></button>
                        <button id="next"><i class="fas fa-arrow-right"></i></button>
        </div>
    </div>

    <div class="row-container usp margin-elements">
         <div class="beige-color-bc box-shadow">
            <i class="fal fa-arrow-up"></i>
            Lorem ipsum dolor sit amet
         </div>  
         <div class="beige-color-bc box-shadow">
            <i class="fal fa-arrow-up"></i>
            Lorem ipsum dolor sit amet
         </div>  
         <div class="beige-color-bc box-shadow">
            <i class="fal fa-arrow-up"></i>
            Lorem ipsum dolor sit amet
         </div>                               
    </div>

    <div class="box-section">
        <div class="flex">
            <div class="photo photo-1">
                <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/frida_image_roomshot_kitchen_item_7675.jpg" alt="khroma">                      
            </div>

            <div class="boxes">
                <div>
                    <p class="section-ft-size white center-align"> Creative Kitchen Walls</p>
                    <a class="rm-txt-dec" href="<?php echo get_site_url();?>/products/brands/boras-tapeter/ ?>"> <i class="fal fa-arrow-left "></i>  Shop Borastapeter <i class="fal fa-arrow-up"></i></a>
                </div>
                <div>
                    <p class="section-ft-size white center-align"> Good Things Are Coming</p>
                    <a class="rm-txt-dec" href="<?php echo get_site_url();?>/products/brands/khroma/ ?>"> Shop Khroma <i class="fal fa-arrow-right"></i>  <i class="fal fa-arrow-down"></i></a>
                </div>
            </div>

            <div class="photo photo-2">
                <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/PICTCAB803-1.jpg" alt="Khroma">                      
            </div>

        </div>
        

    </div>


<!--Loving Section -----> 

    <div class="loving-section row-section margin-row">
        <div class="underline-dg"></div>

        <div class="lg-font-sz center-align">What We’re Loving</div>
        <div class="flex">

        <?php 

            $argsLoving = array(
                'post_type' => 'loving',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => array( 'home-page-loving-section'),
                    )
                    ), 
                    'orderby' => 'date', 
                    'order' => 'ASC'
            );
            $loving = new WP_Query( $argsLoving );

            while($loving->have_posts()){ 
                $loving->the_post(); 

                ?>      
                    <a class="rm-txt-dec" href="<?php echo get_field('add_link');?>">
                        <div class="cards">
                            <div>
                                <img src="<?php echo get_the_post_thumbnail_url(null,"full"); ?>" alt="Khroma">                      
                                <a class="rm-txt-dec center-align" href="<?php echo get_field('add_link');?>">Shop Now <i class="fal fa-arrow-right"></i> </a>                       
                            </div>
                        </div>
                     </a>
                <?php 

            }
            wp_reset_postdata();
            ?>
            

          
                                
        </div>                                
    </div>

    <div class="row-container featured-project-section">
        <div class="underline-dg"></div>

        <div class="lg-font-sz center-align">Featured Project</div>

        <div class="flex">
            <?php 
                $argsPorject = array(
                    'post_type' => 'gd_project', 
                    'posts_per_page' => 4
                );
                $project = new WP_Query( $argsPorject );
    
                while($project->have_posts()){ 
                    $project->the_post(); 
                    ?> 
                            
                       
                    <div class="card">
                         <a href="<?php the_permalink();?>">
                            <div>
                                <img src="<?php echo get_the_post_thumbnail_url();?>" alt="">
                                <div class="hover-overlay"></div>
                                <div class="column-s-font"><?php the_title();?></div>
                            </div>
                        </a>
                    </div>
                    <?php
                    } 
                    wp_reset_postdata();
            ?>
        </div>
    </div>

    <div class="row-container featured-trade-section">
        
       

        <div class="flex">
            <div class="card">
                <div class="column-s-font center-align ft-wt-med">TRADE PROFESSIONALS</div>
                <div class="underline-dg center-align"></div>
                <div class="paragraph center-align">Inspiry Trade <br>
                    List your services with and be apart of our 'Be Inspired' page with your featured projects.
                    <br>Request an information pack<br>
                    trade@inspiry.co.nz
                </div>         
                <a href="<?php echo get_site_url();?>/location" class="rm-txt-dec button btn-dk-green">View our full list of Trade Professionals</a>
            </div>            
          
            <?php 
                $argsTrade = array(
                    'post_type' => 'gd_place', 
                    'posts_per_page' => 2
                );
                $trade = new WP_Query( $argsTrade );
    
                while($trade->have_posts()){ 
                    $trade->the_post(); 
                    ?> 
                            
                       
                    <div class="card">
                         <img class="gallery" src="<?php echo get_the_post_thumbnail_url(null, 'full');?>" alt="Trade Proffesionals">
                         <div class="logo">
                             
                                <?php
                                $variable =  do_shortcode('[gd_post_meta key="logo" show="value-raw" no_wrap="1" alignment="left"]');
                                $variable = substr($variable, 0, strpos($variable, "|"));
                                ?>
                                <img src="<?php echo  $variable?>" alt="">
                         </div>
                         <div class="font-s-med center-align regular light-grey"><?php the_title();  ?></div>
                        
                         <table>
                             <tr>
                                 <th>LOCATION:</th>
                                 
                                 <td><?php echo do_shortcode('[gd_post_meta key="city" show="value-raw" no_wrap="1" alignment="left"]');?></td>
                             </tr>
                             <tr>
                              <th>PHONE:</th>
                              
                              <td>
                              <a class="rm-txt-dec" href='tel:<?php echo do_shortcode('[gd_post_meta key="phone" show="value-raw"  no_wrap="1" alignment="left"]');?>'>
    
                              <?php echo do_shortcode('[gd_post_meta key="phone" show="value-raw" no_wrap="1" alignment="left"]');?>    </td>
                              </a> 
                            </tr>

                             <tr>
                                 <th>EMAIL:</th>

                                 <td>
                                 <a class="rm-txt-dec" href='mailto:<?php echo do_shortcode('[gd_post_meta key="email" show="value-raw"  no_wrap="1" alignment="left"]');?>'>
    
                                 <?php echo do_shortcode('[gd_post_meta key="email" show="value-raw" no_wrap="1" alignment="left"]');?>
                                 </a> 
                                </td>
                                 
                             </tr>

                             <tr>
                                 <th>WEBSITE:</th>
                                 <td>
                                 <a class="rm-txt-dec" href='mailto:<?php echo do_shortcode('[gd_post_meta key="email" show="value-raw"  no_wrap="1" alignment="left"]');?>'>

                                 <?php echo do_shortcode('[gd_post_meta key="website" show="value-strip" no_wrap="1" alignment="left"]');?>
                                 </a> 
                                </td>
                             </tr>
                         </table>

                    </div>
                    <?php
                    } 
            ?>
        </div>
    </div>









               







<script>
        const slides = document.querySelectorAll('.slide');
const next = document.querySelector('#next');
const prev = document.querySelector('#prev');
const auto = true; // Auto scroll
const intervalTime = 5000;
let slideInterval;
slides[0].classList.add('current');

const nextSlide = () => {
  // Get current class
  const current = document.querySelector('.current');
  // Remove current class
  current.classList.remove('current');
  // Check for next slide
  if (current.nextElementSibling) {
    // Add current to next sibling
    current.nextElementSibling.classList.add('current');
  } else {
    // Add current to start
    slides[0].classList.add('current');
  }
  setTimeout(() => current.classList.remove('current'));
};

const prevSlide = () => {
  // Get current class
  const current = document.querySelector('.current');
  // Remove current class
  current.classList.remove('current');
  // Check for prev slide
  if (current.previousElementSibling) {
    // Add current to prev sibling
    current.previousElementSibling.classList.add('current');
  } else {
    // Add current to last
    slides[slides.length - 1].classList.add('current');
  }
  setTimeout(() => current.classList.remove('current'));
};

// Button events
next.addEventListener('click', e => {
    console.log('clicked');
  nextSlide();
  if (auto) {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, intervalTime);
  }
});

prev.addEventListener('click', e => {
  prevSlide();
  if (auto) {
    clearInterval(slideInterval);
    slideInterval = setInterval(nextSlide, intervalTime);
  }
});

// Auto slide
if (auto) {
  // Run next slide at interval time
  slideInterval = setInterval(nextSlide, intervalTime);
}

    </script>
<?php 

get_footer(); 
?>