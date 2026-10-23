<?php
/**
 * King's Farms Properties theme bootstrap.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

define( 'KFP_VERSION', '1.0.0' );
define( 'KFP_DIR', get_template_directory() );
define( 'KFP_URI', get_template_directory_uri() );

/* ------------------------------------------------------------------
 * Includes
 * ---------------------------------------------------------------- */
require KFP_DIR . '/inc/custom-post-types.php';   // Properties CPT + taxonomies + meta boxes.
require KFP_DIR . '/inc/customizer.php';          // Homepage sections, branding, contact settings.
require KFP_DIR . '/inc/leads.php';               // Contact form -> DB table, emails, alerts, CSV export.
require KFP_DIR . '/inc/blocks.php';              // Gutenberg blocks + Elementor widgets + shortcodes.
require KFP_DIR . '/inc/helpers.php';             // Template helpers used across template files.

/* ------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------- */
function kfp_setup() {
	load_theme_textdomain( 'kfp-properties', KFP_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 320,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'kfp-properties' ),
		'footer'  => __( 'Footer Menu', 'kfp-properties' ),
	) );

	set_post_thumbnail_size( 760, 570, true );
	add_image_size( 'kfp-hero', 1920, 1080, true );
	add_image_size( 'kfp-card', 640, 480, true );
	add_image_size( 'kfp-thumb', 160, 160, true );
}
add_action( 'after_setup_theme', 'kfp_setup' );

/* ------------------------------------------------------------------
 * Assets
 * ---------------------------------------------------------------- */
function kfp_assets() {
	wp_enqueue_style(
		'kfp-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'kfp-style', get_stylesheet_uri(), array( 'kfp-fonts' ), KFP_VERSION );

	wp_enqueue_script( 'kfp-main', KFP_URI . '/assets/js/main.js', array(), KFP_VERSION, true );

	wp_localize_script( 'kfp-main', 'kfpData', array(
		'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
		'nonce'        => wp_create_nonce( 'kfp_contact_nonce' ),
		'i18nSending'  => __( 'Sending…', 'kfp-properties' ),
		'i18nError'    => __( 'Something went wrong. Please try again or call us directly.', 'kfp-properties' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'kfp_assets' );

/* ------------------------------------------------------------------
 * Body classes
 * ---------------------------------------------------------------- */
function kfp_body_classes( $classes ) {
	if ( is_front_page() && kfp_section_enabled( 'hero' ) ) {
		$classes[] = 'kfp-has-hero';
	}
	return $classes;
}
add_filter( 'body_class', 'kfp_body_classes' );

/* ------------------------------------------------------------------
 * Widgets
 * ---------------------------------------------------------------- */
function kfp_widgets() {
	register_sidebar( array(
		'id'            => 'footer-1',
		'name'          => __( 'Footer Column 1', 'kfp-properties' ),
		'description'   => __( 'Widgets shown in the footer.', 'kfp-properties' ),
		'before_widget' => '<div id="%1$s" class="kfp-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'id'            => 'footer-2',
		'name'          => __( 'Footer Column 2', 'kfp-properties' ),
		'before_widget' => '<div id="%1$s" class="kfp-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'kfp_widgets' );

/* ------------------------------------------------------------------
 * Menu fallback before a Primary menu is assigned in Appearance → Menus.
 * ---------------------------------------------------------------- */
function kfp_menu_fallback() {
	$items = array(
		array( __( 'Home', 'kfp-properties' ), home_url( '/' ) ),
		array( __( 'About', 'kfp-properties' ), home_url( '/#about' ) ),
		array( __( 'Properties', 'kfp-properties' ), get_post_type_archive_link( 'property' ) ?: home_url( '/#properties' ) ),
		array( __( 'Testimonials', 'kfp-properties' ), home_url( '/#testimonials' ) ),
		array( __( 'Contact', 'kfp-properties' ), home_url( '/#contact' ) ),
	);
	echo '<ul id="kfp-primary-menu">';
	foreach ( $items as $item ) {
		echo '<li class="menu-item"><a href="' . esc_url( $item[1] ) . '">' . esc_html( $item[0] ) . '</a></li>';
	}
	echo '</ul>';
}

/* ------------------------------------------------------------------
 * Activation flush so /properties/ permalinks work immediately.
 * ---------------------------------------------------------------- */
function kfp_activate() {
	kfp_register_cpt();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'kfp_activate' );

/* ------------------------------------------------------------------
 * Add a "Properties" quick-link to the admin toolbar.
 * ---------------------------------------------------------------- */
function kfp_admin_bar( $wp_admin_bar ) {
	if ( current_user_can( 'edit_posts' ) ) {
		$wp_admin_bar->add_node( array(
			'id'    => 'kfp-properties',
			'title' => __( 'Add Property', 'kfp-properties' ),
			'href'  => admin_url( 'post-new.php?post_type=property' ),
		) );
	}
}
add_action( 'admin_bar_menu', 'kfp_admin_bar', 100 );
