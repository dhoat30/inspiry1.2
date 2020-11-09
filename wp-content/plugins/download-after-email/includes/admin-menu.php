<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dae_sanitize_cb_html( $values ) {

	$values = stripslashes_deep( $values );
	
	foreach ( $values as $name => $value ) {
		
		if ( 'email_content' == $name || 'required_checkbox_text' == $name || 'optional_checkbox_text' == $name ) {
			$new_values[ $name ] = wp_kses_post( $value );
		} elseif ( 'email_notification' == $name ) {
			$new_values[ $name ] = sanitize_email( $value );
		}  else {
			$new_values[ $name ] = sanitize_text_field( $value );
		}
		
	}
	
	add_settings_error( 'dae_messages', 'dae_messages_error', __( 'Settings saved.', 'download-after-email' ), 'updated' );
	
	return $new_values;
	
}

function dae_sanitize_cb_text( $values ) {

	if ( empty( $values ) ) {

		$new_values = array();

	} else {

		foreach ( $values as $name => $value ) {
			$new_values[ $name ] = sanitize_text_field( $value );
		}

	}

	add_settings_error( 'dae_integrations', 'dae_integrations_error', __( 'Settings saved.', 'download-after-email' ), 'updated' );

	return $new_values;

}

add_action( 'admin_init', 'dae_settings_init' );
function dae_settings_init() {
	
	register_setting( 'dae_messages', 'dae_messages', array( 'sanitize_callback' => 'dae_sanitize_cb_html' ) );
	register_setting( 'dae_options', 'dae_options', array( 'sanitize_callback' => 'dae_sanitize_cb_text' ) );
	
}

add_action( 'admin_menu', 'dae_add_menu_pages' );
function dae_add_menu_pages() {
	
	add_submenu_page(
		'edit.php?post_type=dae_download',
		__( 'Customize Messages', 'download-after-email' ),
		__( 'Messages', 'download-after-email' ),
		'manage_options',
		'dae-messages',
		'dae_content_messages'
	);
	
	add_submenu_page(
		'edit.php?post_type=dae_download',
		__( 'Subscribers', 'download-after-email' ),
		__( 'Subscribers', 'download-after-email' ),
		'manage_options',
		'dae-subscribers',
		'dae_content_subscribers'
	);
	
	add_submenu_page(
		'edit.php?post_type=dae_download',
		__( 'Options', 'download-after-email' ),
		__( 'Options', 'download-after-email' ),
		'manage_options',
		'dae-options',
		'dae_content_options'
	);
	
}

