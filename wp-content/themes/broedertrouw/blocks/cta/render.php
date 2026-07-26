<?php
/**
 * CTA band block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$text    = get_field( 'text' );
$button  = get_field( 'button' );
$style   = get_field( 'style' );
$style   = $style ? $style : 'dark';

if ( ! $heading ) {
	bt_block_placeholder( $block, __( 'CTA band: add a heading.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-cta' ) ); ?> bt-cta--<?php echo esc_attr( $style ); ?>">

	<div class="bt-cta__inner">
		<h2 class="bt-cta__heading"><?php echo esc_html( $heading ); ?></h2>

		<?php if ( $text ) : ?>
			<p class="bt-cta__text"><?php echo esc_html( $text ); ?></p>
		<?php endif; ?>

		<?php if ( $button && ! empty( $button['url'] ) ) : ?>
			<a
				class="bt-button <?php echo 'dark' === $style ? 'bt-button--white' : 'bt-button--navy'; ?>"
				href="<?php echo esc_url( $button['url'] ); ?>"
				<?php echo ! empty( $button['target'] ) ? 'target="' . esc_attr( $button['target'] ) . '" rel="noopener"' : ''; ?>>
				<?php echo esc_html( $button['title'] ); ?>
			</a>
		<?php endif; ?>
	</div>
</section>
