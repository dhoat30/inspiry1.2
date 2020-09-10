<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<meta name="robots" content="noindex">
    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11"/>
    <?php wp_head(); ?>
</head>
<body <?php body_class( );?>>
    
    <section class="header">
        <div class="top-banner beige-color-bc">
            <div class="wishlist">
               <a href="#" class="text-decoration-none dark-grey">
               <span class="dashicons dashicons-heart"></span>
               </a> 
            </div>
            <div class="login-area playfair-fonts font-s-regular">
                <a href="http://localhost/inspiry/login/" class="text-decoration-none dark-grey">
                    <span class="dashicons dashicons-admin-users"></span> LOGIN / REGISTER
                </a>
            </div>
            <div class="shopping-cart playfair-fonts font-s-regular">
                <a href="#" class="text-decoration-none dark-grey">
                     <span class="dashicons-before dashicons-cart"></span> SHOPPING CART
                </a>
            </div>
            <div class="shopping-cart playfair-fonts font-s-regular dark-grey">
               <span class="dashicons-before dashicons-search"></span> Search
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