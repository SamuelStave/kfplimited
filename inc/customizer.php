<?php
/**
 * Customizer: branding, images, homepage section toggles & content.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

/** Get a theme option with default fallback. */
function kfp_opt( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/** Is a homepage section enabled? */
function kfp_section_enabled( $section ) {
	$defaults = array(
		'hero'         => true,
		'stats'        => true,
		'properties'   => true,
		'about'        => true,
		'gallery'      => true,
		'testimonials' => true,
		'cta'          => true,
		'contact'      => true,
	);
	$enabled = (array) json_decode( kfp_opt( 'kfp_sections', wp_json_encode( $defaults ) ), true );
	if ( empty( $enabled ) ) {
		$enabled = $defaults;
	}
	return ! empty( $enabled[ $section ] );
}

add_action( 'customize_register', function ( $wp_customize ) {

	/* ---------- Panel / sections ---------- */
	$wp_customize->add_panel( 'kfp_theme', array(
		'title'       => __( "King's Farms Theme Settings", 'kfp-properties' ),
		'description' => __( 'Branding, images, homepage sections and contact details.', 'kfp-properties' ),
		'priority'    => 20,
	) );

	$sections = array(
		'kfp_branding'   => __( 'Branding & Logo', 'kfp-properties' ),
		'kfp_images'     => __( 'Site Images', 'kfp-properties' ),
		'kfp_sections'   => __( 'Homepage Sections', 'kfp-properties' ),
		'kfp_contactinfo'=> __( 'Contact Details', 'kfp-properties' ),
		'kfp_socials'    => __( 'Social Links', 'kfp-properties' ),
	);
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array(
			'title' => $title,
			'panel' => 'kfp_theme',
		) );
	}

	/* ---------- Helper to add settings ---------- */
	$add_text = function ( $section, $mod, $label, $default = '', $type = 'text' ) use ( $wp_customize ) {
		$wp_customize->add_setting( $mod, array(
			'default'           => $default,
			'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( $mod . '_ctrl', array(
			'label'   => $label,
			'section' => $section,
			'settings'=> $mod,
			'type'    => $type,
		) );
	};
	$add_image = function ( $section, $mod, $label, $default = '' ) use ( $wp_customize ) {
		$wp_customize->add_setting( $mod, array( 'default' => $default, 'transport' => 'refresh' ) );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $mod . '_ctrl', array(
			'label'     => $label,
			'section'   => $section,
			'settings'  => $mod,
			'mime_type' => 'image',
		) ) );
	};

	/* ---------- Branding ---------- */
	$add_text( 'kfp_branding', 'kfp_tagline_small', __( 'Logo sub-text (under name)', 'kfp-properties' ), __( 'Farms & Properties Ltd.', 'kfp-properties' ) );
	$add_text( 'kfp_branding', 'kfp_footer_about', __( 'Footer about text', 'kfp-properties' ),
		__( 'Your trusted partner in land sales and agricultural opportunities, providing exceptional property solutions across Nigeria.', 'kfp-properties' ),
		'textarea' );
	$add_text( 'kfp_branding', 'kfp_copyright', __( 'Copyright line', 'kfp-properties' ),
		/* translators: %s year */ __( "© %s King's Farms And Properties Ltd. All rights reserved.", 'kfp-properties' ) );

	/* ---------- Images ---------- */
	$add_image( 'kfp_images', 'kfp_hero_image', __( 'Hero background image', 'kfp-properties' ), '' );
	$add_image( 'kfp_images', 'kfp_about_image', __( 'About section image', 'kfp-properties' ), '' );
	$add_image( 'kfp_images', 'kfp_cta_image', __( 'CTA banner background', 'kfp-properties' ), '' );
	for ( $i = 1; $i <= 8; $i++ ) {
		$add_image( 'kfp_images', 'kfp_gallery_img_' . $i, sprintf( /* translators: %d: number */ __( 'Gallery marquee image %d', 'kfp-properties' ), $i ) );
	}

	/* ---------- Section toggles + content ---------- */
	$toggle_defs = array(
		'hero'         => __( 'Show Hero', 'kfp-properties' ),
		'stats'        => __( 'Show Stats under hero', 'kfp-properties' ),
		'properties'   => __( 'Show Featured Properties', 'kfp-properties' ),
		'about'        => __( 'Show About', 'kfp-properties' ),
		'gallery'      => __( 'Show Image Gallery Marquee', 'kfp-properties' ),
		'testimonials' => __( 'Show Testimonials', 'kfp-properties' ),
		'cta'          => __( 'Show CTA Banner', 'kfp-properties' ),
		'contact'      => __( 'Show Contact Form', 'kfp-properties' ),
	);
	foreach ( $toggle_defs as $key => $label ) {
		$wp_customize->add_setting( 'kfp_show_' . $key, array( 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean' ) );
		$wp_customize->add_control( 'kfp_show_' . $key . '_ctrl', array(
			'label'     => $label,
			'section'   => 'kfp_sections',
			'settings'  => 'kfp_show_' . $key,
			'type'      => 'checkbox',
			'priority'  => array_search( $key, array_keys( $toggle_defs ), true ) + 1,
		) );
	}

	// Hero copy.
	$add_text( 'kfp_sections', 'kfp_hero_title', __( 'Hero title (wrap a word in *asterisks* to highlight it)', 'kfp-properties' ), __( 'Find Your *Dream Land*', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_hero_subtitle', __( 'Hero subtitle', 'kfp-properties' ), __( 'Discover exceptional plots and estates in prime Nigerian locations — verified titles, dry land, instant allocation.', 'kfp-properties' ), 'textarea' );
	$add_text( 'kfp_sections', 'kfp_hero_btn1', __( 'Hero button 1 label', 'kfp-properties' ), __( 'View Properties', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_hero_btn2', __( 'Hero button 2 label', 'kfp-properties' ), __( 'Talk on WhatsApp', 'kfp-properties' ) );

	// Stats.
	$add_text( 'kfp_sections', 'kfp_stat1_num', __( 'Stat 1 number', 'kfp-properties' ), '500+' );
	$add_text( 'kfp_sections', 'kfp_stat1_lbl', __( 'Stat 1 label', 'kfp-properties' ), __( 'Plots Sold', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_stat2_num', __( 'Stat 2 number', 'kfp-properties' ), '12' );
	$add_text( 'kfp_sections', 'kfp_stat2_lbl', __( 'Stat 2 label', 'kfp-properties' ), __( 'Estate Locations', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_stat3_num', __( 'Stat 3 number', 'kfp-properties' ), '98%' );
	$add_text( 'kfp_sections', 'kfp_stat3_lbl', __( 'Stat 3 label', 'kfp-properties' ), __( 'Happy Clients', 'kfp-properties' ) );

	// Properties heading.
	$add_text( 'kfp_sections', 'kfp_prop_eyebrow', __( 'Properties eyebrow label', 'kfp-properties' ), __( 'Land opportunities', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_prop_title', __( 'Properties heading', 'kfp-properties' ), __( 'Invest in prime lands today!', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_prop_count', __( 'Number of featured properties', 'kfp-properties' ), '6' );

	// About.
	$add_text( 'kfp_sections', 'kfp_about_eyebrow', __( 'About eyebrow', 'kfp-properties' ), __( 'Royal real estate solutions', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_about_title', __( 'About heading', 'kfp-properties' ), __( 'Your trusted partner in land sales', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_about_text', __( 'About paragraph', 'kfp-properties' ),
		__( "At King's Farms and Properties Limited, we pride ourselves on being the go-to real estate company for land sales and agricultural opportunities. With a commitment to excellence and a focus on customer satisfaction, we connect buyers with prime properties that meet their needs.", 'kfp-properties' ),
		'textarea' );
	$add_text( 'kfp_sections', 'kfp_about_points', __( 'About checklist (one item per line)', 'kfp-properties' ),
		__( 'Verified titles: C of O & Governor’s Consent\nDry, flood-free land\nInstant allocation & survey plan\nTransparent pricing — no hidden fees', 'kfp-properties' ),
		'textarea' );

	// Testimonials heading.
	$add_text( 'kfp_sections', 'kfp_testi_title', __( 'Testimonials heading', 'kfp-properties' ), __( 'What Our Clients Say', 'kfp-properties' ) );

	// CTA.
	$add_text( 'kfp_sections', 'kfp_cta_title', __( 'CTA heading', 'kfp-properties' ), __( 'Ready to own land in Lagos?', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_cta_text', __( 'CTA text', 'kfp-properties' ), __( 'Book a site inspection today — weekend tours available.', 'kfp-properties' ) );
	$add_text( 'kfp_sections', 'kfp_cta_btn', __( 'CTA button label', 'kfp-properties' ), __( 'Book Inspection', 'kfp-properties' ) );

	/* ---------- Contact info ---------- */
	$add_text( 'kfp_contactinfo', 'kfp_phone', __( 'Phone', 'kfp-properties' ), '+234 803 731 9301' );
	$add_text( 'kfp_contactinfo', 'kfp_whatsapp', __( 'WhatsApp number (digits only)', 'kfp-properties' ), '2348037319301' );
	$add_text( 'kfp_contactinfo', 'kfp_email', __( 'Public email', 'kfp-properties' ), 'info@kfplimited.com.ng' );
	$add_text( 'kfp_contactinfo', 'kfp_notify_email', __( 'Lead notification email(s), comma separated', 'kfp-properties' ), get_option( 'admin_email' ) );
	$add_text( 'kfp_contactinfo', 'kfp_address', __( 'Address', 'kfp-properties' ), __( 'Lagos, Nigeria', 'kfp-properties' ) );
	$add_text( 'kfp_contactinfo', 'kfp_hours', __( 'Business hours (one per line: Day | Hours)', 'kfp-properties' ),
		__( "Monday - Friday | 9:00 AM - 6:00 PM\nSaturday | 10:00 AM - 4:00 PM\nSunday | Closed", 'kfp-properties' ),
		'textarea' );

	/* ---------- Socials ---------- */
	$add_text( 'kfp_socials', 'kfp_social_facebook', __( 'Facebook URL', 'kfp-properties' ), '' );
	$add_text( 'kfp_socials', 'kfp_social_instagram', __( 'Instagram URL', 'kfp-properties' ), '' );
	$add_text( 'kfp_socials', 'kfp_social_twitter', __( 'X / Twitter URL', 'kfp-properties' ), '' );
	$add_text( 'kfp_socials', 'kfp_social_linkedin', __( 'LinkedIn URL', 'kfp-properties' ), '' );
} );

/**
 * Read section visibility directly from mods (with sensible defaults).
 * Used instead of the JSON blob so individual toggles work independently.
 */
function kfp_show( $key ) {
	return (bool) get_theme_mod( 'kfp_show_' . $key, true );
}
