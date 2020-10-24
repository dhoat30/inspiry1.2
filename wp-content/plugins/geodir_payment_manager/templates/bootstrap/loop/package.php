<?php
/**
 * Pricing Single Package
 *
 * This template can be overridden by copying it to yourtheme/geodirectory/bootstrap/loop/package.php.
 *
 * HOWEVER, on occasion GeoDirectory will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://wpgeodirectory.com/docs-v2/faq/customizing/#templates
 * @package    GeoDir_Pricing_Manager
 * @version    2.1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="col <?php echo $card_class; ?>">
	<div class="card mb-lg-0 <?php echo $highlight_class . ' ' . $border_class . ' ' . $card_shadow_class; ?>">
		<div class="card-header <?php echo $card_header_border_class; ?>">
			<h4 class="my-0 mb-1 text-base subtitle text-center py-3 text-nowrap text-<?php echo $color; ?>"><?php echo $display_name; ?></h4>
			<p class="text-muted text-center mb-3"><span class="h2 text-dark"><?php echo $display_price; ?></span><span class="ml-2">/ <?php echo $display_lifetime; ?></span></p>
		</div>
		<div class="card-body">
			<ul class="fa-ul my-2">
			<?php if ( ! empty( $package->features ) ) { ?>
				<?php foreach( $package->features as $feature => $data ) { ?>
				<li class="mb-3" data-geodir-feature="<?php echo esc_attr( $feature ); ?>"><span class="fa-li text-<?php echo esc_attr( $data['color'] ); ?>"><i class="<?php echo esc_attr( $data['icon'] ); ?>"></i></span><?php echo $data['text']; ?></li>
				<?php } ?>
			<?php } ?>
			</ul>
			<div class="text-center"><a class="btn btn-<?php echo $color; ?> btn-block" href="<?php echo esc_url( $package_link ); ?>"><?php _e( 'Select Plan', 'geodir_pricing' ); ?></a></div>
		</div>
	</div>
</div>