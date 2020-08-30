<?php
/**
 * Component: Product Price
 *
 * @description Display the price for a product
 *
 * @var Product $product
 * @var string  $visible HTML class name to indicate if default pricing should be visible
 * @var string  $price_range
 * @var string  $calculated_price_range
 * @var string  $retail_price
 * @version 1.0.0
 */

use BigCommerce\Post_Types\Product\Product;

$post_id = get_the_ID();
$product = new \BigCommerce\Post_Types\Product\Product( $post_id );

$price = $product->get_property( 'price' );
?>
<!-- data-js="bc-cached-product-pricing" is required. -->
<p class="bc-product__pricing--cached <?php echo sanitize_html_class( $visible ); ?>" data-js="bc-cached-product-pricing">
<?php if ( $retail_price ) { ?>
	<!-- class="bc-product__retail-price" is required --><!-- class="bc-product__retail-price-value" is required -->
	<span class="bc-product__retail-price"><?php esc_html_e( 'MSRP:', 'bigcommerce' ); ?> <span class="bc-product__retail-price-value"><?php echo esc_html( $retail_price ); ?></span></span>
<?php } ?>
<?php if ( $product->on_sale() ) { ?>
	<!-- class="bc-product__original-price" is required. -->
	<span class="bc-product__original-price"><?php echo esc_html( $price_range ) ?></span>
	<!-- class="bc-product__price" is required. -->
	<span class="bc-product__price bc-product__price--sale">
		<?php echo esc_html( $calculated_price_range ); ?>
	</span>
<?php } else { ?>
	<!-- class="bc-product__price" is required. -->
	<span class="bc-product__price">
	
		
	
		<h4 class="margin-elements"><span class="price"><?php echo esc_html( $calculated_price_range ); ?> Inc GST</span> <br>
						<span class="lay-buy">or 6 weekly interest-free payments from <?php 
						 echo  number_format ( $price/6, 2 ); 
						 ?></span> 
                        <span class="lay-buy information-overlay"> <img src="http://localhost/inspiry/wp-content/uploads/2020/08/ico-laybuy.png"> What's this?</span>
        </h4>	
	</span>
<?php } ?>
</p>

<!-- data-pricing-api-product-id & data-js="bc-api-product-pricing" is required. -->
<p class="bc-product__pricing--api" data-js="bc-api-product-pricing" data-pricing-api-product-id="<?php echo esc_attr( $product->bc_id() ); ?>">
	<!-- class="bc-product__retail-price" is required --><!-- class="bc-product__retail-price-value" is required -->
	<span class="bc-product__retail-price"><?php esc_html_e( 'MSRP:', 'bigcommerce' ); ?> <span class="bc-product__retail-price-value"></span></span>
	<!-- class="bc-product-price bc-product__price--base" is required -->
	<!-- <span class="bc-product-price bc-product__price--base"></span> -->
	<!-- class="bc-product__original-price" is required -->
	<span class="bc-product__original-price"></span>
	<!-- class="bc-product-price bc-product__price--sale" is required -->
	<span class="bc-product__price bc-product__price--sale"></span>
</p>
