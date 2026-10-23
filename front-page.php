<?php
/**
 * Front page: assembles the homepage sections.
 * If the page is built with Elementor/Gutenberg, let the builder render it;
 * otherwise output the Customizer-driven section stack.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

// Respect pages built with a page builder (Elementor / Gutenberg full-page).
$kfp_built_with_builder = (
	class_exists( '\Elementor\Plugin' ) &&
	\Elementor\Plugin::instance()->documents->get( get_the_ID() )->is_built_with_elementor()
) || '' !== get_post_meta( get_the_ID(), '_kfp_use_builder', true );

if ( $kfp_built_with_builder ) {
	while ( have_posts() ) {
		the_post();
		the_content();
	}
	return;
}
?>
<?php if ( kfp_show( 'hero' ) ) : ?>
	<?php get_template_part( 'template-parts/content', 'hero' ); ?>
<?php endif; ?>

<?php if ( kfp_show( 'properties' ) ) : ?>
	<?php echo kfp_render_properties(); // phpcs:ignore ?>
<?php endif; ?>

<?php if ( kfp_show( 'about' ) ) : ?>
	<?php get_template_part( 'template-parts/content', 'about' ); ?>
<?php endif; ?>

<?php if ( kfp_show( 'gallery' ) ) : ?>
	<?php echo kfp_render_gallery(); // phpcs:ignore ?>
<?php endif; ?>

<?php if ( kfp_show( 'testimonials' ) ) : ?>
	<?php get_template_part( 'template-parts/content', 'testimonials' ); ?>
<?php endif; ?>

<?php if ( kfp_show( 'cta' ) ) : ?>
	<?php echo kfp_render_cta(); // phpcs:ignore ?>
<?php endif; ?>

<?php if ( kfp_show( 'contact' ) ) : ?>
	<?php get_template_part( 'template-parts/content', 'contact' ); ?>
<?php endif; ?>
