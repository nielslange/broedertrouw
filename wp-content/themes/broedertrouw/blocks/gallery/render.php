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
		<?php
		/*
		 * With a single category there is nothing to filter, so the chip bar
		 * would only offer "All" and that same category.
		 */
		if ( count( $chips ) > 2 ) :
			?>
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
		<?php endif; ?>

		<div class="bt-gallery__grid">
			<?php
			foreach ( $categories as $index => $category ) :
				if ( empty( $category['images'] ) ) {
					continue;
				}

				foreach ( $category['images'] as $image ) :
					$alt = $image['alt'] ? $image['alt'] : $category['label'];

					/*
					 * The grid shows a medium thumbnail; the full size is only
					 * fetched when the lightbox opens, so four columns of
					 * photos stay cheap to load.
					 */
					$thumb = $image['sizes']['medium_large'] ?? $image['sizes']['large'] ?? $image['url'];
					$full  = $image['sizes']['large'] ?? $image['url'];
					?>
					<figure class="bt-gallery__figure" data-bt-cat="cat-<?php echo esc_attr( $index ); ?>">
						<button
							class="bt-gallery__open"
							type="button"
							data-bt-full="<?php echo esc_url( $full ); ?>"
							data-bt-alt="<?php echo esc_attr( $alt ); ?>">
							<img
								src="<?php echo esc_url( $thumb ); ?>"
								alt="<?php echo esc_attr( $alt ); ?>"
								width="<?php echo esc_attr( $image['sizes']['medium_large-width'] ?? $image['width'] ); ?>"
								height="<?php echo esc_attr( $image['sizes']['medium_large-height'] ?? $image['height'] ); ?>"
								loading="lazy"
								decoding="async">
						</button>
					</figure>
					<?php
				endforeach;
			endforeach;
			?>
		</div>
	</div>

	<?php
	/*
	 * A native <dialog> gives the modal behaviour, focus trapping and Escape
	 * handling for free, so the script only has to swap the image and step
	 * through the visible figures.
	 */
	?>
	<dialog class="bt-lightbox" data-bt-lightbox aria-label="<?php esc_attr_e( 'Photo viewer', 'broedertrouw' ); ?>">
		<button class="bt-lightbox__close" type="button" data-bt-close aria-label="<?php esc_attr_e( 'Close', 'broedertrouw' ); ?>">
			<?php echo bt_icon( 'close', array( 'size' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>

		<button class="bt-lightbox__nav bt-lightbox__nav--prev" type="button" data-bt-prev aria-label="<?php esc_attr_e( 'Previous photo', 'broedertrouw' ); ?>">
			<?php echo bt_icon( 'chevron-left', array( 'size' => 26 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>

		<figure class="bt-lightbox__figure">
			<img class="bt-lightbox__image" src="" alt="" decoding="async">
		</figure>

		<button class="bt-lightbox__nav bt-lightbox__nav--next" type="button" data-bt-next aria-label="<?php esc_attr_e( 'Next photo', 'broedertrouw' ); ?>">
			<?php echo bt_icon( 'chevron-right', array( 'size' => 26 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>

		<p class="bt-lightbox__count" data-bt-count aria-live="polite"></p>
	</dialog>
</section>
