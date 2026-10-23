<?php
/**
 * Properties archive: filterable grid of all listings.
 *
 * @package kfp-properties
 */

get_header();

$kfp_locations = get_terms( array( 'taxonomy' => 'property_location', 'hide_empty' => true ) );
$kfp_cur_loc   = isset( $_GET['loc'] ) ? sanitize_title( wp_unslash( $_GET['loc'] ) ) : ''; // phpcs:ignore

$args = array(
	'post_type'      => 'property',
	'posts_per_page' => 12,
	'paged'          => max( 1, get_query_var( 'paged' ) ),
);
if ( $kfp_cur_loc ) {
	$args['tax_query'] = array( array(
		'taxonomy' => 'property_location',
		'field'    => 'slug',
		'terms'    => $kfp_cur_loc,
	) );
}
$kfp_query = new WP_Query( $args );
?>
<header class="kfp-property-hero" style="padding-bottom:40px;">
	<div class="kfp-container">
		<span class="kfp-badge"><?php esc_html_e( 'All listings', 'kfp-properties' ); ?></span>
		<h1><?php post_type_archive_title(); ?></h1>
		<p style="color:rgba(255,255,255,.8);"><?php esc_html_e( 'Browse every verified plot and estate currently on offer.', 'kfp-properties' ); ?></p>

		<?php if ( $kfp_locations && ! is_wp_error( $kfp_locations ) ) : ?>
			<div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:22px;">
				<a class="kfp-btn <?php echo ! $kfp_cur_loc ? 'kfp-btn--primary' : 'kfp-btn--ghost'; ?>" style="padding:9px 20px;font-size:.8rem;"
				   href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'All', 'kfp-properties' ); ?></a>
				<?php foreach ( $kfp_locations as $loc ) : ?>
					<a class="kfp-btn <?php echo $kfp_cur_loc === $loc->slug ? 'kfp-btn--primary' : 'kfp-btn--ghost'; ?>" style="padding:9px 20px;font-size:.8rem;"
					   href="<?php echo esc_url( add_query_arg( 'loc', $loc->slug, get_post_type_archive_link( 'property' ) ) ); ?>">
						<?php echo esc_html( $loc->name ); ?> (<?php echo absint( $loc->count ); ?>)
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</header>

<section class="kfp-section">
	<div class="kfp-container">
		<?php if ( $kfp_query->have_posts() ) : ?>
			<div class="kfp-grid kfp-grid--3">
				<?php
				while ( $kfp_query->have_posts() ) {
					$kfp_query->the_post();
					get_template_part( 'template-parts/content', 'property-card' );
				}
				wp_reset_postdata();
				?>
			</div>

			<div class="kfp-pagination" style="text-align:center;margin-top:48px;">
				<?php the_posts_pagination( array(
					'prev_text' => __( '← Previous', 'kfp-properties' ),
					'next_text' => __( 'Next →', 'kfp-properties' ),
				) ); ?>
			</div>
		<?php else : ?>
			<p style="text-align:center;"><?php esc_html_e( 'No properties found. Add listings under Properties → Add New Property.', 'kfp-properties' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
