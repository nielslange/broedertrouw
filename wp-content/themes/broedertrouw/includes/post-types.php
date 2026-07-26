<?php
/**
 * Custom post types.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the trip post type.
 */
function bt_register_trip_post_type() {
	register_post_type(
		'trip',
		array(
			'labels'       => array(
				'name'               => _x( 'Trips', 'post type general name', 'broedertrouw' ),
				'singular_name'      => _x( 'Trip', 'post type singular name', 'broedertrouw' ),
				'menu_name'          => _x( 'Trips', 'admin menu', 'broedertrouw' ),
				'add_new'            => __( 'Add New', 'broedertrouw' ),
				'add_new_item'       => __( 'Add New Trip', 'broedertrouw' ),
				'edit_item'          => __( 'Edit Trip', 'broedertrouw' ),
				'new_item'           => __( 'New Trip', 'broedertrouw' ),
				'view_item'          => __( 'View Trip', 'broedertrouw' ),
				'view_items'         => __( 'View Trips', 'broedertrouw' ),
				'search_items'       => __( 'Search Trips', 'broedertrouw' ),
				'not_found'          => __( 'No trips found.', 'broedertrouw' ),
				'not_found_in_trash' => __( 'No trips found in Trash.', 'broedertrouw' ),
				'all_items'          => __( 'All Trips', 'broedertrouw' ),
				'archives'           => __( 'Trip Archives', 'broedertrouw' ),
			),
			'public'       => true,
			'has_archive'  => 'trips',
			'rewrite'      => array(
				'slug'       => 'trips',
				'with_front' => false,
			),
			'menu_icon'    => 'dashicons-calendar-alt',
			'menu_position' => 20,
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		)
	);
}
add_action( 'init', 'bt_register_trip_post_type' );

/**
 * Uses the classic editor for trips.
 *
 * A trip is entered entirely through the Trip Details fields, so the block
 * editor only hides them behind a canvas. The classic screen shows the ACF
 * field group directly under the title.
 *
 * @param bool   $use_block_editor Whether to use the block editor.
 * @param string $post_type        Post type name.
 * @return bool
 */
function bt_trip_disable_block_editor( $use_block_editor, $post_type ) {
	return 'trip' === $post_type ? false : $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'bt_trip_disable_block_editor', 10, 2 );
