<?php
/**
 * Upcoming trips block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading  = get_field( 'heading' );
$count    = (int) get_field( 'count' );
$link     = get_field( 'link' );
$override = get_field( 'manual_override' );

$trips = bt_get_trips(
	array(
		'when'    => 'upcoming',
		'count'   => $count > 0 ? $count : 3,
		'include' => $override ? array_map( 'absint', (array) $override ) : array(),
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
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-upcoming' ) ); ?>">

	<div class="bt-upcoming__inner">
		<?php if ( $heading || ( $link && ! empty( $link['url'] ) ) ) : ?>
			<div class="bt-section-head">
				<?php if ( $heading ) : ?>
					<h2 class="bt-section-head__title"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $link && ! empty( $link['url'] ) ) : ?>
					<a class="bt-arrow-link" href="<?php echo esc_url( $link['url'] ); ?>">
						<?php echo esc_html( $link['title'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="bt-upcoming__list">
			<?php
			foreach ( $trips as $trip ) {
				bt_template_part(
					'template-parts/trip-card',
					array(
						'trip_id' => $trip->ID,
						'variant' => 'row',
					)
				);
			}
			?>
		</div>
	</div>
</section>
