<?php
/**
 * Displayed when no listings are found to compare.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$message = __( "There are no listings to compare.", 'geodir-compare' );
?>
<div class="geodir-compare-page-empty-list">
	<?php echo apply_filters( 'geodir_no_listings_found_to_compare', $message ); ?>
</div>
