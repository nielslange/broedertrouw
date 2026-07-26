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
