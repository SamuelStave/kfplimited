<?php
/**
 * Property card (inside the loop).
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

$kfp_img    = kfp_property_image();
$kfp_price  = kfp_property_meta( 'price' );
$kfp_sqm    = kfp_property_meta( 'price_sqm' );
$kfp_size   = kfp_property_meta( 'size' );
$kfp_loc    = kfp_property_location();
$kfp_badge  = kfp_property_meta( 'title_suffix' );
$kfp_agent  = kfp_property_meta( 'agent_name' );
$kfp_feats  = kfp_property_features();
?>
<article class="kfp-card kfp-reveal">
	<div class="kfp-card__media">
		<?php if ( $kfp_img ) : ?>
			<img src="<?php echo esc_url( $kfp_img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
		<?php else : ?>
			<img src="<?php echo esc_url( KFP_URI . '/assets/img/placeholder.svg' ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
		<?php endif; ?>
		<?php if ( $kfp_badge ) : ?><span class="kfp-card__status"><?php echo esc_html( $kfp_badge ); ?></span><?php endif; ?>
		<?php if ( $kfp_price ) : ?><span class="kfp-card__price"><?php echo esc_html( $kfp_price ); ?></span><?php endif; ?>
	</div>

	<div class="kfp-card__body">
		<h3 class="kfp-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		<div class="kfp-card__meta">
			<?php if ( $kfp_loc ) : ?><span>📍 <?php echo esc_html( $kfp_loc ); ?></span><?php endif; ?>
			<?php if ( $kfp_size ) : ?><span>• <?php echo esc_html( $kfp_size ); ?></span><?php endif; ?>
			<?php if ( $kfp_sqm ) : ?><span>• <?php echo esc_html( $kfp_sqm ); ?></span><?php endif; ?>
		</div>

		<?php if ( $kfp_feats ) : ?>
			<ul class="kfp-card__features">
				<?php foreach ( array_slice( $kfp_feats, 0, 4 ) as $f ) : ?>
					<li><?php echo esc_html( $f ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<div class="kfp-card__footer">
			<?php if ( $kfp_agent ) : ?>
				<span class="kfp-card__agent"><?php esc_html_e( 'Agent:', 'kfp-properties' ); ?> <b><?php echo esc_html( $kfp_agent ); ?></b></span>
			<?php endif; ?>
			<a class="kfp-btn kfp-btn--outline" style="padding:9px 20px;font-size:.8rem;" href="<?php the_permalink(); ?>">
				<?php esc_html_e( 'View Details', 'kfp-properties' ); ?>
			</a>
		</div>
	</div>
</article>
