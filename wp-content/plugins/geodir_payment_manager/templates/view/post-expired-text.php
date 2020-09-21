<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="gd-notification gd-has-expired">
	<i class="fas fa-exclamation-circle" aria-hidden="true"></i> 
	<?php echo wp_sprintf( __( 'This %s appears to have expired.', 'geodir_pricing' ), $cpt_name ); ?>
</div>
