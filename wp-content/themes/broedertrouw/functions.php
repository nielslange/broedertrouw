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
