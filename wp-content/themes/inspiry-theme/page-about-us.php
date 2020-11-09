<?php 
get_header(); 
?>
<section class="about-us">
    <section class="hero-section">
        <div class="slider-container">
            <div class="slider">
                            <div class="hero-overlay"></div>
                            
                                
                             
                                            
                                                <div class="slide"  style='background: url("<?php echo get_site_url();?>/wp-content/uploads/2020/11/Untitled-1-1.jpg") no-repeat
                                                    center top/cover;'>
                                                    
                                            </div>
                                            <div class="slide"  style='background: url("<?php echo get_site_url();?>/wp-content/uploads/2020/11/Untitled-2.jpg") no-repeat
                                                    center top/cover;'>
                                                   
                                            </div>                  
            </div>
                
                    
            <div class="buttons">
                            <button id="prev"><i class="fas fa-arrow-left"></i></button>
                            <button id="next"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    </section>


    <!-- second section --> 
    <div class="lg-font-sz center-align regular margin-elements"><?php the_title();?></div>

    <section class="row-container second-section">
        <div class="content">
            <div class="first">
                <div class="font-s-med bold"><span style="color: #891e32;" class="playfair-fonts">Inspiry:</span> A hub for all things interior design...</div>
                <div class="font-s-regular work-sans-fonts">We are passionate about interior design and helping people with their design projects, so we've created a hub where you can shop for some of the best home furnishing products available locally and internationally; a hub where we share oodles of inspiration, tips and design ideas that you can save to your own personal design account;  a hub to link you directly with local Trade Professionals to view their profile and projects history and make contact. </div>
            </div>

            <div class="second">
                <div class="font-s-med bold">We have made it easy for you to <span style="color: #891e32;" class="playfair-fonts">connect....</span></div>
                <div class="font-s-regular work-sans-fonts">With Trade Professionals and Interior Designers for all your building, renovation or decorating project requirements.  Then with a click you can shop our online store for our hand picked and extensive range of interior decor products including soft furnishing fabrics for upholstery and drapery, wallpaper, furniture, lighting, designer rugs and homewares. Finally, connect with us to get expert advice when you need it on hand with our live chat.</div>
            </div>
           
        </div>
        <div>
            <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/INSPIRY-MAGAZINE-COVER-9.jpg" alt="About Us Poster">
        </div>

    </section>

    <section class="row-container second-flex">
        
        <div>
            <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/INSPIRY-MAGAZINE-COVER-6.jpg" alt="About Us Poster">
        </div>
        <div class="content">
            <div class="first">
                <div class="font-s-med bold">Our <span style="color: #e9831f;" class="playfair-fonts">Brands </span>include...</div>
                <div class="font-s-regular work-sans-fonts">Arte, Andrew Martin, Bill Beaumont, Cole and Son, Christian Lacroix, Designers Guild, French Country, Hooked on Walls, Inspiry Home, Loloi and Mind the Gap. You can order sample-cuttings of fabrics and wallpapers to enable you to see, touch and feel the products. Use our wallpaper and fabric calculator to estimate how much you need. Also our experienced team are ready to help you with your queries and purchases via live chat. We believe that Inspiry has something for everyone.</div>
            </div>

            <div class="second">
                <div class="font-s-med bold">We'll keep you <span style="color: #e9831f;" class="playfair-fonts">informed with the latest..</span></div>
                <div class="font-s-regular work-sans-fonts">Product releases and trends; sales and clearances; tips and design ideas; new services as they are added and oodles of inspiring images for you to live an inspired life!  We love options so either visit our Blog, sign up to our Newsletter or we have a free e-book about Discovering Your Inner Designer - sign up you'll receive 10% off your first purchase! We've got you covered!</div>
            </div>
           
        </div>

    </section>
</section>
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
<?php get_footer(); ?>