function dae_content_messages() {
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	settings_errors( 'dae_messages' );
	
	$messages = get_option( 'dae_messages' );
	$field_labels = get_option( 'dae_field_labels' );
	$fields = get_option( 'dae_fields' );
	
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post" novalidate="novalidate">
			<?php settings_fields( 'dae_messages' ); ?>
			<h2 class="title"><?php esc_html_e( 'Checkbox Messages', 'download-after-email' ); ?></h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="dae-message-required-checkbox"><?php esc_html_e( 'Required Checkbox', 'download-after-email' ); ?></label></th>
						<td>
							<input id="dae-message-required-checkbox" type="checkbox" name="dae_messages[required_checkbox]" value="enabled"<?php if ( ! empty( $messages['required_checkbox'] ) ) { echo esc_attr( ' checked' ); } ?> />
							<span class="dae-message-info"><i><?php esc_html_e( 'Enable the use of the required checkbox.' ); ?></i></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-message-required-checkbox-text"><?php esc_html_e( 'Required Checkbox Text', 'download-after-email' ); ?></label></th>
						<td>
							<textarea id="dae-message-required-checkbox-text" name="dae_messages[required_checkbox_text]"><?php echo ! empty( $messages['required_checkbox_text'] ) ? wp_kses_post( $messages['required_checkbox_text'] ) : wp_kses_post( __( 'I confirm that I have read and agree to the <a href="#" target="_blank">Privacy Policy</a>.', 'download-after-email' ) ); ?></textarea>
							<div class="dae-message-info"><i><?php esc_html_e( 'Replace # with the privacy page url. Saving while left empty, the default value is filled in.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-message-optional-checkbox"><?php esc_html_e( 'Optional Checkbox', 'download-after-email' ); ?></label></th>
						<td>
							<input id="dae-message-optional-checkbox" type="checkbox" name="dae_messages[optional_checkbox]" value="enabled"<?php if ( ! empty( $messages['optional_checkbox'] ) ) { echo esc_attr( ' checked' ); } ?> />
							<span class="dae-message-info"><i><?php esc_html_e( 'Enable the use of the optional checkbox. If Mailchimp integration is activated, subscribers are only added to your Mailchimp audience if this checkbox is checked (GDPR compliant).' ); ?></i></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-message-optional-checkbox-text"><?php esc_html_e( 'Optional Checkbox Text', 'download-after-email' ); ?></label></th>
						<td>
							<textarea id="dae-message-optional-checkbox-text" name="dae_messages[optional_checkbox_text]"><?php echo ! empty( $messages['optional_checkbox_text'] ) ? wp_kses_post( $messages['optional_checkbox_text'] ) : wp_kses_post( __( 'Subscribe to get exclusive content and recommendations every month. You can unsubscribe anytime.', 'download-after-email' ) ); ?></textarea>
							<div class="dae-message-info"><i><?php esc_html_e( 'Saving while left empty, the default value is filled in.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
				</tbody>
			</table>
			<h2 class="title"><?php esc_html_e( 'Submit Messages', 'download-after-email' ); ?></h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="dae-message-unvalid-input"><?php esc_html_e( 'Unvalid Input', 'download-after-email' ); ?></label></th>
						<td>
							<textarea id="dae-message-unvalid-input" name="dae_messages[unvalid_input]"><?php echo ! empty( $messages['unvalid_input'] ) ? esc_html( $messages['unvalid_input'] ) : esc_html__( 'Please make sure all fields are filled in correctly.', 'download-after-email' ); ?></textarea>
							<div class="dae-message-info"><i><?php esc_html_e( 'Saving while left empty, the default value is filled in.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-message-email-exists"><?php esc_html_e( 'Email Exists', 'download-after-email' ); ?></label></th>
						<td>
							<textarea id="dae-message-email-exists" name="dae_messages[email_exists]"><?php echo ! empty( $messages['email_exists'] ) ? esc_html( $messages['email_exists'] ) : esc_html__( 'An email with the download link has already been sent to this email address.', 'download-after-email' ); ?></textarea>
							<div class="dae-message-info"><i><?php esc_html_e( 'Saving while left empty, the default value is filled in.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-message-email-success"><?php esc_html_e( 'Email Success', 'download-after-email' ); ?></label></th>
						<td>
							<textarea id="dae-message-email-success" name="dae_messages[email_success]"><?php echo ! empty( $messages['email_success'] ) ? esc_html( $messages['email_success'] ) : esc_html__( 'An email has been sent with the download link.', 'download-after-email' ); ?></textarea>
							<div class="dae-message-info"><i><?php esc_html_e( 'Saving while left empty, the default value is filled in.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
				</tbody>
			</table>
			<?php do_action( 'dae_messages', $messages ); ?>
			<h2 class="title"><?php esc_html_e( 'Email Messages', 'download-after-email' ); ?></h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="dae-message-unvalid-link"><?php esc_html_e( 'Unvalid Link', 'download-after-email' ); ?></label></th>
						<td>
							<textarea id="dae-message-unvalid-link" name="dae_messages[unvalid_link]"><?php echo ! empty( $messages['unvalid_link'] ) ? esc_html( $messages['unvalid_link'] ) : esc_html__( 'This link has already been used and is now unavailable.', 'download-after-email' ); ?></textarea>
							<div class="dae-message-info"><i><?php esc_html_e( 'Saving while left empty, the default value is filled in.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-message-download-failed"><?php esc_html_e( 'Download Failed', 'download-after-email' ); ?></label></th>
						<td>
							<textarea id="dae-message-download-failed" name="dae_messages[download_failed]"><?php echo ! empty( $messages['download_failed'] ) ? esc_html( $messages['download_failed'] ) : esc_html__( 'This download file could not be found. Please try again or feel free to contact us.', 'download-after-email' ); ?></textarea>
							<div class="dae-message-info"><i><?php esc_html_e( 'Saving while left empty, the default value is filled in.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-messages-email-from-email"><?php esc_html_e( 'From Email', 'download-after-email' ); ?></label></th>
						<td><input id="dae-messages-email-from-email" type="text" name="dae_messages[email_from_email]" value="<?php echo ! empty( $messages['email_from_email'] ) ? esc_html( $messages['email_from_email'] ) : esc_html( '' ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-messages-email-from-name"><?php esc_html_e( 'From Name', 'download-after-email' ); ?></label></th>
						<td><input id="dae-messages-email-from-name" type="text" name="dae_messages[email_from_name]" value="<?php echo ! empty( $messages['email_from_name'] ) ? esc_html( $messages['email_from_name'] ) : esc_html( '' ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-messages-email-subject"><?php esc_html_e( 'Email Subject', 'download-after-email' ); ?></label></th>
						<td>
							<input id="dae-messages-email-subject" type="text" name="dae_messages[email_subject]" value="<?php echo ! empty( $messages['email_subject'] ) ? esc_html( $messages['email_subject'] ) : esc_html( 'Your Free Download' ); ?>" />
							<div class="dae-message-info"><i><?php esc_html_e( 'Placeholders are available excluding {download_link} and {download_url}. Saving while left empty, the default value is filled in.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-messages-email-notification"><?php esc_html_e( 'Notification Email To', 'download-after-email' ); ?></label></th>
						<td>
							<input id="dae-messages-email-notification" type="text" name="dae_messages[email_notification]" value="<?php echo ! empty( $messages['email_notification'] ) ? esc_html( $messages['email_notification'] ) : esc_html( '' ); ?>" />
							<div class="dae-message-info"><i><?php esc_html_e( 'Enter an email address if you want to receive a notification email.', 'download-after-email' ); ?></i></div>
						</td>
					</tr>
				</tbody>
			</table>
			<p id="dae-message-email-tags">
				<?php
				esc_html_e( 'You can use HTML code in the email content below and the following placeholders are available:', 'download-after-email' );
				echo '<br />';
				echo esc_html( ' {download_link}' );
				echo esc_html( ' {download_url}' );
				echo esc_html( ' {download_file}' );
				foreach ( $field_labels as $field_label ) {
					$field_label_name = str_replace( ' ', '_', strtolower( $field_label ) );
					if ( ! empty( $fields[ $field_label_name . '_visible' ] ) ) {
						echo esc_html( ' {' . $field_label_name . '}' );
					}
				}
				?>
			</p>
			<?php
			wp_editor( ! empty( $messages['email_content'] ) ? wp_kses_post( $messages['email_content'] ) : wp_kses_post( '<p>Hi,</p><p>Thank you for subscribing.</p><p>You can find your Free Download here: {download_link}</p>' ), 'wpeditor', array(
				'textarea_name' => 'dae_messages[email_content]'
			) );
			submit_button();
			?>
		</form>
	</div>
	<?php
	
}

function dae_content_subscribers_table( $page = 1, $search_value = '' ) {
	
	$field_labels = get_option( 'dae_field_labels' );
	$fields = get_option( 'dae_fields' );
	$messages = get_option( 'dae_messages' );
	$subscribers_per_page = get_option( 'dae_subscribers_per_page' );
	$offset = ( $page - 1 ) * $subscribers_per_page;
	
	global $wpdb;
	$table_subscribers = $wpdb->prefix . 'dae_subscribers';
	$table_subscribermeta = $wpdb->prefix . 'dae_subscribermeta';
	$table_links = $wpdb->prefix . 'dae_links';
	
	if ( empty( $search_value ) ) {
		$subscribers = $wpdb->get_results( $wpdb->prepare( "SELECT id, time FROM $table_subscribers LIMIT %d OFFSET %d", array( $subscribers_per_page, $offset ) ) );
		$count_subscribers = count( $wpdb->get_col( "SELECT id FROM $table_subscribers" ) );
	} else {
		$search_query = "%" . $search_value . "%";
		$subscribers = array_unique( $wpdb->get_col( $wpdb->prepare( "SELECT subscriber_id FROM $table_subscribermeta WHERE meta_value LIKE %s", $search_query ) ) );
		$count_subscribers = count( $subscribers );
		$subscribers = array_chunk( $subscribers, $subscribers_per_page );
		$subscribers = $subscribers[ $page - 1 ];
	}
	
	$count_pages = ceil( $count_subscribers / $subscribers_per_page );
	$count_pages = $count_pages < 1 ? 1 : $count_pages;
	
	?>
	<?php if ( ! empty( $search_value ) ) : ?>
		<div id="dae-subscribers-search-text">
			<span><?php echo esc_html_e( 'Search results for:', 'download-after-email' ); ?></span>
			<span id="dae-subscribers-search-value"><?php echo esc_html( $search_value ); ?></span>
		</div>
	<?php endif; ?>
	<div class="table">
		<div class="table-head">
			<div class="column" data-label="<?php esc_attr_e( 'ID', 'download-after-email' ); ?>"><?php esc_html_e( 'ID', 'download-after-email' ); ?></div>
			<div class="column" data-label="<?php esc_attr_e( 'Date', 'download-after-email' ); ?>"><?php esc_html_e( 'Date', 'download-after-email' ); ?></div>
			<?php foreach ( $field_labels as $field_label ) : ?>
				<?php
				$field_label_name = str_replace( ' ', '_', strtolower( $field_label ) );
				?>
				<?php if ( ! empty( $fields[ $field_label_name . '_visible' ] ) ) : ?>
					<div class="column" data-label="<?php echo esc_attr( $field_label ); ?>"><?php echo esc_html( $field_label ); ?></div>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if ( ! empty( $messages['optional_checkbox'] ) ) : ?>
				<div class="column" data-label="<?php esc_attr_e( 'Optin Time', 'download-after-email' ); ?>"><?php esc_html_e( 'Optin Time', 'download-after-email' ); ?></div>
			<?php endif; ?>
			<div class="column" data-label="<?php esc_attr_e( 'Downloadlinks', 'download-after-email' ); ?>"><?php esc_html_e( 'Downloadlinks', 'download-after-email' ); ?></div>
		</div>
		<?php foreach ( $subscribers as $subscriber ) : ?>
			<?php
			if ( empty( $search_value ) ) {
				$subscriber_id = $subscriber->id;
			} else {
				$subscriber_id = $subscriber;
				$subscriber = $wpdb->get_row( $wpdb->prepare( "SELECT id, time FROM $table_subscribers WHERE id = %d LIMIT 1", $subscriber_id ) );
			}
			$subscribermeta = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value FROM $table_subscribermeta WHERE subscriber_id = %d", $subscriber_id ) );
			unset( $meta_array );
			foreach ( $subscribermeta as $meta ) {
				$meta_array[ $meta->meta_key ] = $meta->meta_value;
			}
			$links = $wpdb->get_results( $wpdb->prepare( "SELECT id, time, time_used, ip, ip_used, file, link_used FROM $table_links WHERE subscriber_id = %d", $subscriber_id ) );
			?>
			<div class="row">
				<div class="column" data-label="<?php esc_attr_e( 'ID', 'download-after-email' ); ?>"><?php echo esc_html( $subscriber_id ); ?></div>
				<div class="column" data-label="<?php esc_attr_e( 'Date', 'download-after-email' ); ?>"><?php echo esc_html( date( 'd-m-Y', strtotime( $subscriber->time ) ) ); ?></div>
				<?php foreach ( $field_labels as $field_label ) : ?>
					<?php
						$field_label_name = str_replace( ' ', '_', strtolower( $field_label ) );
					?>
					<?php if ( ! empty( $fields[ $field_label_name . '_visible' ] ) ) : ?>
						<div class="column" data-label="<?php echo esc_attr( $field_label ); ?>"><?php if ( ! empty( $meta_array[ $field_label_name ] ) ) { echo esc_html( $meta_array[ $field_label_name ] ); } ?></div>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if ( ! empty( $messages['optional_checkbox'] ) ) : ?>
					<div class="column" data-label="<?php esc_attr_e( 'Optin Time', 'download-after-email' ); ?>"><?php if ( ! empty( $meta_array['optin_time'] ) ) { echo esc_html( date( 'd-m-Y', strtotime( $meta_array['optin_time'] ) ) ); } ?></div>
				<?php endif; ?>
				<div class="column" data-label="<?php esc_attr_e( 'Downloadlinks', 'download-after-email' ); ?>">
					<div class="dae-subscribers-links-icon dashicons-before dashicons-sort"></div>
					<div class="dae-subscribers-links">
						<?php foreach ( $links as $link ) : ?>
							<div class="dae-subscribers-link">
								<div class="row">
									<span class="dae-subscribers-link-label column"><?php esc_html_e( 'ID', 'download-after-email' ); ?></span>
									<span class="dae-subscribers-link-value column"><?php echo esc_html( $link->id ); ?></span>
								</div>
								<div class="row">
									<span class="dae-subscribers-link-label column"><?php esc_html_e( 'File', 'download-after-email' ); ?></span>
									<span class="dae-subscribers-link-value column"><?php echo esc_html( $link->file ); ?></span>
								</div>
								<div class="row">
									<span class="dae-subscribers-link-label column"><?php esc_html_e( 'Created', 'download-after-email' ); ?></span>
									<span class="dae-subscribers-link-value column"><?php echo esc_html( date( 'd-m-Y H:i:s', strtotime( $link->time ) ) ); ?></span>
								</div>
								<div class="row">
									<span class="dae-subscribers-link-label column"><?php esc_html_e( 'IP (created)', 'download-after-email' ); ?></span>
									<span class="dae-subscribers-link-value column"><?php echo esc_html( $link->ip ); ?></span>
								</div>
								<div class="row">
									<span class="dae-subscribers-link-label column"><?php esc_html_e( 'Used', 'download-after-email' ); ?></span>
									<span class="dae-subscribers-link-value column"><?php echo 'used' == $link->link_used ? esc_html( date( 'd-m-Y H:i:s', strtotime( $link->time_used ) ) ) : esc_html__( 'not used', 'download-after-email' ); ?></span>
								</div>
								<div class="row">
									<span class="dae-subscribers-link-label column"><?php esc_html_e( 'IP (used)', 'download-after-email' ); ?></span>
									<span class="dae-subscribers-link-value column"><?php echo ! empty( $link->ip_used ) ? esc_html( $link->ip_used ) : esc_html__( 'not used', 'download-after-email' ); ?></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="column"><div class="dae-subscribers-remove dashicons-before dashicons-dismiss"></div></div>
			</div>
		<?php endforeach; ?>
	</div>
	<div id="dae-subscribers-page-wrap">
		<span class="dae-subscribers-page-nav"><i class="fas fa-angle-double-left"></i></span>
		<span class="dae-subscribers-page-nav"><i class="fas fa-angle-left"></i></span>
		<span class="dae-subscribers-page-nav"><i class="fas fa-angle-right"></i></span>
		<span class="dae-subscribers-page-nav"><i class="fas fa-angle-double-right"></i></span>
		<span id="dae-subscribers-page"><?php echo esc_html( $page ); ?></span>
		<span><?php esc_html_e( 'of', 'download-after-email' ); ?></span>
		<span id="dae-subscribers-count-pages"><?php echo esc_html( $count_pages ); ?></span>
	</div>
	<?php
	
}

function dae_content_subscribers() {
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<h2 class="title"><?php esc_html_e( 'Subscribers Log', 'download-after-email' ); ?></h2>
		<form id="dae-subscribers-search-form" method="post" novalidate="novalidate">
			<input type="text" name="search_value" />
			<input class="button" type="submit" value="<?php esc_attr_e( 'Search', 'download-after-email' ); ?>" />
		</form>
		<div id="dae-subscribers-table-wrap">
			<?php dae_content_subscribers_table(); ?>
		</div>
		<?php do_action( 'dae_subscribers_bottom' ); ?>
	</div>
	<?php
	
}

add_action( 'wp_ajax_dae_search_subscribers', 'dae_search_subscribers' );
function dae_search_subscribers() {
	
	check_ajax_referer( 'dae_admin' );
	
	$_POST = stripslashes_deep( $_POST );
	
	$search_value = sanitize_text_field( $_POST['search_value'] );
	
	if ( current_user_can( 'manage_options' ) ) {
		
		dae_content_subscribers_table( 1, $search_value );
		
	}
	
	wp_die();
	
}

add_action( 'wp_ajax_dae_change_page_subscribers', 'dae_change_page_subscribers' );
function dae_change_page_subscribers() {
	
	check_ajax_referer( 'dae_admin' );
	
	$page =  (int) $_POST['page'];
	$search_value = sanitize_text_field( $_POST['search_value'] );
	
	if ( current_user_can( 'manage_options' ) && ! empty( $page ) && isset( $search_value ) ) {
		
		dae_content_subscribers_table( $page, $search_value );
		
	}
	
	wp_die();
	
}

add_action( 'wp_ajax_dae_remove_subscriber', 'dae_remove_subscriber' );
function dae_remove_subscriber() {
	
	check_ajax_referer( 'dae_admin' );
	
	$id = (int) $_POST['id'];
	
	if ( current_user_can( 'manage_options' ) && ! empty( $id ) ) {
		
		global $wpdb;
		$table_subscribers = $wpdb->prefix . 'dae_subscribers';
		$table_subscribermeta = $wpdb->prefix . 'dae_subscribermeta';
		$table_links = $wpdb->prefix . 'dae_links';
		
		$wpdb->delete(
			$table_subscribers,
			array( 'id' => $id ),
			array( '%d' )
		);
		
		$wpdb->delete(
			$table_subscribermeta,
			array( 'subscriber_id' => $id ),
			array( '%d' )
		);
		
		$wpdb->delete(
			$table_links,
			array( 'subscriber_id' => $id ),
			array( '%d' )
		);
		
	}
	
	wp_die();
	
}

add_action( 'dae_subscribers_bottom', 'dae_content_subscribers_premium' );
function dae_content_subscribers_premium() {
	
	if ( ! is_plugin_active( 'dae-plus/dae-plus.php' ) ) {
		
		?>
		<div id="dae-subscribers-premium">
			<h2 id="dae-subscribers-premium-title"><?php esc_html_e( 'Add Premium Features', 'download-after-email' ); ?></h2>
			<p><?php esc_html_e( 'Download After Email Plus is an extension/add-on that adds the following premium features:', 'download-after-email' ); ?></p>
			<table id="dae-subscribers-features-table">
				<tbody>
					<tr>
						<th><i class="fas fa-check-square"></i><span><?php esc_html_e( 'Form Fields', 'download-after-email' ); ?></span></th>
						<td><?php esc_html_e( 'Create and manage your own form fields with the Drag & Drop Form Builder.', 'download-after-email' ); ?></td>
					</tr>
					<tr>
						<th><i class="fas fa-check-square"></i><span><?php esc_html_e( 'Export CSV', 'download-after-email' ); ?></span></th>
						<td><?php esc_html_e( 'Export subscriber data to a CSV-file and use it for email marketing, newsletters etc.', 'download-after-email' ); ?></td>
					</tr>
					<tr>
						<th><i class="fas fa-check-square"></i><span><?php esc_html_e( 'Integration Mailchimp', 'download-after-email' ); ?></span></th>
						<td><?php esc_html_e( 'Automatically add new subscribers to your Mailchimp audience.', 'download-after-email' ); ?></td>
					</tr>
				</tbody>
			</table>
			<p>
				<a class="button button-primary" href="https://www.download-after-email.com/add-on/" target="_blank"><?php esc_html_e( 'Get Download After Email Plus', 'download-after-email' ); ?></a>
				<a class="button" href="https://www.download-after-email.com/add-on/" target="_blank"><?php esc_html_e( 'More information', 'download-after-email' ); ?></a>
			</p>
		</div>
		<?php
		
	}
	
}

function dae_content_options() {
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	settings_errors( 'dae_integrations' );
	
	$options = get_option( 'dae_options' );
	
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post" novalidate="novalidate">
			<?php settings_fields( 'dae_options' ); ?>
			<h2 class="title"><?php esc_html_e( 'Download Restrictions', 'download-after-email' ); ?></h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="dae-option-unlimited-links"><?php esc_html_e( 'Unlimited Links', 'download-after-email' ); ?></label></th>
						<td>
							<input id="dae-option-unlimited-links" type="checkbox" name="dae_options[unlimited_links]" value="unlimited"<?php if ( ! empty( $options['unlimited_links'] ) ) { echo esc_attr( ' checked' ); } ?> />
							<span class="dae-message-info"><i><?php esc_html_e( 'Enable the use of unlimited download links instead of one-time download links in emails received by subscribers.', 'download-after-email' ); ?></i></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-option-unlimited-emails"><?php esc_html_e( 'Unlimited Emails', 'download-after-email' ); ?></label></th>
						<td>
							<input id="dae-option-unlimited-emails" type="checkbox" name="dae_options[unlimited_emails]" value="unlimited"<?php if ( ! empty( $options['unlimited_emails'] ) ) { echo esc_attr( ' checked' ); } ?> />
							<span class="dae-message-info"><i><?php esc_html_e( 'Enable the use of unlimited emails (split time of 60s in case a subscriber submits the same download form again) instead of one email per subscriber per download file (name).', 'download-after-email' ); ?></i></span>
						</td>
					</tr>
				</tbody>
			</table>
			<h2 class="title"><?php esc_html_e( 'Deactivation', 'download-after-email' ); ?></h2>
			<p><?php esc_html_e( 'The following data will be deleted on deactivation:', 'download-after-email' ); ?></p>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="dae-option-delete-messages"><?php esc_html_e( 'Messages', 'download-after-email' ); ?></label></th>
						<td><input id="dae-option-delete-messages" type="checkbox" name="dae_options[delete_messages]" value="delete"<?php if ( ! empty( $options['delete_messages'] ) ) { echo esc_attr( ' checked' ); } ?> /></td>
					</tr>
					<tr>
						<th scope="row"><label for="dae-option-delete-subscribers"><?php esc_html_e( 'Subscribers + Downloadlinks', 'download-after-email' ); ?></label></th>
						<td><input id="dae-option-delete-subscribers" type="checkbox" name="dae_options[delete_subscribers]" value="delete"<?php if ( ! empty( $options['delete_subscribers'] ) ) { echo esc_attr( ' checked' ); } ?> /></td>
					</tr>
					<?php do_action( 'dae_options_deactivation', $options ); ?>
				</tbody>
			</table>
			<?php do_action( 'dae_options', $options ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
	
}

add_filter( 'admin_footer_text', 'dae_admin_footer_text' );
function dae_admin_footer_text( $content ) {
	
	global $hook_suffix;
	$hook = $hook_suffix;
	
	if(
		( ( 'edit.php' == $hook || 'post.php' == $hook || 'post-new.php' == $hook ) && 'dae_download' == get_post_type() )
		|| 'dae_download_page_dae-messages' == $hook
		|| 'dae_download_page_dae-subscribers' == $hook
		|| 'dae_download_page_dae-options' == $hook
		|| 'dae_download_page_dae-fields' == $hook
		|| 'dae_download_page_dae-integrations' == $hook
	) {
		
		$text = sprintf(
			esc_html__( 'If you like Download After Email, please give us a %s rating. A huge thanks in advance!', 'download-after-email' ),
			'<a href="https://wordpress.org/support/plugin/download-after-email/reviews?rate=5#new-post" target="_blank" class="dae-admin-footer-rating">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
		);
		
		$content = '
			<span id="dae-footer-thankyou">' . $text .  '</span>
		';
		
	}
	
	return $content;
	
}

?>