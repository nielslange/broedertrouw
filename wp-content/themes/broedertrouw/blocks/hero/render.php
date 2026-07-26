<?php
/**
 * Hero block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$image      = get_field( 'image' );
$heading    = get_field( 'heading' );
$subheading = get_field( 'subheading' );
$primary    = get_field( 'cta_primary' );
$secondary  = get_field( 'cta_secondary' );

if ( ! $image || ! $heading ) {
	bt_block_placeholder( $block, __( 'Hero: choose an image and add a heading.', 'broedertrouw' ) );
	return;
}

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-hero' ) ); ?>">

	<img
		class="bt-hero__image"
		src="<?php echo esc_url( $image['url'] ); ?>"
		alt="<?php echo esc_attr( $image['alt'] ); ?>"
		loading="eager"
		decoding="async" />

	<div class="bt-hero__overlay"></div>

	<div class="bt-hero__inner">
		<div class="bt-hero__content">
			<h1 class="bt-hero__heading"><?php echo esc_html( $heading ); ?></h1>

			<?php if ( $subheading ) : ?>
				<p class="bt-hero__subheading"><?php echo esc_html( $subheading ); ?></p>
			<?php endif; ?>

			<?php if ( $primary || $secondary ) : ?>
				<div class="bt-hero__actions">
					<?php if ( $primary && ! empty( $primary['url'] ) ) : ?>
						<a
							class="bt-button bt-button--white"
							href="<?php echo esc_url( $primary['url'] ); ?>"
							<?php echo bt_is_enquiry_url( $primary['url'] ) ? bt_enquiry_attrs() : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo ! empty( $primary['target'] ) ? 'target="' . esc_attr( $primary['target'] ) . '" rel="noopener"' : ''; ?>>
							<?php echo esc_html( $primary['title'] ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $secondary && ! empty( $secondary['url'] ) ) : ?>
						<a
							class="bt-button bt-button--outline-white"
							href="<?php echo esc_url( $secondary['url'] ); ?>"
							<?php echo bt_is_enquiry_url( $secondary['url'] ) ? bt_enquiry_attrs() : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo ! empty( $secondary['target'] ) ? 'target="' . esc_attr( $secondary['target'] ) . '" rel="noopener"' : ''; ?>>
							<?php echo esc_html( $secondary['title'] ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
