<?php
/**
 * Polylang integration.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Opts the trip post type into Polylang translation.
 *
 * @param array $post_types Translated post types.
 * @return array
 */
function bt_polylang_post_types( $post_types ) {
	$post_types['trip'] = 'trip';

	return $post_types;
}
add_filter( 'pll_get_post_types', 'bt_polylang_post_types' );

/**
 * Opts the trip type taxonomy into Polylang translation.
 *
 * @param array $taxonomies Translated taxonomies.
 * @return array
 */
function bt_polylang_taxonomies( $taxonomies ) {
	$taxonomies['trip_type'] = 'trip_type';

	return $taxonomies;
}
add_filter( 'pll_get_taxonomies', 'bt_polylang_taxonomies' );

/**
 * Renders block previews in the language of the post being edited.
 *
 * ACF renders block previews through the REST API, where the locale is the
 * admin user's own (often en_US). Without this, an editor working on the
 * German page sees English strings such as "from" or "With a private cabin"
 * even though the front end is translated correctly.
 */
function bt_switch_locale_for_block_preview() {
	if ( ! function_exists( 'pll_get_post_language' ) ) {
		return;
	}

	// Only relevant for the editor's block renderer, never the front end.
	if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
		return;
	}

	$post_id = 0;

	if ( isset( $_GET['post_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_id = absint( $_GET['post_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	if ( ! $post_id ) {
		return;
	}

	$lang = pll_get_post_language( $post_id, 'locale' );

	if ( $lang && get_locale() !== $lang ) {
		switch_to_locale( $lang );

		// The theme text domain is already loaded for the previous locale.
		unload_textdomain( 'broedertrouw' );
		load_theme_textdomain( 'broedertrouw', get_stylesheet_directory() . '/languages' );
	}
}
add_action( 'rest_api_init', 'bt_switch_locale_for_block_preview' );
