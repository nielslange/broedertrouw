<?php
/**
 * Theme bootstrap.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

define( 'BT_THEME_VERSION', '1.0.0' );
define( 'BT_THEME_DIR', get_stylesheet_directory() );
define( 'BT_THEME_URI', get_stylesheet_directory_uri() );

require_once BT_THEME_DIR . '/includes/colors.php';
require_once BT_THEME_DIR . '/includes/icons.php';
require_once BT_THEME_DIR . '/includes/trips.php';
require_once BT_THEME_DIR . '/includes/post-types.php';
require_once BT_THEME_DIR . '/includes/polylang.php';
require_once BT_THEME_DIR . '/includes/admin-columns.php';
require_once BT_THEME_DIR . '/includes/footer.php';
require_once BT_THEME_DIR . '/includes/blocks.php';
require_once BT_THEME_DIR . '/includes/acf-labels.php';
require_once BT_THEME_DIR . '/includes/trip-templates.php';
require_once BT_THEME_DIR . '/includes/schema.php';

/**
 * Theme setup.
 */
function bt_setup() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'bt_setup' );

/**
 * Loads the theme translations.
 *
 * Uses the resolved locale rather than load_child_theme_textdomain() so the
 * Polylang language of the current request wins over the site default.
 */
function bt_load_textdomain() {
	$locale = determine_locale();
	$mofile = BT_THEME_DIR . '/languages/broedertrouw-' . $locale . '.mo';

	if ( is_readable( $mofile ) ) {
		load_textdomain( 'broedertrouw', $mofile, $locale );
	}
}
add_action( 'init', 'bt_load_textdomain', 1 );

/**
 * Enqueues the child theme stylesheet.
 */
function bt_enqueue_styles() {
	wp_enqueue_style(
		'broedertrouw-style',
		get_stylesheet_uri(),
		array( 'ct-main-styles' ),
		BT_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'bt_enqueue_styles' );

/**
 * Preloads the self-hosted latin webfonts.
 *
 * Blocksy's Local Google Fonts extension serves every face with
 * font-display: swap, so text paints in a fallback and re-renders once the
 * font arrives, which is the visible jump on load. Preloading starts the
 * download with the document instead of after the CSS is parsed.
 *
 * Archivo and Source Sans 3 are both variable fonts, so a single latin file
 * per family covers every weight in use. The URLs are read from Blocksy's
 * generated CSS rather than hardcoded, because the hashed filenames change
 * whenever the font cache is refreshed.
 *
 * @return string[] Absolute URLs of the font files to preload.
 */
function bt_preload_font_urls() {
	$cached = get_transient( 'bt_preload_fonts' );

	if ( is_array( $cached ) ) {
		return $cached;
	}

	$css_file = WP_CONTENT_DIR . '/uploads/blocksy/css/global.css';

	if ( ! file_exists( $css_file ) ) {
		return array();
	}

	$css = file_get_contents( $css_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions

	if ( ! $css ) {
		return array();
	}

	$urls = array();

	preg_match_all( '/@font-face\s*\{[^}]*\}/', $css, $faces );

	foreach ( $faces[0] as $face ) {
		// Upright latin subset only: italics and other subsets are not needed
		// for the first paint.
		if ( preg_match( '/font-style:\s*(\w+)/', $face, $style ) && 'normal' !== $style[1] ) {
			continue;
		}

		if ( ! preg_match( '/unicode-range:[^;]*U\+0000-00FF/', $face ) ) {
			continue;
		}

		if ( ! preg_match( "/font-family:\s*'([^']+)'/", $face, $family ) ) {
			continue;
		}

		if ( ! preg_match( '/url\((https:\/\/[^)]+\.woff2)\)/', $face, $url ) ) {
			continue;
		}

		// One file per family is enough: both faces are variable fonts.
		$urls[ $family[1] ] = $url[1];
	}

	$urls = array_values( $urls );

	set_transient( 'bt_preload_fonts', $urls, DAY_IN_SECONDS );

	return $urls;
}

/**
 * Prints the font preload tags.
 */
function bt_preload_fonts() {
	foreach ( bt_preload_font_urls() as $url ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( $url )
		);
	}
}
add_action( 'wp_head', 'bt_preload_fonts', 1 );

/**
 * Drops the cached preload URLs when Blocksy regenerates its CSS.
 */
function bt_flush_preload_fonts() {
	delete_transient( 'bt_preload_fonts' );
}
add_action( 'blocksy:dynamic-css:refresh-caches', 'bt_flush_preload_fonts' );


/**
 * Shows an admin notice when ACF PRO is missing.
 */
function bt_acf_notice() {
	if ( function_exists( 'acf_register_block_type' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
		esc_html__( 'Broedertrouw: ACF PRO is not active. Trip fields and content blocks are unavailable until it is enabled.', 'broedertrouw' )
	);
}
add_action( 'admin_notices', 'bt_acf_notice' );
