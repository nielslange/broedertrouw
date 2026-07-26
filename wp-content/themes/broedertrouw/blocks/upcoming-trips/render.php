<?php
/**
 * Upcoming trips block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading   = get_field( 'heading' );
$count     = (int) get_field( 'count' );
$layout    = get_field( 'layout' );
$layout    = 'cards' === $layout ? 'cards' : 'rows';
$intro     = get_field( 'intro' );
$cta_label = get_field( 'cta_label' );
$type      = (int) get_field( 'trip_type' );

/*
 * Trips are always queried live: sorted by start date and filtered to those
 * that have not ended yet, so a past trip drops off the page by itself.
 */
$trips = bt_get_trips(
	array(
		'when'  => 'upcoming',
		'count' => $count > 0 ? $count : 3,
		'type'  => $type,
	)
);

if ( ! $trips ) {
	bt_block_placeholder( $block, __( 'Upcoming trips: no published trips with a future end date yet.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-upcoming' ) ); ?> bt-upcoming--<?php echo esc_attr( $layout ); ?>">

	<div class="bt-upcoming__inner">
		<?php if ( $heading ) : ?>
			<div class="bt-section-head">
				<h2 class="bt-section-head__title"><?php echo esc_html( $heading ); ?></h2>
			</div>
		<?php endif; ?>

		<?php if ( $intro ) : ?>
			<p class="bt-upcoming__intro"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>

		<div class="bt-upcoming__list">
			<?php
			foreach ( $trips as $trip ) {
				bt_template_part(
					'template-parts/trip-card',
					array(
						'trip_id'   => $trip->ID,
						'variant'   => 'cards' === $layout ? 'card' : 'row',
						'cta_label' => $cta_label,
					)
				);
			}
			?>
		</div>

	</div>
</section>
