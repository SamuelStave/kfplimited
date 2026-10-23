<?php
/**
 * Footer newsletter mini-form (stores emails in options; wire to any
 * email service by hooking `kfp_newsletter_subscribed`).
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;
?>
<form class="kfp-newsletter" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:flex;gap:10px;flex-wrap:wrap;">
	<input type="hidden" name="action" value="kfp_newsletter" />
	<?php wp_nonce_field( 'kfp_newsletter', 'kfp_nl_nonce' ); ?>
	<label class="screen-reader-text" for="kfp-nl-email"><?php esc_html_e( 'Email address', 'kfp-properties' ); ?></label>
	<input id="kfp-nl-email" type="email" name="nl_email" required placeholder="you@example.com"
	       style="flex:1;min-width:180px;padding:12px 14px;border-radius:10px;border:1.5px solid rgba(255,255,255,.25);background:rgba(255,255,255,.08);color:#fff;" />
	<button class="kfp-btn kfp-btn--primary" style="padding:11px 22px;" type="submit"><?php esc_html_e( 'Subscribe', 'kfp-properties' ); ?></button>
	<?php if ( isset( $_GET['nl'] ) && 'ok' === $_GET['nl'] ) : ?>
		<p style="width:100%;margin:0;color:#7ee2a8;"><?php esc_html_e( 'Subscribed — welcome aboard!', 'kfp-properties' ); ?></p>
	<?php endif; ?>
</form>
