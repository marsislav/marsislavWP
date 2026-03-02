/**
 * File navigation.js.
 * Mobile menu with fullscreen overlay.
 *
 * @package marsislav
 */
( function () {
	const siteNavigation = document.getElementById( 'site-navigation' );
	if ( ! siteNavigation ) return;

	const button = siteNavigation.querySelector( '.menu-toggle' );
	if ( ! button ) return;

	const menu = siteNavigation.querySelector( 'ul' );
	if ( ! menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	// Create overlay backdrop
	const overlay = document.createElement( 'div' );
	overlay.className = 'mobile-menu-overlay';
	document.body.appendChild( overlay );

	// Animate menu items on open
	function animateMenuItems( open ) {
		const items = menu.querySelectorAll( 'li' );
		items.forEach( ( item, i ) => {
			item.style.transitionDelay = open ? ( i * 60 + 80 ) + 'ms' : '0ms';
			item.classList.toggle( 'menu-item-visible', open );
		} );
	}

	function openMenu() {
		siteNavigation.classList.add( 'toggled' );
		overlay.classList.add( 'active' );
		button.setAttribute( 'aria-expanded', 'true' );
		document.body.classList.add( 'menu-open' );
		animateMenuItems( true );
	}

	function closeMenu() {
		animateMenuItems( false );
		siteNavigation.classList.remove( 'toggled' );
		overlay.classList.remove( 'active' );
		button.setAttribute( 'aria-expanded', 'false' );
		document.body.classList.remove( 'menu-open' );
	}

	button.addEventListener( 'click', function () {
		siteNavigation.classList.contains( 'toggled' ) ? closeMenu() : openMenu();
	} );

	overlay.addEventListener( 'click', closeMenu );

	// Close on ESC
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' ) closeMenu();
	} );

	// Close when a menu link is clicked (navigation)
	menu.querySelectorAll( 'a' ).forEach( function ( link ) {
		link.addEventListener( 'click', function () {
			closeMenu();
		} );
	} );

	// Submenu toggles
	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );
	linksWithChildren.forEach( function ( link ) {
		const toggle = document.createElement( 'button' );
		toggle.className = 'submenu-toggle';
		toggle.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		toggle.setAttribute( 'aria-label', 'Toggle submenu' );
		link.parentNode.insertBefore( toggle, link.nextSibling );

		toggle.addEventListener( 'click', function ( e ) {
			e.stopPropagation();
			const parent = this.closest( 'li' );
			parent.classList.toggle( 'submenu-open' );
		} );
	} );
}() );
