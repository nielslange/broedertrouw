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
			$reordered['trip_dates']  = __( 'Dates', 'broedertrouw' );
			$reordered['trip_port']   = __( 'Embarkation', 'broedertrouw' );
			$reordered['trip_status'] = __( 'Berths', 'broedertrouw' );
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

		case 'trip_status':
			$status = bt_field( 'berth_status', $post_id );

			if ( ! $status ) {
				echo '&mdash;';
				break;
			}

			$resolved = bt_berth_status( $status );
			echo esc_html( $resolved['label'] );
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
	$columns['trip_dates']  = 'trip_dates';
	$columns['trip_port']   = 'trip_port';
	$columns['trip_status'] = 'trip_status';

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
		'trip_dates'  => 'date_start',
		'trip_port'   => 'port_embark',
		'trip_status' => 'berth_status',
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
