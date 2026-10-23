<?php
/**
 * Main template wrapper.
 *
 * @package kfp-properties
 */
get_header();
?>
<main id="content" class="site-content">
	<?php
	if ( is_front_page() && ! is_paged() ) {
		get_template_part( 'front-page' );
	} else {
		while ( have_posts() ) :
			the_post();
			if ( is_singular( 'property' ) ) {
				get_template_part( 'template-parts/content', 'property-single' );
			} elseif ( is_singular() || is_home() ) {
				get_template_part( 'template-parts/content', 'page' );
			} else {
				get_template_part( 'template-parts/content', 'archive' );
			}
		endwhile();
	}
	?>
</main>
<?php get_footer(); ?>
