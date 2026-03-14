

/**
 * Customizer — Per-section Reset buttons
 *
 * Injects a small "↺ Reset section" button at the top of every
 * Customizer section. Clicking it reverts only that section's
 * settings to their registered defaults — instantly (live preview
 * updates immediately; Save & Publish makes it permanent).
 *
 * @package marsislav
 */
( function( $, api ) {

    /* ============================================================
     * 1. BUILD THE COMPLETE DEFAULTS MAP  (section → {key: default})
     * ============================================================ */

    var S = {};   // S['section_id'] = { 'setting_key': defaultValue, ... }

    function sec( id ) {
        if ( ! S[ id ] ) S[ id ] = {};
        return S[ id ];
    }

    /* ----------------------------------------------------------
     * Helpers to register groups of related settings
     * ---------------------------------------------------------- */

    // Background defaults for one area key
    function bgDefaults( area ) {
        return {
            [ 'bg_' + area + '_type'     ]: 'solid',
            [ 'bg_' + area + '_color'    ]: '',
            [ 'bg_' + area + '_grad1'    ]: '#ffffff',
            [ 'bg_' + area + '_grad2'    ]: '#eeeeee',
            [ 'bg_' + area + '_grad_dir' ]: 'to bottom',
            [ 'bg_' + area + '_image'    ]: '',
            [ 'bg_' + area + '_repeat'   ]: 'no-repeat',
            [ 'bg_' + area + '_size'     ]: 'cover',
        };
    }

    // Border-radius defaults for one key (global + 4 corners)
    function radiusDefaults( key, globalDefault ) {
        var d = {};
        d[ key ]        = globalDefault;
        d[ key + '_tl'] = '';
        d[ key + '_tr'] = '';
        d[ key + '_br'] = '';
        d[ key + '_bl'] = '';
        return d;
    }

    // Box-shadow defaults for one element key
    function shadowDefaults( elem ) {
        return {
            [ elem + '_shadow_type'    ]: 'none',
            [ elem + '_shadow_color'   ]: '#000000',
            [ elem + '_shadow_opacity' ]: 20,
            [ elem + '_shadow_x'       ]: 0,
            [ elem + '_shadow_y'       ]: 4,
            [ elem + '_shadow_blur'    ]: 8,
            [ elem + '_shadow_spread'  ]: 0,
        };
    }

    // Border defaults for one element key
    function borderDefaults( elem ) {
        return {
            [ elem + '_border_style'  ]: 'none',
            [ elem + '_border_color'  ]: '#e5e7eb',
            [ elem + '_border_width'  ]: 1,
            [ elem + '_border_top'    ]: '',
            [ elem + '_border_right'  ]: '',
            [ elem + '_border_bottom' ]: '',
            [ elem + '_border_left'   ]: '',
        };
    }

    // Typography defaults for one element key
    function typoDefaults( elem ) {
        var d = {};
        [ 'text','link','link_hover','h1','h2','h3','h4','h5','h6' ].forEach( function( sub ) {
            d[ 'typo_' + elem + '_' + sub ] = '';
        } );
        return d;
    }

    function assign( target, source ) {
        Object.keys( source ).forEach( function( k ) { target[ k ] = source[ k ]; } );
    }

    /* ----------------------------------------------------------
     * marsislav_sec_general
     * ---------------------------------------------------------- */
    assign( sec('marsislav_sec_general'), { header_sticky: true } );

    /* ----------------------------------------------------------
     * marsislav_sec_colors  (global text / link / heading colors)
     * ---------------------------------------------------------- */
    assign( sec('marsislav_sec_colors'), {
        color_body_text:         '#1f2937',
        color_body_link:         '#2563eb',
        color_body_link_hover:   '#1d4ed8',
        color_nav_link:          '#1f2937',
        color_nav_link_hover:    '#2563eb',
        color_footer_text:       '#1f2937',
        color_footer_link:       '#2563eb',
        color_footer_link_hover: '#1d4ed8',
        color_h1: '#1f2937', color_h2: '#1f2937', color_h3: '#1f2937',
        color_h4: '#1f2937', color_h5: '#1f2937', color_h6: '#1f2937',
    } );

    /* ----------------------------------------------------------
     * Per-element sections  (marsislav_elem_*)
     * ---------------------------------------------------------- */
    var elemSections = {
        global:         { bgArea: 'global',         radiusKey: 'radius_global',         radiusDef: 0,  hasShadowBorder: false, hasTypo: false },
        header:         { bgArea: 'header',         radiusKey: 'radius_header',         radiusDef: 0,  hasShadowBorder: true,  hasTypo: true  },
        content:        { bgArea: 'content',        radiusKey: 'radius_content',        radiusDef: 0,  hasShadowBorder: true,  hasTypo: true  },
        sidebar:        { bgArea: 'sidebar',        radiusKey: 'radius_sidebar',        radiusDef: 0,  hasShadowBorder: true,  hasTypo: true  },
        footer_widgets: { bgArea: 'footer_widgets', radiusKey: 'radius_footer_widgets', radiusDef: 0,  hasShadowBorder: true,  hasTypo: true  },
        footer:         { bgArea: 'footer',         radiusKey: 'radius_footer',         radiusDef: 0,  hasShadowBorder: true,  hasTypo: true  },
        copyright:      { bgArea: 'copyright',      radiusKey: 'radius_copyright',      radiusDef: 0,  hasShadowBorder: true,  hasTypo: true  },
        buttons:        { bgArea: 'buttons',        radiusKey: 'radius_buttons',        radiusDef: 4,  hasShadowBorder: true,  hasTypo: false },
        inputs:         { bgArea: 'inputs',         radiusKey: 'radius_inputs',         radiusDef: 4,  hasShadowBorder: true,  hasTypo: false },
        cards:          { bgArea: 'cards',          radiusKey: 'radius_cards',          radiusDef: 8,  hasShadowBorder: true,  hasTypo: true  },
        images:         { bgArea: 'images',         radiusKey: 'radius_images',         radiusDef: 0,  hasShadowBorder: true,  hasTypo: false },
    };

    Object.keys( elemSections ).forEach( function( elemKey ) {
        var cfg    = elemSections[ elemKey ];
        var secId  = 'marsislav_elem_' + elemKey;
        var target = sec( secId );

        assign( target, bgDefaults( cfg.bgArea ) );
        assign( target, radiusDefaults( cfg.radiusKey, cfg.radiusDef ) );
        if ( cfg.hasShadowBorder ) {
            assign( target, shadowDefaults( elemKey ) );
            assign( target, borderDefaults( elemKey ) );
        }
        if ( cfg.hasTypo ) {
            assign( target, typoDefaults( elemKey ) );
        }
    } );

    /* ----------------------------------------------------------
     * marsislav_sec_breadcrumbs
     * ---------------------------------------------------------- */
    assign( sec('marsislav_sec_breadcrumbs'), {
        breadcrumbs_enable:           true,
        breadcrumbs_bg:               '#f3f4f6',
        breadcrumbs_text_color:       '#6b7280',
        breadcrumbs_link_color:       '#2563eb',
        breadcrumbs_link_hover_color: '#1d4ed8',
    } );

    /* ----------------------------------------------------------
     * marsislav_sec_scroll_top
     * ---------------------------------------------------------- */
    assign( sec('marsislav_sec_scroll_top'), {
        scroll_to_top_enable:   true,
        scroll_to_top_bg:       '#2563eb',
        scroll_to_top_color:    '#ffffff',
        scroll_to_top_bg_hover: '#1d4ed8',
    } );

    /* ----------------------------------------------------------
     * marsislav_sec_dark_mode
     * ---------------------------------------------------------- */
    assign( sec('marsislav_sec_dark_mode'), {
        dark_mode_enable:    true,
    } );


    /* ----------------------------------------------------------
     * marsislav_footer_section
     * ---------------------------------------------------------- */
    assign( sec('marsislav_footer_section'), {
        footer_layout:         'one-column',
        footer_copyright_text: '',
        footer_col2_text:      '',
        show_footer_menu:      true,
        show_footer_credits:   true,
        footer_sidebar_enable: true,
        footer_sidebar_columns: '3',
    } );

    /* ----------------------------------------------------------
     * marsislav_topbar_section
     * ---------------------------------------------------------- */
    assign( sec('marsislav_topbar_section'), {
        topbar_enable:        false,
        topbar_layout:        'one',
        topbar_marquee:       false,
        topbar_marquee_speed: 18,
        topbar_text:          'Welcome to our website',
        topbar_text_color:    '#ffffff',
        topbar_bg_color:      '#1f2937',
        topbar_col1_text:     '',
        topbar_col2_text:     '',
    } );

    /* ----------------------------------------------------------
     * marsislav_sidebar_section
     * ---------------------------------------------------------- */
    assign( sec('marsislav_sidebar_section'), {
        sidebar_blog:    'right',
        sidebar_post:    'right',
        sidebar_page:    'disabled',
        sidebar_home:    'disabled',
        sidebar_shop:    'right',
        sidebar_product: 'disabled',
    } );

    /* ----------------------------------------------------------
     * Merge static defaults from PHP (footer / topbar / sidebar)
     * ---------------------------------------------------------- */
    if ( window.marsislavResetData && window.marsislavResetData.staticDefaults ) {
        var phpDefaults = window.marsislavResetData.staticDefaults;
        // Map each static key into its section bucket
        var phpSectionMap = {
            marsislav_footer_section:  [ 'footer_layout','footer_copyright_text','footer_col2_text',
                                         'show_footer_menu','show_footer_credits',
                                         'footer_sidebar_enable','footer_sidebar_columns' ],
            marsislav_topbar_section:  [ 'topbar_enable','topbar_layout','topbar_marquee',
                                         'topbar_marquee_speed','topbar_text','topbar_text_color',
                                         'topbar_bg_color','topbar_col1_text','topbar_col2_text' ],
            marsislav_sidebar_section: [ 'sidebar_blog','sidebar_post','sidebar_page',
                                         'sidebar_home','sidebar_shop','sidebar_product' ],
        };
        Object.keys( phpSectionMap ).forEach( function( secId ) {
            phpSectionMap[ secId ].forEach( function( key ) {
                if ( phpDefaults.hasOwnProperty( key ) ) {
                    sec( secId )[ key ] = phpDefaults[ key ];
                }
            } );
        } );
    }

    /* ============================================================
     * 2. INJECT RESET BUTTON INTO EVERY SECTION
     *    Uses the official WP Customizer API: section.container
     *    so the button is always inside the correct DOM node.
     * ============================================================ */

    var btnLabel   = '↺ Reset this section to defaults';
    var confirmMsg = 'Reset this section to defaults?\n\nClick Save & Publish afterwards to make it permanent.';
    var doneMsg    = '✓ Reset! Click Save & Publish to apply.';

    function buildButton( sectionId ) {
        var $btn = $(
            '<div class="marsislav-section-reset-wrap" style="' +
                'padding:12px 16px 4px 16px;' +
            '">' +
                '<button type="button" class="marsislav-section-reset-btn" style="' +
                    'display:block;width:100%;padding:6px 10px;' +
                    'font-size:11px;font-weight:600;cursor:pointer;' +
                    'color:#b32d2e;border:1px solid #b32d2e;' +
                    'background:#fff;border-radius:3px;' +
                    'transition:background .15s,color .15s;' +
                '">' + btnLabel + '</button>' +
            '</div>'
        );

        $btn.find( '.marsislav-section-reset-btn' ).on( 'click', function( e ) {
            e.preventDefault();
            e.stopPropagation();

            if ( ! window.confirm( confirmMsg ) ) return;

            var defaults = S[ sectionId ];
            if ( ! defaults ) return;

            Object.keys( defaults ).forEach( function( key ) {
                if ( api.has( key ) ) {
                    api( key ).set( defaults[ key ] );
                }
            } );

            var $self = $( this );
            $self.text( doneMsg )
                 .css( { color: '#166534', borderColor: '#166534', background: '#f0fdf4' } );
            setTimeout( function() {
                $self.text( btnLabel )
                     .css( { color: '#b32d2e', borderColor: '#b32d2e', background: '#fff' } );
            }, 3500 );
        } );

        return $btn;
    }

    api.bind( 'ready', function() {

        api.section.each( function( section ) {
            if ( ! S[ section.id ] ) return;

            function inject() {
                // contentContainer is the scrollable inner area of the section
                // It holds the actual controls list
                var $c = section.contentContainer;
                if ( ! $c || ! $c.length ) return;
                if ( $c.find( '.marsislav-section-reset-wrap' ).length ) return; // already there
                $c.prepend( buildButton( section.id ) );
            }

            // Inject on every expand (handles lazy-rendered panel sections)
            section.expanded.bind( function( isOpen ) {
                if ( isOpen ) setTimeout( inject, 50 );
            } );

            // Also try immediately in case already expanded
            if ( section.expanded() ) inject();
        } );
    } );

}( jQuery, wp.customize ) );
