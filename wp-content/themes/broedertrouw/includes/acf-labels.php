<?php
/**
 * Translatable ACF labels.
 *
 * ACF field labels live in the Local JSON, which knows nothing about Polylang.
 * Registering them as Polylang strings lets the wording be maintained under
 * Languages > String translations without a code change, and each editor sees
 * the labels in their own admin language.
 *
 * @package broedertrouw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the ACF labels and instructions exposed to Polylang.
 *
 * @return string[]
 */
function bt_acf_translatable_strings() {
	return array(
		// Field group.
		'Trip Details',
		// Labels.
		'Start date',
		'End date',
		'Embarkation port',
		'Disembarkation port',
		'Booking mode',
		'Availability',
		'Price per berth',
		'Price for a private cabin',
		'Highlight',
		'Included',
		'Not included',
		'Gallery',
		'Item',
		// Choices.
		'Per berth',
		'Whole ship',
		'Open trip',
		'On request',
		'Fully booked',
		'Hoorn',
		'Enkhuizen',
		'Amsterdam',
		'Other port',
		// Instructions.
		'Leave empty when it matches the embarkation port.',
		'Per person in a shared deck berth. For a whole-ship charter, the total price.',
		'Total price per person for private use of a cabin. Leave empty when not offered.',
		'One-liner for cards and lists.',
		'Shown on the calendar page as a status pill.',
	);
}

/**
 * Registers the ACF labels with Polylang.
 */
function bt_register_acf_strings() {
	if ( ! function_exists( 'pll_register_string' ) ) {
		return;
	}

	foreach ( bt_acf_translatable_strings() as $string ) {
		pll_register_string( $string, $string, 'Broedertrouw: trip fields' );
	}
}
add_action( 'init', 'bt_register_acf_strings' );

/**
 * Translates ACF field labels, instructions and choices.
 *
 * @param array $field Field definition.
 * @return array
 */
function bt_translate_acf_field( $field ) {
	if ( ! function_exists( 'pll__' ) || is_admin() && ! bt_is_acf_admin_screen() ) {
		return $field;
	}

	foreach ( array( 'label', 'instructions', 'button_label' ) as $key ) {
		if ( ! empty( $field[ $key ] ) ) {
			$field[ $key ] = pll__( $field[ $key ] );
		}
	}

	if ( ! empty( $field['choices'] ) && is_array( $field['choices'] ) ) {
		foreach ( $field['choices'] as $value => $choice_label ) {
			$field['choices'][ $value ] = pll__( $choice_label );
		}
	}

	return $field;
}
add_filter( 'acf/prepare_field', 'bt_translate_acf_field' );

/**
 * Whether the current admin screen should show translated field labels.
 *
 * The field group editor must keep the source labels so saving does not
 * overwrite them with a translation.
 *
 * @return bool
 */
function bt_is_acf_admin_screen() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return true;
	}

	$screen = get_current_screen();

	if ( ! $screen ) {
		return true;
	}

	return 'acf-field-group' !== $screen->post_type;
}
