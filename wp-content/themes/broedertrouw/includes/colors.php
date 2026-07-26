<?php
/**
 * Custom color palette for Broedertrouw.
 *
 * Registers the brand palette so it can be selected in the Blocksy Customizer
 * under General > Colors > Global Color Palette. Colors are referenced
 * throughout the theme as var(--theme-palette-color-N), so changing a swatch in
 * the Customizer propagates everywhere without touching CSS.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Broedertrouw color palette.
 *
 * @param array $palettes Existing palettes from Blocksy.
 * @return array
 */
function bt_register_color_palette( $palettes ) {
	if ( ! is_array( $palettes ) ) {
		$palettes = array();
	}

	$palette = array(
		'id'     => 'palette-broedertrouw',
		'color1' => array( 'color' => '#2273A6' ),
		'color2' => array( 'color' => '#113A5C' ),
		'color3' => array( 'color' => '#3A4650' ),
		'color4' => array( 'color' => '#1A5C8A' ),
		'color5' => array( 'color' => '#DCE5EC' ),
		'color6' => array( 'color' => '#EBF3F8' ),
		'color7' => array( 'color' => '#FFFFFF' ),
		'color8' => array( 'color' => '#0B2C47' ),
	);

	array_unshift( $palettes, $palette );

	return $palettes;
}
add_filter( 'blocksy:options:colors:palette:palettes', 'bt_register_color_palette', 20, 1 );
