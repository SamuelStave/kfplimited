<?php
/**
 * Properties custom post type, taxonomies and admin meta boxes
 * (price, location, size, features, agent, gallery images & videos).
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

/** CPT + taxonomies registration. */
function kfp_register_cpt() {
	register_post_type( 'property', array(
		'labels' => array(
			'name'               => __( 'Properties', 'kfp-properties' ),
			'singular_name'      => __( 'Property', 'kfp-properties' ),
			'add_new'            => __( 'Add New Property', 'kfp-properties' ),
			'add_new_item'       => __( 'Add New Property', 'kfp-properties' ),
			'edit_item'          => __( 'Edit Property', 'kfp-properties' ),
			'new_item'           => __( 'New Property', 'kfp-properties' ),
			'view_item'          => __( 'View Property', 'kfp-properties' ),
			'search_items'       => __( 'Search Properties', 'kfp-properties' ),
			'not_found'          => __( 'No properties found', 'kfp-properties' ),
			'not_found_in_trash' => __( 'No properties found in Trash', 'kfp-properties' ),
			'menu_name'          => __( 'Properties', 'kfp-properties' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'menu_icon'     => 'dashicons-admin-home',
		'menu_position' => 6,
		'rewrite'       => array( 'slug' => 'properties', 'flush' => true ),
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest'  => true, // Gutenberg + Elementor support.
	) );

	register_taxonomy( 'property_location', 'property', array(
		'labels'       => array(
			'name'          => __( 'Locations', 'kfp-properties' ),
			'singular_name' => __( 'Location', 'kfp-properties' ),
			'add_new_item'  => __( 'Add New Location', 'kfp-properties' ),
		),
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'property-location' ),
		'show_in_rest' => true,
	) );

	register_taxonomy( 'property_type', 'property', array(
		'labels'       => array(
			'name'          => __( 'Property Types', 'kfp-properties' ),
			'singular_name' => __( 'Property Type', 'kfp-properties' ),
			'add_new_item'  => __( 'Add New Type (e.g. Dry Land, Corner Piece)', 'kfp-properties' ),
		),
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'property-type' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'kfp_register_cpt' );

/* ==================================================================
 * Meta box: property details, gallery & videos
 * ================================================================== */

function kfp_meta_fields() {
	return array(
		'_kfp_price'        => array( 'label' => __( 'Price (e.g. ₦25,000,000)', 'kfp-properties' ), 'type' => 'text' ),
		'_kfp_price_sqm'    => array( 'label' => __( 'Price per sqm (e.g. ₦50,000/sqm)', 'kfp-properties' ), 'type' => 'text' ),
		'_kfp_size'         => array( 'label' => __( 'Size (e.g. 500 sqm)', 'kfp-properties' ), 'type' => 'text' ),
		'_kfp_title_suffix' => array( 'label' => __( 'Badge / Status (e.g. Available, Occupied)', 'kfp-properties' ), 'type' => 'text' ),
		'_kfp_agent_name'   => array( 'label' => __( 'Agent Name', 'kfp-properties' ), 'type' => 'text' ),
		'_kfp_agent_phone'  => array( 'label' => __( 'Agent Phone (WhatsApp)', 'kfp-properties' ), 'type' => 'text' ),
		'_kfp_features'     => array( 'label' => __( 'Features (comma separated: Dry Land, C of O Available, Secured Location)', 'kfp-properties' ), 'type' => 'textarea' ),
	);
}

function kfp_add_meta_boxes() {
	add_meta_box( 'kfp_property_details', __( 'Property Details', 'kfp-properties' ), 'kfp_render_details_mb', 'property', 'normal', 'high' );
	add_meta_box( 'kfp_property_media', __( 'Extra Pictures & Videos for this Land', 'kfp-properties' ), 'kfp_render_media_mb', 'property', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'kfp_add_meta_boxes' );

function kfp_render_details_mb( $post ) {
	wp_nonce_field( 'kfp_save_meta', 'kfp_meta_nonce' );
	echo '<table class="form-table"><tbody>';
	foreach ( kfp_meta_fields() as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
		if ( 'textarea' === $field['type'] ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="3" class="large-text">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text" />';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

function kfp_render_media_mb( $post ) {
	$gallery = get_post_meta( $post->ID, '_kfp_gallery_ids', true );
	$gallery = is_array( $gallery ) ? $gallery : array();
	$videos  = get_post_meta( $post->ID, '_kfp_videos', true );
	$videos  = is_array( $videos ) ? $videos : array();

	echo '<p><strong>' . esc_html__( 'Gallery Pictures', 'kfp-properties' ) . '</strong><br />';
	echo '<span class="description">' . esc_html__( 'Additional photos of the land/property shown on the single page and lightbox.', 'kfp-properties' ) . '</span></p>';
	echo '<div id="kfp-gallery-wrap">';
	foreach ( $gallery as $id ) {
		$img = wp_get_attachment_image( $id, 'thumbnail' );
		if ( $img ) {
			echo '<span class="kfp-gallery-item" style="display:inline-block;position:relative;margin:0 8px 8px 0;">'
				. $img
				. '<input type="hidden" name="_kfp_gallery_ids[]" value="' . esc_attr( $id ) . '" />'
				. '<button type="button" class="kfp-remove-img dashicons dashicons-dismiss" style="position:absolute;top:-6px;right:-6px;background:#d63638;color:#fff;border-radius:50%;border:0;width:22px;height:22px;cursor:pointer;"></button>'
				. '</span>';
		}
	}
	echo '</div>';
	echo '<p><button type="button" class="button" id="kfp-add-gallery">' . esc_html__( 'Add / Select Pictures', 'kfp-properties' ) . '</button></p>';

	echo '<hr /><p><strong>' . esc_html__( 'Videos', 'kfp-properties' ) . '</strong><br />'
		. '<span class="description">' . esc_html__( 'Paste YouTube/Vimeo URLs or upload MP4 files from the Media Library.', 'kfp-properties' ) . '</span></p>';
	echo '<div id="kfp-video-rows">';
	foreach ( $videos as $url ) {
		echo '<p class="kfp-video-row" style="margin:0 0 6px;"><input type="text" name="_kfp_videos[]" value="' . esc_attr( $url ) . '" class="large-text code" /> '
			. '<button type="button" class="button-link-delete kfp-remove-video">' . esc_html__( 'Remove', 'kfp-properties' ) . '</button></p>';
	}
	echo '</div>';
	echo '<p><button type="button" class="button" id="kfp-add-video-url">' . esc_html__( 'Add Video URL', 'kfp-properties' ) . '</button> '
		. '<button type="button" class="button" id="kfp-add-video-media">' . esc_html__( 'Choose Video from Media Library', 'kfp-properties' ) . '</button></p>';
}

function kfp_save_meta( $post_id ) {
	if ( ! isset( $_POST['kfp_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['kfp_meta_nonce'] ), 'kfp_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( kfp_meta_fields() as $key => $field ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = 'textarea' === $field['type']
				? sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) )
				: sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			update_post_meta( $post_id, $key, $value );
		}
	}

	if ( isset( $_POST['_kfp_gallery_ids'] ) ) {
		$ids = array_filter( array_map( 'absint', (array) wp_unslash( $_POST['_kfp_gallery_ids'] ) ) );
		update_post_meta( $post_id, '_kfp_gallery_ids', array_values( $ids ) );
	}

	if ( isset( $_POST['_kfp_videos'] ) ) {
		$videos = array_filter( array_map( 'esc_url_raw', (array) wp_unslash( $_POST['_kfp_videos'] ) ) );
		update_post_meta( $post_id, '_kfp_videos', array_values( $videos ) );
	}
}
add_action( 'save_post_property', 'kfp_save_meta' );

/* ==================================================================
 * Admin JS for the media picker (WP media modal)
 * ================================================================== */
function kfp_admin_assets( $hook ) {
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'property' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'kfp-admin', KFP_URI . '/assets/js/admin.js', array( 'jquery' ), KFP_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'kfp_admin_assets' );

/* ==================================================================
 * Columns on the Properties list table
 * ================================================================== */
function kfp_columns( $columns ) {
	$columns['kfp_price']    = __( 'Price', 'kfp-properties' );
	$columns['kfp_location'] = __( 'Location', 'kfp-properties' );
	$columns['kfp_size']     = __( 'Size', 'kfp-properties' );
	return $columns;
}
add_filter( 'manage_property_posts_columns', 'kfp_columns' );

function kfp_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'kfp_price':
			echo esc_html( get_post_meta( $post_id, '_kfp_price', true ) );
			break;
		case 'kfp_location':
			$terms = get_the_terms( $post_id, 'property_location' );
			echo $terms ? esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '—';
			break;
		case 'kfp_size':
			echo esc_html( get_post_meta( $post_id, '_kfp_size', true ) );
			break;
	}
}
add_action( 'manage_property_posts_custom_column', 'kfp_column_content', 10, 2 );
