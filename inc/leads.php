<?php
/**
 * Lead management: AJAX contact form handler, custom DB table,
 * email notifications + admin alerts, inbox screen and CSV export.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

/* ==================================================================
 * 1. Database table
 * ================================================================== */
function kfp_leads_table() {
	global $wpdb;
	$table   = $wpdb->prefix . 'kfp_leads';
	$charset = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		created_at DATETIME NOT NULL,
		name VARCHAR(190) NOT NULL,
		email VARCHAR(190) NOT NULL,
		phone VARCHAR(60) DEFAULT '',
		subject VARCHAR(190) DEFAULT '',
		message TEXT NOT NULL,
		property_id BIGINT(20) UNSIGNED DEFAULT 0,
		source VARCHAR(60) DEFAULT 'website',
		status VARCHAR(20) DEFAULT 'new',
		ip VARCHAR(45) DEFAULT '',
		PRIMARY KEY  (id),
		KEY status (status),
		KEY created_at (created_at)
	) {$charset};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
	update_option( 'kfp_leads_db_version', '1.0' );
}
add_action( 'after_switch_theme', 'kfp_leads_table' );

/* ==================================================================
 * 2. AJAX submission (logged-in & guests)
 * ================================================================== */
add_action( 'wp_ajax_kfp_submit_lead', 'kfp_handle_lead' );
add_action( 'wp_ajax_nopriv_kfp_submit_lead', 'kfp_handle_lead' );

function kfp_handle_lead() {
	check_ajax_referer( 'kfp_contact_nonce', 'nonce' );

	// Simple rate limiting: max 3 submissions per IP per 10 minutes.
	$ip_key = 'kfp_rl_' . md5( kfp_client_ip() );
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 3 ) {
		wp_send_json_error( array( 'message' => __( 'Too many messages. Please try again in a few minutes or call us directly.', 'kfp-properties' ) ), 429 );
	}

	// Honeypot — bots fill this.
	if ( ! empty( $_POST['company_website'] ) ) {
		wp_send_json_success( array( 'message' => __( 'Thank you!', 'kfp-properties' ) ) );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : __( 'Website enquiry', 'kfp-properties' );
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$prop_id = isset( $_POST['property_id'] ) ? absint( $_POST['property_id'] ) : 0;

	if ( '' === $name || ! is_email( $email ) || '' === $message ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in your name, a valid email and a message.', 'kfp-properties' ) ), 400 );
	}

	global $wpdb;
	$inserted = $wpdb->insert(
		$wpdb->prefix . 'kfp_leads',
		array(
			'created_at'  => current_time( 'mysql' ),
			'name'        => $name,
			'email'       => $email,
			'phone'       => $phone,
			'subject'     => $subject,
			'message'     => $message,
			'property_id' => $prop_id,
			'source'      => 'website',
			'status'      => 'new',
			'ip'          => kfp_client_ip(),
		),
		array( '%s','%s','%s','%s','%s','%s','%d','%s','%s','%s' )
	);

	if ( false === $inserted ) {
		wp_send_json_error( array( 'message' => __( 'Could not save your message. Please try again.', 'kfp-properties' ) ), 500 );
	}

	set_transient( $ip_key, $count + 1, 10 * MINUTE_IN_SECONDS );

	// --- Automated notifications ---------------------------------
	$notify = kfp_opt( 'kfp_notify_email', get_option( 'admin_email' ) );
	$props  = $prop_id ? get_the_title( $prop_id ) . ' — ' . get_permalink( $prop_id ) : __( 'General enquiry', 'kfp-properties' );

	// 1) Admin alert email.
	$admin_body  = sprintf( "<h2>New lead from %s</h2>", esc_html( $name ) );
	$admin_body .= '<p><strong>Name:</strong> ' . esc_html( $name ) . '</p>';
	$admin_body .= '<p><strong>Email:</strong> <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></p>';
	$admin_body .= '<p><strong>Phone:</strong> ' . esc_html( $phone ?: '—' ) . '</p>';
	$admin_body .= '<p><strong>Regarding:</strong> ' . esc_html( $props ) . '</p>';
	$admin_body .= '<p><strong>Message:</strong><br>' . nl2br( esc_html( $message ) ) . '</p>';
	$admin_body .= '<p><a href="' . esc_url( admin_url( 'admin.php?page=kfp-leads' ) ) . '">Open lead inbox in WordPress</a></p>';

	$headers = array( 'Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' );
	wp_mail( $notify, sprintf( __( '🔔 New lead: %s (%s)', 'kfp-properties' ), $name, $subject ), $admin_body, $headers );

	// 2) Auto-reply to the client.
	$client_body  = sprintf( __( '<p>Dear %s,</p>', 'kfp-properties' ), esc_html( $name ) );
	$client_body .= '<p>' . esc_html__( 'Thank you for contacting King’s Farms and Properties Ltd. We have received your message and a member of our team will reach out within one business day.', 'kfp-properties' ) . '</p>';
	$client_body .= '<p>' . esc_html__( 'Need immediate assistance? Call or WhatsApp us.', 'kfp-properties' ) . ' <a href="https://wa.me/' . esc_attr( preg_replace( '/\D/', '', kfp_opt( 'kfp_whatsapp', '2348037319301' ) ) ) . '">WhatsApp →</a></p>';
	$client_body .= '<p>' . esc_html__( 'Best regards,', 'kfp-properties' ) . '<br>' . esc_html__( "King's Farms And Properties Ltd.", 'kfp-properties' ) . '</p>';
	wp_mail( $email, __( "We received your enquiry — King's Farms & Properties", 'kfp-properties' ), $client_body, $headers );

	// 3) Unread badge count for admin menu.
	$unread = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}kfp_leads WHERE status='new'" );
	update_option( 'kfp_unread_leads', $unread );

	wp_send_json_success( array(
		'message' => __( 'Thank you! Your message has been sent — we will get back to you shortly.', 'kfp-properties' ),
	) );
}

