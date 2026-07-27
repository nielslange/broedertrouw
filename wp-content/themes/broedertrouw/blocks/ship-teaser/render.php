<?php
/**
 * Ship teaser block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$image   = get_field( 'image' );
$kicker  = get_field( 'kicker' );
$heading = get_field( 'heading' );
$text    = get_field( 'text' );
$items   = get_field( 'items' );
$link    = get_field( 'link' );

if ( ! $image || ! $heading ) {
	bt_block_placeholder( $block, __( 'Ship teaser: choose an image and add a heading.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-ship' ) ); ?>">

	<div class="bt-ship__inner">
		<figure class="bt-ship__figure">
			<img
				src="<?php echo esc_url( $image['sizes']['large'] ?? $image['url'] ); ?>"
				alt="<?php echo esc_attr( $image['alt'] ); ?>"
				loading="lazy"
				decoding="async" />
		</figure>

		<div class="bt-ship__body">
			<?php if ( $kicker ) : ?>
				<p class="bt-kicker"><?php echo esc_html( $kicker ); ?></p>
			<?php endif; ?>

			<h2 class="bt-ship__heading"><?php echo esc_html( $heading ); ?></h2>

			<?php if ( $text ) : ?>
				<div class="bt-ship__text"><?php echo wp_kses_post( $text ); ?></div>
			<?php endif; ?>

			<?php if ( $items ) : ?>
				<ul class="bt-checklist">
					<?php foreach ( $items as $item ) : ?>
						<?php if ( empty( $item['item'] ) ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<li><?php echo esc_html( $item['item'] ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if ( $link && ! empty( $link['url'] ) ) : ?>
				<?php
				/*
				 * This block sells the school trip, so a click preselects the
				 * school class group type in the enquiry form. The index maps
				 * to the dropdown, which keeps it language independent.
				 */
				$is_enquiry = bt_is_enquiry_url( $link['url'] );
				?>
				<a
					class="bt-button bt-button--navy"
					href="<?php echo esc_url( $link['url'] ); ?>"
					<?php echo $is_enquiry ? bt_enquiry_attrs( array( 'group' => 0 ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo ! empty( $link['target'] ) ? 'target="' . esc_attr( $link['target'] ) . '" rel="noopener"' : ''; ?>>
					<?php echo esc_html( $link['title'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
