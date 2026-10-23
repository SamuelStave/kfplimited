<?php
/**
 * Hero section template part.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

$kfp_hero_img = kfp_media_url( 'kfp_hero_image', 'kfp-hero' );
if ( ! $kfp_hero_img ) {
	// Fall back to the featured image of the newest property, if any.
	$latest = get_posts( array( 'post_type' => 'property', 'posts_per_page' => 1, 'meta_key' => '_thumbnail_id' ) );
	if ( $latest ) {
		$kfp_hero_img = get_the_post_thumbnail_url( $latest[0], 'kfp-hero' );
	}
}
?>
<section class="kfp-hero" id="home">
	<?php if ( $kfp_hero_img ) : ?>
		<div class="kfp-hero__bg">
			<img src="<?php echo esc_url( $kfp_hero_img ); ?>" alt="" fetchpriority="high" />
		</div>
	<?php endif; ?>

	<div class="kfp-container">
		<div class="kfp-hero__content">
			<h1><?php echo wp_kses( kfp_highlight( kfp_opt( 'kfp_hero_title', __( 'Find Your *Dream Land*', 'kfp-properties' ) ) ), array( 'span' => array() ) ); ?></h1>
			<p><?php echo esc_html( kfp_opt( 'kfp_hero_subtitle', __( 'Discover exceptional plots and estates in prime Nigerian locations — verified titles, dry land, instant allocation.', 'kfp-properties' ) ) ); ?></p>

			<div class="kfp-hero__actions">
				<a class="kfp-btn kfp-btn--primary" href="#properties">
					<?php echo esc_html( kfp_opt( 'kfp_hero_btn1', __( 'View Properties', 'kfp-properties' ) ) ); ?>
				</a>
				<a class="kfp-btn kfp-btn--ghost" href="<?php echo esc_url( kfp_wa_link( __( 'Hello King\'s Farms! I would like to know more about your available lands.', 'kfp-properties' ) ) ); ?>" target="_blank" rel="noopener">
					<?php echo esc_html( kfp_opt( 'kfp_hero_btn2', __( 'Talk on WhatsApp', 'kfp-properties' ) ) ); ?>
				</a>
			</div>

			<?php if ( kfp_show( 'stats' ) ) : ?>
				<div class="kfp-hero__stats">
					<?php for ( $i = 1; $i <= 3; $i++ ) :
						$num = kfp_opt( "kfp_stat{$i}_num", '' );
						$lbl = kfp_opt( "kfp_stat{$i}_lbl", '' );
						if ( ! $num ) continue; ?>
						<div class="kfp-stat">
							<b><?php echo esc_html( $num ); ?></b>
							<span><?php echo esc_html( $lbl ); ?></span>
						</div>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
