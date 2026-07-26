<?php
/**
 * Trip card, shared by the upcoming-trips block, archives and related lists.
 *
 * @package broedertrouw
 *
 * @var int    $trip_id   Trip post ID.
 * @var string $variant   Card layout: 'row', 'tile' or 'card'. Default 'row'.
 * @var string $cta_label Button label. Defaults to "View trip".
 */

defined( 'ABSPATH' ) || exit;

$trip_id   = isset( $args['trip_id'] ) ? (int) $args['trip_id'] : get_the_ID();
$variant   = isset( $args['variant'] ) ? $args['variant'] : 'row';
$cta_label = isset( $args['cta_label'] ) && $args['cta_label'] ? $args['cta_label'] : __( 'View trip', 'broedertrouw' );

$date_range = bt_trip_date_range( $trip_id );
$permalink  = get_permalink( $trip_id );

/*
 * The card variant follows the design: a blue date line, the excerpt as body
 * copy and the price pinned to the bottom. The row variant keeps the compact
 * dot-separated meta line.
 */
if ( 'card' === $variant ) {
	$price   = bt_trip_price_amount( $trip_id );
	$is_from = (bool) bt_field( 'price_berth', $trip_id );
	$excerpt = get_the_excerpt( $trip_id );
	?>
	<article class="bt-trip-card bt-trip-card--card">
		<?php if ( $date_range ) : ?>
			<p class="bt-trip-card__date"><?php echo esc_html( $date_range ); ?></p>
		<?php endif; ?>

		<h3 class="bt-trip-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( get_the_title( $trip_id ) ); ?></a>
		</h3>

		<?php if ( $excerpt ) : ?>
			<p class="bt-trip-card__text"><?php echo esc_html( $excerpt ); ?></p>
		<?php endif; ?>

		<div class="bt-trip-card__price">
			<p class="bt-trip-card__amount">
				<span class="bt-trip-card__amount-value"><?php echo esc_html( $price ); ?></span>
				<?php if ( $is_from ) : ?>
					<span class="bt-trip-card__amount-suffix"><?php esc_html_e( 'p. p.', 'broedertrouw' ); ?></span>
				<?php endif; ?>
			</p>
			<p class="bt-trip-card__cabin"><?php echo esc_html( bt_trip_cabin_note( $trip_id ) ); ?></p>
		</div>

		<?php
		/*
		 * The button says "request this trip", so it goes to the enquiry form
		 * on the same page and carries the trip with it. bt/enquiry reads
		 * these data attributes and fills the form in, so the visitor does not
		 * retype what they just clicked on.
		 */
		?>
		<a
			class="bt-button bt-button--outline-navy bt-trip-card__cta"
			href="#enquiry"
			data-bt-trip="<?php echo esc_attr( get_the_title( $trip_id ) ); ?>"
			data-bt-period="<?php echo esc_attr( $date_range ); ?>">
			<?php echo esc_html( $cta_label ); ?>
		</a>
	</article>
	<?php
	return;
}

$highlight  = bt_field( 'highlight', $trip_id );
$price      = bt_trip_price( $trip_id );
$port       = bt_port_label( bt_field( 'port_embark', $trip_id ) );
// The highlight is authored as a sentence; drop the full stop so it reads as
// the first item of the dot-separated meta line.
$meta_parts = array_filter( array( rtrim( (string) $highlight, '.' ), $port, $price ) );
?>
<article class="bt-trip-card bt-trip-card--<?php echo esc_attr( $variant ); ?>">
	<?php if ( 'tile' === $variant && has_post_thumbnail( $trip_id ) ) : ?>
		<a class="bt-trip-card__media" href="<?php echo esc_url( $permalink ); ?>">
			<?php echo get_the_post_thumbnail( $trip_id, 'medium_large', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<?php if ( $date_range ) : ?>
		<p class="bt-trip-card__date"><?php echo esc_html( $date_range ); ?></p>
	<?php endif; ?>

	<div class="bt-trip-card__body">
		<h3 class="bt-trip-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>">
				<?php echo esc_html( get_the_title( $trip_id ) ); ?>
			</a>
		</h3>

		<?php if ( $meta_parts ) : ?>
			<p class="bt-trip-card__meta"><?php echo esc_html( implode( ' · ', $meta_parts ) ); ?></p>
		<?php endif; ?>
	</div>

	<div class="bt-trip-card__aside">
		<a class="bt-button bt-button--outline-navy" href="<?php echo esc_url( $permalink ); ?>">
			<?php echo esc_html( $cta_label ); ?>
		</a>
	</div>
</article>
