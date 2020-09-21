<?php
/**
 * Claim Listings Email class.
 *
 * @since 2.0.0
 * @package Geodir_Claim_Listing
 * @author AyeCode Ltd
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GeoDir_Claim_Email class.
 */
class GeoDir_Claim_Email {

	public static function init() {
		if ( is_admin() ) {
			add_filter( 'geodir_email_settings', array( __CLASS__, 'filter_email_settings' ), 11, 1 );
			add_filter( 'geodir_admin_email_settings', array( __CLASS__, 'filter_admin_email_settings' ), 11, 1 );
			add_filter( 'geodir_user_email_settings', array( __CLASS__, 'filter_user_email_settings' ), 11, 1 );
		}

		add_filter( 'geodir_email_subject', array( __CLASS__, 'get_subject' ), 30, 3 );
		add_filter( 'geodir_email_content', array( __CLASS__, 'get_content' ), 30, 3 );
		add_filter( 'geodir_email_wild_cards', array( __CLASS__, 'set_wild_cards' ), 30, 4 );
	}

	public static function filter_email_settings( $settings ) {
		if ( $merge_settings = self::bcc_email_settings() ) {
			$position = count( $settings ) - 1;
			$settings = array_merge( array_slice( $settings, 0, $position ), $merge_settings, array_slice( $settings, $position ) );
		}

		return $settings;
	}

	public static function filter_admin_email_settings( $settings ) {
		if ( $merge_settings = self::admin_email_settings() ) {
			$position = count( $settings );
			$settings = array_merge( array_slice( $settings, 0, $position ), $merge_settings, array_slice( $settings, $position ) );
		}

		return $settings;
	}

	public static function filter_user_email_settings( $settings ) {
		if ( $merge_settings = self::user_email_settings() ) {
			$position = count( $settings );
			$settings = array_merge( array_slice( $settings, 0, $position ), $merge_settings, array_slice( $settings, $position ) );
		}

		return $settings;
	}

	public static function bcc_email_settings() {
		$settings = array(
			array(
				'type' => 'checkbox',
				'id' => 'email_bcc_user_claim_request',
				'name' => __( 'Claim listing request', 'geodir-claim' ),
				'desc' => __( 'This will send a BCC email to the site admin on claim listing requested by user.', 'geodir-claim' ),
				'default' => 0,
				'advanced' => false
			),
			array(
				'type' => 'checkbox',
				'id' => 'email_bcc_user_claim_approved',
				'name' => __( 'Claim request approved', 'geodir-claim' ),
				'desc' => __( 'This will send a BCC email to the site admin on claim listing request approved.', 'geodir-claim' ),
				'default' => 0,
				'advanced' => true
			),
			array(
				'type' => 'checkbox',
				'id' => 'email_bcc_user_claim_rejected',
				'name' => __( 'Claim request rejected', 'geodir-claim' ),
				'desc' => __( 'This will send a BCC email to the site admin on claim listing request rejected.', 'geodir-claim' ),
				'default' => 0,
				'advanced' => true
			),
			array(
				'type' => 'checkbox',
				'id' => 'email_bcc_user_claim_verify',
				'name' => __( 'Claim request verification', 'geodir-claim' ),
				'desc' => __( 'This will send a BCC email to the site admin on claim listing request verification required.', 'geodir-claim' ),
				'default' => 0,
				'advanced' => true
			),
		);

		return apply_filters( 'geodir_claim_bcc_email_settings', $settings );
	}

