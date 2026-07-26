<?php
/**
 * Gallery teaser block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$images  = get_field( 'images' );
$link    = get_field( 'link' );

if ( ! $images ) {
	bt_block_placeholder( $block, __( 'Gallery teaser: choose at least three images.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-gallery' ) ); ?>">

	<div class="bt-gallery__inner">
		<?php if ( $heading || ( $link && ! empty( $link['url'] ) ) ) : ?>
			<div class="bt-section-head">
				<?php if ( $heading ) : ?>
					<h2 class="bt-section-head__title"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $link && ! empty( $link['url'] ) ) : ?>
					<a class="bt-arrow-link" href="<?php echo esc_url( $link['url'] ); ?>">
						<?php echo esc_html( $link['title'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="bt-gallery__grid">
			<?php foreach ( $images as $image ) : ?>
				<figure class="bt-gallery__item">
					<img
						src="<?php echo esc_url( $image['sizes']['medium_large'] ?? $image['url'] ); ?>"
						alt="<?php echo esc_attr( $image['alt'] ); ?>"
						loading="lazy"
						decoding="async" />
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
