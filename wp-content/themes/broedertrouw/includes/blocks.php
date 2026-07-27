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

/**
 * Versions block assets by their file time so edits reach the browser.
 *
 * Block scripts and styles otherwise inherit one static version, so a changed
 * file keeps its old query string and visitors are served the cached copy.
 *
 * @param string $src    Asset URL.
 * @param string $handle Asset handle.
 * @return string
 */
function bt_version_block_assets( $src, $handle ) {
	if ( 0 !== strpos( $handle, 'bt-' ) ) {
		return $src;
	}

	$theme = get_stylesheet_directory_uri();

	if ( 0 !== strpos( $src, $theme ) ) {
		return $src;
	}

	$path = get_stylesheet_directory() . substr( wp_parse_url( $src, PHP_URL_PATH ), strlen( wp_parse_url( $theme, PHP_URL_PATH ) ) );

	if ( ! file_exists( $path ) ) {
		return $src;
	}

	return add_query_arg( 'ver', filemtime( $path ), remove_query_arg( 'ver', $src ) );
}
add_filter( 'script_loader_src', 'bt_version_block_assets', 10, 2 );
add_filter( 'style_loader_src', 'bt_version_block_assets', 10, 2 );
add_action( 'init', 'bt_register_blocks', 5 );

/**
 * Registers block styles for core blocks used in theme layouts.
 */
function bt_register_block_styles() {
	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'bt-notice',
			'label' => __( 'Notice box', 'broedertrouw' ),
		)
	);
}
add_action( 'init', 'bt_register_block_styles' );

/**
 * Returns the enquiry anchor for the current language.
 *
 * The anchor ends up in the address bar, so unlike a field key or a block name
 * it is content and gets translated: /de/preise/#anfrage, /prijzen/#aanvraag.
 * Everything that links to the form resolves it through here.
 *
 * @return string Anchor without the leading hash.
 */
function bt_enquiry_anchor() {
	return _x( 'enquiry', 'enquiry form URL anchor', 'broedertrouw' );
}

/**
 * Returns the enquiry link for the current page.
 *
 * @return string
 */
function bt_enquiry_url() {
	return '#' . bt_enquiry_anchor();
}

/**
 * Builds the data attributes that prefill the enquiry form.
 *
 * Every button that points at the form goes through here, so a click behaves
 * the same wherever it happens: the form is filled with whatever the button
 * already knows and the visitor never retypes it.
 *
 * @param array $args {
 *     Optional context to carry into the form.
 *
 *     @type string $trip       Trip name, printed into the message.
 *     @type string $period     Date range for the preferred period field.
 *     @type int    $group      Group type index, 0 school class .. 3 club/other.
 *     @type bool   $individual Whether the trip is booked per berth rather than
 *                              by chartering the whole ship. Individual
 *                              bookings hide the group questions, which a
 *                              single visitor has no answer to.
 * }
 * @return string Attribute string, ready to echo inside a tag.
 */
function bt_enquiry_attrs( $args = array() ) {
	// Always present, so the script can recognise an enquiry button even when
	// it carries no context of its own.
	$attrs = array( 'data-bt-enquiry' => '' );

	if ( ! empty( $args['trip'] ) ) {
		$attrs['data-bt-trip'] = $args['trip'];
	}

	/*
	 * A charter row names a trip too ("Wochenende"), but there the group is
	 * the whole point, so the flag is what distinguishes the two rather than
	 * the presence of a trip name.
	 */
	if ( ! empty( $args['individual'] ) ) {
		$attrs['data-bt-individual'] = '';
	}

	if ( ! empty( $args['period'] ) ) {
		$attrs['data-bt-period'] = $args['period'];
	}

	if ( isset( $args['group'] ) && '' !== $args['group'] ) {
		$attrs['data-bt-group'] = (int) $args['group'];
	}

	$out = '';

	foreach ( $attrs as $name => $value ) {
		$out .= '' === $value
			? ' ' . $name
			: sprintf( ' %s="%s"', $name, esc_attr( $value ) );
	}

	return $out;
}

