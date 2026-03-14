/**
 * Header Search Overlay — open / close / keyboard handling
 *
 * @package marsislav
 */

( function () {
	'use strict';

	var toggle  = document.querySelector( '.marsislav-search-toggle' );
	var overlay = document.getElementById( 'marsislav-search-overlay' );

	if ( ! toggle || ! overlay ) return;

	var closeBtn = overlay.querySelector( '.marsislav-search-overlay__close' );
	var field    = overlay.querySelector( '.search-field' );

	function openOverlay() {
		overlay.hidden = false;
		toggle.setAttribute( 'aria-expanded', 'true' );
		document.body.style.overflow = 'hidden';
		if ( field ) {
			setTimeout( function () { field.focus(); }, 60 );
		}
	}

	function closeOverlay() {
		overlay.hidden = true;
		toggle.setAttribute( 'aria-expanded', 'false' );
		document.body.style.overflow = '';
		toggle.focus();
	}

	toggle.addEventListener( 'click', function () {
		if ( overlay.hidden ) {
			openOverlay();
		} else {
			closeOverlay();
		}
	} );

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', closeOverlay );
	}

	// Click on backdrop (outside inner box) → close
	overlay.addEventListener( 'click', function ( e ) {
		if ( e.target === overlay ) {
			closeOverlay();
		}
	} );

	// Escape key → close
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && ! overlay.hidden ) {
			closeOverlay();
		}
	} );
} )();
