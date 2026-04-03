/**
 * Customizer live preview — Blog Meta, Mobile Menu, Submenu
 *
 * @package marsislav
 */

( function ( api ) {
	'use strict';

	function toggle( selector, visible ) {
		document.querySelectorAll( selector ).forEach( function ( el ) {
			el.style.display = visible ? '' : 'none';
		} );
	}

	function getStyleTag( id ) {
		var tag = document.getElementById( id );
		if ( ! tag ) {
			tag = document.createElement( 'style' );
			tag.id = id;
			document.head.appendChild( tag );
		}
		return tag;
	}

	/* Blog meta */
	api( 'blog_show_category', function ( s ) { s.bind( function ( v ) { toggle( '.post-card__category,.post-card__cat-inline,.post-card__cats', v ); } ); } );
	api( 'blog_show_author',   function ( s ) { s.bind( function ( v ) { toggle( '.post-card__author', v ); } ); } );
	api( 'blog_show_date',     function ( s ) { s.bind( function ( v ) { toggle( '.post-card__date', v ); } ); } );
	api( 'blog_show_comments', function ( s ) { s.bind( function ( v ) { toggle( '.comments-link', v ); } ); } );

	/* Header search */
	api( 'header_show_search', function ( setting ) {
		setting.bind( function ( val ) {
			toggle( '.marsislav-search-toggle', val );
			if ( ! val ) {
				var overlay = document.getElementById( 'marsislav-search-overlay' );
				if ( overlay ) { overlay.hidden = true; }
			}
		} );
	} );

	/* Mobile menu colors — correct selectors for the sidebar structure. */
	var mobileState = {
		mobile_menu_bg_color:         '',
		mobile_menu_text_color:       '',
		mobile_menu_text_hover_color: '',
		mobile_menu_icon_color:       '',
	};

	[ 'mobile_menu_bg_color', 'mobile_menu_text_color', 'mobile_menu_text_hover_color', 'mobile_menu_icon_color' ].forEach( function ( key ) {
		if ( api( key ) ) { mobileState[ key ] = api( key ).get(); }
	} );

	function rebuildMobileCSS() {
		var bg    = mobileState.mobile_menu_bg_color;
		var text  = mobileState.mobile_menu_text_color;
		var hover = mobileState.mobile_menu_text_hover_color;
		var icon  = mobileState.mobile_menu_icon_color;
		var css   = '';

		if ( bg )    css += '.marsislav-menu-sidebar{background-color:' + bg + ' !important;}';
		if ( text )  css += '.marsislav-mobile-nav a,.marsislav-mobile-nav .sub-menu a{color:' + text + ' !important;}';
		if ( hover ) css += '.marsislav-mobile-nav a:hover,.marsislav-mobile-nav li.current-menu-item>a{color:' + hover + ' !important;}';
		if ( icon )  css += '.menu-toggle,.menu-toggle .menu-text{color:' + icon + ' !important;}'
		                  + '.hamburger-lines span{background-color:' + icon + ' !important;}';

		getStyleTag( 'marsislav-mobile-menu-live' ).textContent = css;
	}

	[ 'mobile_menu_bg_color', 'mobile_menu_text_color', 'mobile_menu_text_hover_color', 'mobile_menu_icon_color' ].forEach( function ( key ) {
		api( key, function ( setting ) {
			setting.bind( function ( val ) { mobileState[ key ] = val; rebuildMobileCSS(); } );
		} );
	} );

	rebuildMobileCSS();

	/* Submenu colors */
	var submenuState = {
		submenu_bg_color:         '',
		submenu_text_color:       '',
		submenu_text_hover_color: '',
		submenu_bg_hover_color:   '',
		submenu_border_color:     '',
		submenu_border_radius:    6,
		mobile_submenu_bg_color:  '',
	};

	[ 'submenu_bg_color', 'submenu_text_color', 'submenu_text_hover_color',
	  'submenu_bg_hover_color', 'submenu_border_color', 'submenu_border_radius',
	  'mobile_submenu_bg_color' ].forEach( function ( key ) {
		if ( api( key ) ) { submenuState[ key ] = api( key ).get(); }
	} );

	function rebuildSubmenuCSS() {
		var s = submenuState;
		var css = '';

		if ( s.submenu_bg_color )         css += '.primary-menu .sub-menu{background:' + s.submenu_bg_color + ' !important;}';
		if ( s.submenu_text_color )       css += '.primary-menu .sub-menu a{color:' + s.submenu_text_color + ' !important;}';
		if ( s.submenu_text_hover_color ) css += '.primary-menu .sub-menu a:hover{color:' + s.submenu_text_hover_color + ' !important;}';
		if ( s.submenu_bg_hover_color )   css += '.primary-menu .sub-menu li:hover>a{background:' + s.submenu_bg_hover_color + ' !important;}';
		if ( s.submenu_border_color )     css += '.primary-menu .sub-menu{border-color:' + s.submenu_border_color + ' !important;}';
		if ( s.submenu_border_radius )    css += '.primary-menu .sub-menu{border-radius:' + s.submenu_border_radius + 'px !important;}';
		if ( s.mobile_submenu_bg_color )  css += '.marsislav-mobile-nav .sub-menu{background:' + s.mobile_submenu_bg_color + ' !important;}';

		getStyleTag( 'marsislav-submenu-live' ).textContent = css;
	}

	[ 'submenu_bg_color', 'submenu_text_color', 'submenu_text_hover_color',
	  'submenu_bg_hover_color', 'submenu_border_color', 'submenu_border_radius',
	  'mobile_submenu_bg_color' ].forEach( function ( key ) {
		api( key, function ( setting ) {
			setting.bind( function ( val ) { submenuState[ key ] = val; rebuildSubmenuCSS(); } );
		} );
	} );

	rebuildSubmenuCSS();

} )( wp.customize );
