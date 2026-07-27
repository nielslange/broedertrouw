<?php
/**
 * Price table block template.
 *
 * Rows use native <details>/<summary> so expanding needs no JavaScript.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$intro   = get_field( 'intro' );
$rows    = get_field( 'rows' );
$cta     = get_field( 'cta' );

if ( ! $rows ) {
	bt_block_placeholder( $block, __( 'Price table: add at least one row.', 'broedertrouw' ) );
	return;
}

$cta_label = $cta && ! empty( $cta['title'] ) ? $cta['title'] : __( 'Enquire', 'broedertrouw' );
$cta_url   = $cta && ! empty( $cta['url'] ) ? $cta['url'] : bt_enquiry_url();

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-price-table' ) ); ?>">

	<div class="bt-price-table__inner">
		<?php if ( $heading ) : ?>
			<h2 class="bt-price-table__heading"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php if ( $intro ) : ?>
			<p class="bt-price-table__intro"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>

		<div class="bt-price-table__table">
			<div class="bt-price-table__head" aria-hidden="true">
				<div><?php esc_html_e( 'Trip', 'broedertrouw' ); ?></div>
				<div><?php esc_html_e( 'Period', 'broedertrouw' ); ?></div>
				<div class="bt-price-table__head-price"><?php esc_html_e( 'Price', 'broedertrouw' ); ?></div>
				<div></div>
				<div></div>
			</div>

			<?php foreach ( $rows as $row ) : ?>
				<details class="bt-price-row<?php echo ! empty( $row['featured'] ) ? ' bt-price-row--featured' : ''; ?>">
					<summary class="bt-price-row__summary">
						<div class="bt-price-row__title-cell">
							<p class="bt-price-row__title">
								<?php echo esc_html( $row['title'] ); ?>
								<?php if ( ! empty( $row['badge'] ) ) : ?>
									<span class="bt-price-row__badge"><?php echo esc_html( $row['badge'] ); ?></span>
								<?php endif; ?>
							</p>
							<?php if ( ! empty( $row['subtitle'] ) ) : ?>
								<p class="bt-price-row__subtitle"><?php echo esc_html( $row['subtitle'] ); ?></p>
							<?php endif; ?>
						</div>

						<div class="bt-price-row__period-cell">
							<?php if ( ! empty( $row['period'] ) ) : ?>
								<p class="bt-price-row__period"><?php echo esc_html( $row['period'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $row['period_note'] ) ) : ?>
								<p class="bt-price-row__period-note"><?php echo esc_html( $row['period_note'] ); ?></p>
							<?php endif; ?>
						</div>

						<div class="bt-price-row__price-cell">
							<?php if ( ! empty( $row['price'] ) ) : ?>
								<p class="bt-price-row__price"><?php echo esc_html( $row['price'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $row['price_note'] ) ) : ?>
								<p class="bt-price-row__price-note"><?php echo esc_html( $row['price_note'] ); ?></p>
							<?php endif; ?>
						</div>

						<?php
						/*
						 * The enquiry link lives inside <summary> as the design shows it.
						 * Browsers follow the link instead of toggling the row, so this
						 * needs no JavaScript.
						 */
						?>
						<?php
						/*
						 * Each row is one charter option, so its title and dates
						 * travel to the form the same way a trip card's do.
						 */
						$row_period = trim( ( $row['period'] ?? '' ) . ( ! empty( $row['period_note'] ) ? ' · ' . $row['period_note'] : '' ) );
						?>
						<a
							class="bt-button <?php echo ! empty( $row['featured'] ) ? 'bt-button--navy' : 'bt-button--outline-navy'; ?> bt-price-row__cta"
							href="<?php echo esc_url( $cta_url ); ?>"
							<?php
							echo bt_is_enquiry_url( $cta_url ) ? bt_enquiry_attrs( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								array(
									'trip'   => $row['title'],
									'period' => $row_period,
								)
							) : '';
							?>
							>
							<?php echo esc_html( $cta_label ); ?>
						</a>

						<span class="bt-price-row__toggle">
							<?php esc_html_e( 'Details', 'broedertrouw' ); ?><span class="bt-price-row__chevron" aria-hidden="true">&#9662;</span>
						</span>
					</summary>

					<div class="bt-price-row__panel">
						<?php if ( ! empty( $row['check_in'] ) ) : ?>
							<div class="bt-price-row__detail">
								<p class="bt-price-row__detail-label"><?php esc_html_e( 'Check-in', 'broedertrouw' ); ?></p>
								<p class="bt-price-row__detail-value"><?php echo esc_html( $row['check_in'] ); ?></p>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $row['check_out'] ) ) : ?>
							<div class="bt-price-row__detail">
								<p class="bt-price-row__detail-label"><?php esc_html_e( 'Check-out', 'broedertrouw' ); ?></p>
								<p class="bt-price-row__detail-value"><?php echo esc_html( $row['check_out'] ); ?></p>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $row['group_size'] ) ) : ?>
							<div class="bt-price-row__detail">
								<p class="bt-price-row__detail-label"><?php esc_html_e( 'Group size', 'broedertrouw' ); ?></p>
								<p class="bt-price-row__detail-value"><?php echo esc_html( $row['group_size'] ); ?></p>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $row['discount_note'] ) ) : ?>
							<div class="bt-price-row__discount">
								<p class="bt-price-row__discount-label"><?php esc_html_e( 'Discount periods −10 %', 'broedertrouw' ); ?></p>
								<p class="bt-price-row__detail-value"><?php echo esc_html( $row['discount_note'] ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
