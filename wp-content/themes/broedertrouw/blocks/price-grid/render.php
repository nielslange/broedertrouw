<?php
/**
 * Price grid block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$style   = get_field( 'style' );
$style   = 'cards' === $style ? 'cards' : 'boxed';
$items   = get_field( 'items' );

if ( ! $items ) {
	bt_block_placeholder( $block, __( 'Price grid: add at least one item.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );

/*
 * Boxed keeps the heading inside one bordered box and prints the title first;
 * cards puts the heading above separate bordered cards that lead with the price.
 */
$is_cards = 'cards' === $style;
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-price-grid' ) ); ?> bt-price-grid--<?php echo esc_attr( $style ); ?>">

	<div class="bt-price-grid__inner">
		<?php if ( $heading && $is_cards ) : ?>
			<h3 class="bt-price-grid__heading"><?php echo esc_html( $heading ); ?></h3>
		<?php endif; ?>

		<div class="bt-price-grid__box">
			<?php if ( $heading && ! $is_cards ) : ?>
				<h3 class="bt-price-grid__heading"><?php echo esc_html( $heading ); ?></h3>
			<?php endif; ?>

			<div class="bt-price-grid__items">
				<?php foreach ( $items as $item ) : ?>
					<div class="bt-price-grid__item">
						<?php if ( $is_cards ) : ?>
							<?php if ( ! empty( $item['price'] ) ) : ?>
								<p class="bt-price-grid__price"><?php echo esc_html( $item['price'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $item['title'] ) ) : ?>
								<p class="bt-price-grid__text"><?php echo esc_html( $item['title'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $item['sub'] ) ) : ?>
								<p class="bt-price-grid__sub"><?php echo esc_html( $item['sub'] ); ?></p>
							<?php endif; ?>
						<?php else : ?>
							<?php if ( ! empty( $item['title'] ) ) : ?>
								<p class="bt-price-grid__title"><?php echo esc_html( $item['title'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $item['sub'] ) ) : ?>
								<p class="bt-price-grid__sub"><?php echo esc_html( $item['sub'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $item['price'] ) ) : ?>
								<p class="bt-price-grid__price"><?php echo esc_html( $item['price'] ); ?></p>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
