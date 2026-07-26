<?php
/**
 * Trip data helpers shared by blocks and templates.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders a theme template part with arguments.
 *
 * @param string $slug Part slug relative to the theme root, without extension.
 * @param array  $args Arguments exposed to the part as $args.
 */
function bt_template_part( $slug, $args = array() ) {
	$template = locate_template( $slug . '.php' );

	if ( ! $template ) {
		return;
	}

	load_template( $template, false, $args );
}

/**
 * Reads an ACF field with a graceful fallback to post meta.
 *
 * @param string $selector Field name.
 * @param int    $post_id  Post ID.
 * @return mixed
 */
function bt_field( $selector, $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( function_exists( 'get_field' ) ) {
		return get_field( $selector, $post_id );
	}

	return get_post_meta( $post_id, $selector, true );
}

/**
 * Returns today's date in the format the ACF date picker stores.
 *
 * @return string
 */
function bt_today() {
	return current_datetime()->format( 'Y-m-d' );
}

/**
 * Queries trips by date window.
 *
 * Polylang filters the query to the current language on its own, so no
 * language argument is passed here.
 *
 * @param array $args {
 *     Optional arguments.
 *
 *     @type string $when     'upcoming' or 'past'. Default 'upcoming'.
 *     @type int    $count    Number of trips. Default 3.
 *     @type int[]  $include  Explicit post IDs, returned in the given order.
 *     @type int[]  $exclude  Post IDs to exclude.
 * }
 * @return WP_Post[]
 */
function bt_get_trips( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'when'    => 'upcoming',
			'count'   => 3,
			'include' => array(),
			'exclude' => array(),
		)
	);

	if ( ! empty( $args['include'] ) ) {
		$query_args = array(
			'post_type'      => 'trip',
			'post_status'    => 'publish',
			'post__in'       => array_map( 'absint', $args['include'] ),
			'orderby'        => 'post__in',
			'posts_per_page' => count( $args['include'] ),
		);

		return get_posts( $query_args );
	}

	$is_upcoming = 'past' !== $args['when'];

	$query_args = array(
		'post_type'      => 'trip',
		'post_status'    => 'publish',
		'posts_per_page' => absint( $args['count'] ) > 0 ? absint( $args['count'] ) : -1,
		'meta_key'       => 'date_start',
		'orderby'        => 'meta_value',
		'order'          => $is_upcoming ? 'ASC' : 'DESC',
		'meta_query'     => array(
			array(
				'key'     => 'date_end',
				'value'   => bt_today(),
				'compare' => $is_upcoming ? '>=' : '<',
				'type'    => 'DATE',
			),
		),
	);

	if ( ! empty( $args['exclude'] ) ) {
		$query_args['post__not_in'] = array_map( 'absint', $args['exclude'] );
	}

	return get_posts( $query_args );
}

/**
 * Formats a trip's date range for display.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function bt_trip_date_range( $post_id = null ) {
	$start = bt_field( 'date_start', $post_id );
	$end   = bt_field( 'date_end', $post_id );

	if ( ! $start ) {
		return '';
	}

	$start_ts = strtotime( $start );

	/*
	 * Numeric short dates per CLDR: nl_NL uses dd-MM-y, de_DE uses dd.MM.y.
	 * The full date is the translatable unit so each language keeps its own
	 * separators and its own range wording ("t/m" / "bis").
	 */
	$full = _x( 'd-m-Y', 'trip date format', 'broedertrouw' );

	if ( ! $end || $end === $start ) {
		return wp_date( $full, $start_ts );
	}

	$end_ts = strtotime( $end );

	// Within one month the shared month and year are printed once only.
	if ( wp_date( 'Y-m', $start_ts ) === wp_date( 'Y-m', $end_ts ) ) {
		return sprintf(
			/* translators: 1: start day, 2: full end date. */
			_x( '%1$s t/m %2$s', 'trip date range, same month', 'broedertrouw' ),
			wp_date( _x( 'd', 'trip start day format', 'broedertrouw' ), $start_ts ),
			wp_date( $full, $end_ts )
		);
	}

	return sprintf(
		/* translators: 1: full start date, 2: full end date. */
		_x( '%1$s t/m %2$s', 'trip date range', 'broedertrouw' ),
		wp_date( $full, $start_ts ),
		wp_date( $full, $end_ts )
	);
}

/**
 * Returns the translated label for a port choice.
 *
 * @param string $port Port key.
 * @return string
 */
function bt_port_label( $port ) {
	$labels = array(
		'hoorn'     => __( 'Hoorn', 'broedertrouw' ),
		'enkhuizen' => __( 'Enkhuizen', 'broedertrouw' ),
		'amsterdam' => __( 'Amsterdam', 'broedertrouw' ),
		'other'     => __( 'Other port', 'broedertrouw' ),
	);

	return isset( $labels[ $port ] ) ? $labels[ $port ] : '';
}

/**
 * Formats a money amount the way the Dutch and German sites write it.
 *
 * @param float $amount Amount in euros.
 * @return string
 */
function bt_format_price( $amount ) {
	return number_format( (float) $amount, 0, ',', '.' );
}

/**
 * Formats a trip's headline price for cards and lists.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function bt_trip_price( $post_id = null ) {
	$price = bt_field( 'price_berth', $post_id );

	if ( ! $price ) {
		return '';
	}

	$amount = bt_format_price( $price );

	if ( 'whole_boat' === bt_field( 'booking_mode', $post_id ) ) {
		/* translators: %s: price amount. */
		return sprintf( __( 'from € %s for the whole ship', 'broedertrouw' ), $amount );
	}

	/* translators: %s: price amount. */
	return sprintf( __( 'from € %s per person', 'broedertrouw' ), $amount );
}

/**
 * Formats the private cabin price, when the trip offers one.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function bt_trip_cabin_price( $post_id = null ) {
	$price = bt_field( 'price_cabin', $post_id );

	if ( ! $price ) {
		return '';
	}

	/* translators: %s: price amount. */
	return sprintf( __( '€ %s per person', 'broedertrouw' ), bt_format_price( $price ) );
}
