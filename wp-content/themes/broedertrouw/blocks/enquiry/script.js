/**
 * Prefills the enquiry form when a trip's "request" button is clicked.
 *
 * The button is an in-page link to #enquiry, so the browser does the
 * scrolling; this only fills the fields the trip already answers and leaves
 * the visitor to complete the rest.
 */
document.addEventListener( 'click', function ( event ) {
	const trigger = event.target.closest( '[data-bt-trip]' );

	if ( ! trigger ) {
		return;
	}

	const form = document.querySelector( '.bt-enquiry form' );

	if ( ! form ) {
		return;
	}

	const trip   = trigger.dataset.btTrip || '';
	const period = trigger.dataset.btPeriod || '';

	const setValue = function ( name, value ) {
		const field = form.querySelector( '[name="' + name + '"]' );

		// Never overwrite something the visitor already typed.
		if ( ! field || ! value || field.value ) {
			return;
		}

		field.value = value;
		field.dispatchEvent( new Event( 'input', { bubbles: true } ) );
		field.dispatchEvent( new Event( 'change', { bubbles: true } ) );
	};

	setValue( 'preferred_period', period );

	// An open trip is always the "open trip / regatta" duration, which is the
	// last option in both languages.
	const duration = form.querySelector( '[name="duration"]' );

	if ( duration && ! duration.value ) {
		const last = duration.options[ duration.options.length - 1 ];

		if ( last ) {
			duration.value = last.value;
			duration.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		}
	}

	// Name the trip in the message so the crew knows which one was clicked.
	const message = form.querySelector( '[name="message"]' );

	if ( message && ! message.value && trip ) {
		const card     = form.closest( '.bt-enquiry__card' );
		const template = ( card && card.dataset.btTripTemplate ) || '%s';

		message.value = template.replace( '%s', trip );
		message.dispatchEvent( new Event( 'input', { bubbles: true } ) );
	}
} );
