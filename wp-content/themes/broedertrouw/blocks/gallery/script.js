/**
 * Gallery category filter: chips toggle the hidden state of the figures.
 */
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
