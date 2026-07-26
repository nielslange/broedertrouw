<?php
/**
 * Front-end output for the trip post type.
 *
 * Content is injected through Blocksy's documented container hooks rather than
 * template overrides, so the parent theme keeps ownership of the page shell,
 * hero and sidebar logic.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs the summary card above a single trip's content.
 */
function bt_trip_summary_card() {
	if ( ! is_singular( 'trip' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$rows    = array();

	$date_range = bt_trip_date_range( $post_id );

	if ( $date_range ) {
		$rows[] = array(
			'label' => __( 'Dates', 'broedertrouw' ),
			'value' => $date_range,
		);
	}

	$embark = bt_port_label( bt_field( 'port_embark', $post_id ) );

	if ( $embark ) {
		$rows[] = array(
			'label' => __( 'Embarkation', 'broedertrouw' ),
			'value' => $embark,
		);
	}

	$disembark = bt_port_label( bt_field( 'port_disembark', $post_id ) );

	if ( $disembark && $disembark !== $embark ) {
		$rows[] = array(
			'label' => __( 'Disembarkation', 'broedertrouw' ),
			'value' => $disembark,
		);
	}

	$price = bt_trip_price( $post_id );

	if ( $price ) {
		$rows[] = array(
			'label' => __( 'Price', 'broedertrouw' ),
			'value' => $price,
		);
	}

	$cabin_price = bt_trip_cabin_price( $post_id );

	if ( $cabin_price ) {
		$rows[] = array(
			'label' => __( 'Private cabin', 'broedertrouw' ),
			'value' => $cabin_price,
		);
	}

	if ( ! $rows ) {
		return;
	}

	$highlight = bt_field( 'highlight', $post_id );
	?>
	<div class="bt-trip-summary">
		<?php foreach ( $rows as $row ) : ?>
			<div class="bt-trip-summary__cell">
				<p class="bt-trip-summary__label"><?php echo esc_html( $row['label'] ); ?></p>
				<p class="bt-trip-summary__value"><?php echo esc_html( $row['value'] ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( $highlight ) : ?>
		<p class="bt-kicker"><?php echo esc_html( $highlight ); ?></p>
	<?php endif; ?>
	<?php
}
add_action( 'blocksy:single:container:top', 'bt_trip_summary_card' );

/**
 * Outputs includes/excludes lists, the gallery, a CTA and related trips.
 */
function bt_trip_details() {
	if ( ! is_singular( 'trip' ) ) {
		return;
	}

	$post_id  = get_the_ID();
	$includes = bt_field( 'includes', $post_id );
	$excludes = bt_field( 'excludes', $post_id );
	$gallery  = bt_field( 'gallery', $post_id );

	if ( $includes || $excludes ) :
		?>
		<div class="bt-trip-lists">
			<?php if ( $includes ) : ?>
				<div>
					<h2><?php esc_html_e( 'Included', 'broedertrouw' ); ?></h2>
					<ul class="bt-checklist">
						<?php foreach ( $includes as $row ) : ?>
							<?php if ( empty( $row['item'] ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<li><?php echo esc_html( $row['item'] ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $excludes ) : ?>
				<div>
					<h2><?php esc_html_e( 'Not included', 'broedertrouw' ); ?></h2>
					<ul class="bt-checklist">
						<?php foreach ( $excludes as $row ) : ?>
							<?php if ( empty( $row['item'] ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<li><?php echo esc_html( $row['item'] ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
		<?php
	endif;

	if ( $gallery ) :
		?>
		<div class="bt-trip-gallery">
			<?php foreach ( $gallery as $image ) : ?>
				<figure>
					<img
						src="<?php echo esc_url( $image['sizes']['medium_large'] ?? $image['url'] ); ?>"
						alt="<?php echo esc_attr( $image['alt'] ); ?>"
						loading="lazy"
						decoding="async" />
				</figure>
			<?php endforeach; ?>
		</div>
		<?php
	endif;

	$contact = bt_contact_page_url();

	if ( $contact ) :
		$enquiry_url = add_query_arg( 'trip', $post_id, $contact ) . bt_enquiry_url();
		?>
		<p>
			<a class="bt-button bt-button--navy" href="<?php echo esc_url( $enquiry_url ); ?>">
				<?php esc_html_e( 'Request this trip', 'broedertrouw' ); ?>
			</a>
		</p>
		<?php
	endif;

	$related = bt_get_trips(
		array(
			'when'    => 'upcoming',
			'count'   => 3,
			'exclude' => array( $post_id ),
		)
	);

	if ( $related ) :
		?>
		<section class="bt-related-trips">
			<h2 class="bt-section-head__title"><?php esc_html_e( 'More trips', 'broedertrouw' ); ?></h2>

			<div class="bt-trip-grid">
				<?php
				foreach ( $related as $trip ) {
					bt_template_part(
						'template-parts/trip-card',
						array(
							'trip_id' => $trip->ID,
							'variant' => 'tile',
						)
					);
				}
				?>
			</div>
		</section>
		<?php
	endif;
}
add_action( 'blocksy:single:container:bottom', 'bt_trip_details' );

/**
 * Returns the contact page URL in the current language.
 *
 * @return string
 */
function bt_contact_page_url() {
	$slugs = array( 'contact', 'kontakt' );

	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );

		if ( ! $page ) {
			continue;
		}

		$page_id = function_exists( 'pll_get_post' ) ? pll_get_post( $page->ID ) : $page->ID;

		if ( $page_id ) {
			return (string) get_permalink( $page_id );
		}
	}

	return '';
}

/**
 * Renders the trip archive: upcoming grid plus a collapsed past section.
 */
function bt_trip_archive_content() {
	if ( ! is_post_type_archive( 'trip' ) ) {
		return;
	}

	$upcoming = bt_get_trips(
		array(
			'when'  => 'upcoming',
			'count' => -1,
		)
	);

	$past = bt_get_trips(
		array(
			'when'  => 'past',
			'count' => -1,
		)
	);
	?>
	<?php if ( $upcoming ) : ?>
		<div class="bt-trip-grid">
			<?php
			foreach ( $upcoming as $trip ) {
				bt_template_part(
					'template-parts/trip-card',
					array(
						'trip_id' => $trip->ID,
						'variant' => 'tile',
					)
				);
			}
			?>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( 'No upcoming trips are scheduled at the moment.', 'broedertrouw' ); ?></p>
	<?php endif; ?>

	<?php if ( $past ) : ?>
		<details class="bt-past-trips">
			<summary><?php esc_html_e( 'Past trips', 'broedertrouw' ); ?></summary>

			<div class="bt-trip-grid">
				<?php
				foreach ( $past as $trip ) {
					bt_template_part(
						'template-parts/trip-card',
						array(
							'trip_id' => $trip->ID,
							'variant' => 'tile',
						)
					);
				}
				?>
			</div>
		</details>
	<?php endif; ?>
	<?php
}

/**
 * Replaces the archive canvas with the trip archive output.
 *
 * Blocksy's hero and container helpers are reused so the page shell matches
 * every other archive on the site.
 *
 * @param string|null $output Existing custom output.
 * @return string|null
 */
function bt_trip_archive_canvas( $output ) {
	if ( ! is_post_type_archive( 'trip' ) ) {
		return $output;
	}

	ob_start();

	if ( function_exists( 'blocksy_output_hero_section' ) ) {
		echo blocksy_output_hero_section( array( 'type' => 'type-2' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Blocksy escapes its own output.
	}

	$spacing = function_exists( 'blocksy_get_v_spacing' ) ? blocksy_get_v_spacing() : '';

	printf( '<div class="ct-container" %s>', $spacing ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Blocksy returns a prepared attribute string.
	echo '<section>';
	bt_trip_archive_content();
	echo '</section></div>';

	return (string) ob_get_clean();
}
add_filter( 'blocksy:posts-listing:canvas:custom-output', 'bt_trip_archive_canvas' );
