<?php
/**
 * Footer: 4-column footer, social icons, WhatsApp FAB.
 *
 * @package kfp-properties
 */

$kfp_socials = kfp_social_links();
?>
<footer class="kfp-footer">
	<div class="kfp-container">
		<div class="kfp-footer__grid">

			<div>
				<h4><?php bloginfo( 'name' ); ?></h4>
				<p><?php echo esc_html( kfp_opt( 'kfp_footer_about', __( 'Your trusted partner in land sales and agricultural opportunities, providing exceptional property solutions across Nigeria.', 'kfp-properties' ) ) ); ?></p>
				<?php if ( $kfp_socials ) : ?>
					<div class="kfp-footer__social">
						<?php
						$icons = array(
							'facebook'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7h2.4l.6-3h-3V9.1c0-.9.4-1.6 1.7-1.6H16.6V4.8c-.3 0-1.3-.2-2.5-.2-2.5 0-4.2 1.5-4.2 4.3V11H7.5v3h2.4v7h3.6z"/></svg>',
							'instagram' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.2 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1 .4-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1-.4-2.2C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1-.4 2.2-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.8.1-1.2.1-1.6.2-1.9.4-.5.2-.8.4-1.1.8-.3.3-.5.6-.8 1.1-.1.3-.3.8-.4 1.9-.1 1.3-.1 1.6-.1 4.8s0 3.5.1 4.8c.1 1.2.2 1.6.4 1.9.2.5.4.8.8 1.1.3.3.6.5 1.1.8.3.1.8.3 1.9.4 1.3.1 1.6.1 4.8.1s3.5 0 4.8-.1c1.2-.1 1.6-.2 1.9-.4.5-.2.8-.4 1.1-.8.3-.3.5-.6.8-1.1.1-.3.3-.8.4-1.9.1-1.3.1-1.6.1-4.8s0-3.5-.1-4.8c-.1-1.2-.2-1.6-.4-1.9-.2-.5-.4-.8-.8-1.1-.3-.3-.6-.5-1.1-.8-.3-.1-.8-.3-1.9-.4-1.3-.1-1.7-.1-4.8-.1zm0 3.1a4.9 4.9 0 110 9.8 4.9 4.9 0 010-9.8zm0 8.1a3.2 3.2 0 100-6.4 3.2 3.2 0 000 6.4zm6.2-8.3a1.1 1.1 0 11-2.3 0 1.1 1.1 0 012.3 0z"/></svg>',
							'twitter'   => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-6.8 7.8L23 22h-6.3l-4.9-6.4L6.2 22H3l7.3-8.4L2 2h6.4l4.4 5.9L18.9 2zm-1.1 18.1h1.7L7.1 3.8H5.3l12.5 16.3z"/></svg>',
							'linkedin'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.5 2.5 0 112.5 6 2.5 2.5 0 014.98 3.5zM3 8.9h4V21H3zM9 8.9h3.8v1.7h.1a4.2 4.2 0 013.8-2.1c4 0 4.8 2.6 4.8 6.1V21h-4v-5.5c0-1.3 0-3-1.9-3s-2.1 1.4-2.1 2.9V21H9z"/></svg>',
						);
						foreach ( $kfp_socials as $key => $data ) :
							list( $label, $url ) = $data; ?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $label ); ?>">
								<?php echo isset( $icons[ $key ] ) ? $icons[ $key ] : esc_html( $label ); // phpcs:ignore ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div>
				<h4><?php esc_html_e( 'Quick Links', 'kfp-properties' ); ?></h4>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'menu_id'        => 'kfp-footer-menu',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'kfp_menu_fallback',
				) );
				?>
			</div>

			<div>
				<h4><?php esc_html_e( 'Contact', 'kfp-properties' ); ?></h4>
				<ul>
					<li><?php esc_html_e( 'Address:', 'kfp-properties' ); ?> <?php echo esc_html( kfp_opt( 'kfp_address', __( 'Lagos, Nigeria', 'kfp-properties' ) ) ); ?></li>
					<li><a href="<?php echo esc_url( kfp_tel_link() ); ?>"><?php echo esc_html( kfp_opt( 'kfp_phone', '+234 803 731 9301' ) ); ?></a></li>
					<li><a href="mailto:<?php echo esc_attr( kfp_opt( 'kfp_email', 'info@kfplimited.com.ng' ) ); ?>"><?php echo esc_html( kfp_opt( 'kfp_email', 'info@kfplimited.com.ng' ) ); ?></a></li>
					<li><a href="<?php echo esc_url( kfp_wa_link( __( 'Hello, I am interested in your land offers.', 'kfp-properties' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'WhatsApp Chat', 'kfp-properties' ); ?></a></li>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'Newsletter', 'kfp-properties' ); ?></h4>
				<p><?php esc_html_e( 'Get new estate launches and price drops in your inbox.', 'kfp-properties' ); ?></p>
				<?php get_template_part( 'template-parts/content', 'newsletter' ); ?>
			</div>
		</div>

		<div class="kfp-footer__bottom">
			<p><?php
				$tpl = kfp_opt( 'kfp_copyright', "© %s King's Farms And Properties Ltd." );
				echo esc_html( sprintf( $tpl, gmdate( 'Y' ) ) );
			?></p>
		</div>
	</div>
</footer>

<?php if ( kfp_opt( 'kfp_whatsapp', '2348037319301' ) ) : ?>
	<a class="kfp-whatsapp" href="<?php echo esc_url( kfp_wa_link( __( 'Hello King\'s Farms! I found you on your website.', 'kfp-properties' ) ) ); ?>"
	   target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'kfp-properties' ); ?>">
		<svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor" aria-hidden="true">
			<path d="M20.4 3.6C18.2 1.4 15.2 0 12 0 5.4 0 0 5.4 0 12c0 2.1.6 4.2 1.7 6L0 24l6.2-1.6c1.8 1 3.8 1.5 5.8 1.5 6.6 0 12-5.4 12-12 0-3.2-1.4-6.2-3.6-8.4zM12 22c-1.8 0-3.6-.5-5.2-1.4l-.4-.2-3.7 1 1-3.6-.2-.4c-1-1.6-1.5-3.4-1.5-5.3 0-5.5 4.5-10 10-10s10 4.5 10 10-4.5 10-10 10zm5.5-7.4c-.3-.1-1.8-.9-2.1-1-.3-.1-.5-.1-.7.1-.2.2-.8.9-1 1.1-.2.2-.3.2-.6.1-1.7-.9-2.8-1.6-4-3.6-.3-.5.3-.5.9-1.7.1-.2 0-.4-.1-.6s-.7-1.8-1-2.4c-.3-.7-.6-.6-.8-.6h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.4s1 2.8 1.2 3c.2.2 2.1 3.2 5.1 4.5.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.8-.7 2-1.4.2-.7.2-1.3.2-1.4 0-.2-.2-.3-.5-.4z"/>
		</svg>
	</a>
<?php endif; ?>

<div class="kfp-lightbox" id="kfp-lightbox" role="dialog" aria-modal="true">
	<button class="kfp-lightbox__close" aria-label="<?php esc_attr_e( 'Close image', 'kfp-properties' ); ?>">×</button>
	<img src="" alt="">
</div>

<?php wp_footer(); ?>
</body>
</html>