	public static function admin_email_settings() {
		$settings = array(
			// Claim listing request email to admin.
			array(
				'type' => 'title',
				'id' => 'email_admin_claim_request_settings',
				'name' => __( 'Claim listing request', 'geodir-claim' ),
				'desc' => '',
			),
			array(
				'type' => 'checkbox',
				'id' => 'email_admin_claim_request',
				'name' => __( 'Enable email', 'geodir-claim' ),
				'desc' => __( 'Send an email to admin on claim listing request from user.', 'geodir-claim' ),
				'default' => 1,
			),
			array(
				'type' => 'text',
				'id' => 'email_admin_claim_request_subject',
				'name' => __( 'Subject', 'geodir-claim' ),
				'desc' => __( 'The email subject.', 'geodir-claim' ),
				'class' => 'large-text',
				'desc_tip' => true,
				'placeholder' => self::email_admin_claim_request_subject(),
				'advanced' => true
			),
			array(
				'type' => 'textarea',
				'id' => 'email_admin_claim_request_body',
				'name' => __( 'Body', 'geodir-claim' ),
				'desc' => __( 'The email body, this can be text or HTML.', 'geodir-claim' ),
				'class' => 'code gd-email-body',
				'desc_tip' => true,
				'advanced' => true,
				'placeholder' => self::email_admin_claim_request_body(),
				'custom_desc' => __( 'Available template tags:', 'geodir-claim' ) . ' ' . self::admin_claim_request_email_tags()
			),
			array(
				'type' => 'sectionend',
				'id' => 'email_admin_claim_request_settings'
			)
		);

		return apply_filters( 'geodir_claim_admin_email_settings', $settings );
	}

