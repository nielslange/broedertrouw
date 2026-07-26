<?php
/**
 * Enquiry block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading    = get_field( 'heading' );
$text       = get_field( 'text' );
$phone      = get_field( 'phone' );
$email      = get_field( 'email' );
$form_id    = (int) get_field( 'form_id' );
$disclaimer = get_field( 'disclaimer' );

if ( ! $heading ) {
	bt_block_placeholder( $block, __( 'Enquiry: add a heading.', 'broedertrouw' ) );
	return;
}

/*
 * Every link to the form resolves the same translated anchor, so the address
 * bar stays in the visitor's language.
 */
$anchor = bt_block_anchor( $block );
$anchor = $anchor ? $anchor : bt_enquiry_anchor();

// The phone number is written for humans; strip it down for the tel: link.
$tel = $phone ? preg_replace( '/[^0-9+]/', '', $phone ) : '';
?>
<section
	id="<?php echo esc_attr( $anchor ); ?>"
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-enquiry' ) ); ?>">

	<div class="bt-enquiry__inner">
		<div class="bt-enquiry__body">
			<h2 class="bt-enquiry__heading"><?php echo esc_html( $heading ); ?></h2>

			<?php if ( $text ) : ?>
				<p class="bt-enquiry__text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>

			<?php if ( $phone || $email ) : ?>
				<ul class="bt-enquiry__contact">
					<?php if ( $phone ) : ?>
						<li>
							<?php echo bt_icon( 'phone', array( 'class' => 'bt-enquiry__icon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<a href="tel:<?php echo esc_attr( $tel ); ?>"><?php echo esc_html( $phone ); ?></a>
						</li>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<li>
							<?php echo bt_icon( 'mail', array( 'class' => 'bt-enquiry__icon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
						</li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php
		/*
		 * Template for the message a trip card prefills. It lives here rather
		 * than in the script so it stays translatable; %s is the trip name.
		 */
		$trip_template = __( 'I would like to enquire about: %s', 'broedertrouw' );
		?>
		<div class="bt-enquiry__card" data-bt-trip-template="<?php echo esc_attr( $trip_template ); ?>">
			<?php if ( $form_id && shortcode_exists( 'fluentform' ) ) : ?>
				<?php echo do_shortcode( sprintf( '[fluentform id="%d"]', $form_id ) ); ?>
			<?php else : ?>
				<p class="bt-enquiry__missing"><?php esc_html_e( 'The enquiry form is not available yet.', 'broedertrouw' ); ?></p>
			<?php endif; ?>

			<?php if ( $disclaimer ) : ?>
				<p class="bt-enquiry__disclaimer"><?php echo esc_html( $disclaimer ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
