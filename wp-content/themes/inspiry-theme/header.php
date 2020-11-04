<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
    
    <meta name="robots" content="noindex">
    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="profile" href="https://gmpg.org/xfn/11"/>
    <?php wp_head(); ?>
</head>
<?php 
    global $template;
    //check the template 
    if(is_post_type_archive()) {
        $archive = 'product-archive'; 
    }

?>
<body <?php body_class( );?> data-archive='<?php echo $archive ?>'>
    
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
              
            </div>`
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
            <div class="shopping-cart playfair-fonts font-s-regular">
                <a href="<?php echo get_site_url(); ?>/cart/" class="text-decoration-none dark-grey">
                     <span class="dashicons-before dashicons-cart"></span> SHOPPING CART
                </a>
            </div>
            <div class="shopping-cart playfair-fonts font-s-regular dark-grey">
               <?php  echo  do_shortcode('[ivory-search id="7686" title="Default Search Form"]');?>
            </div>
        </div>

        <!--logo -->
        <div class="logo-container">
            <img src="<?php echo get_site_url(); ?>/wp-content/uploads/2020/08/inspiry_logo_transparent.png" alt="Inspiry Logo">
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
            <!-- design services navbar--> 
            <div class="design-services">
                <!-- design services navbar--> 
                <div class="sub-nav-container">
                    <!--design resources --> 
                    <div>
                        <div class="bold">Design Resources</div>
                            <?php
                            wp_nav_menu(
                                    array(
                                        'theme_location' => 'Design-resources', 
                                        'container_id' => 'design-resources-subnav'
                                    ));
                            ?>
                    </div>
                    <!--design services --> 
                    <div>
                    <div class="bold">Design Services</div>
                        <?php
                        wp_nav_menu(
                                array(
                                    'theme_location' => 'design-services', 
                                    'container_id' => 'design-services-subnav'
                                ));
                        ?>
                    </div>

                    <!--B2b services --> 
                    <div>
                    <div class="bold">B2B Services</div>
                        <?php
                        wp_nav_menu(
                                array(
                                    'theme_location' => 'b2b-services', 
                                    'container_id' => 'b2b-services-subnav'
                                ));
                        ?>
                    </div>

                    <div class="img">
                        <img src="<?php echo get_site_url();?>/wp-content/uploads/2020/11/FUJI-IC.jpg" alt="Design Services">
                    </div>
                </div>
                
            </div>

            
            
        </nav>

        <!--Shop  navbar--> 
        <nav class="navbar margin-elements">
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

   
