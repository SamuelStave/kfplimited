# King's Farms Properties — WordPress Theme

A polished, image-rich real estate **WordPress theme** (plain PHP, no build step) for **King's Farms And Properties Ltd.** It recreates and upgrades the original React one-pager into a fully manageable site.

## Requirements
- WordPress 6.0+ · PHP 7.4+
- Recommended plugins: **Elementor** (free is enough) — the theme auto-registers its own Elementor widgets when installed. SMTP plugin (e.g. *WP Mail SMTP*) so `wp_mail()` actually delivers.

## Install
1. Copy this folder to `wp-content/themes/kfp-properties/` (or zip it and upload via Appearance → Themes → Add New).
2. Activate the theme. Permalinks are flushed automatically; listings live at `/properties/`.

## What you get

### 🏠 Properties in the dashboard (item 3)
- Admin menu **Properties → Add New Property**: title, description, featured image, plus a **Property Details** box (price, price/sqm, size, badge/status, agent name & WhatsApp, features) and an **Extra Pictures & Videos** box with a media picker (multiple land photos) and video rows (YouTube / Vimeo URLs or MP4s from the Media Library).
- **Locations** and **Property Types** taxonomies (e.g. Epe, Sangi; Dry Land, Corner Piece) power the archive filter chips and "similar lands nearby".
- Archive page `/properties/`, single pages with picture grid + lightbox + embedded videos + per-property enquiry form that tags the lead with that property.

### ✉️ Contacts & automation (item 4)
- The contact form is **custom plain-PHP AJAX** (`inc/leads.php`) — no plugin needed. Every submission:
  1. Saves to a custom table `wp_kfp_leads`.
  2. Emails an **admin alert** (to the address(es) set in Customizer → Contact Details).
  3. Sends an **auto-reply** to the client.
  4. Bumps an **unread badge** on the admin **Leads** menu.
- Dashboard → **Leads**: inbox with mark-read/delete and **CSV export**. Includes honeypot + nonce + IP rate-limiting.
- Footer newsletter form stores subscribers and emails you each new one (hook `kfp_newsletter_subscribed` to forward to Mailchimp etc.).

### 🖼 Logo & images everywhere (items 1 & 5)
- Upload your logo at **Appearance → Customize → Site Identity** (custom-logo support, sized for the dark header).
- **Customize → Site Images**: hero background, about image, CTA banner, and 8 gallery-marquee slots. Leave them empty and the theme automatically pulls featured images from your properties, so the site never looks bare.
- Design keeps the original navy/blue identity with gold accents, Poppins/Inter fonts, scroll animations, hover effects and a pulsing WhatsApp button.

### 🧩 Editable sections (item 2)
Three ways to edit every section — pick whichever you like:
1. **Customizer** → panel *"King's Farms Theme Settings"*: toggle Hero / Stats / Properties / About / Gallery / Testimonials / CTA / Contact ON-OFF and edit all headings, texts, buttons, stats and checklist lines inline.
2. **Gutenberg**: blocks *KFP: Hero, Properties Grid, Gallery Marquee, Testimonials, CTA Banner, Contact Form*.
3. **Elementor**: category **"King's Farms"** with widgets *Properties Grid, Contact Form, Gallery Marquee, CTA Banner* — plus shortcodes `[kfp_properties] [kfp_contact_form] [kfp_gallery] [kfp_cta_banner] [kfp_hero] [kfp_testimonials]` usable anywhere. If the front page is built with Elementor, the theme defers to the builder automatically.

### Menu naming (item 3)
The nav fallback already labels the listing link **"Properties"**. To control it precisely: Appearance → Menus → create "Primary", add the **Properties archive**, assign to *Primary Menu*.

## First-run checklist
1. Customize → Site Identity → upload **logo**.
2. Customize → Contact Details → phone, WhatsApp, email(s), address, hours.
3. Properties → Add New Property ×6 (featured image + gallery + videos + details).
4. Appearance → Menus → build Primary (Home, About, Properties, Testimonials, Contact).
5. Settings → Reading → homepage = static page "Home" (blank page is fine — the theme renders the section stack).
6. Install an SMTP plugin so lead notifications reliably reach your inbox.

## File map
```
style.css                     theme header + full design system
functions.php                 setup, assets, menus, helpers bootstrap
front-page.php                section stack (builder-aware)
header.php / footer.php       chrome, socials, WhatsApp FAB, lightbox
index.php / archive-property.php / single-property.php
template-parts/               hero, about, testimonials, contact, cards…
inc/custom-post-types.php     CPT, taxonomies, meta boxes, media picker
inc/customizer.php            branding/images/sections/contact settings
inc/leads.php                 AJAX leads, DB table, emails, inbox, CSV
inc/blocks.php                shortcodes, Gutenberg blocks, Elementor widgets
inc/helpers.php               template helpers
assets/js/main.js             nav, reveal, AJAX form, lightbox (vanilla)
assets/js/admin.js            property media gallery/video UI
assets/js/blocks.js           Gutenberg block stubs
```
