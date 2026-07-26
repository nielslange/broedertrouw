<?php
/**
 * Admin list columns for the trip post type.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Adds trip columns to the post list table.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function bt_trip_columns( $columns ) {
	$reordered = array();

	foreach ( $columns as $key => $label ) {
		$reordered[ $key ] = $label;

		if ( 'title' === $key ) {
			$reordered['trip_dates'] = __( 'Dates', 'broedertrouw' );
			$reordered['trip_port']  = __( 'Embarkation', 'broedertrouw' );
			$reordered['trip_price'] = __( 'Price', 'broedertrouw' );
		}
	}

	return $reordered;
}
add_filter( 'manage_trip_posts_columns', 'bt_trip_columns' );

/**
 * Renders the custom trip columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function bt_trip_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'trip_dates':
			$range = bt_trip_date_range( $post_id );
			echo $range ? esc_html( $range ) : '&mdash;';
			break;

		case 'trip_port':
			$port = bt_port_label( bt_field( 'port_embark', $post_id ) );
			echo $port ? esc_html( $port ) : '&mdash;';
			break;

		case 'trip_price':
			$price = bt_trip_price( $post_id );
			echo $price ? esc_html( $price ) : '&mdash;';
			break;
	}
}
add_action( 'manage_trip_posts_custom_column', 'bt_trip_column_content', 10, 2 );

/**
 * Marks the date column as sortable.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function bt_trip_sortable_columns( $columns ) {
	$columns['trip_dates'] = 'trip_dates';
	$columns['trip_port']  = 'trip_port';
	$columns['trip_price'] = 'trip_price';

	return $columns;
}
add_filter( 'manage_edit-trip_sortable_columns', 'bt_trip_sortable_columns' );

/**
 * Applies sorting and the default order for the trip list table.
 *
 * @param WP_Query $query Current query.
 */
function bt_trip_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'trip' !== $query->get( 'post_type' ) ) {
		return;
	}

	$meta_keys = array(
		'trip_dates' => 'date_start',
		'trip_port'  => 'port_embark',
		'trip_price' => 'price_berth',
	);

	$orderby = $query->get( 'orderby' );

	if ( isset( $meta_keys[ $orderby ] ) ) {
		$query->set( 'meta_key', $meta_keys[ $orderby ] );
		$query->set( 'orderby', 'meta_value' );

		return;
	}

	if ( ! $orderby ) {
		$query->set( 'meta_key', 'date_start' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'DESC' );
	}
}
add_action( 'pre_get_posts', 'bt_trip_admin_order' );

/**
 * Orders the page list table by menu order, so it mirrors the menus.
 *
 * Pages carry a menu_order that follows the main menu, then the footer legals
 * menu. WordPress defaults the list table to title order, which interleaves the
 * two languages alphabetically and hides that structure.
 *
 * @param WP_Query $query Current query.
 */
function bt_page_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'page' !== $query->get( 'post_type' ) ) {
		return;
	}

	if ( $query->get( 'orderby' ) ) {
		return;
	}

	$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
}
add_action( 'pre_get_posts', 'bt_page_admin_order' );
