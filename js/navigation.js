/**
 * File navigation.js.
 * Mobile dropdown menu + 3rd level submenu fly-right.
 *
 * @package marsislav
 */
( function () {
	var siteNavigation = document.getElementById( 'site-navigation' );
	if ( ! siteNavigation ) return;

	var button = siteNavigation.querySelector( '.menu-toggle' );
	var menu   = siteNavigation.querySelector( 'ul' );

	if ( ! menu ) {
		if ( button ) button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	function isMobile() {
		return window.innerWidth <= 768;
	}

	function openMenu() {
		menu.classList.add( 'mobile-open' );
		if ( button ) button.setAttribute( 'aria-expanded', 'true' );
		siteNavigation.classList.add( 'toggled' );
	}

	function closeMenu() {
		menu.classList.remove( 'mobile-open' );
		if ( button ) button.setAttribute( 'aria-expanded', 'false' );
		siteNavigation.classList.remove( 'toggled' );
		menu.querySelectorAll( 'li.submenu-open' ).forEach( function ( li ) {
			li.classList.remove( 'submenu-open' );
		} );
	}

	/* ── Hamburger toggle ── */
	if ( button ) {
		button.addEventListener( 'click', function () {
			menu.classList.contains( 'mobile-open' ) ? closeMenu() : openMenu();
		} );
	}

	/* ── Close on click outside (mobile only) ── */
	document.addEventListener( 'click', function ( e ) {
		if ( ! isMobile() ) return;
		if ( ! siteNavigation.contains( e.target ) ) {
			closeMenu();
		}
	} );

	/* ── Close on Escape ── */
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' ) closeMenu();
	} );

	/* ── Close on regular link click (mobile only) ── */
	menu.querySelectorAll( 'a' ).forEach( function ( link ) {
		link.addEventListener( 'click', function () {
			if ( ! isMobile() ) return;
			var parentLi = link.closest( 'li' );
			if ( ! parentLi || ! parentLi.classList.contains( 'menu-item-has-children' ) ) {
				closeMenu();
			}
		} );
	} );

	/* ── Submenu toggle buttons (mobile accordion) ── */
	menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' ).forEach( function ( link ) {
		var toggle = document.createElement( 'button' );
		toggle.className = 'submenu-toggle';
		toggle.setAttribute( 'aria-label', 'Toggle submenu' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		link.parentNode.appendChild( toggle );

		toggle.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			e.stopPropagation();

			/* On desktop, submenu toggles do nothing — CSS hover handles it */
			if ( ! isMobile() ) return;

			var parent = this.closest( 'li' );
			var isOpen = parent.classList.contains( 'submenu-open' );

			/* Close siblings */
			if ( parent.parentNode ) {
				parent.parentNode.querySelectorAll( ':scope > li.submenu-open' ).forEach( function ( li ) {
					li.classList.remove( 'submenu-open' );
					var t = li.querySelector( ':scope > .submenu-toggle' );
					if ( t ) t.setAttribute( 'aria-expanded', 'false' );
				} );
			}

			parent.classList.toggle( 'submenu-open', ! isOpen );
			this.setAttribute( 'aria-expanded', String( ! isOpen ) );
		} );
	} );

} () );

/* ── 3rd level: auto-flip if submenu goes off-screen right (desktop) ── */
( function () {
	function checkThirdLevelFlip() {
		if ( window.innerWidth <= 768 ) return;
		document.querySelectorAll( '.primary-menu .sub-menu .menu-item-has-children' ).forEach( function ( li ) {
			var sub = li.querySelector( ':scope > .sub-menu' );
			if ( ! sub ) return;
			/* Temporarily measure without showing */
			var prev = { visibility: sub.style.visibility, opacity: sub.style.opacity, display: sub.style.display };
			sub.style.cssText += ';visibility:hidden!important;opacity:0!important;display:block!important;';
			var rect = sub.getBoundingClientRect();
			sub.style.visibility = prev.visibility;
			sub.style.opacity    = prev.opacity;
			sub.style.display    = prev.display;
			li.classList.toggle( 'submenu-flip-left', rect.right > window.innerWidth - 10 );
		} );
	}
	document.addEventListener( 'DOMContentLoaded', checkThirdLevelFlip );
	window.addEventListener( 'resize', checkThirdLevelFlip );
} () );
