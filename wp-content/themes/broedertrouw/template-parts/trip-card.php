<?php
/**
 * Trip card, shared by the upcoming-trips block, archives and related lists.
 *
 * @package broedertrouw
 *
 * @var int    $trip_id Trip post ID.
 * @var string $variant Card layout: 'row' or 'tile'. Default 'row'.
 */

defined( 'ABSPATH' ) || exit;

$trip_id = isset( $args['trip_id'] ) ? (int) $args['trip_id'] : get_the_ID();
$variant = isset( $args['variant'] ) ? $args['variant'] : 'row';

$date_range = bt_trip_date_range( $trip_id );
$highlight  = bt_field( 'highlight', $trip_id );
$price      = bt_trip_price( $trip_id );
$port       = bt_port_label( bt_field( 'port_embark', $trip_id ) );
$meta_parts = array_filter( array( $port, $price ) );
?>
<article class="bt-trip-card bt-trip-card--<?php echo esc_attr( $variant ); ?>">
	<?php if ( 'tile' === $variant && has_post_thumbnail( $trip_id ) ) : ?>
		<a class="bt-trip-card__media" href="<?php echo esc_url( get_permalink( $trip_id ) ); ?>">
			<?php echo get_the_post_thumbnail( $trip_id, 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<?php if ( $date_range ) : ?>
		<p class="bt-trip-card__date"><?php echo esc_html( $date_range ); ?></p>
	<?php endif; ?>

	<div class="bt-trip-card__body">
		<h3 class="bt-trip-card__title">
			<a href="<?php echo esc_url( get_permalink( $trip_id ) ); ?>">
				<?php echo esc_html( get_the_title( $trip_id ) ); ?>
			</a>
		</h3>

		<?php if ( $highlight ) : ?>
			<p class="bt-trip-card__highlight"><?php echo esc_html( $highlight ); ?></p>
		<?php endif; ?>

		<?php if ( $meta_parts ) : ?>
			<p class="bt-trip-card__meta"><?php echo esc_html( implode( ' · ', $meta_parts ) ); ?></p>
		<?php endif; ?>
	</div>

	<div class="bt-trip-card__aside">
		<a class="bt-button bt-button--outline-navy" href="<?php echo esc_url( get_permalink( $trip_id ) ); ?>">
			<?php esc_html_e( 'View trip', 'broedertrouw' ); ?>
		</a>
	</div>
</article>
