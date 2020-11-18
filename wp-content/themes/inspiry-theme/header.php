<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PS7XFHN');</script>
<!-- End Google Tag Manager -->

    <meta name="robots" content="noindex">
    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="profile" href="https://gmpg.org/xfn/11"/>
    <?php wp_head(); ?>
    <!-- Start of inspiry Zendesk Widget script -->
        <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=976538b8-22ee-4f8b-ad47-cb919ef8094b"> </script>
        <!-- End of inspiry Zendesk Widget script -->
</head>
<?php 
    global $template;
    //check the template 
    if(is_post_type_archive()) {
        $archive = 'product-archive'; 
    }

?>
<body <?php body_class( );?> data-archive='<?php echo $archive ?>'>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PS7XFHN"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <section class="header">
        <div class="top-banner beige-color-bc">
            <?php 
            if(is_user_logged_in()){
                global $current_user; wp_get_current_user();  
                ?>
                    <div class="wishlist">
               <a href="<?php echo get_home_url().'/bigcommerce-wishlist' ?>" class="text-decoration-none dark-grey">
               <span class="dashicons dashicons-heart"></span>
               </a> 
              
                </div>
                <?php 
            }
            ?>
            
            <div class="login-area playfair-fonts font-s-regular profile-trigger ">
               
                    <?php 
                        if(is_user_logged_in()){
                            global $current_user; wp_get_current_user();  
                            ?> <a href="<?php echo get_site_url(); ?>/login/" class="profile-name-value text-decoration-none dark-grey">
                                 <span class="dashicons dashicons-admin-users"></span> <?php echo  $current_user->display_name;?>
                                 <i class="fas fa-chevron-down regular arrow-icon"></i>
                                <nav>
                                <?php
                                    wp_nav_menu( array( 
                                        'theme_location' => 'my-account-nav-top', 
                                        'container_class' => "my-account-nav"
                                    )); 
                                ?>
                                </nav>  
                                </a>       
                            <?php
                        }
                        else{
                            ?><a href="<?php echo get_site_url(); ?>/login/" class="login-tag text-decoration-none dark-grey" data-root-url='<?php echo get_home_url()?>'>
                                <span class="dashicons dashicons-admin-users"></span> LOGIN / REGISTER
                        </a>
                            <?php
                        }
                    ?>
                
            </div>
            <div class="shopping-cart playfair-fonts font-s-regular desktop-visible">
                <a href="<?php echo get_site_url(); ?>/cart/" class="text-decoration-none dark-grey">
                     <span class="dashicons-before dashicons-cart"></span> SHOPPING CART
                </a>
            </div>
            <div class="shopping-cart playfair-fonts font-s-regular mobile-visible">
                <a href="<?php echo get_site_url(); ?>/cart/" class="text-decoration-none dark-grey">
                     <span class="dashicons-before dashicons-cart"></span> 
                </a>
            </div>
            <div class="playfair-fonts font-s-regular dark-grey">
               <?php  echo  do_shortcode('[ivory-search id="7686" title="Default Search Form"]');?>
            </div>
        </div>

        <!--logo -->
        <div class="logo-container">
            <a href="<?php echo get_site_url(); ?>">
                <img class="logo" src="<?php echo get_site_url(); ?>/wp-content/uploads/2020/11/Inspiry_Logo-transparent-1.png" alt="Inspiry Logo">
            </a>
            <?php 
             global $post;
             $post_slug = $post->post_name;
                if($post_slug == 'inspiry-blogs'){ 
                    ?>
                    <img class="slogan" src="<?php echo  get_site_url();?>/wp-content/uploads/2020/11/Inspiry_Slogan.jpg" alt="Slogan">
                    <?php
                }
                
            ?>
        </div>

        <!--top navbar --> 
        <nav class="navbar margin-elements top-navbar">
            <?php
               wp_nav_menu(
                    array(
                        'theme_location' => 'top-navbar', 
                        'container_id' => 'top-navbar'
                    ));
            ?>
           

            
            
        </nav>

        <!--Shop  navbar--> 
        <nav class="navbar">
            <?php
               wp_nav_menu(
                    array(
                        'theme_location' => 'inspiry_main_menu', 
                        'container_id' => 'cssmenu'
                    ));
            ?>
        </nav>
                 
        <div class="login-overlay"> 
            <i class="fal fa-times"></i>   
            <div class="form-content">
                
            </div>      
        </div>

    </section>

   
