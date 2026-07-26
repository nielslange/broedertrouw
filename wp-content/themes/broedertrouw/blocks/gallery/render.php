<?php
/**
 * Filterable gallery block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$categories = get_field( 'categories' );

if ( ! $categories ) {
	bt_block_placeholder( $block, __( 'Gallery: add at least one category with images.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
$all    = __( 'All', 'broedertrouw' );

// Category slugs drive the chip filter; the "all" chip is added automatically.
$chips = array( 'all' => $all );

foreach ( $categories as $index => $category ) {
	if ( ! empty( $category['label'] ) ) {
		$chips[ 'cat-' . $index ] = $category['label'];
	}
}
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-gallery' ) ); ?>"
	data-bt-gallery>

	<div class="bt-gallery__inner">
		<div class="bt-gallery__chips" role="group" aria-label="<?php esc_attr_e( 'Filter photos by category', 'broedertrouw' ); ?>">
			<?php foreach ( $chips as $slug => $label ) : ?>
				<button
					class="bt-gallery__chip"
					type="button"
					data-bt-chip="<?php echo esc_attr( $slug ); ?>"
					aria-pressed="<?php echo 'all' === $slug ? 'true' : 'false'; ?>">
					<?php echo esc_html( $label ); ?>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="bt-gallery__grid">
			<?php
			foreach ( $categories as $index => $category ) :
				if ( empty( $category['images'] ) ) {
					continue;
				}

				foreach ( $category['images'] as $image ) :
					$alt = $image['alt'] ? $image['alt'] : $category['label'];
					?>
					<figure class="bt-gallery__figure" data-bt-cat="cat-<?php echo esc_attr( $index ); ?>">
						<img
							src="<?php echo esc_url( $image['sizes']['large'] ?? $image['url'] ); ?>"
							alt="<?php echo esc_attr( $alt ); ?>"
							width="<?php echo esc_attr( $image['sizes']['large-width'] ?? $image['width'] ); ?>"
							height="<?php echo esc_attr( $image['sizes']['large-height'] ?? $image['height'] ); ?>"
							loading="lazy"
							decoding="async">
					</figure>
					<?php
				endforeach;
			endforeach;
			?>
		</div>
	</div>
</section>
