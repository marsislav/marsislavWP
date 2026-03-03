/**
 * Customizer live preview — Colors, Backgrounds, Border Radius, Sticky Header
 *
 * @package marsislav
 */
( function( $, api ) {

    var data    = window.marsislavBgData || {};
    var imgUrls = data.imgUrls  || {};
    var ajaxUrl = data.ajaxUrl  || '';
    var nonce   = data.nonce    || '';

    var areaSelectors = {
        global:         'body',
        header:         'body #masthead',
        content:        'body #primary, body #content',
        sidebar:        'body #secondary',
        footer_widgets: 'body #footer-sidebar-area',
        footer:         'body #colophon',
        copyright:      'body #colophon .site-info',
    };

    /* -------------------------------------------------------
     * Helpers
     * ------------------------------------------------------- */

    function hexToRgba( hex, opacity ) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );
        if ( ! result ) return hex;
        return 'rgba(' + parseInt( result[1], 16 ) + ',' +
                         parseInt( result[2], 16 ) + ',' +
                         parseInt( result[3], 16 ) + ',' +
                         ( opacity / 100 ).toFixed(2) + ')';
    }

    function fetchImgUrl( imgId, callback ) {
        if ( ! imgId ) { callback( '' ); return; }
        if ( imgUrls[ imgId ] ) { callback( imgUrls[ imgId ] ); return; }
        $.get( ajaxUrl, { action: 'marsislav_bg_img', id: imgId, nonce: nonce } )
            .done( function( res ) {
                var url = ( res.success && res.data.url ) ? res.data.url : '';
                if ( url ) imgUrls[ imgId ] = url;
                callback( url );
            } )
            .fail( function() { callback( '' ); } );
    }

    function setStyle( id, css ) {
        $( '#marsislav-live-' + id ).remove();
        if ( css ) $( 'head' ).append( '<style id="marsislav-live-' + id + '">' + css + '</style>' );
    }

    /* -------------------------------------------------------
     * Build & apply background for one area
     * ------------------------------------------------------- */

    function applyBg( area ) {
        var selector = areaSelectors[ area ];
        if ( ! selector ) return;

        var type    = api( 'bg_' + area + '_type'     )();
        var color   = api( 'bg_' + area + '_color'    )();
        var opacity = parseFloat( api( 'bg_' + area + '_opacity' )() );
        var grad1   = api( 'bg_' + area + '_grad1'    )();
        var grad2   = api( 'bg_' + area + '_grad2'    )();
        var dir     = api( 'bg_' + area + '_grad_dir' )();
        var repeat  = api( 'bg_' + area + '_repeat'   )();
        var size    = api( 'bg_' + area + '_size'     )();
        var imgId   = api( 'bg_' + area + '_image'    )();

        // Глобален без !important; конкретни с !important
        var imp  = ( area === 'global' ) ? '' : ' !important';
        var css  = '';

        if ( type === 'transparent' ) {
            css = selector + '{background:transparent' + imp + ';}';
            setStyle( 'bg-' + area, css );
            return;
        }

        if ( type === 'solid' && color ) {
            var bgVal = ( opacity < 100 ) ? hexToRgba( color, opacity ) : color;
            css = selector + '{background:' + bgVal + imp + ';}';
            setStyle( 'bg-' + area, css );
            return;
        }

        if ( type === 'gradient' ) {
            css = selector + '{background:linear-gradient(' + dir + ',' + grad1 + ',' + grad2 + ')' + imp + ';}';
            setStyle( 'bg-' + area, css );
            return;
        }

        if ( type === 'image' ) {
            fetchImgUrl( imgId, function( url ) {
                if ( url ) {
                    var imgCss = selector + '{'
                        + 'background-image:url(' + url + ')' + imp + ';'
                        + 'background-repeat:' + repeat + ';'
                        + 'background-size:' + size + ';'
                        + 'background-position:center center;'
                        + ( opacity < 100 ? 'opacity:' + ( opacity / 100 ).toFixed(2) + ';' : '' )
                        + '}';
                    setStyle( 'bg-' + area, imgCss );
                } else {
                    setStyle( 'bg-' + area, '' );
                }
            } );
            return;
        }

        // Ако нищо не е избрано — изчисти
        setStyle( 'bg-' + area, '' );
    }

    /* -------------------------------------------------------
     * Bind background settings
     * ------------------------------------------------------- */

    var bgKeys = [ 'type', 'color', 'opacity', 'grad1', 'grad2', 'grad_dir', 'image', 'repeat', 'size' ];

    Object.keys( areaSelectors ).forEach( function( area ) {
        bgKeys.forEach( function( key ) {
            api( 'bg_' + area + '_' + key, function( value ) {
                value.bind( function() { applyBg( area ); } );
            } );
        } );
    } );

    /* -------------------------------------------------------
     * Sticky header
     * ------------------------------------------------------- */

    api( 'header_sticky', function( value ) {
        value.bind( function( sticky ) {
            if ( sticky ) {
                setStyle( 'sticky-header', '' );
            } else {
                setStyle( 'sticky-header', 'body #masthead{position:relative !important;top:auto !important;}' );
            }
        } );
    } );

    /* -------------------------------------------------------
     * Colors
     * ------------------------------------------------------- */

    var colorMap = {
        color_body_text:         'body,.site-content',
        color_body_link:         '.site-content a',
        color_body_link_hover:   '.site-content a:hover',
        color_nav_link:          '.primary-menu a',
        color_nav_link_hover:    '.primary-menu a:hover,.primary-menu .current-menu-item>a',
        color_footer_text:       '#colophon,#colophon .footer-sidebar-area',
        color_footer_link:       '#colophon a',
        color_footer_link_hover: '#colophon a:hover',
        color_h1: 'h1', color_h2: 'h2', color_h3: 'h3',
        color_h4: 'h4', color_h5: 'h5', color_h6: 'h6',
    };

    Object.keys( colorMap ).forEach( function( key ) {
        api( key, function( value ) {
            value.bind( function( newVal ) {
                setStyle( key, newVal ? colorMap[ key ] + '{color:' + newVal + ' !important;}' : '' );
            } );
        } );
    } );

    /* -------------------------------------------------------
     * Border radius
     * ------------------------------------------------------- */

    var radiusMap = {
        radius_header:         'body #masthead',
        radius_content:        'body #primary,body #content',
        radius_sidebar:        'body #secondary',
        radius_footer_widgets: 'body #footer-sidebar-area',
        radius_footer:         'body #colophon',
        radius_copyright:      'body #colophon .site-info',
        radius_buttons:        'a.button,.button,button,input[type="submit"],input[type="button"]',
        radius_inputs:         'input[type="text"],input[type="email"],input[type="search"],textarea',
        radius_cards:          '.post,.card,.entry,article',
        radius_images:         'img',
    };

    Object.keys( radiusMap ).forEach( function( key ) {
        api( key, function( value ) {
            value.bind( function( newVal ) {
                var px = parseInt( newVal, 10 );
                setStyle( key, radiusMap[ key ] + '{border-radius:' + px + 'px !important;}' );
            } );
        } );
    } );

}( jQuery, wp.customize ) );
