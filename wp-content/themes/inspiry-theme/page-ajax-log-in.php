<?php

global $wp;
$current_url = home_url( $wp->request );
echo $current_url;

    if($current_url == 'http://localhost/inspiry/ajax-log-in'){ 
       echo "front page";
       echo '<h1 style="font-size: 100px">Hello</h1>';
       echo $current_url;
    }
?>
    
        <?php 
            while(have_posts()){
                the_post(); 
                ?>
                 
              
                        <?php the_content();?>
                 

                <?php
            }
        ?>
   
   
    
