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
 * Registers the trip type taxonomy.
 *
 * Exactly two terms exist, "Sailing trip" and "Regatta", seeded by
 * bt_seed_trip_types(). The taxonomy is hierarchical so the editor shows
 * radio-style checkboxes rather than a free-text tag field, and editors
 * cannot invent further types.
 */
function bt_register_trip_type_taxonomy() {
	register_taxonomy(
		'trip_type',
		'trip',
		array(
			'labels'            => array(
				'name'          => _x( 'Trip types', 'taxonomy general name', 'broedertrouw' ),
				'singular_name' => _x( 'Trip type', 'taxonomy singular name', 'broedertrouw' ),
				'all_items'     => __( 'All trip types', 'broedertrouw' ),
				'edit_item'     => __( 'Edit trip type', 'broedertrouw' ),
				'view_item'     => __( 'View trip type', 'broedertrouw' ),
				'menu_name'     => __( 'Trip types', 'broedertrouw' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'capabilities'      => array(
				// Editors assign the two seeded terms but never create more.
				'manage_terms' => 'manage_options',
				'edit_terms'   => 'manage_options',
				'delete_terms' => 'manage_options',
				'assign_terms' => 'edit_posts',
			),
			'rewrite'           => array(
				'slug'       => 'trip-type',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'bt_register_trip_type_taxonomy', 6 );

/**
 * Creates the two trip type terms and registers them with Polylang.
 *
 * Slugs are English so code and queries stay language independent; the NL and
 * DE names are the translated term names.
 */
function bt_seed_trip_types() {
	$types = array(
		'sailing-trip' => array(
			'nl' => 'Zeiltocht',
			'de' => 'Segeltörn',
		),
		'regatta'      => array(
			'nl' => 'Regatta',
			'de' => 'Regatta',
		),
	);

	foreach ( $types as $slug => $names ) {
		$term = get_term_by( 'slug', $slug, 'trip_type' );

		if ( ! $term ) {
			$created = wp_insert_term( $names['nl'], 'trip_type', array( 'slug' => $slug ) );

			if ( is_wp_error( $created ) ) {
				continue;
			}

			$term = get_term( $created['term_id'], 'trip_type' );
		}

		if ( $term && function_exists( 'pll_set_term_language' ) && ! pll_get_term_language( $term->term_id ) ) {
			pll_set_term_language( $term->term_id, 'nl' );
		}
	}
}

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
