/* Property editor: media modal for gallery images & videos. */
/* global jQuery, wp, kfpAdminData */
jQuery(function ($) {
	'use strict';

	if (typeof wp === 'undefined' || !wp.media) return;

	/* ---------- Gallery picker ---------- */
	$('#kfp-add-gallery').on('click', function (e) {
		e.preventDefault();
		var frame = wp.media({
			title: 'Select Land Pictures',
			button: { text: 'Add to Gallery' },
			library: { type: 'image' },
			multiple: true
		});
		frame.on('select', function () {
			frame.state().get('selection').each(function (att) {
				var a = att.toJSON();
				var thumb = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
				var $item = $(
					'<span class="kfp-gallery-item" style="display:inline-block;position:relative;margin:0 8px 8px 0;">' +
					'<img src="' + thumb + '" width="150" height="150" style="object-fit:cover;border-radius:6px;" />' +
					'<input type="hidden" name="_kfp_gallery_ids[]" value="' + a.id + '" />' +
					'<button type="button" class="kfp-remove-img dashicons dashicons-dismiss" style="position:absolute;top:-6px;right:-6px;background:#d63638;color:#fff;border-radius:50%;border:0;width:22px;height:22px;cursor:pointer;"></button>' +
					'</span>'
				);
				$('#kfp-gallery-wrap').append($item);
			});
		});
		frame.open();
	});

	$(document).on('click', '.kfp-remove-img', function () {
		$(this).closest('.kfp-gallery-item').remove();
	});

	/* ---------- Video URL row ---------- */
	$('#kfp-add-video-url').on('click', function (e) {
		e.preventDefault();
		$('#kfp-video-rows').append(
			'<p class="kfp-video-row" style="margin:0 0 6px;"><input type="text" name="_kfp_videos[]" class="large-text code" placeholder="https://youtube.com/watch?v=… or https://vimeo.com/… or .mp4 URL" /> ' +
			'<button type="button" class="button-link-delete kfp-remove-video">Remove</button></p>'
		);
	});

	$(document).on('click', '.kfp-remove-video', function () {
		$(this).closest('.kfp-video-row').remove();
	});

	/* ---------- Video from Media Library ---------- */
	$('#kfp-add-video-media').on('click', function (e) {
		e.preventDefault();
		var frame = wp.media({
			title: 'Choose Videos',
			button: { text: 'Use selected videos' },
			library: { type: 'video' },
			multiple: true
		});
		frame.on('select', function () {
			frame.state().get('selection').each(function (att) {
				var a = att.toJSON();
				$('#kfp-video-rows').append(
					'<p class="kfp-video-row" style="margin:0 0 6px;"><input type="text" name="_kfp_videos[]" value="' + a.url + '" class="large-text code" /> ' +
					'<button type="button" class="button-link-delete kfp-remove-video">Remove</button></p>'
				);
			});
		});
		frame.open();
	});
});
