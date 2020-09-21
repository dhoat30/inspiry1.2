<?php
/**
 * Claim Listings AJAX class.
 *
 * Claim Listings AJAX Event Handler.
 *
 * @since 2.0.0
 * @package Geodir_Claim_Listing
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDir_Claim_AJAX class.
 */
class GeoDir_Claim_AJAX {

	/**
	 * Hook in ajax handlers.
	 */
	public static function init() {
		self::add_ajax_events();

		// add login message
		add_filter( 'login_message', array(__CLASS__,'login_message') );

	}

	public static function login_message( $message )
	{
		if(isset($_REQUEST['gd_go']) && $_REQUEST['gd_go']=='claim'){
			$message .= "<p class='message'>".__( '<strong>NOTICE</strong>: Please login to claim your listing.', 'geodir-claim' )."</p>";
		}

		return $message;
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public static function add_ajax_events() {
		// geodirectory_EVENT => nopriv
		$ajax_events = array(
			'claim_post_form' => true,
			'claim_submit_form' => true,
			'claim_approve_request' => false,
			'claim_reject_request' => false,
			'claim_undo_request' => false,
			'claim_delete_request' => false,
			'claim_view_request' => false,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_geodir_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_geodir_' . $ajax_event, array( __CLASS__, $ajax_event ) );

				// GeoDir AJAX can be used for frontend ajax requests.
				add_action( 'geodir_claim_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}

	public static function claim_view_request() {
		$claim_id = ! empty( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;

		check_ajax_referer( 'geodir-claim-' . $claim_id, 'security' );


		if ( ! current_user_can( 'manage_options' ) ) {
			_e( 'Invalid access.', 'geodir-claim' );
		}

//		echo 'zzzzzzzzzzzzzzzz';
//
//		exit;


		//$claim = GeoDir_Claim_Post::get_item( $claim_id );
		$claim = GeoDir_Claim_Post::view_item( $claim_id );

		wp_die();
	}

	/**
     * Get claim form html.
     *
     * @since 2.0.0
     *
     * @return string
     */
	public static function claim_post_form() {
		global $post;

		check_ajax_referer( 'geodir_basic_nonce', 'security' );

		if ( ! is_user_logged_in() ) {
			echo __( 'Login to claim this listing!', 'geodir-claim' );
			wp_die();
		}

		$post_id = isset( $_REQUEST['p'] ) ? absint( $_REQUEST['p'] ) : url_to_postid( wp_get_referer() );

		$post = $post_id ? get_post( $post_id ) : NULL;
		if ( empty( $post ) ) {
			echo __( 'No post found!', 'geodir-claim' );
			wp_die();
		}

		// fake the post_id for ninja forms
		add_filter( 'url_to_postid', function ( $url ) {
				global $post;
				return add_query_arg( 'p', $post->ID, $url );
			}
		);

		if ( GeoDir_Claim_Post::post_claim_allowed( $post->ID ) ) {
			
			// Check for an outstanding claim.
			if ( GeoDir_Claim_Post::is_claimed( $post->ID ) ) {
				$notification = array('gd_claim_notice' =>
					                      array(
						                      'type' => 'info',
						                      'note' =>  __( 'This listing is already claimed!', 'geodir-claim' )
					                      )
				);
				echo geodir_notification( $notification);
			}elseif ( GeoDir_Claim_Post::is_claim_pending( $post->ID ) ) {
				$notification = array('gd_claim_notice' =>
					array(
						'type' => 'info',
						'note' =>  __( 'It looks like you already have a claim pending review for this listing, you will be notified by email once we have reviewed your claim.', 'geodir-claim' )
					)
				);
				echo geodir_notification( $notification);
			} else {
				echo geodir_claim_post_get_form( $post->ID );
			}
		} else {
			$notification = array('gd_claim_notice' =>
				                      array(
					                      'type' => 'error',
					                      'note' =>  __( 'Claim not allowed for this listing!', 'geodir-claim' )
				                      )
			);
			echo geodir_notification( $notification);
		}
		wp_die();
	}

	/**
     * Submit claim post form.
     *
     * @since 2.0.0
     *
     * @return string
     */
	public static function claim_submit_form() {
		$post_id = ! empty( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

		check_ajax_referer( 'geodir_claim_nonce_' . $post_id, 'security' );

		// Pre validation
		$validate = apply_filters( 'geodir_validate_ajax_claim_listing_data', true, $_POST );

		if ( is_wp_error( $validate ) ) {
			wp_send_json_error( array( 'message' => $validate->get_error_message() ) );
		}

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Login to claim this listing!', 'geodir-claim' ) ) );
		}

		$post = $post_id ? get_post( $post_id ) : NULL;
		if ( empty( $post ) ) {
			wp_send_json_error( array( 'message' => __( 'No post found!', 'geodir-claim' ) ) );
		}

		if ( GeoDir_Claim_Post::post_claim_allowed( $post->ID ) ) {
			if ( GeoDir_Claim_Post::is_claimed( $post->ID ) ) {
				wp_send_json_error( array( 'message' => __( 'This listing is already claimed!', 'geodir-claim' ) ) );
			} else {
				$handle = GeoDir_Claim_Form::handle_claim_submit( $_POST );

				if ( is_wp_error( $handle ) ) { // Claim request error
					wp_send_json_error( array( 'message' => $handle->get_error_message() ) );
				} else {
					if ( $handle ) { // Claim request success
						// clear cache
						delete_transient( 'geodir_claim_stats' );

						$claim = GeoDir_Claim_Post::get_item( $handle );

						if ( geodir_get_option( 'claim_auto_approve' ) && empty( $claim->payment_id ) ) {
							$message = __( 'A verification link has been sent to your email address, please click the link in the email to verify your listing claim.', 'geodir-claim' );
						} else {
							$message = __( 'Your request to claim this listing has been sent successfully. You will be notified by email once a decision has been made.', 'geodir-claim' );
						}

						$message = apply_filters( 'geodir_claim_submit_success_message', $message, $claim, $post_id );

						$data = array( 'message' => $message );

						wp_send_json_success( $data );
					} else { // Claim request fails
						wp_send_json_error( array( 'message' => __( 'Something went wrong, please try again!', 'geodir-claim' ) ) );
					}
				}
			}
		} else {
			wp_send_json_error( array( 'message' => __( 'Claim not allowed for this listing!', 'geodir-claim' ) ) );
		}
	}

	public static function claim_approve_request() {
		$claim_id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		check_ajax_referer( 'geodir-claim-' . $claim_id, 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid access.', 'geodir-claim' ) ) );
		}

		$claim = GeoDir_Claim_Post::get_item( $claim_id );
		if ( empty( $claim ) ) {
			wp_send_json_error( array( 'message' => __( 'Claim request not found.', 'geodir-claim' ) ) );
		}

		$response = GeoDir_Claim_Post::approve_claim( $claim, true );
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		if ( $response ) {
			// clear cache
			delete_transient( 'geodir_claim_stats' );
			wp_send_json_success( array( 'reload' => true ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Something went wrong, can not approve claim request.', 'geodir-claim' ) ) );
		}
	}

	public static function claim_undo_request() {
		$claim_id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		check_ajax_referer( 'geodir-claim-' . $claim_id, 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid access.', 'geodir-claim' ) ) );
		}

		$claim = GeoDir_Claim_Post::get_item( $claim_id );
		if ( empty( $claim ) ) {
			wp_send_json_error( array( 'message' => __( 'Claim request not found.', 'geodir-claim' ) ) );
		}

		$response = GeoDir_Claim_Post::undo_claim( $claim, true );
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		if ( $response ) {
			// clear cache
			delete_transient( 'geodir_claim_stats' );
			wp_send_json_success( array( 'reload' => true ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Something went wrong, can not undo claim request.', 'geodir-claim' ) ) );
		}
	}

	public static function claim_reject_request() {
		$claim_id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		check_ajax_referer( 'geodir-claim-' . $claim_id, 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid access.', 'geodir-claim' ) ) );
		}

		$claim = GeoDir_Claim_Post::get_item( $claim_id );
		if ( empty( $claim ) ) {
			wp_send_json_error( array( 'message' => __( 'Claim request not found.', 'geodir-claim' ) ) );
		}

		$response = GeoDir_Claim_Post::reject_claim( $claim, true );
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		if ( $response ) {
			// clear cache
			delete_transient( 'geodir_claim_stats' );
			wp_send_json_success( array( 'reload' => true ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Something went wrong, can not reject claim request.', 'geodir-claim' ) ) );
		}
	}

	public static function claim_delete_request() {
		$claim_id = ! empty( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

		check_ajax_referer( 'geodir-claim-' . $claim_id, 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid access.', 'geodir-claim' ) ) );
		}

		$claim = GeoDir_Claim_Post::get_item( $claim_id );
		if ( empty( $claim ) ) {
			wp_send_json_error( array( 'message' => __( 'Claim request not found.', 'geodir-claim' ) ) );
		}

		$response = GeoDir_Claim_Post::delete_claim( $claim, true );
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => $response->get_error_message() ) );
		}

		if ( $response ) {
			// clear cache
			delete_transient( 'geodir_claim_stats' );
			wp_send_json_success( array( 'reload' => false ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Something went wrong, can not delete claim request.', 'geodir-claim' ) ) );
		}
	}
}