	public static function user_email_settings() {
		$settings = array(
			// Claim listing request submitted email to user.
			array(
				'type' => 'title',
				'id' => 'email_user_claim_request_settings',
				'name' => __( 'Claim listing submitted', 'geodir-claim' ),
				'desc' => '',
			),
			array(
				'type' => 'checkbox',
				'id' => 'email_user_claim_request',
				'name' => __( 'Enable email', 'geodir-claim' ),
				'desc' => __( 'Send an email to user on claim listing request submitted.', 'geodir-claim' ),
				'default' => 1,
			),
			array(
				'type' => 'text',
				'id' => 'email_user_claim_request_subject',
				'name' => __( 'Subject', 'geodir-claim' ),
				'desc' => __( 'The email subject.', 'geodir-claim' ),
				'class' => 'large-text',
				'desc_tip' => true,
				'placeholder' => self::email_user_claim_request_subject(),
				'advanced' => true
			),
			array(
				'type' => 'textarea',
				'id' => 'email_user_claim_request_body',
				'name' => __( 'Body', 'geodir-claim' ),
				'desc' => __( 'The email body, this can be text or HTML.', 'geodir-claim' ),
				'class' => 'code gd-email-body',
				'desc_tip' => true,
				'advanced' => true,
				'placeholder' => self::email_user_claim_request_body(),
				'custom_desc' => __( 'Available template tags:', 'geodir-claim' ) . ' ' . self::user_claim_request_email_tags()
			),
			array(
				'type' => 'sectionend',
				'id' => 'email_user_claim_request_settings'
			),

			// Claim listing approved email to user.
			array(
				'type' => 'title',
				'id' => 'email_user_claim_approved_settings',
				'name' => __( 'Claim listing approved', 'geodir-claim' ),
				'desc' => '',
			),
			array(
				'type' => 'checkbox',
				'id' => 'email_user_claim_approved',
				'name' => __( 'Enable email', 'geodir-claim' ),
				'desc' => __( 'Send an email to user on claim listing request approved.', 'geodir-claim' ),
				'default' => 1,
			),
			array(
				'type' => 'text',
				'id' => 'email_user_claim_approved_subject',
				'name' => __( 'Subject', 'geodir-claim' ),
				'desc' => __( 'The email subject.', 'geodir-claim' ),
				'class' => 'large-text',
				'desc_tip' => true,
				'placeholder' => self::email_user_claim_approved_subject(),
				'advanced' => true
			),
			array(
				'type' => 'textarea',
				'id' => 'email_user_claim_approved_body',
				'name' => __( 'Body', 'geodir-claim' ),
				'desc' => __( 'The email body, this can be text or HTML.', 'geodir-claim' ),
				'class' => 'code gd-email-body',
				'desc_tip' => true,
				'advanced' => true,
				'placeholder' => self::email_user_claim_approved_body(),
				'custom_desc' => __( 'Available template tags:', 'geodir-claim' ) . ' ' . self::user_claim_approved_email_tags()
			),
			array(
				'type' => 'sectionend',
				'id' => 'email_user_claim_approved_settings'
			),

			// Claim listing rejected email to user.
			array(
				'type' => 'title',
				'id' => 'email_user_claim_rejected_settings',
				'name' => __( 'Claim listing rejected', 'geodir-claim' ),
				'desc' => '',
			),
			array(
				'type' => 'checkbox',
				'id' => 'email_user_claim_rejected',
				'name' => __( 'Enable email', 'geodir-claim' ),
				'desc' => __( 'Send an email to user on claim listing request rejected.', 'geodir-claim' ),
				'default' => 1,
			),
			array(
				'type' => 'text',
				'id' => 'email_user_claim_rejected_subject',
				'name' => __( 'Subject', 'geodir-claim' ),
				'desc' => __( 'The email subject.', 'geodir-claim' ),
				'class' => 'large-text',
				'desc_tip' => true,
				'placeholder' => self::email_user_claim_rejected_subject(),
				'advanced' => true
			),
			array(
				'type' => 'textarea',
				'id' => 'email_user_claim_rejected_body',
				'name' => __( 'Body', 'geodir-claim' ),
				'desc' => __( 'The email body, this can be text or HTML.', 'geodir-claim' ),
				'class' => 'code gd-email-body',
				'desc_tip' => true,
				'advanced' => true,
				'placeholder' => self::email_user_claim_rejected_body(),
				'custom_desc' => __( 'Available template tags:', 'geodir-claim' ) . ' ' . self::user_claim_rejected_email_tags()
			),
			array(
				'type' => 'sectionend',
				'id' => 'email_user_claim_rejected_settings'
			),

			// Claim listing verification email to user.
			array(
				'type' => 'title',
				'id' => 'email_user_claim_verify_settings',
				'name' => __( 'Claim listing verification required', 'geodir-claim' ),
				'desc' => '',
			),
			array(
				'type' => 'checkbox',
				'id' => 'email_user_claim_verify',
				'name' => __( 'Enable email', 'geodir-claim' ),
				'desc' => __( 'Send an email to user on claim listing verification required.', 'geodir-claim' ),
				'default' => 1,
			),
			array(
				'type' => 'text',
				'id' => 'email_user_claim_verify_subject',
				'name' => __( 'Subject', 'geodir-claim' ),
				'desc' => __( 'The email subject.', 'geodir-claim' ),
				'class' => 'large-text',
				'desc_tip' => true,
				'placeholder' => self::email_user_claim_verify_subject(),
				'advanced' => true
			),
			array(
				'type' => 'textarea',
				'id' => 'email_user_claim_verify_body',
				'name' => __( 'Body', 'geodir-claim' ),
				'desc' => __( 'The email body, this can be text or HTML.', 'geodir-claim' ),
				'class' => 'code gd-email-body',
				'desc_tip' => true,
				'advanced' => true,
				'placeholder' => self::email_user_claim_verify_body(),
				'custom_desc' => __( 'Available template tags:', 'geodir-claim' ) . ' ' . self::user_claim_verify_email_tags()
			),
			array(
				'type' => 'sectionend',
				'id' => 'email_user_claim_verify_settings'
			)
		);

		return apply_filters( 'geodir_claim_user_email_settings', $settings );
	}

	public static function get_subject( $subject, $email_name = '', $email_vars = array() ) {
		if ( ! empty( $subject ) ) {
			return $subject;
		}

		$method = 'email_' . $email_name . '_subject';
		if (  method_exists( __CLASS__, $method ) ) {
			$subject = self::$method();
		}

		if ( $subject ) {
			$subject = GeoDir_Email::replace_variables( __( $subject, 'geodirectory' ), $email_name, $email_vars );
		}

		return $subject;
	}

