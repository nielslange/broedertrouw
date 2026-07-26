<?php
/**
 * Footer extensions.
 *
 * The footer is built entirely from Blocksy footer menu elements and their menu
 * locations. Blocksy ships two footer locations; the site needs three (contact,
 * info and legal columns), so a third element and location are registered here
 * through Blocksy's documented extension filters.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the third footer menu location for Appearance > Menus.
 */
function bt_register_third_footer_menu() {
	register_nav_menus(
		array(
			'footer_3' => __( 'Footer Menu 3', 'broedertrouw' ),
		)
	);
}
add_action( 'after_setup_theme', 'bt_register_third_footer_menu', 20 );

/**
 * Exposes the third footer location to Blocksy's customizer menu dropdown.
 *
 * @param array $items Location slug => label.
 * @return array
 */
function bt_register_third_footer_location( $items ) {
	$items['footer_3'] = __( 'Footer Menu 3', 'broedertrouw' );

	return $items;
}
add_filter( 'blocksy:register_nav_menus:input', 'bt_register_third_footer_location', 60 );

/**
 * Adds the child theme's footer builder elements.
 *
 * @param array $paths Item directories.
 * @return array
 */
function bt_footer_items_paths( $paths ) {
	$paths[] = BT_THEME_DIR . '/footer-items';

	return $paths;
}
add_filter( 'blocksy:footer:items-paths', 'bt_footer_items_paths' );
add_filter( 'blocksy:footer:items-root-paths', 'bt_footer_items_paths' );

/**
 * Sorts the menu locations alphabetically by label.
 *
 * Blocksy registers them in builder order (header rows, then footer rows), which
 * is hard to scan on Appearance > Menus > Manage Locations. get_registered_nav_menus()
 * reads the global without a filter, so the global itself is sorted.
 */
function bt_sort_nav_menu_locations() {
	global $_wp_registered_nav_menus;

	if ( ! is_array( $_wp_registered_nav_menus ) ) {
		return;
	}

	uasort( $_wp_registered_nav_menus, 'strnatcasecmp' );
}
add_action( 'admin_init', 'bt_sort_nav_menu_locations', 99 );
