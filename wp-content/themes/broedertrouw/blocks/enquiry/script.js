/**
 * Prefills the enquiry form when a trip's "request" button is clicked.
 *
 * The button is an in-page link to #enquiry, so the browser does the
 * scrolling; this only fills the fields the trip already answers and leaves
 * the visitor to complete the rest.
 */
document.addEventListener( 'click', function ( event ) {
	const trigger = event.target.closest( '[data-bt-trip], [data-bt-group]' );

	if ( ! trigger ) {
		return;
	}

	const form = document.querySelector( '.bt-enquiry form' );

	if ( ! form ) {
		return;
	}

	const trip   = trigger.dataset.btTrip || '';
	const period = trigger.dataset.btPeriod || '';

	/*
	 * Values this script wrote are replaced when another trip is clicked, but
	 * anything the visitor typed is left alone. Without the marker a second
	 * click would leave the first trip's dates in the form.
	 */
	const setValue = function ( name, value ) {
		const field = form.querySelector( '[name="' + name + '"]' );

		if ( ! field || ! value ) {
			return;
		}

		const ours = field.dataset.btPrefilled;

		if ( field.value && field.value !== ours ) {
			return;
		}

		field.value = value;
		field.dataset.btPrefilled = value;
		field.dispatchEvent( new Event( 'input', { bubbles: true } ) );
		field.dispatchEvent( new Event( 'change', { bubbles: true } ) );
	};

	setValue( 'preferred_period', period );

	/*
	 * A button can name the group type it is selling, by index into the
	 * dropdown so the value stays correct in both languages: 0 school class,
	 * 1 company outing, 2 family celebration, 3 club or other.
	 */
	const groupIndex = trigger.dataset.btGroup;

	if ( groupIndex !== undefined ) {
		const groupType = form.querySelector( '[name="group_type"]' );
		const option    = groupType && groupType.querySelectorAll( 'option:not([value=""])' )[ groupIndex ];

		if ( option ) {
			setValue( 'group_type', option.value );
		}
	}

	// A trip is always the "open trip / regatta" duration, which is the last
	// option in both languages.
	if ( trigger.dataset.btTrip ) {
		const duration = form.querySelector( '[name="duration"]' );
		const last     = duration && duration.options[ duration.options.length - 1 ];

		if ( last ) {
			setValue( 'duration', last.value );
		}
	}

	// Name the trip in the message so the crew knows which one was clicked.
	if ( trip ) {
		const card     = form.closest( '.bt-enquiry__card' );
		const template = ( card && card.dataset.btTripTemplate ) || '%s';

		setValue( 'message', template.replace( '%s', trip ) );
	}
} );
