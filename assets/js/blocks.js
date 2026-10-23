/* Gutenberg block registrations for KFP sections (server-rendered). */
/* global wp */
(function (blocks, element, components) {
	'use strict';

	var el = element.createElement;
	var registerBlockType = blocks.registerBlockType;
	var ServerSideRender = components.ServerSideRender || window.wp.blockEditor && window.wp.serverSideRender;

	var defs = {
		'kfp/properties': 'KFP: Properties Grid',
		'kfp/contact-form': 'KFP: Contact Form',
		'kfp/gallery': 'KFP: Gallery Marquee',
		'kfp/cta-banner': 'KFP: CTA Banner',
		'kfp/hero': 'KFP: Hero',
		'kfp/testimonials': 'KFP: Testimonials'
	};

	Object.keys(defs).forEach(function (name) {
		registerBlockType(name, {
			title: defs[name],
			category: 'widgets',
			icon: 'admin-home',
			supports: { html: false },
			edit: function () {
				return el('div', { className: 'kfp-block-placeholder' },
					el('p', null, defs[name] + ' — renders the live section. Edit its content in Appearance → Customize → Homepage Sections.')
				);
			},
			save: function () { return null; } // server rendered
		});
	});
})(window.wp.blocks, window.wp.element, window.wp.components);
