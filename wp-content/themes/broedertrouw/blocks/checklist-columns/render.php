<?php
/**
 * Checklist columns block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$columns = get_field( 'columns' );

if ( ! $columns ) {
	bt_block_placeholder( $block, __( 'Checklist columns: add at least one column.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-checklist-columns' ) ); ?>">

	<div class="bt-checklist-columns__inner">
		<?php foreach ( $columns as $column ) : ?>
			<div class="bt-checklist-columns__column">
				<?php if ( ! empty( $column['heading'] ) ) : ?>
					<h3 class="bt-checklist-columns__heading"><?php echo esc_html( $column['heading'] ); ?></h3>
				<?php endif; ?>

				<?php if ( ! empty( $column['items'] ) ) : ?>
					<ul class="bt-checklist bt-checklist--<?php echo esc_attr( 'plus' === $column['marker'] ? 'plus' : 'check' ); ?>">
						<?php foreach ( $column['items'] as $item ) : ?>
							<?php if ( ! empty( $item['item'] ) ) : ?>
								<li><?php echo esc_html( $item['item'] ); ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
