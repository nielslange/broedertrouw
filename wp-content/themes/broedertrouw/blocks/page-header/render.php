<?php
/**
 * Page header block template.
 *
 * @package broedertrouw
 *
 * @var array $block Block settings.
 */

defined( 'ABSPATH' ) || exit;

$kicker = get_field( 'kicker' );
$people = get_field( 'people' );

/*
 * Heading and lead default to the page title and excerpt, so the page stays
 * the single source of truth and the two can never drift apart.
 */
$heading = get_field( 'heading' );
$heading = $heading ? $heading : get_the_title();

$lead = get_field( 'lead' );
$lead = $lead ? $lead : get_the_excerpt();

$anchor = bt_block_anchor( $block );
?>
<section
	<?php echo $anchor ? 'id="' . esc_attr( $anchor ) . '" ' : ''; ?>
	class="<?php echo esc_attr( bt_block_classes( $block, 'bt-page-header' ) ); ?><?php echo $people ? ' bt-page-header--has-people' : ''; ?>">

	<div class="bt-page-header__inner">
		<div class="bt-page-header__body">
			<?php if ( $kicker ) : ?>
				<p class="bt-kicker"><?php echo esc_html( $kicker ); ?></p>
			<?php endif; ?>

			<h1 class="bt-page-header__title"><?php echo esc_html( $heading ); ?></h1>

			<?php if ( $lead ) : ?>
				<p class="bt-page-header__lead"><?php echo esc_html( $lead ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $people ) : ?>
			<div class="bt-page-header__people">
				<?php foreach ( $people as $person ) : ?>
					<div class="bt-person">
						<?php if ( ! empty( $person['image'] ) ) : ?>
							<img
								class="bt-person__photo"
								src="<?php echo esc_url( $person['image']['sizes']['medium'] ?? $person['image']['url'] ); ?>"
								alt="<?php echo esc_attr( $person['image']['alt'] ? $person['image']['alt'] : $person['name'] ); ?>"
								width="140"
								height="140"
								loading="lazy">
						<?php endif; ?>

						<?php if ( ! empty( $person['name'] ) || ! empty( $person['role'] ) ) : ?>
							<div class="bt-person__text">
								<?php if ( ! empty( $person['name'] ) ) : ?>
									<p class="bt-person__name"><?php echo esc_html( $person['name'] ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $person['role'] ) ) : ?>
									<p class="bt-person__role"><?php echo esc_html( $person['role'] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
