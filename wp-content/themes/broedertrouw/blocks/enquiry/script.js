/**
 * Prefills the enquiry form when a trip's "request" button is clicked.
 *
 * The button is an in-page link to #enquiry, so the browser does the
 * scrolling; this only fills the fields the trip already answers and leaves
 * the visitor to complete the rest.
 */
document.addEventListener( 'click', function ( event ) {
	// Every button that links to the form carries data-bt-enquiry, whether or
	// not it has context to pass, so behaviour is identical site wide.
	const trigger = event.target.closest( '[data-bt-enquiry]' );

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
	 * A berth on a named trip, as opposed to chartering the whole ship. Both
	 * kinds of button name a trip, so this is what tells them apart.
	 */
	const individual = trigger.dataset.btIndividual !== undefined;

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

	/*
	 * An individual trip is booked by the berth, so the group questions have
	 * no answer the visitor can give and only add noise. They are hidden
	 * together with their row, and un-required for the same reason duration is
	 * below: a hidden required field blocks submission.
	 */
	const groupFields = [ 'group_type', 'group_size' ].map( function ( name ) {
		return form.querySelector( '[name="' + name + '"]' );
	} ).filter( Boolean );

	if ( groupFields.length ) {
		// Both fields share one row, so hiding the row leaves no empty column
		// where the pair used to sit.
		const groupRow = groupFields[ 0 ].closest( '.ff-t-container' );

		groupFields.forEach( function ( field ) {
			if ( individual ) {
				// Cleared as well as hidden, so a charter clicked earlier
				// cannot submit its group answers under an individual trip.
				field.value = '';
				delete field.dataset.btPrefilled;
				field.required = false;
				field.removeAttribute( 'required' );
			} else {
				field.required = true;
				field.setAttribute( 'required', 'required' );
			}
		} );

		if ( groupRow ) {
			groupRow.hidden = individual;
		}
	}

	/*
	 * A named tour answers the duration question by itself, so the duration
	 * dropdown is swapped for a read-only tour field. Duration is also cleared
	 * and un-required, otherwise a hidden required field blocks submission.
	 */
	const durationField = form.querySelector( '[name="duration"]' );
	const tourField     = form.querySelector( '[name="tour"]' );

	if ( durationField && tourField ) {
		const durationWrap = durationField.closest( '.ff-el-group' );
		const tourWrap     = tourField.closest( '.ff-el-group' );

		if ( trip ) {
			tourField.value = trip;
			tourField.dispatchEvent( new Event( 'input', { bubbles: true } ) );

			// Hidden and empty, so it must not block submission. The stored
			// rule allows this; the attribute is what the browser enforces.
			durationField.value = '';
			durationField.required = false;
			durationField.removeAttribute( 'required' );

			if ( durationWrap ) {
				durationWrap.hidden = true;
			}

			if ( tourWrap ) {
				tourWrap.hidden = false;
			}
		} else {
			// A generic enquiry: put the duration question back.
			tourField.value = '';
			durationField.required = true;
			durationField.setAttribute( 'required', 'required' );

			if ( durationWrap ) {
				durationWrap.hidden = false;
			}

			if ( tourWrap ) {
				tourWrap.hidden = true;
			}
		}
	}

	// Name the trip in the message so the crew knows which one was clicked.
	if ( trip ) {
		const card     = form.closest( '.bt-enquiry__card' );
		const template = ( card && card.dataset.btTripTemplate ) || '%s';

		setValue( 'message', template.replace( '%s', trip ) );
	}
} );
