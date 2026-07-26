<?php
/**
 * Contact cards block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$items = get_field( 'items' );

if ( ! $items ) {
	bt_block_placeholder( $block, __( 'Contact cards: add at least one card.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-contact-cards' ) ); ?>">

	<div class="bt-contact-cards__inner">
		<?php foreach ( $items as $item ) : ?>
			<div class="bt-contact-card">
				<?php if ( ! empty( $item['label'] ) ) : ?>
					<p class="bt-kicker"><?php echo esc_html( $item['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $item['value'] ) ) : ?>
					<?php if ( ! empty( $item['url'] ) ) : ?>
						<a class="bt-contact-card__value" href="<?php echo esc_url( $item['url'] ); ?>">
							<?php echo esc_html( $item['value'] ); ?>
						</a>
					<?php else : ?>
						<p class="bt-contact-card__value"><?php echo esc_html( $item['value'] ); ?></p>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( ! empty( $item['text'] ) ) : ?>
					<p class="bt-contact-card__text"><?php echo esc_html( $item['text'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
