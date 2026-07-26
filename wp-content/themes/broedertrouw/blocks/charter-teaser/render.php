<?php
/**
 * Charter teaser block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$cards = array(
	'berth' => get_field( 'card_berth' ),
	'boat'  => get_field( 'card_boat' ),
);

$has_content = false;

foreach ( $cards as $card ) {
	if ( ! empty( $card['heading'] ) ) {
		$has_content = true;
		break;
	}
}

if ( ! $has_content ) {
	bt_block_placeholder( $block, __( 'Charter teaser: add a heading to at least one card.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-charter' ) ); ?>">

	<div class="bt-charter__inner">
		<?php foreach ( $cards as $key => $card ) : ?>
			<?php if ( empty( $card['heading'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>

			<article class="bt-charter__card bt-charter__card--<?php echo esc_attr( $key ); ?>">
				<?php if ( ! empty( $card['image']['url'] ) ) : ?>
					<img
						class="bt-charter__image"
						src="<?php echo esc_url( $card['image']['sizes']['medium_large'] ?? $card['image']['url'] ); ?>"
						alt="<?php echo esc_attr( $card['image']['alt'] ); ?>"
						loading="lazy"
						decoding="async" />
				<?php endif; ?>

				<div class="bt-charter__body">
					<?php if ( ! empty( $card['kicker'] ) ) : ?>
						<p class="bt-kicker"><?php echo esc_html( $card['kicker'] ); ?></p>
					<?php endif; ?>

					<h2 class="bt-charter__heading"><?php echo esc_html( $card['heading'] ); ?></h2>

					<?php if ( ! empty( $card['text'] ) ) : ?>
						<p class="bt-charter__text"><?php echo esc_html( $card['text'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $card['link']['url'] ) ) : ?>
						<a class="bt-arrow-link" href="<?php echo esc_url( $card['link']['url'] ); ?>">
							<?php echo esc_html( $card['link']['title'] ); ?>
						</a>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
