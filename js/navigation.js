/**
 * File navigation.js.
 * Mobile dropdown menu.
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

	function openMenu() {
		menu.classList.add( 'mobile-open' );
		button.setAttribute( 'aria-expanded', 'true' );
		siteNavigation.classList.add( 'toggled' );
	}

	function closeMenu() {
		menu.classList.remove( 'mobile-open' );
		button.setAttribute( 'aria-expanded', 'false' );
		siteNavigation.classList.remove( 'toggled' );
		menu.querySelectorAll( 'li.submenu-open' ).forEach( function( li ) {
			li.classList.remove( 'submenu-open' );
		} );
	}

	button.addEventListener( 'click', function () {
		menu.classList.contains( 'mobile-open' ) ? closeMenu() : openMenu();
	} );

	// Затваряй при клик извън
	document.addEventListener( 'click', function ( e ) {
		if ( ! siteNavigation.contains( e.target ) ) {
			closeMenu();
		}
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' ) closeMenu();
	} );

	// Затваряй при клик на обикновен линк
	menu.querySelectorAll( 'a' ).forEach( function ( link ) {
		link.addEventListener( 'click', function () {
			var parentLi = link.closest( 'li' );
			if ( ! parentLi || ! parentLi.classList.contains( 'menu-item-has-children' ) ) {
				closeMenu();
			}
		} );
	} );

	// Submenu toggles
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
			var parent = this.closest( 'li' );
			var isOpen = parent.classList.contains( 'submenu-open' );

			// Затвори siblings
			if ( parent.parentNode ) {
				parent.parentNode.querySelectorAll( ':scope > li.submenu-open' ).forEach( function( li ) {
					li.classList.remove( 'submenu-open' );
					var t = li.querySelector( ':scope > .submenu-toggle' );
					if ( t ) t.setAttribute( 'aria-expanded', 'false' );
				} );
			}

			parent.classList.toggle( 'submenu-open', ! isOpen );
			this.setAttribute( 'aria-expanded', String( ! isOpen ) );
		} );
	} );
}() );