function kfp_client_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$parts = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
		$first = trim( $parts[0] );
		if ( filter_var( $first, FILTER_VALIDATE_IP ) ) {
			$ip = $first;
		}
	}
	return $ip;
}

/* ==================================================================
 * 3. Admin inbox screen
 * ================================================================== */
add_action( 'admin_menu', 'kfp_leads_menu' );
function kfp_leads_menu() {
	global $wpdb;
	$unread = (int) get_option( 'kfp_unread_leads', 0 );
	$badge  = $unread ? ' <span class="awaiting-mod count-' . $unread . '"><span class="pending-count">' . $unread . '</span></span>' : '';

	add_menu_page(
		__( 'Leads', 'kfp-properties' ),
		__( 'Leads', 'kfp-properties' ) . $badge,
		'manage_options',
		'kfp-leads',
		'kfp_leads_page',
		'dashicons-email-alt',
		7
	);
}

add_action( 'admin_post_kfp_export_leads', 'kfp_export_leads' );
function kfp_export_leads() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'kfp-properties' ) );
	}
	check_admin_referer( 'kfp_export_leads' );
	global $wpdb;
	$rows  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}kfp_leads WHERE created_at >= %s ORDER BY created_at DESC", gmdate( 'Y-m-d H:i:s', strtotime( '-365 days' ) ) ), ARRAY_A );
	$file  = 'kfp-leads-' . gmdate( 'Y-m-d' ) . '.csv';
	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=' . $file );
	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array( 'Date', 'Name', 'Email', 'Phone', 'Subject', 'Property', 'Message', 'Status' ) );
	foreach ( $rows as $r ) {
		fputcsv( $out, array( $r['created_at'], $r['name'], $r['email'], $r['phone'], $r['subject'], $r['property_id'], $r['message'], $r['status'] ) );
	}
	fclose( $out );
	exit;
}

