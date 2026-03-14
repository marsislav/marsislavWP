/**
 * Customizer live preview
 *
 * Handles postMessage transport for:
 *  - blog_show_category
 *  - blog_show_author
 *  - blog_show_date
 *  - blog_show_comments
 *  - header_show_search
 *  - mobile_menu_bg_color
 *  - mobile_menu_text_color
 *  - mobile_menu_icon_color
 *
 * @package marsislav
 */

( function ( api ) {
	'use strict';

	/* ── helpers ── */

	function toggle( selector, visible ) {
		document.querySelectorAll( selector ).forEach( function ( el ) {
			el.style.display = visible ? '' : 'none';
		} );
	}

	/* ── Blog meta: category ── */
	api( 'blog_show_category', function ( setting ) {
		setting.bind( function ( val ) {
			toggle( '.post-card__category', val );
			toggle( '.post-card__cat-inline', val );
			toggle( '.post-card__cats', val );
		} );
	} );

	/* ── Blog meta: author ── */
	api( 'blog_show_author', function ( setting ) {
		setting.bind( function ( val ) {
			toggle( '.post-card__author', val );
		} );
	} );

	/* ── Blog meta: date ── */
	api( 'blog_show_date', function ( setting ) {
		setting.bind( function ( val ) {
			toggle( '.post-card__date', val );
		} );
	} );

	/* ── Blog meta: comments ── */
	api( 'blog_show_comments', function ( setting ) {
		setting.bind( function ( val ) {
			toggle( '.comments-link', val );
		} );
	} );

	/* ── Header search: show/hide ── */
	api( 'header_show_search', function ( setting ) {
		setting.bind( function ( val ) {
			toggle( '.marsislav-search-toggle', val );
			if ( ! val ) {
				var overlay = document.getElementById( 'marsislav-search-overlay' );
				if ( overlay ) { overlay.hidden = true; }
			}
			toggle( '#marsislav-search-overlay', val );
		} );
	} );

	/* ── Mobile menu colors ── */

	function getMobileStyleTag() {
		var tag = document.getElementById( 'marsislav-mobile-menu-colors' );
		if ( ! tag ) {
			tag = document.createElement( 'style' );
			tag.id = 'marsislav-mobile-menu-colors';
			document.head.appendChild( tag );
		}
		return tag;
	}

	var mobileColors = {
		mobile_menu_bg_color   : api( 'mobile_menu_bg_color'   ) ? api( 'mobile_menu_bg_color'   ).get() : '',
		mobile_menu_text_color : api( 'mobile_menu_text_color' ) ? api( 'mobile_menu_text_color' ).get() : '',
		mobile_menu_icon_color : api( 'mobile_menu_icon_color' ) ? api( 'mobile_menu_icon_color' ).get() : '',
	};

	function rebuildMobileCSS() {
		var bg   = mobileColors.mobile_menu_bg_color;
		var text = mobileColors.mobile_menu_text_color;
		var icon = mobileColors.mobile_menu_icon_color;

		var css = '@media (max-width: 899px) {';

		if ( bg ) {
			css += '#site-navigation .primary-menu.mobile-open,' +
			       '#site-navigation .nav-menu.mobile-open {' +
			       'background-color:' + bg + ';}';
		}

		if ( text ) {
			css += '#site-navigation .primary-menu.mobile-open a,' +
			       '#site-navigation .nav-menu.mobile-open a {' +
			       'color:' + text + ';}';
		}

		if ( icon ) {
			css += '.menu-toggle,.menu-toggle .menu-text{color:' + icon + ';}' +
			       '.hamburger-lines span{background-color:' + icon + ';}';
		}

		css += '}';
		getMobileStyleTag().textContent = css;
	}

	[ 'mobile_menu_bg_color', 'mobile_menu_text_color', 'mobile_menu_icon_color' ].forEach( function ( key ) {
		api( key, function ( setting ) {
			setting.bind( function ( val ) {
				mobileColors[ key ] = val;
				rebuildMobileCSS();
			} );
		} );
	} );

} )( wp.customize );
