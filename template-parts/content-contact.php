<?php
/**
 * Contact section: AJAX lead form + info card.
 * Accepts $args['property_id'] when embedded on a single property page.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

$property_id = isset( $args['property_id'] ) ? absint( $args['property_id'] ) : 0;
$subject     = $property_id
	? sprintf( /* translators: %s: property title */ __( 'Enquiry: %s', 'kfp-properties' ), get_the_title( $property_id ) )
	: __( 'Website enquiry', 'kfp-properties' );
?>
<section class="kfp-section" id="contact">
	<div class="kfp-container">
		<div class="kfp-section__head">
			<span class="kfp-badge" style="background:#eef2ff;color:var(--kfp-blue-dark);"><?php esc_html_e( 'We reply within one business day', 'kfp-properties' ); ?></span>
			<h2><?php esc_html_e( 'Get in touch', 'kfp-properties' ); ?></h2>
		</div>

		<div class="kfp-contact">
			<form class="kfp-form kfp-reveal" id="kfp-contact-form" novalidate>
				<div class="kfp-form__msg" role="status" aria-live="polite"></div>

				<div class="kfp-form__row">
					<div>
						<label for="kfp-name"><?php esc_html_e( 'Full Name *', 'kfp-properties' ); ?></label>
						<input type="text" id="kfp-name" name="name" required autocomplete="name" />
					</div>
					<div>
						<label for="kfp-phone"><?php esc_html_e( 'Phone / WhatsApp', 'kfp-properties' ); ?></label>
						<input type="tel" id="kfp-phone" name="phone" autocomplete="tel" placeholder="+234…" />
					</div>
				</div>

				<div>
					<label for="kfp-email"><?php esc_html_e( 'Email *', 'kfp-properties' ); ?></label>
					<input type="email" id="kfp-email" name="email" required autocomplete="email" />
				</div>

				<div>
					<label for="kfp-subject"><?php esc_html_e( 'Subject', 'kfp-properties' ); ?></label>
					<input type="text" id="kfp-subject" name="subject" value="<?php echo esc_attr( $subject ); ?>" />
				</div>

				<div>
					<label for="kfp-message"><?php esc_html_e( 'Message *', 'kfp-properties' ); ?></label>
					<textarea id="kfp-message" name="message" required placeholder="<?php esc_attr_e( 'Tell us which location or budget you have in mind…', 'kfp-properties' ); ?>"></textarea>
				</div>

				<!-- Honeypot for bots -->
				<div style="position:absolute;left:-9999px;" aria-hidden="true">
					<label>Company website <input type="text" name="company_website" tabindex="-1" autocomplete="off" /></label>
				</div>

				<input type="hidden" name="property_id" value="<?php echo esc_attr( $property_id ); ?>" />

				<button type="submit" class="kfp-btn kfp-btn--primary" style="width:100%;">
					<?php esc_html_e( 'Send Message', 'kfp-properties' ); ?>
				</button>
				<p class="description" style="font-size:.8rem;color:var(--kfp-muted);margin:0;">
					<?php esc_html_e( 'By sending this form you agree to be contacted about our property offers.', 'kfp-properties' ); ?>
				</p>
			</form>

			<aside class="kfp-info-card kfp-reveal">
				<h3><?php esc_html_e( 'Contact Information', 'kfp-properties' ); ?></h3>
				<ul class="kfp-info-list">
					<li>
						<span class="kfp-ico">📞</span>
						<div><b><?php esc_html_e( 'Phone', 'kfp-properties' ); ?></b>
							<a href="<?php echo esc_url( kfp_tel_link() ); ?>"><?php echo esc_html( kfp_opt( 'kfp_phone', '+234 803 731 9301' ) ); ?></a></div>
					</li>
					<li>
						<span class="kfp-ico">✉️</span>
						<div><b><?php esc_html_e( 'Email', 'kfp-properties' ); ?></b>
							<a href="mailto:<?php echo esc_attr( kfp_opt( 'kfp_email', 'info@kfplimited.com.ng' ) ); ?>"><?php echo esc_html( kfp_opt( 'kfp_email', 'info@kfplimited.com.ng' ) ); ?></a></div>
					</li>
					<li>
						<span class="kfp-ico">📍</span>
						<div><b><?php esc_html_e( 'Address', 'kfp-properties' ); ?></b>
							<?php echo esc_html( kfp_opt( 'kfp_address', __( 'Lagos, Nigeria', 'kfp-properties' ) ) ); ?></div>
					</li>
					<li>
						<span class="kfp-ico">💬</span>
						<div><b><?php esc_html_e( 'WhatsApp', 'kfp-properties' ); ?></b>
							<a href="<?php echo esc_url( kfp_wa_link( __( 'Hello King\'s Farms!', 'kfp-properties' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Chat instantly', 'kfp-properties' ); ?></a></div>
					</li>
				</ul>

				<?php $hours = kfp_hours_rows(); if ( $hours ) : ?>
					<div class="kfp-hours">
						<h4 style="color:#fff;"><?php esc_html_e( 'Business Hours', 'kfp-properties' ); ?></h4>
						<table>
							<?php foreach ( $hours as $row ) : ?>
								<tr><td><?php echo esc_html( $row[0] ); ?></td><td><?php echo esc_html( $row[1] ); ?></td></tr>
							<?php endforeach; ?>
						</table>
					</div>
				<?php endif; ?>
			</aside>
		</div>
	</div>
</section>
