<?php
/**
 * USP grid block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$items   = get_field( 'items' );

if ( ! $items ) {
	bt_block_placeholder( $block, __( 'USP grid: add at least two items.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-usp' ) ); ?>">

	<div class="bt-usp__inner">
		<?php if ( $heading ) : ?>
			<h2 class="bt-section-head__title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<div class="bt-usp__grid" style="--bt-usp-columns: <?php echo esc_attr( count( $items ) ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<?php if ( empty( $item['title'] ) && empty( $item['text'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>

				<div class="bt-usp__item">
					<?php if ( ! empty( $item['icon']['url'] ) ) : ?>
						<img
							class="bt-usp__icon"
							src="<?php echo esc_url( $item['icon']['url'] ); ?>"
							alt="<?php echo esc_attr( $item['icon']['alt'] ); ?>"
							loading="lazy"
							decoding="async" />
					<?php endif; ?>

					<?php if ( ! empty( $item['title'] ) ) : ?>
						<h3 class="bt-usp__title"><?php echo esc_html( $item['title'] ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $item['text'] ) ) : ?>
						<p class="bt-usp__text"><?php echo esc_html( $item['text'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
