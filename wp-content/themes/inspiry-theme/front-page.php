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
                                                </div>
                                        </div>

                                            <?php

                                        
                                        }


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

    <div class="loving-section row-section margin-row">
        <div class="underline-dg"></div>

        <div class="lg-font-sz center-align">What We’re Loving</div>
        <div class="flex">
            <div class="cards">
                <div>
                    <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/PICTCAB803-1.jpg" alt="Khroma">                      
                    <a class="rm-txt-dec center-align" href="<?php echo get_site_url();?>">Shop Now <i class="fal fa-arrow-right"></i> </a>                       
                </div>
            </div>

            <div class="cards">
                <div>
                    <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/PICTCAB803-1.jpg" alt="Khroma">                      
                    <a class="rm-txt-dec" href="<?php echo get_site_url();?>">Shop Now <i class="fal fa-arrow-right"></i> </a>                       
                </div>
            </div>

            <div class="cards">
                <div>
                    <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/PICTCAB803-1.jpg" alt="Khroma">                      
                    <a class="rm-txt-dec" href="<?php echo get_site_url();?>">Shop Now <i class="fal fa-arrow-right"></i> </a>                       
                </div>
            </div>
                                
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