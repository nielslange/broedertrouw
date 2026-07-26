<?php
/**
 * Availability calendar block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$show_legend = get_field( 'show_legend' );
$rows        = get_field( 'rows' );

if ( ! $rows ) {
	bt_block_placeholder( $block, __( 'Calendar: add at least one row.', 'broedertrouw' ) );
	return;
}

$statuses = array(
	'request' => __( 'On request', 'broedertrouw' ),
	'booked'  => __( 'Fully booked', 'broedertrouw' ),
	'open'    => __( 'Open trip', 'broedertrouw' ),
);

// Rows arrive flat and are grouped by their month label, preserving order.
$months = array();

foreach ( $rows as $row ) {
	$key = isset( $row['month'] ) ? $row['month'] : '';

	if ( ! isset( $months[ $key ] ) ) {
		$months[ $key ] = array();
	}

	$months[ $key ][] = $row;
}

$anchor = bt_block_anchor( $block );

// Authors mark the trip name inside the row text, so allow inline emphasis only.
$allowed = array(
	'strong' => array(),
	'em'     => array(),
);
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-calendar' ) ); ?>">

	<div class="bt-calendar__inner">
		<?php if ( $show_legend ) : ?>
			<ul class="bt-calendar__legend">
				<?php foreach ( array( 'request', 'booked', 'open' ) as $key ) : ?>
					<li class="bt-calendar__legend-item">
						<span class="bt-calendar__swatch bt-calendar__swatch--<?php echo esc_attr( $key ); ?>" aria-hidden="true"></span>
						<?php echo esc_html( $statuses[ $key ] ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php foreach ( $months as $month => $month_rows ) : ?>
			<div class="bt-calendar__month">
				<?php if ( $month ) : ?>
					<h2 class="bt-calendar__month-title"><?php echo esc_html( $month ); ?></h2>
				<?php endif; ?>

				<div class="bt-calendar__rows">
					<?php
					foreach ( $month_rows as $row ) :
						$status = isset( $row['status'] ) && isset( $statuses[ $row['status'] ] ) ? $row['status'] : 'request';
						$link   = ! empty( $row['link'] ) && ! empty( $row['link']['url'] ) ? $row['link'] : null;
						?>
						<div class="bt-calendar__row bt-calendar__row--<?php echo esc_attr( $status ); ?>">
							<p class="bt-calendar__period"><?php echo esc_html( $row['period'] ); ?></p>

							<p class="bt-calendar__status">
								<span class="bt-calendar__pill bt-calendar__pill--<?php echo esc_attr( $status ); ?>">
									<?php echo esc_html( $statuses[ $status ] ); ?>
								</span>
							</p>

							<p class="bt-calendar__text"><?php echo wp_kses( $row['text'], $allowed ); ?></p>

							<?php
							if ( $link ) :
								/*
								 * Open rows name the trip in bold at the start of
								 * their text, so the button can carry it into the
								 * form along with the row's own period.
								 */
								$trip = '';

								if ( preg_match( '/<strong>(.*?)<\/strong>/', (string) $row['text'], $match ) ) {
									$trip = wp_strip_all_tags( $match[1] );
								}
								?>
								<a
									class="bt-button <?php echo 'open' === $status ? 'bt-button--navy' : 'bt-button--outline-navy'; ?> bt-calendar__cta"
									href="<?php echo esc_url( $link['url'] ); ?>"
									<?php
									echo bt_is_enquiry_url( $link['url'] ) ? bt_enquiry_attrs( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										array(
											'trip'   => $trip,
											'period' => $row['period'],
										)
									) : '';
									?>
									>
									<?php echo esc_html( $link['title'] ); ?>
								</a>
							<?php else : ?>
								<span class="bt-calendar__cta-spacer" aria-hidden="true"></span>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
