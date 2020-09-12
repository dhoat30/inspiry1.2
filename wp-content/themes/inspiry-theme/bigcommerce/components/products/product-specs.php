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
		
		<table>
			<?php foreach ( $specs as $key => $value ) { ?>
				<tr>
					<td class="attr-title playfair-fonts ft-wt-med font-s-med"><?php echo esc_html( $key ); ?></td>
					<td class="attr-value work-sans-fonts font-s-regular"><?php echo esc_html( $value ); ?></td>
				</tr>
				
			<?php } ?>
		</table>
	</section>
<?php } ?>