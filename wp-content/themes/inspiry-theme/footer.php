
    <footer class="off-white-bc footer">

        <div class="footer-menu-row row-container light-grey">
          <div class="trade-nav">
            <h6 class="footer-menu-title">
              Trade
            </h6>
            <?php
                  wp_nav_menu( array( 
                      'theme_location' => 'footer-trade-menu'
                    )); 
            ?>
          </div>

          <div class="help-info-nav">
            <h6 class="footer-menu-title">
              Help & Info
            </h6>
            <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-help-info'
              ) )
            ?>

          </div>

          <div class="Store">
            <h6 class="footer-menu-title">
              Store
            </h6>
            <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-store'
              ) )
            ?>

          </div>

          <div class="ways-to-shop">
            <h6 class="footer-menu-title">
              Ways to Shop
            </h6>
            <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-ways-to-shop'
              ) )
            ?>

          </div>

          <div class="ideas-insipiration">
            <h6 class="footer-menu-title">
              IDEAS & INSPIRATION
            </h6>
            <?php 
              wp_nav_menu( array(
                'theme_location' => 'footer-ideas-inspiration'
              ) )
            ?>
            <div class="social-media-footer">
                    <h6 class="column-s-font regular">Get social with us</h6>
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
        <div class="copyright-container row-container light-grey">
              <div>© Copyright 2019 Inspiry NZ. All rights reserved. <a href="https://webduel.co.nz" rel="nofollow" target="_blank" class="dark-green rm-txt-dec"> Build By WebDuel</a></div>
        </div>
    </footer>

<?php wp_footer();?>
<script>
  document.write(
    '<script src="http://' +
      (location.host || '${1:localhost}').split(':')[0] +
      ':${2:35729}/livereload.js?snipver=1"></' +
      'script>'
  );
</script>
</body>
</html>