/**
 * Customizer live preview — Colors & Backgrounds
 *
 * Стратегия за наслагване:
 *  - Глобалният фон се пише в #marsislav-bg-global БЕЗ !important
 *  - Всяка конкретна зона се пише в #marsislav-bg-{area} С !important
 *  - Така конкретната зона винаги презаписва глобалната за себе си,
 *    но глобалната остава видима за останалите зони.
 *
 * @package marsislav
 */
( function( $, api ) {

    var data    = window.marsislavBgData || {};
    var imgUrls = data.imgUrls  || {};
    var ajaxUrl = data.ajaxUrl  || '';
    var nonce   = data.nonce    || '';

    // Конкретните зони с 'body ' prefix за по-висока специфичност
    var areaSelectors = {
        global:    'body',
        header:    'body #masthead',
        content:   'body #primary, body #content',
        sidebar:   'body #secondary',
        footer:    'body #colophon',
        copyright: 'body #colophon .site-info',
    };

    /* -------------------------------------------------------
     * Fetch image URL via AJAX and cache
     * ------------------------------------------------------- */
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

    /* -------------------------------------------------------
     * Build CSS string for one area
     * ------------------------------------------------------- */
    function buildBgCSS( area, selector, url ) {
        var type   = api( 'bg_' + area + '_type'     )();
        var color  = api( 'bg_' + area + '_color'    )();
        var grad1  = api( 'bg_' + area + '_grad1'    )();
        var grad2  = api( 'bg_' + area + '_grad2'    )();
        var dir    = api( 'bg_' + area + '_grad_dir' )();
        var repeat = api( 'bg_' + area + '_repeat'   )();
        var size   = api( 'bg_' + area + '_size'     )();

        // Глобалният фон — без !important за да може конкретната зона да го override-не
        var imp = ( area === 'global' ) ? '' : ' !important';
        var css = '';

        if ( type === 'solid' && color ) {
            css = selector + '{background:' + color + imp + ';}';

        } else if ( type === 'gradient' ) {
            css = selector + '{background:linear-gradient(' + dir + ',' + grad1 + ',' + grad2 + ')' + imp + ';}';

        } else if ( type === 'image' && url ) {
            css = selector + '{'
                + 'background-image:url(' + url + ')' + imp + ';'
                + 'background-repeat:' + repeat + ';'
                + 'background-size:' + size + ';'
                + 'background-position:center center;'
                + '}';
        }

        return css;
    }

    /* -------------------------------------------------------
     * Write/update <style> tag for one area
     * ------------------------------------------------------- */
    function applyBg( area ) {
        var selector = areaSelectors[ area ];
        if ( ! selector ) return;
        var styleId  = 'marsislav-bg-' + area;
        var imgId    = api( 'bg_' + area + '_image' )();

        if ( api( 'bg_' + area + '_type' )() === 'image' && imgId ) {
            fetchImgUrl( imgId, function( url ) {
                var css = buildBgCSS( area, selector, url );
                $( '#' + styleId ).remove();
                if ( css ) $( 'head' ).append( '<style id="' + styleId + '">' + css + '</style>' );
            } );
        } else {
            var css = buildBgCSS( area, selector, '' );
            $( '#' + styleId ).remove();
            if ( css ) $( 'head' ).append( '<style id="' + styleId + '">' + css + '</style>' );
        }
    }

    /* -------------------------------------------------------
     * Bind background settings
     * ------------------------------------------------------- */
    var bgKeys = [ 'type', 'color', 'grad1', 'grad2', 'grad_dir', 'image', 'repeat', 'size' ];

    Object.keys( areaSelectors ).forEach( function( area ) {
        bgKeys.forEach( function( key ) {
            api( 'bg_' + area + '_' + key, function( value ) {
                value.bind( function() { applyBg( area ); } );
            } );
        } );
    } );

    /* -------------------------------------------------------
     * Bind color settings
     * ------------------------------------------------------- */
    var colorMap = {
        color_body_text:         'body, .site-content',
        color_body_link:         '.site-content a',
        color_body_link_hover:   '.site-content a:hover',
        color_nav_link:          '.primary-menu a',
        color_nav_link_hover:    '.primary-menu a:hover, .primary-menu .current-menu-item > a',
        color_footer_text:       '#colophon',
        color_footer_link:       '#colophon a',
        color_footer_link_hover: '#colophon a:hover',
        color_h1: 'h1', color_h2: 'h2', color_h3: 'h3',
        color_h4: 'h4', color_h5: 'h5', color_h6: 'h6',
    };

    Object.keys( colorMap ).forEach( function( key ) {
        api( key, function( value ) {
            value.bind( function( newVal ) {
                var styleId = 'marsislav-live-' + key;
                $( '#' + styleId ).remove();
                if ( newVal ) {
                    $( 'head' ).append(
                        '<style id="' + styleId + '">'
                        + colorMap[ key ] + '{color:' + newVal + ' !important;}'
                        + '</style>'
                    );
                }
            } );
        } );
    } );

}( jQuery, wp.customize ) );
