/**
 * Customizer — Per-section Reset buttons
 *
 * Injects a “↺ Reset” button at the top of each Customizer section.
 * On click :
 *  - settings with transport: postMessage → set() + force-fire callbacks → live preview instantly
 *  - settings with transport: refresh     → set() + api.previewer.refresh() at the end
 *
 * @package marsislav
 */
( function( $, api ) {

    /* ============================================================
     * 1. DEFAULTS MAP  (section_id → { setting_key: defaultValue })
     * ============================================================ */

    var S = {};

    function sec( id ) {
        if ( ! S[ id ] ) S[ id ] = {};
        return S[ id ];
    }

    function assign( target, source ) {
        Object.keys( source ).forEach( function( k ) { target[ k ] = source[ k ]; } );
    }

    /* -- Helpers for groups with related settings -- */

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

    function radiusDefaults( key, globalDefault ) {
        var d = {};
        d[ key ]         = globalDefault;
        d[ key + '_tl' ] = '';
        d[ key + '_tr' ] = '';
        d[ key + '_br' ] = '';
        d[ key + '_bl' ] = '';
        return d;
    }

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

    function typoDefaults( elem ) {
        var d = {};
        [ 'text','link','link_hover','h1','h2','h3','h4','h5','h6' ].forEach( function( sub ) {
            d[ 'typo_' + elem + '_' + sub ] = '';
        } );
        return d;
    }

    /* -- Sections -- */

    assign( sec('marsislav_sec_general'), { header_sticky: true } );

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

    var elemSections = {
        global:         { bgArea: 'global',         radiusKey: 'radius_global',         radiusDef: 0, hasShadowBorder: false, hasTypo: false },
        header:         { bgArea: 'header',         radiusKey: 'radius_header',         radiusDef: 0, hasShadowBorder: true,  hasTypo: true  },
        content:        { bgArea: 'content',        radiusKey: 'radius_content',        radiusDef: 0, hasShadowBorder: true,  hasTypo: true  },
        sidebar:        { bgArea: 'sidebar',        radiusKey: 'radius_sidebar',        radiusDef: 0, hasShadowBorder: true,  hasTypo: true  },
        footer_widgets: { bgArea: 'footer_widgets', radiusKey: 'radius_footer_widgets', radiusDef: 0, hasShadowBorder: true,  hasTypo: true  },
        footer:         { bgArea: 'footer',         radiusKey: 'radius_footer',         radiusDef: 0, hasShadowBorder: true,  hasTypo: true  },
        copyright:      { bgArea: 'copyright',      radiusKey: 'radius_copyright',      radiusDef: 0, hasShadowBorder: true,  hasTypo: true  },
        buttons:        { bgArea: 'buttons',        radiusKey: 'radius_buttons',        radiusDef: 4, hasShadowBorder: true,  hasTypo: false },
        inputs:         { bgArea: 'inputs',         radiusKey: 'radius_inputs',         radiusDef: 4, hasShadowBorder: true,  hasTypo: false },
        cards:          { bgArea: 'cards',          radiusKey: 'radius_cards',          radiusDef: 8, hasShadowBorder: true,  hasTypo: true  },
        images:         { bgArea: 'images',         radiusKey: 'radius_images',         radiusDef: 0, hasShadowBorder: true,  hasTypo: false },
    };

    Object.keys( elemSections ).forEach( function( elemKey ) {
        var cfg   = elemSections[ elemKey ];
        var secId = 'marsislav_elem_' + elemKey;
        var t     = sec( secId );

        assign( t, bgDefaults( cfg.bgArea ) );
        assign( t, radiusDefaults( cfg.radiusKey, cfg.radiusDef ) );
        if ( cfg.hasShadowBorder ) {
            assign( t, shadowDefaults( elemKey ) );
            assign( t, borderDefaults( elemKey ) );
        }
        if ( cfg.hasTypo ) {
            assign( t, typoDefaults( elemKey ) );
        }
    } );

    assign( sec('marsislav_sec_breadcrumbs'), {
        breadcrumbs_enable:           true,
        breadcrumbs_bg:               '#f3f4f6',
        breadcrumbs_text_color:       '#6b7280',
        breadcrumbs_link_color:       '#2563eb',
        breadcrumbs_link_hover_color: '#1d4ed8',
    } );

    assign( sec('marsislav_sec_scroll_top'), {
        scroll_to_top_enable:   true,
        scroll_to_top_bg:       '#2563eb',
        scroll_to_top_color:    '#ffffff',
        scroll_to_top_bg_hover: '#1d4ed8',
    } );

    assign( sec('marsislav_sec_dark_mode'), {
        dark_mode_enable: true,
    } );

    assign( sec('marsislav_footer_section'), {
        footer_layout:          'one-column',
        footer_copyright_text:  '',
        footer_col2_text:       '',
    } );

    assign( sec('marsislav_footer_menu_section'), {
        show_footer_menu:        true,
        show_footer_credits:     true,
        footer_powered_text:     'Proudly powered by %s',
        footer_credits_text:     'Theme: %1$s by %2$s.',
    } );

    assign( sec('marsislav_footer_widgets_section'), {
        footer_sidebar_enable:   true,
        footer_sidebar_columns:  '3',
    } );

    assign( sec('marsislav_footer_waves_section'), {
        footer_waves_enable: false,
        footer_wave_color1:  '#1e90ff',
        footer_wave_color2:  '#3aa0ff',
        footer_wave_color3:  '#63b3ff',
    } );

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

    assign( sec('marsislav_mobile_menu_section'), {
        mobile_menu_bg_color:         '',
        mobile_menu_text_color:       '',
        mobile_menu_text_hover_color: '',
        mobile_menu_icon_color:       '',
    } );

    assign( sec('marsislav_submenu_section'), {
        submenu_bg_color:         '',
        submenu_text_color:       '',
        submenu_text_hover_color: '',
        submenu_bg_hover_color:   '',
        submenu_border_color:     '',
        submenu_border_radius:    6,
        mobile_submenu_bg_color:  '',
    } );

    assign( sec('marsislav_header_search_section'), {
        header_show_search: true,
    } );

    assign( sec('marsislav_blog_meta_section'), {
        blog_show_category: true,
        blog_show_author:   true,
        blog_show_date:     true,
        blog_show_comments: true,
    } );

    assign( sec('marsislav_sec_page_title'), {
        show_title_page:     true,
        show_title_post:     true,
        show_title_archive:  true,
        show_title_category: true,
        show_title_home:     true,
    } );

    // Sidebar position 
    var sidebarContexts = [ 'blog','post','page','home','shop','product' ];
    var sidebarPosDefs  = { blog:'right', post:'right', page:'disabled', home:'disabled', shop:'right', product:'disabled' };
    var sidebarIdDefs   = { blog:'sidebar-blog', post:'sidebar-post', page:'sidebar-page', home:'sidebar-blog', shop:'sidebar-shop', product:'sidebar-product' };

    sidebarContexts.forEach( function( ctx ) {
        assign( sec( 'marsislav_sidebar_section_' + ctx ), {
            [ 'sidebar_pos_' + ctx ]: sidebarPosDefs[ ctx ],
            [ 'sidebar_id_'  + ctx ]: sidebarIdDefs[ ctx ],
        } );
    } );

    /* ============================================================
     *RESET LOGIC — with support for postMessage and refresh
     * ============================================================ */

  /**
 * Applies reset to a given section.
 *
 * For postMessage settings: api(key).set() automatically sends
 * the value to the preview iframe.
 *
 * For refresh settings: api(key).set() only marks the setting
 * as "dirty" — the preview is NOT updated until we call
 * api.previewer.refresh().
 *
 * Solution: after setting all values, we check if there is
 * at least one refresh setting and if so — we refresh the preview.
 */
    function applyReset( sectionId ) {
        var defaults = S[ sectionId ];
        if ( ! defaults ) return;

        var needsRefresh = false;

        Object.keys( defaults ).forEach( function( key ) {
            if ( ! api.has( key ) ) return;

            var setting = api( key );
            var currentVal = setting.get();
            var newVal     = defaults[ key ];

            // Skipped if the value is already the default.
            if ( currentVal === newVal ) return;

            // Set new value
            setting.set( newVal );

            // Check transport
            var transport = setting.transport || 'refresh';
            if ( transport === 'refresh' ) {
                needsRefresh = true;
            }
            // For postMessage — set() has already sent a message to the preview.
        } );

        // If there are refresh settings — we refresh the preview iframe.
        if ( needsRefresh && api.previewer ) {
            // A short delay to allow all set() calls to complete.
            setTimeout( function() {
                api.previewer.refresh();
            }, 100 );
        }

        return needsRefresh;
    }

    /* ============================================================
     * 3. BUTTON INJECTION
     * ============================================================ */

    var btnLabel   = '↺ Reset section';
    var doneLabel  = '✓ Reset! Click Save & Publish.';
    var doneRefLabel = '✓ Reset! Preview is loading...';

    function buildButton( sectionId ) {
        var $wrap = $(
            '<div class="marsislav-section-reset-wrap" style="padding:10px 16px 2px;">' +
                '<button type="button" class="marsislav-section-reset-btn" style="' +
                    'display:block;width:100%;padding:6px 10px;' +
                    'font-size:11px;font-weight:600;cursor:pointer;' +
                    'color:#b32d2e;border:1px solid #b32d2e;' +
                    'background:#fff;border-radius:3px;' +
                    'transition:background .15s,color .15s;' +
                '">' + btnLabel + '</button>' +
            '</div>'
        );

        $wrap.find( '.marsislav-section-reset-btn' ).on( 'click', function( e ) {
            e.preventDefault();
            e.stopPropagation();

            if ( ! window.confirm( 'Reset this section to defaults?\n\nSave & Publish afterwards to make it permanent.' ) ) return;

            var hadRefresh = applyReset( sectionId );

            var $self = $( this );
            var label = hadRefresh ? doneRefLabel : doneLabel;

            $self.text( label )
                 .css( { color: '#166534', borderColor: '#166534', background: '#f0fdf4' } );

            setTimeout( function() {
                $self.text( btnLabel )
                     .css( { color: '#b32d2e', borderColor: '#b32d2e', background: '#fff' } );
            }, 3500 );
        } );

        return $wrap;
    }

    api.bind( 'ready', function() {
        api.section.each( function( section ) {
            if ( ! S[ section.id ] ) return;

            function inject() {
                var $c = section.contentContainer;
                if ( ! $c || ! $c.length ) return;
                if ( $c.find( '.marsislav-section-reset-wrap' ).length ) return;
                $c.prepend( buildButton( section.id ) );
            }

            section.expanded.bind( function( isOpen ) {
                if ( isOpen ) setTimeout( inject, 50 );
            } );

            if ( section.expanded() ) inject();
        } );
    } );

}( jQuery, wp.customize ) );