	public static function get_content( $content, $email_name = '', $email_vars = array() ) {
		if ( ! empty( $content ) ) {
			return $content;
		}

		$method = 'email_' . $email_name . '_body';
		if (  method_exists( __CLASS__, $method ) ) {
			$content = self::$method();
		}

		if ( $content ) {
			$content = GeoDir_Email::replace_variables( __( $content, 'geodirectory' ), $email_name, $email_vars );
		}

		return $content;
	}

	public static function set_wild_cards( $wild_cards, $content, $email_name, $email_vars = array() ) {
		switch ( $email_name ) {
			case 'admin_claim_request':
			case 'user_claim_request':
			case 'user_claim_approved':
			case 'user_claim_rejected':
			case 'user_claim_verify':
				$params = array();

				if ( ! empty( $email_vars['post'] ) ) {
					$gd_post = $email_vars['post'];

					$author_data = get_userdata( $gd_post->post_author );

					$params['username'] = $author_data->user_login;
					$params['user_email'] = $author_data->user_email;
				}

				if ( ! empty( $email_vars['claim'] ) ) {
					$claim = $email_vars['claim'];
					$params['claim_date'] = $claim->claim_date;
					$params['display_claim_date'] = date_i18n( geodir_date_format(), strtotime( $claim->claim_date ) );
					$params['display_claim_time'] = date_i18n( geodir_time_format(), strtotime( $claim->claim_date ) );
					$params['display_claim_date_time'] = date_i18n( geodir_date_format() . ' ' . geodir_time_format(), strtotime( $claim->claim_date ) );
					$params['claim_verify_url'] = add_query_arg( array( '_claim_verify' => $claim->rand_string ), get_permalink( $claim->post_id ) );
					$params['claim_verify_link'] = '<a href="' . esc_url( $params['claim_verify_url'] ) . '">' . __( 'CLICK HERE TO VERIFY', 'geodir-claim' ) . '</a>';
				}

				$defaults = array(
					'claim_date' => '',
					'display_claim_date' => '',
					'claim_verify_url' => '',
					'claim_verify_link' => '',
				);
				$params = wp_parse_args( $params, $defaults );

				foreach ( $params as $key => $value ) {
					if ( ! isset( $email_vars[ '[#' . $key . '#]' ] ) ) {
						$wild_cards[ '[#' . $key . '#]' ] = $value;
					}
				}
			break;
		}

		return $wild_cards;
	}

	/**
	 * Claim listings email tags.
	 *
	 * @since  2.0.0
	 *
	 * @param bool $inline Optional. Email tag inline value. Default true.
	 * @return array|string $tags.
	 */
	public static function claim_email_tags( $inline = true ) { 
		$tags = array( '[#post_id#]', '[#post_status#]', '[#post_date#]', '[#post_author_ID#]', '[#post_author_name#]', '[#client_name#]', '[#listing_title#]', '[#listing_url#]', '[#listing_link#]' );
		
		$tags = apply_filters( 'geodir_claim_email_tags', $tags );

		if ( $inline ) {
			$tags = '<code>' . implode( '</code> <code>', $tags ) . '</code>';
		}
		
		return $tags;
	}

	/**
	 * Global email tags.
	 *
	 * @since  2.0.0
	 *
	 * @param bool $inline Optional. Email tag inline value. Default true.
	 * @return array|string $tags.
	 */
	public static function global_email_tags( $inline = true ) { 
		$tags = array( '[#blogname#]', '[#site_name#]', '[#site_url#]', '[#site_name_url#]', '[#login_url#]', '[#login_link#]', '[#date#]', '[#time#]', '[#date_time#]', '[#current_date#]', '[#to_name#]', '[#to_email#]', '[#from_name#]', '[#from_email#]' );
		
		$tags = apply_filters( 'geodir_email_global_email_tags', $tags );

		if ( $inline ) {
			$tags = '<code>' . implode( '</code> <code>', $tags ) . '</code>';
		}
		
		return $tags;
	}

