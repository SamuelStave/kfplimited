<?php
/**
 * Header.
 *
 * @package kfp-properties
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="kfp-header" id="kfp-header">
	<div class="kfp-container kfp-header__inner">

		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<a class="kfp-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="kfp-brand__text">
					<?php bloginfo( 'name' ); ?>
					<small><?php echo esc_html( kfp_opt( 'kfp_tagline_small', __( 'Farms & Properties Ltd.', 'kfp-properties' ) ) ); ?></small>
				</span>
			</a>
		<?php endif; ?>

		<button class="kfp-nav-toggle" aria-controls="kfp-primary-menu" aria-expanded="false">
			<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<path d="M3 6h18M3 12h18M3 18h18"/>
			</svg>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'kfp-properties' ); ?></span>
		</button>

		<nav class="kfp-nav" aria-label="<?php esc_attr_e( 'Primary', 'kfp-properties' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'kfp-primary-menu',
				'container'      => false,
				'fallback_cb'    => 'kfp_menu_fallback',
				'depth'          => 2,
			) );
			?>
		</nav>
	</div>
</header>
