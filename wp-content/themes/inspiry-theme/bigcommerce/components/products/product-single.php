<?php
/**
 * @var Product $product
 * @var string  $images
 * @var string  $title
 * @var string  $brand
 * @var string  $price
 * @var string  $rating
 * @var string  $form
 * @var string  $description
 * @var string  $sku
 * @var string  $specs
 * @var string  $related
 * @var string  $reviews
 * @version 1.0.0
 */

use BigCommerce\Post_Types\Product\Product;

?>

<!-- data-js="bc-product-data-wrapper" is required. -->
<?php
                    if ( function_exists('yoast_breadcrumb') ) {
                    yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
                    }
                    ?>
<section class="bc-product-single__top" data-js="bc-product-data-wrapper">
  
<div class="bc-product__gallery bc-medium-img">
<?php echo $images; ?>
  <section class="bc-single-product__warranty desktop-warranty">

  <?php  echo $product->get_property('warranty') ;?>
  </section>
</div>
  

	<!-- data-js="bc-product-meta" is required. -->
	<div class="bc-product-single__meta" data-js="bc-product-meta">
    <?php echo $title; ?>
		<?php echo $description; ?>
		<?php echo $specs; ?>

		
                    
					<div class="underline grey margin-elements"></div>
					
					
           

	
		<?php echo $price; ?>
    <?php echo $form; ?>
    <!--design baord button --> 
             <!--custom board post ui-->
             <?php 
                                $existStatus = 'no'; 

                                if(is_user_logged_in( )){ 
                                    $existQuery = new WP_Query(array(
                                        'author' => get_current_user_id(), 
                                        'post_type' => 'boards', 
                                        'meta_query' => array(
                                            array(
                                                'key' => 'saved_project_id', 
                                                'compare' => '=', 
                                                'value' => get_the_id()
                                            )
                                        )
                                    )); 
    
                                    if($existQuery->found_posts){ 
                                        $existStatus = 'yes'; 
                                    }
                                    wp_reset_postdata(  );
                                }

                               
      ?>

<?php 
    $post_id = get_the_id(); 
    $category_list = get_the_terms( $post_id, 'bigcommerce_category'); 

    $category_list_name = wp_list_pluck($category_list, 'name'); 

    //checking wallpaer in the list 
    $wallpaper = in_array('Wallpaper', $category_list_name); 

    //checking fabric in a list
    $fabric = in_array('Fabric', $category_list_name); 

    if($wallpaper ){ 
      ?>
              <!--calculator and sample-->
                <!--  and calculator -->
              
							<div class="product-page-btn-container">
                <!--
                <a href="<?php //echo rtrim(get_the_permalink(),'/').'-sample' ;?>" class="order-sample-button"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2020/08/icon-cut.png" alt="order a sample"> Order a Sample</a>
    -->     
                <a href="#" class="sizing-calculator-button"><i class="far fa-calculator"></i> Wallpaper Calculator</a>       
              </div>
              
      <?php 
    }
    elseif($fabric){ 
      ?>      <!--calculator and sample-->
      <!--  and calculator -->

            <div class="product-page-btn-container">
              <!--
                <a href="<?php //echo rtrim(get_the_permalink(),'/').'-sample' ;?>" class="order-sample-button"><i class="fal fa-cut"></i>  Order a Sample</a>
    
                <a href="#" class="sizing-calculator-button"><i class="far fa-calculator"></i> Fabric Calculator</a>
            -->
              </div>
      <?php 
    }

    ?>


                            
                            

                           
       
		<p class="availability work-sans-fonts regular-text">Availability: <span class="days">7 - 10 Days</span></p>
    
    <p class="share-section work-sans-fonts regular-text">Share: <?php echo do_shortcode( '[Sassy_Social_Share]' );?></p>
    
	</div>
</section>



<section class="bc-single-product__warranty mobile-warranty">

  <?php  echo $product->get_property('warranty') ;?>
</section>


<?php echo $related; ?>







