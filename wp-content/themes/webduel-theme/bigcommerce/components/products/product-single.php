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
$post_id = get_the_ID();
$product1 = new \BigCommerce\Post_Types\Product\Product( $post_id );



?>

<!-- data-js="bc-product-data-wrapper" is required. -->
<section class="bc-product-single__top" data-js="bc-product-data-wrapper">
	<?php echo $images; ?>

	<!-- data-js="bc-product-meta" is required. -->
	<div class="bc-product-page-meta" data-js="bc-product-meta">
		<?php echo $title; ?>
		<?php echo $description; ?>
		<h3 class="product-short-description-title work-sans-fonts">DETAILS 
                    </h3>
                    <span class="margin-elements product-short-description work-sans-fonts">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris porttitor, lacus et euismod porttitor, turpis tortor viverra dolor, id hendrerit dui nunc quis nulla. Nunc et nunc non turpis gravida ornare eu eget orci. Aliquam faucibus, sapien a euismod posuere, nibh leo auctor lacus, non scelerisque diam erat tincidunt dolor.
                    </span> 
		<div class="underline grey margin-elements"></div>
		
		<!--calculator and sample-->
		<!--  and calculator -->
	
                
	<a href="<?php echo rtrim(get_the_permalink(),'/').'-sample' ;?>" class="order-sample-button"><img src="http://localhost/inspiry/wp-content/uploads/2020/08/icon-cut.png" alt="order a sample"> Order a Sample</a>
                        <br>
	<a href="#" class="sizing-calculator-button"><img src="http://localhost/inspiry/wp-content/uploads/2020/08/icon-calc.png" alt="order a sample"> Sizing Calculator</a>
	
		<?php echo $price; ?>
		<?php echo $form; ?>

		<p class="availability margin-elements">Availability: <span class="days">7 - 10 Days</span></p>


	</div>
</section>

<!--had a description area and specs, reviews here -->

<?php echo $specs;?>
<?php echo $related; ?>



<div class="body-container">
       

    <!--sizing calculator-->
    <div class="overlay-background">
        <div class="calculator-overlay">
            <div id="calculator-container">
                <div class="popup-modal wallpaper-calculator-modal is-open">
                  <a href="#" class="close" aria-label="Close popup modal">×</a>
              
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
