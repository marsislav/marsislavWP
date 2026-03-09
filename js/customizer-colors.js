/**
 * Customizer live preview
 * Handles: backgrounds (incl. images), colors, radius, shadow, border, typography
 *
 * @package marsislav
 */
( function( $, api ) {

    var data    = window.marsislavBgData || {};
    var imgUrls = data.imgUrls  || {};   // pre-seeded image URLs keyed by '__area__' + area



    /* -------------------------------------------------------
     * CSS selectors per element key
     * ----------------------------------------------------- */
    var SEL = {
        global:         'body',
        header:         'body #masthead',
        content:        'body #primary,body #content',
        sidebar:        'body #secondary',
        footer_widgets: 'body #footer-sidebar-area',
        footer:         'body #colophon',
        copyright:      'body #colophon .site-info',
        buttons:        'a.button,.button,button,input[type="submit"],input[type="button"]',
        inputs:         'input[type="text"],input[type="email"],input[type="search"],textarea',
        cards:          '.post,.card,.entry,article',
        images:         'img',
    };

    /* -------------------------------------------------------
     * Utilities
     * ----------------------------------------------------- */

    function hexToRgba( hex, pct ) {
        var r = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );
        if ( ! r ) return hex;
        return 'rgba(' + parseInt(r[1],16) + ',' + parseInt(r[2],16) + ',' + parseInt(r[3],16) + ',' + (pct/100).toFixed(2) + ')';
    }

    // Inject or remove a <style> block in the preview <head>
    function css( id, rule ) {
        var el = document.getElementById( 'ms-live-' + id );
        if ( rule ) {
            if ( ! el ) {
                el = document.createElement('style');
                el.id = 'ms-live-' + id;
                document.head.appendChild( el );
            }
            el.textContent = rule;
        } else if ( el ) {
            el.parentNode.removeChild( el );
        }
    }

    // Read a setting value safely (returns '' if not found)
    function get( key ) {
        return api.has( key ) ? api( key ).get() : '';
    }


    function getImgUrl( imgId, cb, area ) {
        // imgId is now a URL string (WP_Customize_Image_Control stores URL directly)
        if ( imgId && /^https?:\/\//.test( imgId ) ) {
            cb( imgId );
            return;
        }
        // Fallback: check area cache from pre-seeded data
        if ( area && imgUrls[ '__area__' + area ] ) {
            cb( imgUrls[ '__area__' + area ] );
            return;
        }
        cb('');
    }

    /* -------------------------------------------------------
     * Background
     * ----------------------------------------------------- */
    function applyBg( area ) {
        var sel  = SEL[ area ];
        if ( ! sel ) return;
        var type    = get( 'bg_' + area + '_type'     ) || 'solid';
        var color   = get( 'bg_' + area + '_color'    );
        var grad1   = get( 'bg_' + area + '_grad1'    ) || '#ffffff';
        var grad2   = get( 'bg_' + area + '_grad2'    ) || '#eeeeee';
        var dir     = get( 'bg_' + area + '_grad_dir' ) || 'to bottom';
        var repeat  = get( 'bg_' + area + '_repeat'   ) || 'no-repeat';
        var size    = get( 'bg_' + area + '_size'     ) || 'cover';
        var imgId   = get( 'bg_' + area + '_image'    );
        var imp     = area === 'global' ? '' : ' !important';

        if ( type === 'solid' && color ) {
            var v = color;
            css( 'bg-'+area, sel+'{background:'+v+imp+';}' );
            return;
        }
        if ( type === 'gradient' ) {
            css( 'bg-'+area, sel+'{background:linear-gradient('+dir+','+grad1+','+grad2+')'+imp+';}' );
            return;
        }
        if ( type === 'image' ) {
            getImgUrl( imgId, function(url) {
                if ( url ) {
                    css( 'bg-'+area,
                        sel+'{background-image:url('+url+')'+imp+';'
                           +'background-repeat:'+repeat+imp+';'
                           +'background-size:'+size+imp+';'
                           +'background-position:center center;}'
                    );
                } else { css('bg-'+area,''); }
            }, area );
            return;
        }
        css( 'bg-'+area, '' );
    }

    var BG_KEYS = ['type','color','grad1','grad2','grad_dir','image','repeat','size'];
    Object.keys(SEL).forEach(function(area){
        BG_KEYS.forEach(function(k){
            api( 'bg_'+area+'_'+k, function(v){ v.bind(function(){ applyBg(area); }); } );
        });
    });

    /* -------------------------------------------------------
     * Sticky header
     * ----------------------------------------------------- */
    api( 'header_sticky', function(v){
        v.bind(function(on){
            css('sticky', on ? '' : 'body #masthead{position:relative !important;top:auto !important;}');
        });
    });

    /* -------------------------------------------------------
     * Global colors
     * ----------------------------------------------------- */
    var COLOR_MAP = {
        color_body_text:         'html body,html .site-content',
        color_body_link:         'html .site-content a',
        color_body_link_hover:   'html .site-content a:hover',
        color_nav_link:          'html .primary-menu a',
        color_nav_link_hover:    'html .primary-menu a:hover,html .primary-menu .current-menu-item>a',
        color_footer_text:       'html #colophon,html #colophon .footer-sidebar-area',
        color_footer_link:       'html #colophon a',
        color_footer_link_hover: 'html #colophon a:hover',
        color_h1:'html h1', color_h2:'html h2', color_h3:'html h3',
        color_h4:'html h4', color_h5:'html h5', color_h6:'html h6',
    };
    Object.keys(COLOR_MAP).forEach(function(key){
        api( key, function(v){
            function updateColor(c){ css( key, c ? COLOR_MAP[key]+'{color:'+c+' !important;}' : '' ); }
            updateColor( v.get() );
            v.bind( updateColor );
        });
    });

    /* -------------------------------------------------------
     * Border radius (global slider + 4 corners)
     * ----------------------------------------------------- */
    var RADIUS_SEL = {
        radius_global:         'body',
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

    function applyRadius(rk){
        var sel = RADIUS_SEL[rk]; if(!sel) return;
        var g  = parseInt(get(rk)||'0',10);
        var tl = get(rk+'_tl'); tl = tl!==''?parseInt(tl,10):g;
        var tr = get(rk+'_tr'); tr = tr!==''?parseInt(tr,10):g;
        var br = get(rk+'_br'); br = br!==''?parseInt(br,10):g;
        var bl = get(rk+'_bl'); bl = bl!==''?parseInt(bl,10):g;
        // Skip if all corners are 0 (matches PHP behavior)
        if ( tl === 0 && tr === 0 && br === 0 && bl === 0 ) {
            css(rk, '');
            reapplyColors();
            return;
        }
        var val = (tl===tr&&tr===br&&br===bl) ? tl+'px' : tl+'px '+tr+'px '+br+'px '+bl+'px';
        css(rk, sel+'{border-radius:'+val+' !important;}');
        reapplyColors();
    }

    var _reapplyTimer = null;
    function reapplyColors(){
        clearTimeout( _reapplyTimer );
        _reapplyTimer = setTimeout( function() {
            Object.keys(COLOR_MAP).forEach(function(key){
                var c = get(key);
                css( key, c ? COLOR_MAP[key]+'{color:'+c+' !important;}' : '' );
            });
            Object.keys(TYPO_ELEMS).forEach(function(elem){
                var scope = TYPO_ELEMS[elem];
                Object.keys(TYPO_RULES).forEach(function(sub){
                    var key = 'typo_'+elem+'_'+sub;
                    var c = get(key);
                    if(c) css(key, TYPO_RULES[sub](scope, c));
                });
            });
        }, 50 );
    }
    Object.keys(RADIUS_SEL).forEach(function(rk){
        ['','_tl','_tr','_br','_bl'].forEach(function(s){
            api(rk+s,function(v){ v.bind(function(){ applyRadius(rk); }); });
        });
    });

    /* -------------------------------------------------------
     * Box shadow
     * ----------------------------------------------------- */
    var SHADOW_ELEMS = ['header','content','sidebar','footer_widgets','footer','copyright','buttons','inputs','cards','images'];

    function applyShadow(elem){
        var sel = SEL[elem]; if(!sel) return;
        var type = get(elem+'_shadow_type')||'none';
        if(type==='none'){ css('sh-'+elem,''); reapplyColors(); return; }
        var color   = get(elem+'_shadow_color')||'#000000';
        var opacity = parseFloat(get(elem+'_shadow_opacity')); if(isNaN(opacity)) opacity=20;
        var x       = parseInt(get(elem+'_shadow_x')||'0',10);
        var y       = parseInt(get(elem+'_shadow_y')||'4',10);
        var blur    = parseInt(get(elem+'_shadow_blur')||'8',10);
        var spread  = parseInt(get(elem+'_shadow_spread')||'0',10);
        var rgba    = hexToRgba(color,opacity);
        var inset   = type==='inset'?'inset ':'';
        css('sh-'+elem, sel+'{box-shadow:'+inset+x+'px '+y+'px '+blur+'px '+spread+'px '+rgba+' !important;}');
        reapplyColors();
    }
    SHADOW_ELEMS.forEach(function(elem){
        ['_shadow_type','_shadow_color','_shadow_opacity','_shadow_x','_shadow_y','_shadow_blur','_shadow_spread'].forEach(function(s){
            api(elem+s, function(v){ v.bind(function(){ applyShadow(elem); }); });
        });
    });

    /* -------------------------------------------------------
     * Border
     * ----------------------------------------------------- */
    function applyBorder(elem){
        var sel = SEL[elem]; if(!sel) return;
        var style = get(elem+'_border_style')||'none';
        if(style==='none'){ css('bd-'+elem,''); reapplyColors(); return; }
        var color  = get(elem+'_border_color')||'#e5e7eb';
        var w      = parseInt(get(elem+'_border_width')||'1',10);
        var wt = get(elem+'_border_top');    wt = wt!==''?parseInt(wt,10):w;
        var wr = get(elem+'_border_right');  wr = wr!==''?parseInt(wr,10):w;
        var wb = get(elem+'_border_bottom'); wb = wb!==''?parseInt(wb,10):w;
        var wl = get(elem+'_border_left');   wl = wl!==''?parseInt(wl,10):w;
        var rule = (wt===wr&&wr===wb&&wb===wl)
            ? sel+'{border:'+wt+'px '+style+' '+color+' !important;}'
            : sel+'{border-style:'+style+' !important;border-color:'+color+' !important;border-width:'+wt+'px '+wr+'px '+wb+'px '+wl+'px !important;}';
        css('bd-'+elem, rule);
        reapplyColors();
    }
    SHADOW_ELEMS.forEach(function(elem){
        ['_border_style','_border_color','_border_width','_border_top','_border_right','_border_bottom','_border_left'].forEach(function(s){
            api(elem+s, function(v){ v.bind(function(){ applyBorder(elem); }); });
        });
    });

    /* -------------------------------------------------------
     * Per-element typography (text / links / H1–H6)
     * ----------------------------------------------------- */
    var TYPO_ELEMS = {
        header:         'body #masthead',
        content:        'body #primary,body #content',
        sidebar:        'body #secondary',
        footer_widgets: 'body #footer-sidebar-area',
        footer:         'body #colophon',
        copyright:      'body #colophon .site-info',
        cards:          '.post,.card,.entry,article',
    };
    var TYPO_RULES = {
        text:       function(s,c){ return 'html '+s+'{color:'+c+' !important;}'; },
        link:       function(s,c){ return 'html '+s+' a{color:'+c+' !important;}'; },
        link_hover: function(s,c){ return 'html '+s+' a:hover{color:'+c+' !important;}'; },
        h1: function(s,c){ return 'html '+s+' h1{color:'+c+' !important;}'; },
        h2: function(s,c){ return 'html '+s+' h2{color:'+c+' !important;}'; },
        h3: function(s,c){ return 'html '+s+' h3{color:'+c+' !important;}'; },
        h4: function(s,c){ return 'html '+s+' h4{color:'+c+' !important;}'; },
        h5: function(s,c){ return 'html '+s+' h5{color:'+c+' !important;}'; },
        h6: function(s,c){ return 'html '+s+' h6{color:'+c+' !important;}'; },
    };

    Object.keys(TYPO_ELEMS).forEach(function(elem){
        var scope = TYPO_ELEMS[elem];
        Object.keys(TYPO_RULES).forEach(function(sub){
            var key = 'typo_'+elem+'_'+sub;
            var fn  = TYPO_RULES[sub];
            // Use api() callback — fires when setting is registered regardless of transport
            api( key, function(v){
                // Bind immediately to current value AND future changes
                function update(c){ css(key, c ? fn(scope,c) : ''); }
                update( v.get() );   // apply current value right away
                v.bind( update );
            });
        });
    });

    /* -------------------------------------------------------
     * Scroll-to-Top colors
     * ----------------------------------------------------- */
    api('scroll_to_top_bg',    function(v){ v.bind(function(c){ css('stt_bg',    c?'#marsislav-scroll-top{background:'+c+' !important;}':''); }); });
    api('scroll_to_top_color', function(v){ v.bind(function(c){ css('stt_color', c?'#marsislav-scroll-top{color:'+c+' !important;}#marsislav-scroll-top svg{stroke:'+c+' !important;}':''); }); });
    api('scroll_to_top_bg_hover',function(v){ v.bind(function(c){ css('stt_hover',c?'#marsislav-scroll-top:hover{background:'+c+' !important;}':''); }); });

    /* -------------------------------------------------------
     * Breadcrumbs colors
     * ----------------------------------------------------- */
    api('breadcrumbs_bg',              function(v){ v.bind(function(c){ css('bc_bg',   c?'.marsislav-breadcrumbs{background:'+c+' !important;}':''); }); });
    api('breadcrumbs_text_color',      function(v){ v.bind(function(c){ css('bc_text', c?'.marsislav-breadcrumbs,.bc-sep,.bc-current{color:'+c+' !important;}':''); }); });
    api('breadcrumbs_link_color',      function(v){ v.bind(function(c){ css('bc_link', c?'.marsislav-breadcrumbs .bc-link{color:'+c+' !important;}':''); }); });
    api('breadcrumbs_link_hover_color',function(v){ v.bind(function(c){ css('bc_hover',c?'.marsislav-breadcrumbs .bc-link:hover{color:'+c+' !important;}':''); }); });


}( jQuery, wp.customize ) );
