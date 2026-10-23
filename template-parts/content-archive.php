<?php
/**
 * Archive / blog listing content.
 *
 * @package kfp-properties
 */
?>
<header class="kfp-property-hero" style="padding-bottom:40px;">
	<div class="kfp-container">
		<h1><?php the_archive_title(); ?></h1>
		<p style="color:rgba(255,255,255,.8);"><?php the_archive_description(); ?></p>
	</div>
</header>
<section class="kfp-section">
	<div class="kfp-container">
		<div class="kfp-grid kfp-grid--3">
			<?php
			do_action( 'loop_start' );
			while ( have_posts() ) {
				the_post(); ?>
				<article class="kfp-card kfp-reveal">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="kfp-card__media"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'kfp-card' ); ?></a></div>
					<?php endif; ?>
					<div class="kfp-card__body">
						<h3 class="kfp-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="kfp-card__meta"><?php echo esc_html( get_the_date() ); ?></div>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
						<div class="kfp-card__footer">
							<a class="kfp-btn kfp-btn--outline" style="padding:9px 20px;font-size:.8rem;" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'kfp-properties' ); ?></a>
						</div>
					</div>
				</article>
				<?php
			}
			do_action( 'loop_end' );
			?>
		</div>
		<div style="text-align:center;margin-top:48px;">
			<?php the_posts_pagination(); ?>
		</div>
	</div>
</section>
