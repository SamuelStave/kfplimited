/* King's Farms Properties – front-end behaviour (vanilla JS, no dependencies). */
(function () {
	'use strict';

	/* ---------- Sticky header shadow ---------- */
	var header = document.getElementById('kfp-header');
	function onScroll() {
		if (header) header.classList.toggle('is-scrolled', window.scrollY > 40);
	}
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();

	/* ---------- Mobile menu ---------- */
	var toggle = document.querySelector('.kfp-nav-toggle');
	var nav = document.querySelector('.kfp-nav');
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		nav.addEventListener('click', function (e) {
			if (e.target.tagName === 'A') {
				nav.classList.remove('is-open');
				toggle.setAttribute('aria-expanded', 'false');
			}
		});
	}

	/* ---------- Reveal on scroll ---------- */
	var reveals = document.querySelectorAll('.kfp-reveal');
	if ('IntersectionObserver' in window && reveals.length) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					io.unobserve(entry.target);
				}
			});
		}, { threshold: 0.12 });
		reveals.forEach(function (el) { io.observe(el); });
	} else {
		reveals.forEach(function (el) { el.classList.add('is-visible'); });
	}

	/* ---------- Contact form via AJAX ---------- */
	document.querySelectorAll('#kfp-contact-form').forEach(function (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();

			var msgBox = form.querySelector('.kfp-form__msg');
			var btn = form.querySelector('button[type="submit"]');
			var data = new FormData(form);
			data.append('action', 'kfp_submit_lead');
			if (window.kfpData) {
				data.append('nonce', window.kfpData.nonce);
			}

			// Client-side validation basics.
			var name = (form.querySelector('[name="name"]') || {}).value || '';
			var email = (form.querySelector('[name="email"]') || {}).value || '';
			var message = (form.querySelector('[name="message"]') || {}).value || '';
			if (!name.trim() || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email) || !message.trim()) {
				msgBox.textContent = 'Please fill in your name, a valid email and a message.';
				msgBox.className = 'kfp-form__msg is-error';
				return;
			}

			btn.disabled = true;
			var original = btn.textContent;
			btn.textContent = (window.kfpData && window.kfpData.i18nSending) || 'Sending…';

			fetch((window.kfpData && window.kfpData.ajaxUrl) || '/wp-admin/admin-ajax.php', {
				method: 'POST',
				body: data,
				credentials: 'same-origin'
			})
				.then(function (r) { return r.json(); })
				.then(function (json) {
					msgBox.textContent = (json.data && json.data.message) || 'Done!';
					msgBox.className = 'kfp-form__msg ' + (json.success ? 'is-success' : 'is-error');
					if (json.success) form.reset();
					msgBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
				})
				.catch(function () {
					msgBox.textContent = (window.kfpData && window.kfpData.i18nError) || 'Something went wrong.';
					msgBox.className = 'kfp-form__msg is-error';
				})
				.finally(function () {
					btn.disabled = false;
					btn.textContent = original;
				});
		});
	});

	/* ---------- Gallery lightbox ---------- */
	var lightbox = document.getElementById('kfp-lightbox');
	if (lightbox) {
		var lbImg = lightbox.querySelector('img');
		var closeBtn = lightbox.querySelector('.kfp-lightbox__close');

		document.addEventListener('click', function (e) {
			var img = e.target.closest('.kfp-gallery img');
			if (img) {
				lbImg.src = img.getAttribute('data-full') || img.src;
				lbImg.alt = img.alt || '';
				lightbox.classList.add('is-open');
				document.body.style.overflow = 'hidden';
			}
		});

		function closeLb() {
			lightbox.classList.remove('is-open');
			document.body.style.overflow = '';
		}
		closeBtn.addEventListener('click', closeLb);
		lightbox.addEventListener('click', function (e) { if (e.target === lightbox) closeLb(); });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeLb(); });
	}

	/* ---------- Smooth scroll for in-page anchors ---------- */
	document.querySelectorAll('a[href^="#"]').forEach(function (a) {
		a.addEventListener('click', function (e) {
			var id = a.getAttribute('href');
			if (id.length > 1) {
				var target = document.querySelector(id);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({ behavior: 'smooth' });
				}
			}
		});
	});
})();
