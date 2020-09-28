<?php 
get_header(); 

  while(have_posts()){
    the_post(); 
    ?>
    <div class="body-contaienr">
    <h1 class="center-align section-ft-size playfair-fonts margin-row"><?php the_title();?></h1>

        <div class="row-container contact-us-page">
            <div class="column contact-form">
                <?php the_content();?>
            </div>
            
            <div class="column contact">
                <div class="contact-info">
                    <div class="phone">
                        <a href="tel:08004677479" class="rm-txt-dec"><i class="fas fa-phone-alt"></i> 0800 INSPIRY (467 7479)
                        </a>                   
                    </div>
                    <div class="email">
                        <a href="mailto:hello@inspiry.co.nz" class="rm-txt-dec"><i class="fas fa-envelope"></i> hello@inspiry.co.nz</a>
                    </div>
                    <div class="chat">
                        <a href="#" class="rm-txt-dec"><i class="fas fa-comment-dots"></i> Live Chat</a>
                    </div>
                    <div class="business-hours">
                        <a href="#" class="rm-txt-dec"><i class="fas fa-clock"></i> Monday - Friday: 9:00am - 4:30pm </a>
                    </div>
                </div>

                <div class="social-media">
                    <h4 class="column-s-font regular">Get social with us</h4>
                    <div class="underline-dg"></div>
                    <div class="social-media-container">
                        <a href="https://www.facebook.com/inspiryliveaninspiredlife/" target="_blank"><i class="fab fa-facebook-square"></i></a>
                        <a href="" target="_blank"><i class="fab fa-instagram-square"></i></a>
                        <a href="" target="_blank"><i class="fab fa-pinterest-square"></i></a>
                        <a href="" target="_blank"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
      </div>
      <div>

      </div>
    </div>
    <?php
}

get_footer();
?>