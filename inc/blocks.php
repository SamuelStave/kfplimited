<?php
/**
 * Shortcodes + Gutenberg blocks + Elementor widgets so every homepage
 * section can also be edited with page builders.
 *
 * @package kfp-properties
 */

defined( 'ABSPATH' ) || exit;

/* ==================================================================
 * 1. Section renderers (shared by templates, shortcodes & blocks)
 * ================================================================== */

/** Featured properties grid. */
function kfp_render_properties( $args = array() ) {
	$args = wp_parse_args( $args, array(
		'count'    => (int) kfp_opt( 'kfp_prop_count', 6 ),
		'eyebrow'  => kfp_opt( 'kfp_prop_eyebrow', __( 'Land opportunities', 'kfp-properties' ) ),
		'title'    => kfp_opt( 'kfp_prop_title', __( 'Invest in prime lands today!', 'kfp-properties' ) ),
		'style'    => 'navy', // navy | light
		'columns'  => 3,
		'location' => '',
	) );

	$query = new WP_Query( array(
		'post_type'      => 'property',
		'posts_per_page' => $args['count'],
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => $args['location'] ? array( array(
			'taxonomy' => 'property_location',
			'field'    => 'slug',
			'terms'    => $args['location'],
		) ) : array(),
	) );

	ob_start(); ?>
	<section class="kfp-section <?php echo 'light' === $args['style'] ? 'kfp-section--soft' : 'kfp-section--navy'; ?>" id="properties">
		<div class="kfp-container">
			<div class="kfp-section__head">
				<?php if ( $args['eyebrow'] ) : ?><span class="kfp-badge"><?php echo esc_html( $args['eyebrow'] ); ?></span><?php endif; ?>
				<h2><?php echo esc_html( $args['title'] ); ?></h2>
			</div>
			<?php if ( $query->have_posts() ) : ?>
				<div class="kfp-grid kfp-grid--<?php echo absint( $args['columns'] ); ?>">
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();
						get_template_part( 'template-parts/content', 'property-card' );
					}
					wp_reset_postdata();
					?>
				</div>
				<p style="text-align:center;margin-top:44px;">
					<a class="kfp-btn <?php echo 'light' === $args['style'] ? 'kfp-btn--primary' : 'kfp-btn--ghost'; ?>" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>">
						<?php esc_html_e( 'See All Properties', 'kfp-properties' ); ?>
					</a>
				</p>
			<?php else : ?>
				<p style="text-align:center;"><?php esc_html_e( 'No properties yet. Add your first listing under Properties → Add New Property.', 'kfp-properties' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** Contact section (form + info card). */
function kfp_render_contact( $property_id = 0 ) {
	ob_start();
	get_template_part( 'template-parts/content', 'contact', array(
		'property_id' => $property_id,
	) );
	return ob_get_clean();
}

/** Gallery marquee from Customizer images + property thumbnails. */
function kfp_render_gallery() {
	$ids = array();
	for ( $i = 1; $i <= 8; $i++ ) {
		$id = (int) kfp_opt( 'kfp_gallery_img_' . $i, 0 );
		if ( $id ) {
			$ids[] = $id;
		}
	}
	if ( count( $ids ) < 4 ) {
		// Top up with property featured images.
		$props = get_posts( array( 'post_type' => 'property', 'posts_per_page' => 8, 'meta_key' => '_thumbnail_id' ) );
		foreach ( $props as $p ) {
			$ids[] = (int) get_post_thumbnail_id( $p->ID );
		}
	}
	if ( ! $ids ) {
		return '';
	}
	$ids   = array_slice( array_unique( $ids ), 0, 12 );
	$doubled = array_merge( $ids, $ids ); // seamless loop

	ob_start(); ?>
	<section class="kfp-section kfp-section--navy" id="gallery" style="padding-top:56px;padding-bottom:56px;">
		<div class="kfp-container">
			<div class="kfp-section__head" style="margin-bottom:28px;">
				<span class="kfp-badge"><?php esc_html_e( 'Our estates in pictures', 'kfp-properties' ); ?></span>
				<h2><?php esc_html_e( 'A picture is worth a thousand plots', 'kfp-properties' ); ?></h2>
			</div>
		</div>
		<div class="kfp-marquee"><div class="kfp-marquee__track">
			<?php foreach ( $doubled as $id ) :
				$url = wp_get_attachment_image_url( $id, 'medium_large' );
				$alt = trim( wp_strip_all_tags( get_post( $id )->post_excerpt ?: get_post( $id )->post_title ) );
				if ( ! $url ) continue; ?>
				<img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" />
			<?php endforeach; ?>
		</div></div>
	</section>
	<?php
	return ob_get_clean();
}

/** CTA banner. */
function kfp_render_cta() {
	$title = kfp_opt( 'kfp_cta_title', __( 'Ready to own land in Lagos?', 'kfp-properties' ) );
	$text  = kfp_opt( 'kfp_cta_text', __( 'Book a site inspection today — weekend tours available.', 'kfp-properties' ) );
	$btn   = kfp_opt( 'kfp_cta_btn', __( 'Book Inspection', 'kfp-properties' ) );
	$wa    = preg_replace( '/\D/', '', kfp_opt( 'kfp_whatsapp', '2348037319301' ) );

	return '<section class="kfp-section kfp-section--soft"><div class="kfp-container"><div class="kfp-cta">'
		. '<div><h3>' . esc_html( $title ) . '</h3><p>' . esc_html( $text ) . '</p></div>'
		. '<a class="kfp-btn kfp-btn--primary" href="https://wa.me/' . esc_attr( $wa ) . '?text=' . rawurlencode( __( 'Hello, I would like to book a site inspection.', 'kfp-properties' ) ) . '" target="_blank" rel="noopener">'
		. esc_html( $btn ) . '</a></div></div></section>';
}

/* ==================================================================
 * 2. Shortcodes (usable anywhere incl. Elementor "Shortcode" widget)
 * ================================================================== */
add_shortcode( 'kfp_properties', function ( $atts ) {
	return kfp_render_properties( shortcode_atts( array(
		'count'    => 6,
		'eyebrow'  => '',
		'title'    => '',
		'style'    => 'navy',
		'columns'  => 3,
		'location' => '',
	), $atts, 'kfp_properties' ) );
} );

add_shortcode( 'kfp_contact_form', function ( $atts ) {
	$atts = shortcode_atts( array( 'property_id' => 0 ), $atts, 'kfp_contact_form' );
	return kfp_render_contact( absint( $atts['property_id'] ) );
} );

add_shortcode( 'kfp_gallery', 'kfp_shortcode_gallery' );
function kfp_shortcode_gallery( $atts ) { return kfp_render_gallery(); }

add_shortcode( 'kfp_cta_banner', function ( $atts ) { return kfp_render_cta(); } );

add_shortcode( 'kfp_hero', function () {
	ob_start();
	get_template_part( 'template-parts/content', 'hero' );
	return ob_get_clean();
} );

add_shortcode( 'kfp_testimonials', function () {
	ob_start();
	get_template_part( 'template-parts/content', 'testimonials' );
	return ob_get_clean();
} );

/* ==================================================================
 * 3. Gutenberg blocks
 * ================================================================== */
add_action( 'init', function () {
	$sections = array(
		'properties'     => array( __( 'KFP: Properties Grid', 'kfp-properties' ), 'dashicons-admin-home' ),
		'contact-form'   => array( __( 'KFP: Contact Form', 'kfp-properties' ), 'dashicons-email' ),
		'gallery'        => array( __( 'KFP: Gallery Marquee', 'kfp-properties' ), 'dashicons-images-alt2' ),
		'cta-banner'     => array( __( 'KFP: CTA Banner', 'kfp-properties' ), 'dashicons-megaphone' ),
		'hero'           => array( __( 'KFP: Hero', 'kfp-properties' ), 'dashicons-format-image' ),
		'testimonials'   => array( __( 'KFP: Testimonials', 'kfp-properties' ), 'dashicons-star-half' ),
	);
	foreach ( $sections as $name => $meta ) {
		register_block_type( 'kfp/' . $name, array(
			'api_version'     => 2,
			'editor_script'   => 'kfp-blocks',
			'render_callback' => function () use ( $name ) {
				switch ( $name ) {
					case 'properties':   return kfp_render_properties();
					case 'contact-form': return kfp_render_contact();
					case 'gallery':      return kfp_render_gallery();
					case 'cta-banner':   return kfp_render_cta();
					case 'hero':
					case 'testimonials':
						ob_start();
						get_template_part( 'template-parts/content', str_replace( '-', '_', $name ) );
						return ob_get_clean();
				}
				return '';
			},
			'attributes'      => array(),
		) );
	}

	wp_register_script(
		'kfp-blocks',
		KFP_URI . '/assets/js/blocks.js',
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n' ),
		KFP_VERSION,
		true
	);
} );

/* ==================================================================
 * 4. Elementor widgets (registered only when Elementor is active)
 * ================================================================== */
add_action( 'elementor/elements/categories_registered', function ( $manager ) {
	$manager->add_category( 'kfp', array(
		'title' => __( "King's Farms", 'kfp-properties' ),
		'icon'  => 'fa fa-home',
	) );
} );

add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
	$map = array(
		'kfp-properties-grid' => array( 'kfp_render_properties', array() ),
		'kfp-contact-form'    => array( 'kfp_render_contact', array() ),
		'kfp-gallery-marquee' => array( 'kfp_render_gallery', array() ),
		'kfp-cta-banner'      => array( 'kfp_render_cta', array() ),
	);
	foreach ( $map as $widget_name => $cb ) {
		$widgets_manager->register( new KFP_Generic_Elementor_Widget( $widget_name, $cb[0] ) );
	}
} );

/**
 * Lightweight Elementor widget wrapper that renders one of our PHP sections.
 */
class KFP_Generic_Elementor_Widget extends \Elementor\Widget_Base {
	private $callback;
	public function __construct( $name, $callback, $args = array() ) {
		$this->callback = $callback;
		parent::__construct( $args );
		$this->set_data( array( 'base' => $name ) );
	}
	public function get_name() { return $this->get_base(); }
	public function get_title() { return ucwords( str_replace( array( 'kfp-', '-' ), array( '', ' ' ), $this->get_name() ) ); }
	public function get_icon() { return 'eicon-posts-grid'; }
	public function get_categories() { return array( 'kfp' ); }
	protected function render() {
		echo call_user_func( $this->callback ); // phpcs:ignore
	}
}
