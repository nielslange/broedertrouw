<?php
/**
 * Structured data for trips.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs Event JSON-LD on single trip views.
 */
function bt_trip_event_schema() {
	if ( ! is_singular( 'trip' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$start   = bt_field( 'date_start', $post_id );

	if ( ! $start ) {
		return;
	}

	$end   = bt_field( 'date_end', $post_id );
	$port  = bt_port_label( bt_field( 'port_embark', $post_id ) );
	$price = bt_field( 'price_berth', $post_id );

	$data = array(
		'@context'            => 'https://schema.org',
		'@type'               => 'Event',
		'name'                => get_the_title( $post_id ),
		'startDate'           => $start,
		'url'                 => get_permalink( $post_id ),
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
		'organizer'           => array(
			'@type' => 'Organization',
			'name'  => 'Broedertrouw',
			'url'   => home_url( '/' ),
		),
	);

	if ( $end ) {
		$data['endDate'] = $end;
	}

	if ( $port ) {
		$data['location'] = array(
			'@type' => 'Place',
			'name'  => $port,
		);
	}

	$description = get_the_excerpt( $post_id );

	if ( $description ) {
		$data['description'] = wp_strip_all_tags( $description );
	}

	$image = get_the_post_thumbnail_url( $post_id, 'large' );

	if ( $image ) {
		$data['image'] = $image;
	}

	if ( $price ) {
		$data['offers'] = array(
			'@type'         => 'Offer',
			'price'         => (float) $price,
			'priceCurrency' => 'EUR',
			'url'           => get_permalink( $post_id ),
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'bt_trip_event_schema' );
