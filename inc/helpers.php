<?php
/**
 * Template helpers.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

/** WhatsApp click-to-chat URL with prefilled message. */
function kfp_wa_link( $message = '' ) {
	$number = preg_replace( '/\D/', '', kfp_opt( 'kfp_whatsapp', '2348037319301' ) );
	$link   = 'https://wa.me/' . $number;
	if ( $message ) {
		$link .= '?text=' . rawurlencode( $message );
	}
	return $link;
}

/** tel: href from the Customizer phone. */
function kfp_tel_link() {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', kfp_opt( 'kfp_phone', '+234 803 731 9301' ) );
}

/** Highlight *word* segments inside hero titles. */
function kfp_highlight( $text ) {
	$escaped = esc_html( $text );
	return preg_replace( '/\*(.+?)\*/', '<span>$1</span>', $escaped );
}

/** Property meta getter with fallbacks. */
function kfp_property_meta( $key, $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	return get_post_meta( $post_id, '_kfp_' . $key, true );
}

/** Features list as array. */
function kfp_property_features( $post_id = 0 ) {
	$post_id  = $post_id ?: get_the_ID();
	$features = kfp_property_meta( 'features', $post_id );
	$features = array_filter( array_map( 'trim', explode( ',', (string) $features ) ) );
	if ( ! $features ) {
		$terms = get_the_terms( $post_id, 'property_type' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$features = wp_list_pluck( $terms, 'name' );
		}
	}
	return $features;
}

/** Primary location label. */
function kfp_property_location( $post_id = 0 ) {
	$post_id = $post_id ?: get_the_ID();
	$terms   = get_the_terms( $post_id, 'property_location' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}

/** First property image URL: gallery[0] > featured > placeholder gradient. */
function kfp_property_image( $post_id = 0, $size = 'kfp-card' ) {
	$post_id  = $post_id ?: get_the_ID();
	$gallery  = (array) get_post_meta( $post_id, '_kfp_gallery_ids', true );
	if ( $gallery ) {
		$url = wp_get_attachment_image_url( $gallery[0], $size );
		if ( $url ) {
			return $url;
		}
	}
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail_url( $post_id, $size );
	}
	return '';
}

/** Render a video embed (YouTube/Vimeo/direct file). */
function kfp_video_embed( $url ) {
	if ( ! $url ) {
		return '';
	}
	$iframe_attr = 'title="' . esc_attr__( 'Property video', 'kfp-properties' ) . '" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture" allowfullscreen';

	if ( preg_match( '~(?:youtube\.com/watch\?v=|youtu\.be/)([\w-]{6,})~', $url, $m ) ) {
		return '<div class="kfp-video-wrap"><iframe src="https://www.youtube-nocookie.com/embed/' . esc_attr( $m[1] ) . '" ' . $iframe_attr . '></iframe></div>';
	}
	if ( preg_match( '~vimeo\.com/(\d+)~', $url, $m ) ) {
		return '<div class="kfp-video-wrap"><iframe src="https://player.vimeo.com/video/' . esc_attr( $m[1] ) . '" ' . $iframe_attr . '></iframe></div>';
	}
	// Direct file (mp4/webm).
	$ext = strtolower( pathinfo( wp_parse_url( $url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
	if ( in_array( $ext, array( 'mp4', 'webm', 'ogg' ), true ) ) {
		return '<div class="kfp-video-wrap"><video src="' . esc_url( $url ) . '" controls preload="metadata"></video></div>';
	}
	return '';
}

/** Business hours rows from Customizer text. */
function kfp_hours_rows() {
	$rows  = array();
	$lines = preg_split( '/\r\n|\r|\n/', (string) kfp_opt( 'kfp_hours' ) );
	foreach ( $lines as $line ) {
		if ( strpos( $line, '|' ) !== false ) {
			list( $day, $time ) = array_map( 'trim', explode( '|', $line, 2 ) );
			$rows[] = array( $day, $time );
		}
	}
	return $rows;
}

/** Echo an attachment image from a media Customizer setting (ID or URL). */
function kfp_media_url( $mod, $size = 'full' ) {
	$value = kfp_opt( $mod, '' );
	if ( ! $value ) {
		return '';
	}
	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, $size );
		return $url ? $url : '';
	}
	return $value; // already a URL
}

/** Social links as name => url pairs. */
function kfp_social_links() {
	$map = array(
		'facebook'  => __( 'Facebook', 'kfp-properties' ),
		'instagram' => __( 'Instagram', 'kfp-properties' ),
		'twitter'   => __( 'X (Twitter)', 'kfp-properties' ),
		'linkedin'  => __( 'LinkedIn', 'kfp-properties' ),
	);
	$out = array();
	foreach ( $map as $key => $label ) {
		$url = kfp_opt( 'kfp_social_' . $key, '' );
		if ( $url ) {
			$out[ $key ] = array( $label, $url );
		}
	}
	return $out;
}
