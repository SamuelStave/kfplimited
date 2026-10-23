<?php
/**
 * Single property page: hero, gallery with lightbox, videos, enquiry form.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

$kfp_price   = kfp_property_meta( 'price' );
$kfp_sqm     = kfp_property_meta( 'price_sqm' );
$kfp_size    = kfp_property_meta( 'size' );
$kfp_loc     = kfp_property_location();
$kfp_badge   = kfp_property_meta( 'title_suffix' );
$kfp_agent   = kfp_property_meta( 'agent_name' );
$kfp_phone   = kfp_property_meta( 'agent_phone' );
$kfp_feats   = kfp_property_features();
$kfp_gallery = array_filter( (array) get_post_meta( get_the_ID(), '_kfp_gallery_ids', true ) );
$kfp_videos  = array_filter( (array) get_post_meta( get_the_ID(), '_kfp_videos', true ) );
?>
<header class="kfp-property-hero">
	<div class="kfp-container">
		<?php if ( $kfp_badge ) : ?><span class="kfp-badge kfp-badge--solid"><?php echo esc_html( $kfp_badge ); ?></span><?php endif; ?>
		<h1><?php the_title(); ?></h1>
		<p style="color:rgba(255,255,255,.8);max-width:760px;"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<div class="kfp-property-meta">
			<?php if ( $kfp_price ) : ?><div><b><?php echo esc_html( $kfp_price ); ?></b><?php esc_html_e( 'Price', 'kfp-properties' ); ?></div><?php endif; ?>
			<?php if ( $kfp_sqm ) : ?><div><b><?php echo esc_html( $kfp_sqm ); ?></b><?php esc_html_e( 'Per sqm', 'kfp-properties' ); ?></div><?php endif; ?>
			<?php if ( $kfp_size ) : ?><div><b><?php echo esc_html( $kfp_size ); ?></b><?php esc_html_e( 'Plot Size', 'kfp-properties' ); ?></div><?php endif; ?>
			<?php if ( $kfp_loc ) : ?><div><b><?php echo esc_html( $kfp_loc ); ?></b><?php esc_html_e( 'Location', 'kfp-properties' ); ?></div><?php endif; ?>
		</div>
		<div style="margin-top:26px;display:flex;gap:14px;flex-wrap:wrap;">
			<a class="kfp-btn kfp-btn--primary" href="#contact"><?php esc_html_e( 'Enquire About This Property', 'kfp-properties' ); ?></a>
			<?php if ( $kfp_phone ) : ?>
				<a class="kfp-btn kfp-btn--ghost" href="<?php echo esc_url( 'https://wa.me/' . preg_replace( '/\D/', '', $kfp_phone ) . '?text=' . rawurlencode( sprintf( __( 'Hello, I am interested in: %s', 'kfp-properties' ), get_the_title() ) ) ); ?>" target="_blank" rel="noopener">
					<?php esc_html_e( 'WhatsApp Agent', 'kfp-properties' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</header>

<article <?php post_class( 'kfp-section' ); ?>>
	<div class="kfp-container">

		<h2><?php esc_html_e( 'About this property', 'kfp-properties' ); ?></h2>
		<div class="entry-content"><?php the_content(); ?></div>

		<?php if ( $kfp_feats ) : ?>
			<ul class="kfp-card__features" style="margin:22px 0;">
				<?php foreach ( $kfp_feats as $f ) : ?><li><?php echo esc_html( $f ); ?></li><?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $kfp_gallery || has_post_thumbnail() ) : ?>
			<h2 style="margin-top:48px;"><?php esc_html_e( 'Pictures', 'kfp-properties' ); ?></h2>
			<div class="kfp-gallery" id="kfp-gallery">
				<?php
				$items = $kfp_gallery;
				if ( ! $items && has_post_thumbnail() ) {
					$items = array( get_post_thumbnail_id() );
				}
				foreach ( $items as $id ) :
					$full  = wp_get_attachment_image_url( $id, 'large' );
					$thumb = wp_get_attachment_image_url( $id, 'medium_large' );
					if ( ! $full ) continue; ?>
					<img src="<?php echo esc_url( $thumb ?: $full ); ?>" data-full="<?php echo esc_url( $full ); ?>"
					     alt="<?php echo esc_attr( trim( get_post( $id )->post_excerpt ?: get_the_title() ) ); ?>" loading="lazy" />
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $kfp_videos ) : ?>
			<h2 style="margin-top:48px;"><?php esc_html_e( 'Videos', 'kfp-properties' ); ?></h2>
			<?php foreach ( $kfp_videos as $url ) :
				echo kfp_video_embed( $url ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( $kfp_agent ) : ?>
			<div class="kfp-cta" style="margin-top:56px;">
				<div>
					<h3><?php printf( /* translators: %s agent name */ esc_html__( 'Speak with %s', 'kfp-properties' ), esc_html( $kfp_agent ) ); ?></h3>
					<p><?php esc_html_e( 'Ask about title documents, payment plans and site inspections.', 'kfp-properties' ); ?></p>
				</div>
				<a class="kfp-btn kfp-btn--ghost" href="#contact"><?php esc_html_e( 'Send Enquiry', 'kfp-properties' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</article>

<?php get_template_part( 'template-parts/content', 'contact', array( 'property_id' => get_the_ID() ) ); ?>

<?php
// Related properties from the same location.
$related = new WP_Query( array(
	'post_type'      => 'property',
	'posts_per_page' => 3,
	'post__not_in'   => array( get_the_ID() ),
	'tax_query'      => get_the_terms( get_the_ID(), 'property_location' ) ? array( array(
		'taxonomy' => 'property_location',
		'field'    => 'term_id',
		'terms'    => wp_list_pluck( get_the_terms( get_the_ID(), 'property_location' ), 'term_id' ),
	) ) : array(),
) );
if ( $related->have_posts() ) : ?>
	<section class="kfp-section kfp-section--soft">
		<div class="kfp-container">
			<div class="kfp-section__head"><h2><?php esc_html_e( 'Similar lands nearby', 'kfp-properties' ); ?></h2></div>
			<div class="kfp-grid kfp-grid--3">
				<?php while ( $related->have_posts() ) { $related->the_post(); get_template_part( 'template-parts/content', 'property-card' ); } wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>
