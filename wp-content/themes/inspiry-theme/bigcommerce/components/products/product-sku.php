<?php
/**
 * Component: Product SKU
 *
 * @description Displays the sku of the product
 *
 * @var string  $sku
 * @var Product $product
 * @version 1.0.0
 */

use BigCommerce\Post_Types\Product\Product;

if ( empty( $sku ) ) {
	return;
}
?>

	<?php $sku; ?>

<?php echo esc_html( $sku ); ?>