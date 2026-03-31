/**
 * File navigation.js
 * Mobile sidebar menu (Neve-style) + desktop hover dropdowns.
 *
 * @package marsislav
 */
( function () {
	'use strict';

	/* ── Helpers ── */
	var body       = document.body;
	var sidebar    = document.getElementById( 'marsislav-menu-sidebar' );
	var overlay    = document.getElementById( 'marsislav-sidebar-overlay' );
	var openBtn    = document.querySelector( '.marsislav-menu-toggle' );
	var closeBtn   = document.querySelector( '.marsislav-close-btn' );

	var CLASS_OPEN    = 'is-menu-sidebar';   // on body — same concept as Neve
	var CLASS_HIDING  = 'hiding-menu-sidebar';
	var CLASS_ACTIVE  = 'is-active';         // on toggle button

	function isMobile() {
		return window.innerWidth <= 768;
	}

	/* ── Open sidebar ── */
	function openSidebar() {
		body.classList.add( CLASS_OPEN );
		body.classList.remove( CLASS_HIDING );
		if ( sidebar )  { sidebar.setAttribute( 'aria-hidden', 'false' ); sidebar.classList.add( CLASS_ACTIVE ); }
		if ( overlay )  { overlay.setAttribute( 'aria-hidden', 'false' ); overlay.classList.add( CLASS_ACTIVE ); }
		if ( openBtn )  { openBtn.setAttribute( 'aria-expanded', 'true' ); openBtn.classList.add( CLASS_ACTIVE ); }
	}

	/* ── Close sidebar ── */
	function closeSidebar() {
		body.classList.add( CLASS_HIDING );
		body.classList.remove( CLASS_OPEN );
		if ( sidebar )  { sidebar.setAttribute( 'aria-hidden', 'true' ); sidebar.classList.remove( CLASS_ACTIVE ); }
		if ( overlay )  { overlay.setAttribute( 'aria-hidden', 'true' ); overlay.classList.remove( CLASS_ACTIVE ); }
		if ( openBtn )  { openBtn.setAttribute( 'aria-expanded', 'false' ); openBtn.classList.remove( CLASS_ACTIVE ); }
		// Collapse all open submenus when closing
		if ( sidebar ) {
			sidebar.querySelectorAll( '.submenu-open' ).forEach( function ( li ) {
				li.classList.remove( 'submenu-open' );
				var btn = li.querySelector( ':scope > .ms-caret' );
				if ( btn ) { btn.setAttribute( 'aria-expanded', 'false' ); }
			} );
		}
		setTimeout( function () {
			body.classList.remove( CLASS_HIDING );
		}, 350 );
	}

	/* ── Toggle button ── */
	if ( openBtn ) {
		openBtn.addEventListener( 'click', function () {
			body.classList.contains( CLASS_OPEN ) ? closeSidebar() : openSidebar();
		} );
	}

	/* ── Close button inside sidebar ── */
	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', closeSidebar );
	}

	/* ── Overlay click ── */
	if ( overlay ) {
		overlay.addEventListener( 'click', closeSidebar );
	}

	/* ── Escape key ── */
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && body.classList.contains( CLASS_OPEN ) ) {
			closeSidebar();
		}
	} );

	/* ── Close on regular link click inside sidebar ── */
	if ( sidebar ) {
		sidebar.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				var li = link.closest( 'li' );
				if ( ! li || ! li.classList.contains( 'menu-item-has-children' ) ) {
					closeSidebar();
				}
			} );
		} );
	}

	/* ───────────────────────────────────────────────
	   Mobile accordion submenus inside sidebar
	   Uses Neve's pattern: toggle button (.ms-caret)
	   next to the <a>, toggles .submenu-open on <li>
	─────────────────────────────────────────────── */
	if ( sidebar ) {
		// Wrap each .sub-menu's children in a single div
		// This is required for grid-template-rows: 0fr to clip ALL children,
		// not just the first one (grid trick only works with a single child).
		sidebar.querySelectorAll( '.sub-menu' ).forEach( function ( ul ) {
			var wrapper = document.createElement( 'div' );
			wrapper.className = 'ms-sub-inner';
			while ( ul.firstChild ) {
				wrapper.appendChild( ul.firstChild );
			}
			ul.appendChild( wrapper );
		} );

		sidebar.querySelectorAll( '.menu-item-has-children, .page_item_has_children' ).forEach( function ( li ) {
			// Create caret button (like Neve's .caret-wrap)
			var caretBtn = document.createElement( 'button' );
			caretBtn.className = 'ms-caret';
			caretBtn.type = 'button';
			caretBtn.setAttribute( 'aria-label', 'Toggle submenu' );
			caretBtn.setAttribute( 'aria-expanded', 'false' );
			caretBtn.innerHTML =
				'<svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">' +
				'<path d="M2 5l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' +
				'</svg>';

			// Insert caret after the <a> tag
			var link = li.querySelector( ':scope > a' );
			if ( link ) {
				link.parentNode.insertBefore( caretBtn, link.nextSibling );
			}

			caretBtn.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				e.stopPropagation();

				var isOpen = li.classList.contains( 'submenu-open' );

				// Close siblings at same level
				var parent = li.parentNode;
				if ( parent ) {
					parent.querySelectorAll( ':scope > .ms-sub-inner > li.submenu-open, :scope > li.submenu-open' ).forEach( function ( sibling ) {
						if ( sibling !== li ) {
							sibling.classList.remove( 'submenu-open' );
							var sibBtn = sibling.querySelector( ':scope > .ms-caret' );
							if ( sibBtn ) { sibBtn.setAttribute( 'aria-expanded', 'false' ); }
						}
					} );
				}

				li.classList.toggle( 'submenu-open', ! isOpen );
				caretBtn.setAttribute( 'aria-expanded', String( ! isOpen ) );
			} );
		} );
	}

	/* ── Resize: close sidebar if switching to desktop ── */
	window.addEventListener( 'resize', function () {
		if ( ! isMobile() && body.classList.contains( CLASS_OPEN ) ) {
			closeSidebar();
		}
	} );

} () );

/* ── Desktop: reposition sub-menus that overflow viewport ── */
( function () {
	function checkOverflow() {
		if ( window.innerWidth <= 768 ) return;
		document.querySelectorAll( '.primary-menu .sub-menu .menu-item-has-children' ).forEach( function ( li ) {
			var sub = li.querySelector( ':scope > .sub-menu' );
			if ( ! sub ) return;
			var prev = sub.style.cssText;
			sub.style.cssText += ';visibility:hidden!important;opacity:0!important;display:block!important;';
			var rect = sub.getBoundingClientRect();
			sub.style.cssText = prev;
			li.classList.toggle( 'submenu-flip-left', rect.right > window.innerWidth - 10 );
		} );
	}
	document.addEventListener( 'DOMContentLoaded', checkOverflow );
	window.addEventListener( 'resize', checkOverflow );
} () );
