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
$link      = get_field( 'link' );
$override  = get_field( 'manual_override' );
$layout    = get_field( 'layout' );
$layout    = 'cards' === $layout ? 'cards' : 'rows';
$intro     = get_field( 'intro' );
$cta_label = get_field( 'cta_label' );

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
$has_link = $link && ! empty( $link['url'] );

// The cards layout puts the link centered below the grid, so the heading row
// only carries it in the rows layout.
$link_in_head = $has_link && 'cards' !== $layout;
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-upcoming' ) ); ?> bt-upcoming--<?php echo esc_attr( $layout ); ?>">

	<div class="bt-upcoming__inner">
		<?php if ( $heading || $link_in_head ) : ?>
			<div class="bt-section-head">
				<?php if ( $heading ) : ?>
					<h2 class="bt-section-head__title"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $link_in_head ) : ?>
					<a class="bt-arrow-link" href="<?php echo esc_url( $link['url'] ); ?>">
						<?php echo esc_html( $link['title'] ); ?>
					</a>
				<?php endif; ?>
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

		<?php if ( $has_link && 'cards' === $layout ) : ?>
			<p class="bt-upcoming__more">
				<a class="bt-arrow-link" href="<?php echo esc_url( $link['url'] ); ?>">
					<?php echo esc_html( $link['title'] ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>
