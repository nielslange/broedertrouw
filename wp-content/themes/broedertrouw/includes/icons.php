<?php
/**
 * Inline SVG icons.
 *
 * Paths are from Lucide (https://lucide.dev, ISC licence). Unicode glyphs such
 * as U+260E and U+2709 were used before, but their size depends on whichever
 * fallback font supplies them, so the envelope rendered far smaller than the
 * phone. These share one 24x24 viewBox and a common stroke, so every icon has
 * identical optical weight and height.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the path markup for a named icon.
 *
 * @param string $name Icon name.
 * @return string SVG inner markup, or '' when the icon is unknown.
 */
function bt_icon_path( $name ) {
	$icons = array(
		// lucide: phone
		'phone' => '<path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/>',
		// lucide: mail
		'mail'  => '<path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/>',
		// lucide: users — a whole group chartering the ship together
		'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/>',
		// lucide: user — a single guest joining an open trip
		'user'  => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Renders an inline SVG icon.
 *
 * The icon is decorative wherever it sits beside its own label, so it is
 * hidden from assistive technology by default.
 *
 * @param string $name  Icon name.
 * @param array  $args {
 *     Optional arguments.
 *
 *     @type string $class Extra class names.
 *     @type int    $size  Pixel size for width and height. Default 18.
 *     @type string $label Accessible label. Omit to keep the icon decorative.
 * }
 * @return string
 */
function bt_icon( $name, $args = array() ) {
	$path = bt_icon_path( $name );

	if ( ! $path ) {
		return '';
	}

	$args = wp_parse_args(
		$args,
		array(
			'class' => '',
			'size'  => 18,
			'label' => '',
		)
	);

	$classes = trim( 'bt-icon ' . $args['class'] );

	$a11y = $args['label']
		? sprintf( 'role="img" aria-label="%s"', esc_attr( $args['label'] ) )
		: 'aria-hidden="true" focusable="false"';

	return sprintf(
		'<svg class="%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" %3$s>%4$s</svg>',
		esc_attr( $classes ),
		(int) $args['size'],
		$a11y, // Built from escaped values above.
		$path  // Static markup from the table above.
	);
}
