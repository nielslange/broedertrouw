<?php
/**
 * Testimonials block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$items   = get_field( 'items' );

if ( ! $items ) {
	bt_block_placeholder( $block, __( 'Testimonials: add at least one quote.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-testimonials' ) ); ?>">

	<div class="bt-testimonials__inner">
		<?php if ( $heading ) : ?>
			<h2 class="bt-section-head__title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<div class="bt-testimonials__grid">
			<?php foreach ( $items as $item ) : ?>
				<?php if ( empty( $item['quote'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>

				<figure class="bt-testimonial">
					<blockquote class="bt-testimonial__quote">
						<?php echo esc_html( $item['quote'] ); ?>
					</blockquote>

					<?php if ( ! empty( $item['name'] ) || ! empty( $item['context'] ) ) : ?>
						<figcaption class="bt-testimonial__source">
							<?php if ( ! empty( $item['name'] ) ) : ?>
								<span class="bt-testimonial__name"><?php echo esc_html( $item['name'] ); ?></span>
							<?php endif; ?>

							<?php if ( ! empty( $item['context'] ) ) : ?>
								<span class="bt-testimonial__context"><?php echo esc_html( $item['context'] ); ?></span>
							<?php endif; ?>
						</figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
