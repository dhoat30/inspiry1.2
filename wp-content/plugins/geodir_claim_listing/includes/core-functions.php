<?php
/**
 * Claim Listings Core Functions.
 *
 * @since 2.0.0
 * @package Geodir_Claim_Listing
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function geodir_claim_get_statuses() {
	$statuses = array(
		'0' => _x( 'Pending', 'Claim status', 'geodir-claim' ),
		'1' => _x( 'Approved', 'Claim status', 'geodir-claim' ),
		'2' => _x( 'Rejected', 'Claim status', 'geodir-claim' )
	);

	return apply_filters( 'geodir_claim_get_statuses', $statuses );
}

function geodir_claim_status_name( $status ) {
	$statuses = geodir_claim_get_statuses();
	if ( ! empty( $statuses ) && isset( $statuses[ absint( $status ) ] ) ) {
		$status_name = $statuses[ absint( $status ) ];
	} else {
		$status_name = $status;
	}

	return apply_filters( 'geodir_claim_status_name', $status_name, $status );
}