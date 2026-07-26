<?php
/**
 * Stats band block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$items = get_field( 'items' );

if ( ! $items ) {
	bt_block_placeholder( $block, __( 'Stats band: add at least two values.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-stats' ) ); ?>">

	<div class="bt-stats__inner">
		<?php foreach ( $items as $item ) : ?>
			<?php if ( empty( $item['value'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>

			<div class="bt-stats__item">
				<p class="bt-stats__value"><?php echo esc_html( $item['value'] ); ?></p>

				<?php if ( ! empty( $item['label'] ) ) : ?>
					<p class="bt-stats__label"><?php echo esc_html( $item['label'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
