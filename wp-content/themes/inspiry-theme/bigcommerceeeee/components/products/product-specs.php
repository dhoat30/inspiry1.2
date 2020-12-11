<?php
/**
 * @var array $specs
 * @version 1.0.0
 */
?>
<?php if ( ! empty( $specs ) ) { ?>

	<section class="bc-single-product__specifications">
		<!--edited by Webduel--> 
		<h4 class="product-short-description-title work-sans-fonts"><?php echo esc_html__( 'DETAILS', 'bigcommerce' ); ?></h4>
		
		<table class="specification-table">
			<?php 
				
			foreach ( $specs as $key => $value ) { 
				$condition = 'no-value'; 
				if($key == 'Width:' || $key == 'Depth:' || $key == 'Height:' || $key == 'Weight:'){ 
					$condition = 'has-value'; 
				}
				?>

				<tr data-dimensionsExists='<?php echo $condition;?>'>
					<td class="attr-title playfair-fonts ft-wt-med font-s-regular"><?php echo esc_html( $key ); ?></td>
					<td class="attr-value work-sans-fonts font-s-regular"><?php echo esc_html( $value ); ?></td>
				</tr>
				
			<?php } ?>
		</table>
	</section>
<?php } ?>