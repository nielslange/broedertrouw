<?php
/**
 * ACF block registration.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the block category used by all theme blocks.
 *
 * @param array $categories Block categories.
 * @return array
 */
function bt_register_block_category( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'broedertrouw',
				'title' => __( 'Broedertrouw', 'broedertrouw' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'bt_register_block_category' );

/**
 * Registers every block found in the theme's blocks directory.
 */
function bt_register_blocks() {
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	$blocks_dir = get_stylesheet_directory() . '/blocks';

	if ( ! is_dir( $blocks_dir ) ) {
		return;
	}

	foreach ( glob( $blocks_dir . '/*/block.json' ) as $block_json ) {
		register_block_type( dirname( $block_json ) );
	}
}
add_action( 'init', 'bt_register_blocks', 5 );

/**
 * Renders an editor-only placeholder for a block whose required fields are empty.
 *
 * @param array  $block   Block settings.
 * @param string $message Placeholder message.
 * @return bool True when the placeholder was rendered and the block should stop.
 */
function bt_block_placeholder( $block, $message ) {
	$is_preview = ! empty( $block['is_preview'] );

	if ( $is_preview ) {
		printf(
			'<div class="bt-block-placeholder">%s</div>',
			esc_html( $message )
		);
	}

	return true;
}

/**
 * Builds the class attribute for a block wrapper.
 *
 * @param array  $block Block settings.
 * @param string $base  Base class name.
 * @return string
 */
function bt_block_classes( $block, $base ) {
	$classes = array( $base );

	if ( ! empty( $block['className'] ) ) {
		$classes[] = $block['className'];
	}

	if ( ! empty( $block['align'] ) ) {
		$classes[] = 'align' . $block['align'];
	}

	return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
}

/**
 * Builds the id attribute for a block wrapper.
 *
 * @param array $block Block settings.
 * @return string
 */
function bt_block_anchor( $block ) {
	return ! empty( $block['anchor'] ) ? $block['anchor'] : '';
}