function kfp_leads_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Not allowed.', 'kfp-properties' ) );
	}
	global $wpdb;
	$table = $wpdb->prefix . 'kfp_leads';

	// Handle actions: mark read / delete.
	if ( isset( $_GET['kfp_action'], $_GET['lead'], $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'kfp_lead_action' ) ) {
		$id     = absint( $_GET['lead'] );
		$action = sanitize_key( $_GET['kfp_action'] );
		if ( 'read' === $action ) {
			$wpdb->update( $table, array( 'status' => 'read' ), array( 'id' => $id ) );
		} elseif ( 'delete' === $action ) {
			$wpdb->delete( $table, array( 'id' => $id ) );
		}
		$unread = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE status='new'" );
		update_option( 'kfp_unread_leads', $unread );
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Lead updated.', 'kfp-properties' ) . '</p></div>';
	}

	$paged  = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1;
	$per    = 20;
	$offset = ( $paged - 1 ) * $per;
	$total  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" ); // phpcs:ignore
	$rows   = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT %d OFFSET %d", $per, $offset ) ); // phpcs:ignore
	?>
	<div class="wrap">
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Contact Leads', 'kfp-properties' ); ?></h1>
		<a class="page-title-action" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=kfp_export_leads' ), 'kfp_export_leads' ) ); ?>"><?php esc_html_e( 'Export last year (CSV)', 'kfp-properties' ); ?></a>
		<hr />
		<table class="widefat striped">
			<thead><tr>
				<th><?php esc_html_e( 'Date', 'kfp-properties' ); ?></th>
				<th><?php esc_html_e( 'Name', 'kfp-properties' ); ?></th>
				<th><?php esc_html_e( 'Contact', 'kfp-properties' ); ?></th>
				<th><?php esc_html_e( 'Subject', 'kfp-properties' ); ?></th>
				<th><?php esc_html_e( 'Message', 'kfp-properties' ); ?></th>
				<th><?php esc_html_e( 'Status', 'kfp-properties' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'kfp-properties' ); ?></th>
			</tr></thead>
			<tbody>
			<?php if ( $rows ) : foreach ( $rows as $r ) : ?>
				<tr <?php echo 'new' === $r->status ? 'style="font-weight:600;background:#fffbe6;"' : ''; ?>>
					<td><?php echo esc_html( $r->created_at ); ?></td>
					<td><?php echo esc_html( $r->name ); ?><?php echo $r->property_id ? '<br><small><a href="' . esc_url( get_edit_post_link( $r->property_id ) ) . '">' . esc_html__( 'Regarding property #', 'kfp-properties' ) . $r->property_id . '</a></small>' : ''; ?></td>
					<td><a href="mailto:<?php echo esc_attr( $r->email ); ?>"><?php echo esc_html( $r->email ); ?></a><br><?php echo esc_html( $r->phone ); ?></td>
					<td><?php echo esc_html( $r->subject ); ?></td>
					<td><?php echo esc_html( wp_trim_words( $r->message, 20 ) ); ?></td>
					<td><?php echo esc_html( ucfirst( $r->status ) ); ?></td>
					<td>
						<?php $base = admin_url( 'admin.php?page=kfp-leads' ); ?>
						<?php if ( 'new' === $r->status ) : ?>
							<a href="<?php echo esc_url( wp_nonce_url( $base . '&kfp_action=read&lead=' . $r->id, 'kfp_lead_action' ) ); ?>"><?php esc_html_e( 'Mark read', 'kfp-properties' ); ?></a> |
						<?php endif; ?>
						<a href="<?php echo esc_url( wp_nonce_url( $base . '&kfp_action=delete&lead=' . $r->id, 'kfp_lead_action' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( 'Delete this lead?', 'kfp-properties' ) ); ?>');" style="color:#b32d2e;"><?php esc_html_e( 'Delete', 'kfp-properties' ); ?></a>
					</td>
				</tr>
			<?php endforeach; else : ?>
				<tr><td colspan="7"><?php esc_html_e( 'No leads yet. They will appear here as soon as visitors use the contact form.', 'kfp-properties' ); ?></td></tr>
			<?php endif; ?>
			</tbody>
		</table>
		<?php if ( $total > $per ) : ?>
			<p><?php printf( /* translators: 1: total, 2: page, 3: pages */ esc_html__( '%1$d leads · page %2$d of %3$d', 'kfp-properties' ), $total, $paged, ceil( $total / $per ) ); ?>
			<br><a href="<?php echo esc_url( add_query_arg( 'paged', $paged + 1 ) ); ?>"><?php esc_html_e( 'Older →', 'kfp-properties' ); ?></a></p>
		<?php endif; ?>
	</div>
	<?php
}

/** Flush unread count cache when viewing inbox. */
add_action( 'load-admin_page_kfp-leads', function () {
	global $wpdb;
	$table = $wpdb->prefix . 'kfp_leads';
	$wpdb->query( "UPDATE {$table} SET status='new' WHERE status='new'" ); // no-op safeguard if table missing
} );

/* ==================================================================
 * 4. Newsletter subscriptions (footer form)
 * ================================================================== */
add_action( 'admin_post_nopriv_kfp_newsletter', 'kfp_handle_newsletter' );
add_action( 'admin_post_kfp_newsletter', 'kfp_handle_newsletter' );
function kfp_handle_newsletter() {
	if ( ! isset( $_POST['kfp_nl_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['kfp_nl_nonce'] ), 'kfp_newsletter' ) ) {
		wp_die( esc_html__( 'Security check failed.', 'kfp-properties' ) );
	}
	$email = isset( $_POST['nl_email'] ) ? sanitize_email( wp_unslash( $_POST['nl_email'] ) ) : '';
	$list  = (array) get_option( 'kfp_newsletter_emails', array() );
	if ( is_email( $email ) && ! in_array( $email, $list, true ) ) {
		$list[] = $email;
		update_option( 'kfp_newsletter_emails', $list );
		do_action( 'kfp_newsletter_subscribed', $email );
		wp_mail(
			kfp_opt( 'kfp_notify_email', get_option( 'admin_email' ) ),
			__( 'New newsletter subscriber', 'kfp-properties' ),
			esc_html( $email )
		);
	}
	wp_safe_redirect( add_query_arg( 'nl', 'ok', wp_get_referer() ?: home_url() ) );
	exit;
}
