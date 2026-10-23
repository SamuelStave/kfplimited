<?php
/**
 * Testimonials section. Pulls from the "Testimonial" CPT if present,
 * otherwise renders the three default client quotes from the original design.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

$defaults = array(
	array(
		'name'  => 'Oluwaseun Adebayo',
		'role'  => __( 'Property Investor', 'kfp-properties' ),
		'text'  => __( "The team at King's Farms made my land acquisition process seamless. Their expertise in the Nigerian real estate market is unmatched!", 'kfp-properties' ),
	),
	array(
		'name'  => 'Chioma Okonkwo',
		'role'  => __( 'Real Estate Developer', 'kfp-properties' ),
		'text'  => __( 'Excellent service and professional guidance. They helped me secure prime land in Lagos at a great value.', 'kfp-properties' ),
	),
	array(
		'name'  => 'Babajide Ogunleye',
		'role'  => __( 'First-time Buyer', 'kfp-properties' ),
		'text'  => __( 'As a first-time investor, they guided me through every step. Their knowledge of land documentation in Nigeria is impressive!', 'kfp-properties' ),
	),
);

// Use testimonial posts if the site has a "testimonial" post type (or use pages tagged).
$kfp_items = $defaults;
if ( post_type_exists( 'testimonial' ) ) {
	$posts = get_posts( array( 'post_type' => 'testimonial', 'posts_per_page' => 6 ) );
	if ( $posts ) {
		$kfp_items = array();
		foreach ( $posts as $p ) {
			$kfp_items[] = array(
				'name'  => $p->post_title,
				'role'  => get_post_meta( $p->ID, '_kfp_testi_role', true ),
				'text'  => $p->post_content,
				'image' => get_the_post_thumbnail_url( $p, 'kfp-thumb' ),
			);
		}
	}
}
?>
<section class="kfp-section kfp-section--navy" id="testimonials">
	<div class="kfp-container">
		<div class="kfp-section__head">
			<span class="kfp-badge"><?php esc_html_e( 'Client stories', 'kfp-properties' ); ?></span>
			<h2><?php echo esc_html( kfp_opt( 'kfp_testi_title', __( 'What Our Clients Say', 'kfp-properties' ) ) ); ?></h2>
		</div>

		<div class="kfp-grid kfp-grid--3">
			<?php foreach ( $kfp_items as $t ) : ?>
				<div class="kfp-testimonial kfp-reveal">
					<div class="kfp-stars" aria-hidden="true">★★★★★</div>
					<?php if ( ! empty( $t['image'] ) ) : ?>
						<img src="<?php echo esc_url( $t['image'] ); ?>" alt="<?php echo esc_attr( $t['name'] ); ?>" loading="lazy">
					<?php else : ?>
						<span style="display:flex;width:84px;height:84px;margin:0 auto 18px;border-radius:50%;background:linear-gradient(135deg,var(--kfp-blue),var(--kfp-navy-2));align-items:center;justify-content:center;font-size:1.7rem;font-weight:800;color:#fff;font-family:var(--kfp-font-head);border:3px solid var(--kfp-gold);"><?php echo esc_html( mb_substr( $t['name'], 0, 1 ) ); ?></span>
					<?php endif; ?>
					<blockquote>&ldquo;<?php echo esc_html( $t['text'] ); ?>&rdquo;</blockquote>
					<cite><?php echo esc_html( $t['name'] ); ?></cite>
					<small><?php echo esc_html( $t['role'] ); ?></small>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
