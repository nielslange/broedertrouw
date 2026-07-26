/**
 * Gallery: category chips plus a lightbox.
 *
 * No dependencies. The modal is a native <dialog>, so the browser handles
 * focus trapping, the backdrop and Escape; this only swaps the image and
 * walks the figures that are currently visible.
 */
( function () {
	/**
	 * Returns the figures the current filter leaves visible.
	 *
	 * @param {Element} gallery Gallery root.
	 * @return {Element[]} Visible figures.
	 */
	function visibleFigures( gallery ) {
		return Array.prototype.filter.call(
			gallery.querySelectorAll( '[data-bt-cat]' ),
			function ( figure ) {
				return ! figure.hidden;
			}
		);
	}

	/* ------------------------------------------------------------ Chips */

	document.addEventListener( 'click', function ( event ) {
		const chip = event.target.closest( '[data-bt-chip]' );

		if ( ! chip ) {
			return;
		}

		const gallery = chip.closest( '[data-bt-gallery]' );

		if ( ! gallery ) {
			return;
		}

		const selected = chip.dataset.btChip;

		gallery.querySelectorAll( '[data-bt-chip]' ).forEach( function ( button ) {
			button.setAttribute( 'aria-pressed', button === chip ? 'true' : 'false' );
		} );

		gallery.querySelectorAll( '[data-bt-cat]' ).forEach( function ( figure ) {
			figure.hidden = 'all' !== selected && figure.dataset.btCat !== selected;
		} );
	} );

	/* --------------------------------------------------------- Lightbox */

	document.addEventListener( 'click', function ( event ) {
		const opener = event.target.closest( '[data-bt-full]' );

		if ( ! opener ) {
			return;
		}

		const gallery = opener.closest( '[data-bt-gallery]' );
		const dialog  = gallery && gallery.querySelector( '[data-bt-lightbox]' );

		if ( ! dialog || typeof dialog.showModal !== 'function' ) {
			return;
		}

		const image   = dialog.querySelector( '.bt-lightbox__image' );
		const counter = dialog.querySelector( '[data-bt-count]' );
		let figures   = visibleFigures( gallery );
		let index     = figures.indexOf( opener.closest( '[data-bt-cat]' ) );

		/**
		 * Shows the photo at the given position, wrapping at both ends.
		 *
		 * @param {number} next Target index.
		 */
		function show( next ) {
			if ( ! figures.length ) {
				return;
			}

			index = ( next + figures.length ) % figures.length;

			const button = figures[ index ].querySelector( '[data-bt-full]' );

			if ( ! button ) {
				return;
			}

			image.src = button.dataset.btFull;
			image.alt = button.dataset.btAlt || '';

			if ( counter ) {
				counter.textContent = ( index + 1 ) + ' / ' + figures.length;
			}
		}

		show( index );
		dialog.showModal();

		// Handlers live for as long as the dialog is open.
		const onPrev  = function () {
			show( index - 1 );
		};
		const onNext  = function () {
			show( index + 1 );
		};
		const onKey   = function ( keyEvent ) {
			if ( 'ArrowLeft' === keyEvent.key ) {
				onPrev();
			}

			if ( 'ArrowRight' === keyEvent.key ) {
				onNext();
			}
		};
		const onClick = function ( clickEvent ) {
			// Clicking the backdrop, which is the dialog itself, closes it.
			if ( clickEvent.target === dialog || clickEvent.target.closest( '[data-bt-close]' ) ) {
				dialog.close();
			}
		};

		dialog.querySelector( '[data-bt-prev]' ).addEventListener( 'click', onPrev );
		dialog.querySelector( '[data-bt-next]' ).addEventListener( 'click', onNext );
		dialog.addEventListener( 'keydown', onKey );
		dialog.addEventListener( 'click', onClick );

		dialog.addEventListener(
			'close',
			function () {
				dialog.querySelector( '[data-bt-prev]' ).removeEventListener( 'click', onPrev );
				dialog.querySelector( '[data-bt-next]' ).removeEventListener( 'click', onNext );
				dialog.removeEventListener( 'keydown', onKey );
				dialog.removeEventListener( 'click', onClick );

				// Drop the source so a large photo is not kept in memory.
				image.src = '';
				opener.focus();
			},
			{ once: true }
		);
	} );
}() );
