/**
 * File navigation.js.
 * Mobile slide-panel menu.
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

	// Overlay backdrop
	const overlay = document.createElement( 'div' );
	overlay.className = 'mobile-menu-overlay';
	document.body.appendChild( overlay );

	// Staggered item animation
	function animateMenuItems( open ) {
		menu.querySelectorAll( ':scope > li' ).forEach( ( item, i ) => {
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
		// Collapse all open submenus
		menu.querySelectorAll( 'li.submenu-open' ).forEach( li => li.classList.remove( 'submenu-open' ) );
	}

	button.addEventListener( 'click', function () {
		siteNavigation.classList.contains( 'toggled' ) ? closeMenu() : openMenu();
	} );

	overlay.addEventListener( 'click', closeMenu );

	// Close on ESC
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' ) closeMenu();
	} );

	// Close when a top-level menu link is clicked (not submenu toggles)
	menu.querySelectorAll( 'a' ).forEach( function ( link ) {
		link.addEventListener( 'click', function () {
			// Only close if it's not a parent link with submenu
			const parentLi = link.closest( 'li' );
			if ( ! parentLi || ! parentLi.classList.contains( 'menu-item-has-children' ) ) {
				closeMenu();
			}
		} );
	} );

	// Submenu toggles — insert chevron button next to parent links
	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );
	linksWithChildren.forEach( function ( link ) {
		const toggle = document.createElement( 'button' );
		toggle.className = 'submenu-toggle';
		toggle.setAttribute( 'aria-label', 'Toggle submenu' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		link.parentNode.appendChild( toggle );

		toggle.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			e.stopPropagation();
			const parent = this.closest( 'li' );
			const isOpen = parent.classList.contains( 'submenu-open' );

			// Close all siblings first
			if ( parent.parentNode ) {
				parent.parentNode.querySelectorAll( ':scope > li.submenu-open' ).forEach( li => {
					li.classList.remove( 'submenu-open' );
					const t = li.querySelector( ':scope > .submenu-toggle' );
					if ( t ) t.setAttribute( 'aria-expanded', 'false' );
				} );
			}

			parent.classList.toggle( 'submenu-open', ! isOpen );
			this.setAttribute( 'aria-expanded', String( ! isOpen ) );
		} );
	} );
}() );