<div class="body-container">
       

    <!--sizing calculator-->
    <div class="overlay-background">
        <div class="calculator-overlay">
        <i class="fal fa-times close"></i>

            <div id="calculator-container">
                <div class="popup-modal wallpaper-calculator-modal is-open">
              
                  <h1>Wallpaper Calculator</h1>
              
              
              <form name="wallpaper_calculator" id="wallpaper-calculator">
                <section>
                  <div>
                    <label for="calc-roll-width">Roll Width<em>*</em> </label>
                    <select name="calc-roll-width" id="calc-roll-width"><option value="37.2">37.2 cm</option><option value="42">42 cm</option><option value="45">45 cm</option><option value="48.5">48.5 cm</option><option value="53">53 cm</option><option value="52">52 cm</option><option value="64">64 cm</option><option value="68">68 cm</option><option value="68.5">68.5 cm</option><option value="70">70 cm</option><option value="90">90 cm</option><option value="95">95 cm</option><option value="100">100 cm</option><option value="140">140 cm</option></select>
                    <label for="calc-roll-height">Roll Length<em>*</em> </label>
                    <select name="calc-roll-height" id="calc-roll-height"><option value="2.65">2.65 cm</option><option value="2.79">2.79 cm</option><option value="3">3 cm</option><option value="5.6">5.6 cm</option><option value="6">6 cm</option><option value="8.5">8.5 cm</option><option value="8.37">8.37 cm</option><option value="9">9 cm</option><option value="10">10 cm</option><option value="10.05">10.05 cm</option><option value="12">12 cm</option><option value="24">24 cm</option></select>
                  </div>
                  <aside>
                    <label for="last-name">Wall width<em>*</em></label>
                    <div class="input-group">
                      <input type="text" name="calc-wall-width1" value="" id="calc-wall-width1" class="form-control" placeholder="Wall 1 width">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-width2" value="" id="calc-wall-width2" class="form-control" placeholder="Wall 2 width">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-width" value="" id="calc-wall-width3" class="form-control" placeholder="Wall 3 width">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-width4" value="" id="calc-wall-width4" class="form-control" placeholder="Wall 4 width">
                          <span class="input-group-addon">m</span>
                      </div>
                  </aside>
                  <aside>
                    <label for="last-name">Wall height<em>*</em></label>
                    <div class="input-group">
                      <input type="text" name="calc-wall-height1" value="" id="calc-wall-height1" class="form-control" placeholder="Wall 1 length">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-height2" value="" id="calc-wall-height2" class="form-control" placeholder="Wall 3 length">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-height3" value="" id="calc-wall-height3" class="form-control" placeholder="Wall 3 length">
                          <span class="input-group-addon">m</span>
                    </div>
                    <div class="input-group">
                      <input type="text" name="calc-wall-height4" value="" id="calc-wall-height4" class="form-control" placeholder="Wall 4 length">
                          <span class="input-group-addon">m</span>
                      </div>
                  </aside>
                </section>
                <section>
                  <label for="address">Repeat<em>(optional)</em></label>
                  <div class="input-group">
                    <input type="text" name="calc-pattern-repeat" value="" id="calc-pattern-repeat" class="form-control">
                    <span class="input-group-addon">cm</span>
                  </div>
                </section>
                <section class="buttons">
                  <button id="estimate-roll" class="button black-background">Calculate</button>
                </section>
                <section class="estimate-result margin-elements">
                      <h3>Result</h3>
                      <p>
                          
                              <span class="calc-round">0</span>&nbsp;
                              <span class="suffix-singular hidden" style="display: none;">roll</span>
                              <span class="suffix-plural">rolls</span>
                       
                      </p>
                </section>
                
                <section class="message margin-elements">
                  <p>Please check your measurements carefully. Inspiry is not responsible for overages or shortages based on this calculator.</p>
                </section>
                
              </form>
              
              
              
              
                </div>
              </div>
        </div>
	  </div>
</div>