/**
 * Whether a URL points at the enquiry form on the current page.
 *
 * @param string $url URL to test.
 * @return bool
 */
function bt_is_enquiry_url( $url ) {
	return false !== strpos( (string) $url, '#' . bt_enquiry_anchor() );
}

/**
 * Records the tone a self-coloured section paints, without taking a band.
 *
 * Sections such as the page header, the enquiry band and the light CTA are
 * tinted by their own stylesheet. The alternation has to know about them, or a
 * neutral section that happens to land on tint will sit flush against one and
 * the seam between the two disappears.
 *
 * @param string $tone 'tint', 'dark' or 'plain'.
 */
function bt_band_note( $tone ) {
	bt_band_state( $tone );
}

/**
 * Tracks and returns the tone of the previous section.
 *
 * @param string|null $set Tone to record, or null to read the current one.
 * @return string
 */
function bt_band_state( $set = null ) {
	static $previous = 'plain';

	if ( null !== $set ) {
		$previous = $set;
	}

	return $previous;
}

/**
 * Restarts the alternation for each rendered post.
 *
 * Without this the state carries over between the main query and anything
 * else that renders blocks in the same request, such as the REST preview.
 */
function bt_band_reset() {
	bt_band_state( 'plain' );
}
add_action( 'the_post', 'bt_band_reset' );

/**
 * Returns the band class for a neutral section, then records its tone.
 *
 * Rather than counting sections, this looks at what the previous section
 * painted: a neutral section takes the tint only when the one before it did
 * not, so two tinted bands can never touch and every seam stays visible.
 *
 * @param bool $counts Whether this block participates in the alternation.
 * @return string Class name to add to the section, or ''.
 */
function bt_band_class( $counts = true ) {
	if ( ! $counts ) {
		return '';
	}

	/*
	 * Follow a tinted neighbour with plain. A dark band is a hard break, so the
	 * section after it may start on tint again without the two running together.
	 */
	if ( 'tint' === bt_band_state() ) {
		bt_band_state( 'plain' );

		return '';
	}

	bt_band_state( 'tint' );

	return 'bt-section-alt';
}

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

	/*
	 * Sections that paint their own background report the tone they use so the
	 * alternation can avoid putting two tinted bands next to each other. The
	 * rest take a band based on what came before them.
	 */
	/*
	 * Every block whose own stylesheet sets a section background must be listed
	 * here. Adding .bt-section-alt on top of one would repaint it: the stats
	 * band lost its navy that way and left white text on a light tint.
	 */
	$self_colored = array(
		'bt-hero'         => 'dark',
		'bt-stats'        => 'dark',
		'bt-ship'         => 'tint',
		'bt-testimonials' => 'tint',
		'bt-page-header'  => 'tint',
		'bt-enquiry'      => 'tint',
		'bt-charter'      => 'plain',
	);

	if ( 'bt-enquiry' === $base ) {
		/*
		 * The enquiry band is always tinted and always last, so when the
		 * section above it already took the tint it switches to the soft band
		 * instead of repeating it.
		 */
		if ( 'tint' === bt_band_state() ) {
			$classes[] = 'bt-enquiry--soft';
			bt_band_note( 'soft' );
		} else {
			bt_band_note( 'tint' );
		}
	} elseif ( isset( $self_colored[ $base ] ) ) {
		bt_band_note( $self_colored[ $base ] );
	} elseif ( 'bt-cta' === $base ) {
		// The CTA is tinted when light and reads as a break when dark.
		$style = function_exists( 'get_field' ) ? get_field( 'style' ) : '';
		bt_band_note( 'light' === $style ? 'tint' : 'dark' );
	} else {
		$band = bt_band_class();

		if ( $band && empty( $block['className'] ) ) {
			$classes[] = $band;
		}
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
