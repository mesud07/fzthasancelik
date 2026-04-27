<?php
/**
 * Plugin Name: Modern Header and Footer Polish
 * Description: Adds refined header and footer styling without editing the active theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fzthasancelik_modern_header_footer_styles() {
	if ( is_admin() ) {
		return;
	}
	?>
	<style id="fzthasancelik-modern-header-footer">
		:root {
			--fz-header-bg: rgba(255, 255, 255, .92);
			--fz-ink: #173238;
			--fz-muted: #647276;
			--fz-line: rgba(23, 50, 56, .12);
			--fz-accent: #1f8f76;
			--fz-accent-soft: rgba(31, 143, 118, .1);
			--fz-footer-bg: #10272d;
			--fz-footer-panel: rgba(255, 255, 255, .055);
			--fz-footer-text: rgba(255, 255, 255, .78);
		}

		#header.cmsmasters_header,
		.elementor-location-header,
		[data-elementor-type="header"],
		header.elementor-section,
		header.elementor-location-header {
			position: sticky !important;
			top: 0 !important;
			z-index: 9000 !important;
			background: var(--fz-header-bg) !important;
			border-bottom: 1px solid var(--fz-line) !important;
			box-shadow: 0 14px 38px rgba(18, 42, 48, .12) !important;
			backdrop-filter: blur(18px);
			-webkit-backdrop-filter: blur(18px);
		}

		body.admin-bar #header.cmsmasters_header,
		body.admin-bar .elementor-location-header,
		body.admin-bar [data-elementor-type="header"] {
			top: 32px !important;
		}

		#header.cmsmasters_header .cmsmasters-header-top,
		.elementor-location-header .cmsmasters-header-top,
		[data-elementor-type="header"] .cmsmasters-header-top {
			background: #10272d !important;
			color: rgba(255, 255, 255, .78) !important;
			font-size: 13px !important;
			letter-spacing: 0 !important;
		}

		#header.cmsmasters_header .cmsmasters-header-top a,
		.elementor-location-header .cmsmasters-header-top a {
			color: rgba(255, 255, 255, .84);
		}

		#header.cmsmasters_header .cmsmasters-header-top a:hover,
		.elementor-location-header .cmsmasters-header-top a:hover {
			color: #fff;
		}

		#header.cmsmasters_header .cmsmasters-header-mid,
		#header.cmsmasters_header .cmsmasters-header-bot,
		.elementor-location-header .cmsmasters-header-mid,
		.elementor-location-header .cmsmasters-header-bot,
		[data-elementor-type="header"] .cmsmasters-header-mid,
		[data-elementor-type="header"] .cmsmasters-header-bot,
		[data-elementor-type="header"] .elementor-section,
		[data-elementor-type="header"] .elementor-container,
		[data-elementor-type="header"] .e-con {
			background: transparent !important;
		}

		#header.cmsmasters_header .cmsmasters-header-mid__outer,
		#header.cmsmasters_header .cmsmasters-header-bot__outer,
		.elementor-location-header .cmsmasters-header-mid__outer,
		.elementor-location-header .cmsmasters-header-bot__outer {
			padding-left: clamp(16px, 4vw, 48px) !important;
			padding-right: clamp(16px, 4vw, 48px) !important;
		}

		#header.cmsmasters_header .cmsmasters-header-mid__inner,
		.elementor-location-header .cmsmasters-header-mid__inner {
			min-height: 82px !important;
			gap: 28px !important;
		}

		#header.cmsmasters_header .cmsmasters-header-bot__inner,
		.elementor-location-header .cmsmasters-header-bot__inner {
			min-height: 54px !important;
			border-top: 1px solid rgba(23, 50, 56, .08) !important;
		}

		#header.cmsmasters_header .cmsmasters-header-mid-logo img,
		#header.cmsmasters_header .cmsmasters-header-mid-logo svg,
		.elementor-location-header .cmsmasters-header-mid-logo img,
		.elementor-location-header .cmsmasters-header-mid-logo svg,
		.elementor-location-header .elementor-widget-theme-site-logo img {
			max-height: 58px;
			width: auto;
		}

		#header.cmsmasters_header nav ul,
		.elementor-location-header nav ul,
		[data-elementor-type="header"] nav ul {
			gap: 6px !important;
		}

		#header.cmsmasters_header nav a,
		.elementor-location-header nav a,
		.elementor-location-header .elementor-nav-menu a,
		[data-elementor-type="header"] nav a,
		[data-elementor-type="header"] .elementor-nav-menu a {
			border-radius: 999px !important;
			color: var(--fz-ink) !important;
			font-weight: 500 !important;
			letter-spacing: 0 !important;
			padding: 10px 15px !important;
			transition: color .2s ease, background-color .2s ease, box-shadow .2s ease, transform .2s ease !important;
		}

		#header.cmsmasters_header nav a:hover,
		#header.cmsmasters_header nav .current-menu-item > a,
		#header.cmsmasters_header nav .current-menu-ancestor > a,
		.elementor-location-header nav a:hover,
		.elementor-location-header .elementor-nav-menu a:hover,
		.elementor-location-header .elementor-nav-menu .current-menu-item > a {
		[data-elementor-type="header"] nav a:hover,
		[data-elementor-type="header"] .elementor-nav-menu a:hover,
		[data-elementor-type="header"] .elementor-nav-menu .current-menu-item > a {
			color: var(--fz-accent) !important;
			background: var(--fz-accent-soft) !important;
			box-shadow: inset 0 0 0 1px rgba(31, 143, 118, .14) !important;
		}

		#header.cmsmasters_header nav .sub-menu,
		.elementor-location-header nav .sub-menu,
		.elementor-location-header .elementor-nav-menu--dropdown {
			overflow: hidden;
			min-width: 230px;
			padding: 10px;
			background: #fff !important;
			border: 1px solid rgba(23, 50, 56, .1) !important;
			border-radius: 16px !important;
			box-shadow: 0 18px 46px rgba(18, 42, 48, .16) !important;
		}

		#header.cmsmasters_header nav .sub-menu a,
		.elementor-location-header nav .sub-menu a,
		.elementor-location-header .elementor-nav-menu--dropdown a {
			border-radius: 10px;
			color: var(--fz-ink);
			padding: 10px 12px;
		}

		#header.cmsmasters_header [class*="-button"] a,
		.elementor-location-header .elementor-button {
			border-radius: 999px;
			box-shadow: 0 12px 26px rgba(31, 143, 118, .18);
		}

		#footer.cmsmasters-footer,
		.cmsmasters-footer-widgets,
		.elementor-location-footer,
		[data-elementor-type="footer"],
		footer.elementor-section,
		footer.elementor-location-footer {
			background: var(--fz-footer-bg) !important;
			color: var(--fz-footer-text) !important;
		}

		[data-elementor-type="footer"] .elementor-section,
		[data-elementor-type="footer"] .elementor-container,
		[data-elementor-type="footer"] .e-con,
		.elementor-location-footer .elementor-section,
		.elementor-location-footer .elementor-container,
		.elementor-location-footer .e-con {
			background-color: transparent !important;
		}

		.cmsmasters-footer-widgets__outer,
		#footer.cmsmasters-footer .cmsmasters-footer__outer,
		.elementor-location-footer > .elementor-section-wrap,
		.elementor-location-footer > .elementor {
			padding-left: clamp(18px, 4vw, 56px) !important;
			padding-right: clamp(18px, 4vw, 56px) !important;
		}

		.cmsmasters-footer-widgets {
			padding-top: clamp(42px, 7vw, 86px) !important;
			padding-bottom: clamp(30px, 5vw, 58px) !important;
			border-top: 1px solid rgba(255, 255, 255, .1) !important;
		}

		.cmsmasters-footer-widgets__inner {
			gap: 24px;
		}

		.cmsmasters-footer-widgets__area {
			padding: 28px !important;
			background: var(--fz-footer-panel) !important;
			border: 1px solid rgba(255, 255, 255, .08) !important;
			border-radius: 18px !important;
		}

		.cmsmasters-footer-widgets h1,
		.cmsmasters-footer-widgets h2,
		.cmsmasters-footer-widgets h3,
		.cmsmasters-footer-widgets h4,
		.cmsmasters-footer-widgets h5,
		.cmsmasters-footer-widgets h6,
		#footer.cmsmasters-footer h1,
		#footer.cmsmasters-footer h2,
		#footer.cmsmasters-footer h3,
		#footer.cmsmasters-footer h4,
		#footer.cmsmasters-footer h5,
		#footer.cmsmasters-footer h6,
		.elementor-location-footer h1,
		.elementor-location-footer h2,
		.elementor-location-footer h3,
		.elementor-location-footer h4,
		.elementor-location-footer h5,
		.elementor-location-footer h6,
		[data-elementor-type="footer"] h1,
		[data-elementor-type="footer"] h2,
		[data-elementor-type="footer"] h3,
		[data-elementor-type="footer"] h4,
		[data-elementor-type="footer"] h5,
		[data-elementor-type="footer"] h6 {
			color: #fff !important;
			letter-spacing: 0 !important;
		}

		.cmsmasters-footer-widgets a,
		#footer.cmsmasters-footer a,
		.elementor-location-footer a,
		[data-elementor-type="footer"] a {
			color: rgba(255, 255, 255, .82) !important;
			text-decoration: none !important;
			transition: color .2s ease, background-color .2s ease !important;
		}

		.cmsmasters-footer-widgets a:hover,
		#footer.cmsmasters-footer a:hover,
		.elementor-location-footer a:hover {
			color: #75ddc6;
		}

		#footer.cmsmasters-footer .cmsmasters-footer__inner {
			min-height: 72px;
			padding-top: 20px;
			padding-bottom: 20px;
			border-top: 1px solid rgba(255, 255, 255, .1);
		}

		#footer.cmsmasters-footer .cmsmasters-footer-copyright p {
			margin: 0;
			color: rgba(255, 255, 255, .62);
			font-size: 14px;
			letter-spacing: 0;
		}

		#footer.cmsmasters-footer .cmsmasters-footer-menu__list {
			gap: 8px 14px;
		}

		#footer.cmsmasters-footer .cmsmasters-footer-menu__list a {
			display: inline-flex;
			padding: 8px 10px;
			border-radius: 999px;
		}

		#footer.cmsmasters-footer .cmsmasters-footer-menu__list a:hover {
			background: rgba(255, 255, 255, .08);
		}

		@media (max-width: 1180px) {
			#header.cmsmasters_header .cmsmasters-header-mid__inner,
			.elementor-location-header .cmsmasters-header-mid__inner {
				min-height: 72px;
			}

			#header.cmsmasters_header nav a,
			.elementor-location-header nav a,
			.elementor-location-header .elementor-nav-menu a {
				padding: 9px 12px;
			}
		}

		@media (max-width: 767px) {
			#header.cmsmasters_header,
			.elementor-location-header {
				position: relative;
				box-shadow: 0 8px 22px rgba(18, 42, 48, .07);
			}

			#header.cmsmasters_header .cmsmasters-header-mid__inner,
			.elementor-location-header .cmsmasters-header-mid__inner {
				min-height: 66px;
			}

			.cmsmasters-footer-widgets__area {
				padding: 22px;
				border-radius: 14px;
			}

			#footer.cmsmasters-footer .cmsmasters-footer__inner {
				text-align: center;
			}
		}
	</style>
	<?php
}
add_action( 'wp_head', 'fzthasancelik_modern_header_footer_styles', 80 );
