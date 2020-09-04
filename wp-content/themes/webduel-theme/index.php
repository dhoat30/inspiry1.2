<?php get_header();?>
   
    <button class="api-button"> Api Testing</button>
    <h1 class="hello1">hello</h1>
   <?php 
    $curl = curl_init();
    $url = "https://api.bigcommerce.com/stores/zuh5fsa2r/v3/catalog/products/113";
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "x-auth-client: 23k00mq7lb0d2k461j5wr6vh5u9xaur",
        "x-auth-token: 8v5clxzj8a3xmu9fjp0g093t8x4ktco"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      //Only show errors while testing
      //echo "cURL Error #:" . $err;
    } else {
      //The API returns data in JSON format, so first convert that to an array of data objects
      $responseObj = json_decode($response);
      
     $results1 = print_r($responseObj, true);
     $result2 = $results1->data;
     echo $results2;
    }
   echo do_shortcode( '[bigcommerce_product category="Wallpaper"]');
    while(have_posts()){
        the_post(); 
        ?>
        <h1><?php the_title( );?></h1>
        <div><?php the_content();?></div>
        <?php
    }

    $wallpaper = new WP_Query(array(
        'posts_per_page'=> 5, 
        'category_name' => 'Wallpaper'
    )); 
    while($wallpaper->have_posts()){ 
        $wallpaper->the_post(); 
       echo the_title();
    }
    
   ?>

<?php get_footer();?>