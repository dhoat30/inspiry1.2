<?php
/**
 * Product Card used in loops and grids.
 *
 * @package BigCommerce
 * @since v1.7
 *
 * @var Product $product
 * @var string  $image
 * @var string  $quick_view
 * @var string  $attributes
 * @version 1.0.0
 */

use BigCommerce\Post_Types\Product\Product;

?>

<!-- data-js="bc-product-quick-view-dialog-trigger" is required -->
<button type="button" class="bc-quickview-trigger"
        data-js="bc-product-quick-view-dialog-trigger"
        data-content=""
        data-productid="<?php echo $product->post_id(); ?>"
        <?php echo $attributes;?>
>
	<?php echo $image; ?>
	<div class="bc-quickview-trigger--hover">
		<span class="bc-quickview-trigger--hover-label">
			<?php echo esc_html( __( 'Quick View', 'bigcommerce' ) ); ?>
		</span>
	</div>
</button>

<!-- data-quick-view-script="" is required -->
<script data-quick-view-script="" type="text/template">
	<!-- data-js="bc-product-quick-view-content" is required -->
	<section class="bc-product-quick-view__content-inner" data-js="bc-product-quick-view-content">
		<?php echo $quick_view; ?>
		
		<!--design board container--> 
		<div class="overlay">
		
										  <div class="choose-board-container" data-post-id="value" data-post-title="value">
											<div class="choose-board">Choose Board</div>
											<div class="close-icon">X</div>
											<ul class="board-list">
												
												<?php 
                                        
												//wp query to get parent title of boards 
                                        
												$boardLoop = new WP_Query(array(
													'post_type' => 'boards', 
													'post_parent' => 0
												));
                                        
												while($boardLoop->have_posts()){
													$boardLoop->the_post(); 
                                            
                                          
												}
                                    
													while($boardLoop->have_posts()){ 
														$boardLoop->the_post(); 
														?>
																<li class="board-list-item" data-boardID='<?php echo get_the_id(); ?>'>
                                                        
																<?php 
                                                            
																the_title();?>
																<div class="loader"></div>

																</li>

														<?php
														wp_reset_postdata(  );
													}
												?>
											</ul>
											<div class="create-new-board"><span>+</span> Create New Board</div>
										</div>

										<div class="project-save-form-section">
   
										<div class="project-save-form-container"> 
											<div class="roboto-font regular form-title font-s-med">Create Board</div>
											<div class="form-underline"></div>
											<div class="form">
												<form id="new-board-form">
													<label for="name">Give your board a title*</label>
													<input type="text" name="board-name" id="board-name">
													<label for="description">Description</label>
													<textarea name="board-description" id="board-description" cols="30" rows="10"></textarea>
                                            
													<div class="btn-container">
														<button type="button" class="cancel-btn btn"> Cancel</button>
														<button type="submit" class="save-btn btn btn-dk-green"> Save</button>
                                              
														<div class="loader"></div>
													</div>
												</form>
											</div>
										</div>
									</div>
		</div>
	</section>
</script>

