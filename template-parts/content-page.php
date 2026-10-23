<?php
/**
 * Standard page content.
 *
 * @package kfp-properties
 */
?>
<header class="kfp-property-hero" style="padding-bottom:40px;">
	<div class="kfp-container"><h1><?php the_title(); ?></h1></div>
</header>
<article <?php post_class( 'kfp-section' ); ?>>
	<div class="kfp-container entry-content">
		<?php
		while ( have_posts() ) {
			the_post();
			the_content();
		}
		?>
	</div>
</article>
