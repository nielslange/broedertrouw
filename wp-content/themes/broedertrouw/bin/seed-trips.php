<?php
/**
 * Development seed data for the trip post type.
 *
 * Run with: wp eval-file wp-content/themes/broedertrouw/bin/seed-trips.php
 * Idempotent: existing trips are matched by slug and updated, never duplicated.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'update_field' ) ) {
	echo "ACF PRO is required to seed trip fields.\n";
	return;
}

$year      = (int) current_datetime()->format( 'Y' );
$next_year = $year + 1;

$trips = array(
	array(
		'slug'    => 'wadden-trip-summer',
		'start'   => sprintf( '%d-07-31', $next_year ),
		'end'     => sprintf( '%d-08-09', $next_year ),
		'embark'  => 'enkhuizen',
		'mode'    => 'per_berth',
		'price'   => 1080,
		'price_cabin' => 1680,
		'nl'      => array(
			'title'     => 'Waddentocht',
			'highlight' => 'Droogvallen op het wad bij eb.',
			'excerpt'   => 'Negen dagen zeilen op de Waddenzee, met droogvallen en wandelen over de zandbanken.',
			'includes'  => array( 'Volledige verzorging aan boord', 'Ervaren vaste crew', 'Beddengoed en handdoeken', 'Havengelden' ),
			'excludes'  => array( 'Reis naar Enkhuizen', 'Dranken aan de bar', 'Persoonlijke verzekering' ),
		),
		'de'      => array(
			'title'     => 'Wattentour',
			'highlight' => 'Trockenfallen auf dem Watt bei Ebbe.',
			'excerpt'   => 'Neun Tage Segeln auf dem Wattenmeer, mit Trockenfallen und Wandern über die Sandbänke.',
			'includes'  => array( 'Vollverpflegung an Bord', 'Erfahrene Stammcrew', 'Bettwäsche und Handtücher', 'Hafengebühren' ),
			'excludes'  => array( 'Anreise nach Enkhuizen', 'Getränke an der Bar', 'Persönliche Versicherung' ),
		),
	),
	array(
		'slug'    => 'klipperrace',
		'start'   => sprintf( '%d-10-09', $next_year ),
		'end'     => sprintf( '%d-10-11', $next_year ),
		'embark'  => 'enkhuizen',
		'mode'    => 'per_berth',
		'price'   => 265,
		'price_cabin' => 420,
		'nl'      => array(
			'title'     => 'Klipperrace',
			'highlight' => 'Regattaweekend met historische klippers.',
			'excerpt'   => 'Drie dagen wedstrijdzeilen tussen tientallen historische klippers op het IJsselmeer.',
			'includes'  => array( 'Maaltijden aan boord', 'Inschrijfgeld regatta', 'Beddengoed' ),
			'excludes'  => array( 'Reis naar Enkhuizen', 'Dranken aan de bar' ),
		),
		'de'      => array(
			'title'     => 'Klipperrace',
			'highlight' => 'Regatta-Wochenende mit historischen Klippern.',
			'excerpt'   => 'Drei Tage Wettfahrt zwischen Dutzenden historischen Klippern auf dem IJsselmeer.',
			'includes'  => array( 'Mahlzeiten an Bord', 'Regatta-Startgeld', 'Bettwäsche' ),
			'excludes'  => array( 'Anreise nach Enkhuizen', 'Getränke an der Bar' ),
		),
	),
	array(
		'slug'    => 'pieperrace',
		'start'   => sprintf( '%d-05-15', $next_year ),
		'end'     => sprintf( '%d-05-17', $next_year ),
		'embark'  => 'enkhuizen',
		'mode'    => 'per_berth',
		'price'   => 265,
		'price_cabin' => 420,
		'nl'      => array(
			'title'     => 'Pieperrace',
			'highlight' => 'Seizoensopening met de hele vloot.',
			'excerpt'   => 'De klassieke seizoensopener op het IJsselmeer, met een groot deelnemersveld.',
			'includes'  => array( 'Maaltijden aan boord', 'Inschrijfgeld regatta' ),
			'excludes'  => array( 'Reis naar Enkhuizen', 'Dranken aan de bar' ),
		),
		'de'      => array(
			'title'     => 'Pieperrace',
			'highlight' => 'Saisonstart mit der ganzen Flotte.',
			'excerpt'   => 'Der klassische Saisonauftakt auf dem IJsselmeer, mit großem Teilnehmerfeld.',
			'includes'  => array( 'Mahlzeiten an Bord', 'Regatta-Startgeld' ),
			'excludes'  => array( 'Anreise nach Enkhuizen', 'Getränke an der Bar' ),
		),
	),
	array(
		'slug'    => 'bontekoerace',
		'start'   => sprintf( '%d-10-23', $next_year ),
		'end'     => sprintf( '%d-10-25', $next_year ),
		'embark'  => 'enkhuizen',
		'mode'    => 'per_berth',
		'price'   => 265,
		'price_cabin' => 420,
		'nl'      => array(
			'title'     => 'Bontekoerace',
			'highlight' => 'Seizoensafsluiting vanuit Enkhuizen.',
			'excerpt'   => 'De laatste regatta van het seizoen, traditioneel vanuit de haven van Enkhuizen.',
			'includes'  => array( 'Maaltijden aan boord', 'Inschrijfgeld regatta' ),
			'excludes'  => array( 'Reis naar Enkhuizen', 'Dranken aan de bar' ),
		),
		'de'      => array(
			'title'     => 'Bontekoerace',
			'highlight' => 'Saisonabschluss ab Enkhuizen.',
			'excerpt'   => 'Die letzte Regatta der Saison, traditionell ab dem Hafen von Enkhuizen.',
			'includes'  => array( 'Mahlzeiten an Bord', 'Regatta-Startgeld' ),
			'excludes'  => array( 'Anreise nach Enkhuizen', 'Getränke an der Bar' ),
		),
	),
	array(
		'slug'    => 'easter-weekend',
		'start'   => sprintf( '%d-04-03', $next_year ),
		'end'     => sprintf( '%d-04-06', $next_year ),
		'embark'  => 'enkhuizen',
		'mode'    => 'per_berth',
		'price'   => 395,
		'price_cabin' => 620,
		'nl'      => array(
			'title'     => 'Paasweekend',
			'highlight' => 'Vier dagen zeilen rond Pasen.',
			'excerpt'   => 'Een lang paasweekend op het IJsselmeer, met overnachtingen in sfeervolle havens.',
			'includes'  => array( 'Volledige verzorging aan boord', 'Beddengoed en handdoeken', 'Havengelden' ),
			'excludes'  => array( 'Reis naar Enkhuizen', 'Dranken aan de bar' ),
		),
		'de'      => array(
			'title'     => 'Osterwochenende',
			'highlight' => 'Vier Tage Segeln rund um Ostern.',
			'excerpt'   => 'Ein langes Osterwochenende auf dem IJsselmeer, mit Übernachtungen in stimmungsvollen Häfen.',
			'includes'  => array( 'Vollverpflegung an Bord', 'Bettwäsche und Handtücher', 'Hafengebühren' ),
			'excludes'  => array( 'Anreise nach Enkhuizen', 'Getränke an der Bar' ),
		),
	),
	array(
		'slug'    => 'sailing-weekend-june',
		'start'   => sprintf( '%d-06-12', $next_year ),
		'end'     => sprintf( '%d-06-14', $next_year ),
		'embark'  => 'enkhuizen',
		'mode'    => 'whole_boat',
		'price'   => 4200,
		'nl'      => array(
			'title'      => 'Zeilweekend',
			'date_label' => 'Meerdere data · op aanvraag',
			'highlight'  => 'Het hele schip voor jouw groep.',
			'excerpt'   => 'Een weekend het hele schip charteren, met eigen programma en koers.',
			'includes'  => array( 'Schipper en vaste crew', 'Beddengoed en handdoeken', 'Havengelden', 'Brandstof' ),
			'excludes'  => array( 'Maaltijden en dranken', 'Reis naar Enkhuizen' ),
		),
		'de'      => array(
			'title'      => 'Segelwochenende',
			'date_label' => 'Mehrere Termine · auf Anfrage',
			'highlight'  => 'Das ganze Schiff für deine Gruppe.',
			'excerpt'   => 'Ein Wochenende das ganze Schiff chartern, mit eigenem Programm und Kurs.',
			'includes'  => array( 'Skipper und Stammcrew', 'Bettwäsche und Handtücher', 'Hafengebühren', 'Treibstoff' ),
			'excludes'  => array( 'Mahlzeiten und Getränke', 'Anreise nach Enkhuizen' ),
		),
	),
	array(
		'slug'    => 'einsegeln-beurtveer',
		'start'   => '2027-09-25',
		'end'     => '2027-09-26',
		'embark'  => 'hoorn',
		'mode'    => 'per_berth',
		'price'   => '',
		'nl'      => array(
			'title'     => 'Inzeilen voor de Beurtveer',
			'highlight' => 'Voorbereidingstocht voor de Beurtveer.',
			'excerpt'   => 'De voorbereidingstocht: manoeuvres oefenen, de crew op elkaar inspelen, klaar zijn voor de Beurtveer.',
			'includes'  => array( 'Maaltijden aan boord', 'Beddengoed' ),
			'excludes'  => array( 'Reis naar Hoorn', 'Dranken aan de bar' ),
		),
		'de'      => array(
			'title'     => 'Einsegeln für die Beurtveer',
			'highlight' => 'Vorbereitungstörn für die Beurtveer.',
			'excerpt'   => 'Der Vorbereitungstörn: Manöver üben, Crew einspielen, bereit sein für die Beurtveer.',
			'includes'  => array( 'Mahlzeiten an Bord', 'Bettwäsche' ),
			'excludes'  => array( 'Anreise nach Hoorn', 'Getränke an der Bar' ),
		),
	),
	array(
		'slug'    => 'beurtveer',
		'start'   => '2027-10-08',
		'end'     => '2027-10-10',
		'embark'  => 'hoorn',
		'mode'    => 'per_berth',
		'price'   => '',
		'nl'      => array(
			'title'     => 'Beurtveer',
			'highlight' => 'Historische vrachtzeilrace.',
			'excerpt'   => 'Historische vrachtzeilrace: zeilen als honderd jaar geleden, met lading en tactiek.',
			'includes'  => array( 'Maaltijden aan boord', 'Inschrijfgeld regatta' ),
			'excludes'  => array( 'Reis naar Hoorn', 'Dranken aan de bar' ),
		),
		'de'      => array(
			'title'     => 'Beurtveer',
			'highlight' => 'Historische Frachtsegel-Regatta.',
			'excerpt'   => 'Historische Frachtsegel-Regatta: segeln wie vor 100 Jahren, mit Ladung und Taktik.',
			'includes'  => array( 'Mahlzeiten an Bord', 'Regatta-Startgeld' ),
			'excludes'  => array( 'Anreise nach Hoorn', 'Getränke an der Bar' ),
		),
	),
	array(
		'slug'    => 'wadden-trip-past-season',
		'start'   => sprintf( '%d-08-05', $year - 1 ),
		'end'     => sprintf( '%d-08-12', $year - 1 ),
		'embark'  => 'enkhuizen',
		'mode'    => 'per_berth',
		'price'   => 980,
		'price_cabin' => 1560,
		'nl'      => array(
			'title'     => 'Waddentocht (vorig seizoen)',
			'highlight' => 'Afgelopen editie, ter referentie.',
			'excerpt'   => 'Deze tocht is voorbij en dient als voorbeeld voor het archief.',
			'includes'  => array( 'Volledige verzorging aan boord' ),
			'excludes'  => array( 'Reis naar Enkhuizen' ),
		),
		'de'      => array(
			'title'     => 'Wattentour (letzte Saison)',
			'highlight' => 'Vergangene Ausgabe, als Referenz.',
			'excerpt'   => 'Dieser Törn ist vorbei und dient als Beispiel für das Archiv.',
			'includes'  => array( 'Vollverpflegung an Bord' ),
			'excludes'  => array( 'Anreise nach Enkhuizen' ),
		),
	),
);

$created = 0;
$updated = 0;

foreach ( $trips as $trip ) {
	$translations = array();

	foreach ( array( 'nl', 'de' ) as $lang ) {
		$slug    = 'nl' === $lang ? $trip['slug'] : $trip['slug'] . '-' . $lang;
		$content = $trip[ $lang ];

		$existing = get_posts(
			array(
				'post_type'        => 'trip',
				'name'             => $slug,
				'post_status'      => 'any',
				'posts_per_page'   => 1,
				'suppress_filters' => true,
			)
		);

		$postarr = array(
			'post_type'    => 'trip',
			'post_title'   => $content['title'],
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_excerpt' => $content['excerpt'],
			'post_content' => '',
		);

		if ( $existing ) {
			$post_id           = (int) $existing[0]->ID;
			$postarr['ID']     = $post_id;
			wp_update_post( $postarr );
			++$updated;
		} else {
			$post_id = wp_insert_post( $postarr );

			if ( is_wp_error( $post_id ) || ! $post_id ) {
				echo "insert failed: {$slug}\n";
				continue;
			}

			if ( function_exists( 'pll_set_post_language' ) ) {
				pll_set_post_language( $post_id, $lang );
			}

			++$created;
			echo "created trip: {$slug} ({$lang})\n";
		}

		$translations[ $lang ] = $post_id;

		update_field( 'date_start', $trip['start'], $post_id );
		update_field( 'date_end', $trip['end'], $post_id );
		update_field( 'port_embark', $trip['embark'], $post_id );
		update_field( 'booking_mode', $trip['mode'], $post_id );

		// Written unconditionally so re-seeding also clears a value that was removed.
		update_field( 'date_label', isset( $content['date_label'] ) ? $content['date_label'] : '', $post_id );
		update_field( 'price_berth', $trip['price'], $post_id );
		update_field( 'price_cabin', isset( $trip['price_cabin'] ) ? $trip['price_cabin'] : '', $post_id );
		update_field( 'highlight', $content['highlight'], $post_id );

		update_field(
			'includes',
			array_map(
				static function ( $item ) {
					return array( 'item' => $item );
				},
				$content['includes']
			),
			$post_id
		);

		update_field(
			'excludes',
			array_map(
				static function ( $item ) {
					return array( 'item' => $item );
				},
				$content['excludes']
			),
			$post_id
		);
	}

	if ( function_exists( 'pll_save_post_translations' ) && count( $translations ) === 2 ) {
		pll_save_post_translations( $translations );
	}
}

printf( "done: %d created, %d updated\n", $created, $updated );