	/**
	 * Email tags for claim listings emails.
	 *
	 * @since  2.0.0
	 *
	 * @param bool $inline Optional. Email tag inline value. Default true.
	 * @return array|string $tags.
	 */
	public static function email_tags( $inline = true ) { 
		$email_tags = self::global_email_tags( false );
		$claim_email_tags = self::claim_email_tags( false );

		if ( ! empty( $claim_email_tags ) ) {
			$email_tags = array_merge( $email_tags, $claim_email_tags );
		}

		if ( $inline ) {
			$email_tags = '<code>' . implode( '</code> <code>', $email_tags ) . '</code>';
		}
		
		return $email_tags;
	}

	public static function email_admin_claim_request_subject() {
		$subject = __( '[[#site_name#]] Claim listing requested', 'geodir-claim' );

		return apply_filters( 'geodir_claim_email_admin_claim_request_subject', $subject );
	}

	public static function email_admin_claim_request_body() {
		$body = "" . 
__( "Dear Admin,

A user has requested to become the owner of the listing [#listing_link#], you will have to login to review this claim.

Thank You.", "geodir-claim" );

		return apply_filters( 'geodir_claim_email_admin_claim_request_body', $body );
	}

	public static function admin_claim_request_email_tags( $inline = true ) { 
		$email_tags = self::email_tags( false );

		$tags = array_merge( $email_tags, array() );

		$tags = apply_filters( 'geodir_claim_admin_claim_request_email_tags', $tags );

		if ( $inline ) {
			$tags = '<code>' . implode( '</code> <code>', $tags ) . '</code>';
		}

		return $tags;
	}

	public static function email_user_claim_request_subject() {
		$subject = __( '[[#site_name#]] Claim listing submitted', 'geodir-claim' );

		return apply_filters( 'geodir_claim_email_user_claim_request_subject', $subject );
	}

	public static function email_user_claim_request_body() {
		$body = "" . 
__( "Dear [#to_name#],

You have requested to become the owner of the listing [#listing_link#].

We may contact you to confirm your request is genuine. You will receive a email once a decision has been made.

Thank You.", "geodir-claim" );

		return apply_filters( 'geodir_claim_email_user_claim_request_body', $body );
	}

	public static function user_claim_request_email_tags( $inline = true ) { 
		$email_tags = self::email_tags( false );

		$tags = array_merge( $email_tags, array() );

		$tags = apply_filters( 'geodir_claim_user_claim_request_email_tags', $tags );

		if ( $inline ) {
			$tags = '<code>' . implode( '</code> <code>', $tags ) . '</code>';
		}

		return $tags;
	}

	public static function email_user_claim_approved_subject() {
		$subject = __( '[[#site_name#]] Claim listing has been Approved', 'geodir-claim' );

		return apply_filters( 'geodir_claim_email_user_claim_approved_subject', $subject );
	}

	public static function email_user_claim_approved_body() {
		$body = "" . 
__( "Dear [#to_name#],

Your request to become the owner of the listing [#listing_link#] has been APPROVED.

Thank You.", "geodir-claim" );

		return apply_filters( 'geodir_claim_email_user_claim_approved_body', $body );
	}

	public static function user_claim_approved_email_tags( $inline = true ) { 
		$email_tags = self::email_tags( false );

		$tags = array_merge( $email_tags, array() );

		$tags = apply_filters( 'geodir_claim_user_claim_approved_email_tags', $tags );

		if ( $inline ) {
			$tags = '<code>' . implode( '</code> <code>', $tags ) . '</code>';
		}

		return $tags;
	}

	public static function email_user_claim_rejected_subject() {
		$subject = __( '[[#site_name#]] Claim listing has been Rejected', 'geodir-claim' );

		return apply_filters( 'geodir_claim_email_user_claim_rejected_subject', $subject );
	}

	public static function email_user_claim_rejected_body() {
		$body = "" . 
__( "Dear [#to_name#],

Your request to become the owner of the listing [#listing_link#] has been REJECTED.

If you feel this is a wrong decision please reply to this email with your reasons.

Thank You.", "geodir-claim" );

		return apply_filters( 'geodir_claim_email_user_claim_rejected_body', $body );
	}

	public static function user_claim_rejected_email_tags( $inline = true ) { 
		$email_tags = self::email_tags( false );

		$tags = array_merge( $email_tags, array() );

		$tags = apply_filters( 'geodir_claim_user_claim_rejected_email_tags', $tags );

		if ( $inline ) {
			$tags = '<code>' . implode( '</code> <code>', $tags ) . '</code>';
		}

		return $tags;
	}

	public static function email_user_claim_verify_subject() {
		$subject = __( '[[#site_name#]] Claim listing verification required', 'geodir-claim' );

		return apply_filters( 'geodir_claim_email_user_claim_verify_subject', $subject );
	}

	public static function email_user_claim_verify_body() {
		$body = "" . 
__( "Dear [#to_name#],

Your request to become the owner of the below listing [#listing_link#] needs to be verified.

By clicking the VERIFY link below you are stating you are legally associated with this business and have the owners consent to edit the listing.

If you are not associated with this business and edit the listing with malicious intent you will be solely liable for any legal action or claims for damages.

To verify the claim, visit the following address:
[#claim_verify_link#]

Thank You.", "geodir-claim" );

		return apply_filters( 'geodir_claim_email_user_claim_verify_body', $body );
	}

	public static function user_claim_verify_email_tags( $inline = true ) { 
		$email_tags = self::email_tags( false );

		$tags = array_merge( $email_tags, array( '[#claim_verify_url#]', '[#claim_verify_link#]' ) );

		$tags = apply_filters( 'geodir_claim_user_claim_verify_email_tags', $tags );

		if ( $inline ) {
			$tags = '<code>' . implode( '</code> <code>', $tags ) . '</code>';
		}

		return $tags;
	}

	// Claim request email to admin
	public static function send_admin_claim_request_email( $claim, $post, $data = array() ) {
		$email_name = 'admin_claim_request';

		if ( ! GeoDir_Email::is_email_enabled( $email_name, 1 ) ) {
			return false;
		}
		//echo '###'.$email_name;exit;
		$recipient = GeoDir_Email::get_admin_email();

		if ( empty( $post ) || ! is_email( $recipient ) ) {
			return;
		}

		$email_vars             = $data;
		$email_vars['post']     = $post;
		$email_vars['claim']    = $claim;
		$email_vars['to_email'] = $recipient;

		do_action( 'geodir_claim_pre_' . $email_name . '_email', $email_name, $email_vars );

		$subject      = GeoDir_Email::get_subject( $email_name, $email_vars );
		$message_body = GeoDir_Email::get_content( $email_name, $email_vars );
		$headers      = GeoDir_Email::get_headers( $email_name, $email_vars );
		$attachments  = GeoDir_Email::get_attachments( $email_name, $email_vars );

		$plain_text = GeoDir_Email::get_email_type() != 'html' ? true : false;
		$template   = $plain_text ? 'emails/plain/email-' . $email_name . '.php' : 'emails/email-' . $email_name . '.php';

		$content = geodir_get_template_html( $template, array(
			'email_name'    => $email_name,
			'email_vars'    => $email_vars,
			'email_heading'	=> '',
			'sent_to_admin' => true,
			'plain_text'    => $plain_text,
			'message_body'  => $message_body,
		) );

		$sent = GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );

		do_action( 'geodir_claim_post_' . $email_name . '_email', $email_vars );

		return $sent;
	}

	// Claim request email to user
	public static function send_user_claim_request_email( $claim, $post, $data = array() ) {
		$email_name = 'user_claim_request';

		if ( ! GeoDir_Email::is_email_enabled( $email_name,1 ) ) {
			return false;
		}

		$author_data = get_userdata( $claim->user_id );
		if ( empty( $author_data ) ) {
			return false;
		}

		$recipient = ! empty( $author_data->user_email ) ? $author_data->user_email : '';

		if ( empty( $post ) || ! is_email( $recipient ) ) {
			return;
		}

		$email_vars             = $data;
		$email_vars['post']     = $post;
		$email_vars['claim']    = $claim;
		$email_vars['to_name']  = geodir_get_client_name( $claim->user_id );
		$email_vars['to_email'] = $recipient;

		do_action( 'geodir_claim_pre_' . $email_name . '_email', $email_name, $email_vars );

		$subject      = GeoDir_Email::get_subject( $email_name, $email_vars );
		$message_body = GeoDir_Email::get_content( $email_name, $email_vars );
		$headers      = GeoDir_Email::get_headers( $email_name, $email_vars );
		$attachments  = GeoDir_Email::get_attachments( $email_name, $email_vars );

		$plain_text = GeoDir_Email::get_email_type() != 'html' ? true : false;
		$template   = $plain_text ? 'emails/plain/email-' . $email_name . '.php' : 'emails/email-' . $email_name . '.php';

		$content = geodir_get_template_html( $template, array(
			'email_name'    => $email_name,
			'email_vars'    => $email_vars,
			'email_heading'	=> '',
			'sent_to_admin' => true,
			'plain_text'    => $plain_text,
			'message_body'  => $message_body,
		) );

		$sent = GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );

		if ( GeoDir_Email::is_admin_bcc_active( $email_name ) ) {
			$recipient = GeoDir_Email::get_admin_email();
			$subject .= ' - ADMIN BCC COPY';
			GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );
		}

		do_action( 'geodir_claim_post_' . $email_name . '_email', $email_vars );

		return $sent;
	}

	// Claim request verify email to user
	public static function send_user_claim_verify_email( $claim, $post, $data = array() ) {
		$email_name = 'user_claim_verify';

		if ( ! GeoDir_Email::is_email_enabled( $email_name , 1) ) {
			return false;
		}

		$author_data = get_userdata( $claim->user_id );
		if ( empty( $author_data ) ) {
			return false;
		}

		$recipient = ! empty( $author_data->user_email ) ? $author_data->user_email : '';

		if ( empty( $post ) || ! is_email( $recipient ) ) {
			return;
		}

		$email_vars             = $data;
		$email_vars['post']     = $post;
		$email_vars['claim']    = $claim;
		$email_vars['to_name']  = geodir_get_client_name( $claim->user_id );
		$email_vars['to_email'] = $recipient;

		do_action( 'geodir_claim_pre_' . $email_name . '_email', $email_name, $email_vars );

		$subject      = GeoDir_Email::get_subject( $email_name, $email_vars );
		$message_body = GeoDir_Email::get_content( $email_name, $email_vars );
		$headers      = GeoDir_Email::get_headers( $email_name, $email_vars );
		$attachments  = GeoDir_Email::get_attachments( $email_name, $email_vars );

		$plain_text = GeoDir_Email::get_email_type() != 'html' ? true : false;
		$template   = $plain_text ? 'emails/plain/email-' . $email_name . '.php' : 'emails/email-' . $email_name . '.php';

		$content = geodir_get_template_html( $template, array(
			'email_name'    => $email_name,
			'email_vars'    => $email_vars,
			'email_heading'	=> '',
			'sent_to_admin' => true,
			'plain_text'    => $plain_text,
			'message_body'  => $message_body,
		) );

		$sent = GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );

		if ( GeoDir_Email::is_admin_bcc_active( $email_name ) ) {
			$recipient = GeoDir_Email::get_admin_email();
			$subject .= ' - ADMIN BCC COPY';
			GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );
		}

		do_action( 'geodir_claim_post_' . $email_name . '_email', $email_vars );

		return $sent;
	}

	// Claim request approved email to user
	public static function send_user_claim_approved_email( $claim, $post, $data = array() ) {
		$email_name = 'user_claim_approved';

		if ( ! GeoDir_Email::is_email_enabled( $email_name, 1 ) ) {
			return false;
		}

		$author_data = get_userdata( $claim->user_id );
		if ( empty( $author_data ) ) {
			return false;
		}

		$recipient = ! empty( $author_data->user_email ) ? $author_data->user_email : '';

		if ( empty( $post ) || ! is_email( $recipient ) ) {
			return;
		}

		$email_vars             = $data;
		$email_vars['post']     = $post;
		$email_vars['claim']    = $claim;
		$email_vars['to_name']  = geodir_get_client_name( $claim->user_id );
		$email_vars['to_email'] = $recipient;

		do_action( 'geodir_claim_pre_' . $email_name . '_email', $email_name, $email_vars );

		$subject      = GeoDir_Email::get_subject( $email_name, $email_vars );
		$message_body = GeoDir_Email::get_content( $email_name, $email_vars );
		$headers      = GeoDir_Email::get_headers( $email_name, $email_vars );
		$attachments  = GeoDir_Email::get_attachments( $email_name, $email_vars );

		$plain_text = GeoDir_Email::get_email_type() != 'html' ? true : false;
		$template   = $plain_text ? 'emails/plain/email-' . $email_name . '.php' : 'emails/email-' . $email_name . '.php';

		$content = geodir_get_template_html( $template, array(
			'email_name'    => $email_name,
			'email_vars'    => $email_vars,
			'email_heading'	=> '',
			'sent_to_admin' => true,
			'plain_text'    => $plain_text,
			'message_body'  => $message_body,
		) );

		$sent = GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );

		if ( GeoDir_Email::is_admin_bcc_active( $email_name ) ) {
			$recipient = GeoDir_Email::get_admin_email();
			$subject .= ' - ADMIN BCC COPY';
			GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );
		}

		do_action( 'geodir_claim_post_' . $email_name . '_email', $email_vars );

		return $sent;
	}

	// Claim request rejected email to user
	public static function send_user_claim_rejected_email( $claim, $post, $data = array() ) {
		$email_name = 'user_claim_rejected';

		if ( ! GeoDir_Email::is_email_enabled( $email_name, 1 ) ) {
			return false;
		}

		$author_data = get_userdata( $claim->user_id );
		if ( empty( $author_data ) ) {
			return false;
		}

		$recipient = ! empty( $author_data->user_email ) ? $author_data->user_email : '';

		if ( empty( $post ) || ! is_email( $recipient ) ) {
			return;
		}

		$email_vars             = $data;
		$email_vars['post']     = $post;
		$email_vars['claim']    = $claim;
		$email_vars['to_name']  = geodir_get_client_name( $claim->user_id );
		$email_vars['to_email'] = $recipient;

		do_action( 'geodir_claim_pre_' . $email_name . '_email', $email_name, $email_vars );

		$subject      = GeoDir_Email::get_subject( $email_name, $email_vars );
		$message_body = GeoDir_Email::get_content( $email_name, $email_vars );
		$headers      = GeoDir_Email::get_headers( $email_name, $email_vars );
		$attachments  = GeoDir_Email::get_attachments( $email_name, $email_vars );

		$plain_text = GeoDir_Email::get_email_type() != 'html' ? true : false;
		$template   = $plain_text ? 'emails/plain/email-' . $email_name . '.php' : 'emails/email-' . $email_name . '.php';

		$content = geodir_get_template_html( $template, array(
			'email_name'    => $email_name,
			'email_vars'    => $email_vars,
			'email_heading'	=> '',
			'sent_to_admin' => true,
			'plain_text'    => $plain_text,
			'message_body'  => $message_body,
		) );

		$sent = GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );

		if ( GeoDir_Email::is_admin_bcc_active( $email_name ) ) {
			$recipient = GeoDir_Email::get_admin_email();
			$subject .= ' - ADMIN BCC COPY';
			GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );
		}

		do_action( 'geodir_claim_post_' . $email_name . '_email', $email_vars );

		return $sent;
	}
}