<?php
/**
 * Availability calendar block template.
 *
 * Rows are the trip posts themselves, grouped by the month they start in, so
 * dates, prices and availability are maintained once under Trips and never
 * retyped per language.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$show_legend  = get_field( 'show_legend' );
$show_past    = get_field( 'show_past' );
$trips        = bt_get_calendar_trips( (bool) $show_past );

if ( ! $trips ) {
	bt_block_placeholder( $block, __( 'Calendar: no trips are published yet.', 'broedertrouw' ) );
	return;
}

$statuses = bt_trip_statuses();

// Trips arrive sorted by start date, so grouping preserves chronology.
$months = array();

foreach ( $trips as $trip ) {
	$months[ bt_trip_month( $trip->ID ) ][] = $trip;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-calendar' ) ); ?>">

	<div class="bt-calendar__inner">
		<?php if ( $show_legend ) : ?>
			<ul class="bt-calendar__legend">
				<?php foreach ( $statuses as $key => $label ) : ?>
					<li class="bt-calendar__legend-item">
						<span class="bt-calendar__swatch bt-calendar__swatch--<?php echo esc_attr( $key ); ?>" aria-hidden="true"></span>
						<?php echo esc_html( $label ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php foreach ( $months as $month => $month_trips ) : ?>
			<div class="bt-calendar__month">
				<?php if ( $month ) : ?>
					<h2 class="bt-calendar__month-title"><?php echo esc_html( $month ); ?></h2>
				<?php endif; ?>

				<div class="bt-calendar__rows">
					<?php
					foreach ( $month_trips as $trip ) :
						$status = bt_trip_status( $trip->ID );
						$period = bt_trip_date_range( $trip->ID );
						$title  = get_the_title( $trip );

						/*
						 * The highlight is the one-line description the trip
						 * already carries; the price repeats what its card
						 * shows. Booked and on-request rows have nothing to
						 * book, so they print the description only.
						 */
						$details = array_filter(
							array(
								bt_field( 'highlight', $trip->ID ),
								'booked' === $status ? '' : bt_trip_price_amount( $trip->ID ),
							)
						);
						?>
						<div class="bt-calendar__row bt-calendar__row--<?php echo esc_attr( $status ); ?>">
							<p class="bt-calendar__period"><?php echo esc_html( $period ); ?></p>

							<p class="bt-calendar__status">
								<span class="bt-calendar__pill bt-calendar__pill--<?php echo esc_attr( $status ); ?>">
									<?php echo esc_html( $statuses[ $status ] ); ?>
								</span>
							</p>

							<p class="bt-calendar__text">
								<a class="bt-calendar__link" href="<?php echo esc_url( get_permalink( $trip ) ); ?>"><?php echo esc_html( $title ); ?></a>
								<?php if ( $details ) : ?>
									<span class="bt-calendar__meta"><?php echo esc_html( ' · ' . implode( ' · ', $details ) ); ?></span>
								<?php endif; ?>
							</p>

							<?php if ( 'booked' === $status ) : ?>
								<span class="bt-calendar__cta-spacer" aria-hidden="true"></span>
							<?php else : ?>
								<a
									class="bt-button <?php echo 'open' === $status ? 'bt-button--navy' : 'bt-button--outline-navy'; ?> bt-calendar__cta"
									href="<?php echo esc_url( bt_enquiry_url() ); ?>"
									<?php
									echo bt_enquiry_attrs( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										array(
											'trip'       => $title,
											'period'     => $period,
											'individual' => true,
										)
									);
									?>
									>
									<?php echo esc_html__( 'Request trip', 'broedertrouw' ); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
