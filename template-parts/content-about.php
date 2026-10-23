<?php
/**
 * About section.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

$kfp_about_img = kfp_media_url( 'kfp_about_image', 'large' );
if ( ! $kfp_about_img ) {
	$props = get_posts( array( 'post_type' => 'property', 'posts_per_page' => 1, 'meta_key' => '_thumbnail_id' ) );
	if ( $props ) {
		$kfp_about_img = get_the_post_thumbnail_url( $props[0], 'large' );
	}
}

$kfp_points = preg_split( '/\r\n|\r|\n/', (string) kfp_opt( 'kfp_about_points' ) );
$kfp_points = array_filter( array_map( 'trim', $kfp_points ) );
?>
<section class="kfp-section" id="about">
	<div class="kfp-container">
		<div class="kfp-split">
			<div class="kfp-reveal">
				<span class="kfp-badge" style="background:#eef2ff;color:var(--kfp-blue-dark);"><?php echo esc_html( kfp_opt( 'kfp_about_eyebrow', __( 'Royal real estate solutions', 'kfp-properties' ) ) ); ?></span>
				<h2><?php echo esc_html( kfp_opt( 'kfp_about_title', __( 'Your trusted partner in land sales', 'kfp-properties' ) ) ); ?></h2>
				<p><?php echo esc_html( kfp_opt( 'kfp_about_text', __( "At King's Farms and Properties Limited, we pride ourselves on being the go-to real estate company for land sales and agricultural opportunities.", 'kfp-properties' ) ) ); ?></p>

				<?php if ( $kfp_points ) : ?>
					<ul class="kfp-checklist">
						<?php foreach ( $kfp_points as $point ) : ?>
							<li><?php echo esc_html( $point ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<a class="kfp-btn kfp-btn--primary" href="#contact"><?php esc_html_e( 'Get in touch', 'kfp-properties' ); ?></a>
			</div>

			<div class="kfp-split__media kfp-reveal">
				<?php if ( $kfp_about_img ) : ?>
					<img src="<?php echo esc_url( $kfp_about_img ); ?>" alt="<?php esc_attr_e( 'Our estates', 'kfp-properties' ); ?>" loading="lazy" />
				<?php else : ?>
					<img src="<?php echo esc_url( KFP_URI . '/assets/img/placeholder.svg' ); ?>" alt="" loading="lazy" />
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
