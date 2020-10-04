<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
    
    <meta name="robots" content="noindex">
    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="profile" href="https://gmpg.org/xfn/11"/>
    <?php wp_head(); ?>
</head>
<body <?php body_class( );?>>
    
    <section class="header">
        <div class="top-banner beige-color-bc">
            <div class="wishlist">
               <a href="<?php echo get_home_url().'/bigcommerce-wishlist' ?>" class="text-decoration-none dark-grey">
               <span class="dashicons dashicons-heart"></span>
               </a> 
              
            </div>
            <div class="login-area playfair-fonts font-s-regular profile-trigger ">
                <a href="http://localhost/inspiry/login/" class="profile-name-value text-decoration-none dark-grey">
                    <?php 
                        if(is_user_logged_in()){
                            global $current_user; wp_get_current_user();  
                            ?>
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
                            <?php
                        }
                        else{
                            ?>
                                <span class="dashicons dashicons-admin-users"></span> LOGIN / REGISTER
                            <?php
                        }
                    ?>
                </a>
            </div>
            <div class="shopping-cart playfair-fonts font-s-regular">
                <a href="http://localhost/inspiry/cart/" class="text-decoration-none dark-grey">
                     <span class="dashicons-before dashicons-cart"></span> SHOPPING CART
                </a>
            </div>
            <div class="shopping-cart playfair-fonts font-s-regular dark-grey">
               <?php  echo  do_shortcode('[ivory-search id="7686" title="Default Search Form"]');?>
            </div>
        </div>
        <div class="logo-container">
            <img src="http://localhost/inspiry/wp-content/uploads/2020/08/inspiry_logo_transparent.png" alt="Inspiry Logo">
        </div>
        <nav class="navbar margin-elements">
            <?php
               wp_nav_menu(
                    array(
                        'theme_location' => 'inspiry_main_menu'
                    ));
            ?>
        </nav>

        

    </section>