/**
 * Customizer Tabs
 *
 * Разделя Customizer панелите на два таба:
 *   [🎨 Тема]     — всички marsislav_* панели
 *   [⚙ WordPress] — стандартните WP панели/секции
 *
 * Работи изцяло в controls iframe чрез WP Customizer API.
 *
 * @package marsislav
 */
( function( $, api ) {
    'use strict';

    /* IDs на НАШИТЕ панели */
    var THEME_PANELS = [
        'marsislav_header_panel',
        'marsislav_sidebar_panel',
        'marsislav_content_panel',
        'marsislav_footer_panel',
        'marsislav_design_panel',
    ];

    /* Текст на табовете */
    var TAB_THEME = '🎨 Тема';
    var TAB_WP    = '⚙ WordPress';

    /* Активен таб — запомняме в sessionStorage */
    var STORAGE_KEY = 'marsislav_customizer_tab';
    var activeTab   = sessionStorage.getItem( STORAGE_KEY ) || 'theme';

    /* CSS инжектиран веднъж */
    var CSS = [
        /* Скриване на оригиналната WP лента за търсене на панели */
        /* (запазваме я — просто я местим под табовете) */

        /* Контейнер на табовете */
        '#marsislav-tabs{',
            'display:flex;',
            'background:#1e1e1e;',
            'border-bottom:2px solid #2563eb;',
            'flex-shrink:0;',
        '}',

        /* Отделен таб */
        '.ms-tab{',
            'flex:1;',
            'padding:10px 6px;',
            'font-size:12px;',
            'font-weight:600;',
            'text-align:center;',
            'cursor:pointer;',
            'color:#9ca3af;',
            'border:none;',
            'background:transparent;',
            'transition:color .15s,background .15s;',
            'letter-spacing:.02em;',
            'line-height:1.3;',
        '}',

        /* Активен таб */
        '.ms-tab.is-active{',
            'color:#fff;',
            'background:#2563eb;',
        '}',

        '.ms-tab:hover:not(.is-active){',
            'color:#e5e7eb;',
            'background:#2d2d2d;',
        '}',
    ].join('');

    /* Инжектираме CSS в <head> на controls iframe */
    function injectCSS() {
        if ( document.getElementById('marsislav-tabs-css') ) return;
        var style = document.createElement('style');
        style.id  = 'marsislav-tabs-css';
        style.textContent = CSS;
        document.head.appendChild( style );
    }

    /* Инжектираме таб лентата точно преди #customize-info */
    function injectTabs() {
        if ( document.getElementById('marsislav-tabs') ) return;

        var outer = document.getElementById('customize-outer-theme-controls') ||
                    document.querySelector('.wp-full-overlay-sidebar-content') ||
                    document.getElementById('customize-controls');
        if ( ! outer ) return;

        /* Намираме #customize-info — вмъкваме след него */
        var info = document.getElementById('customize-info') ||
                   outer.querySelector('.customize-info');

        var bar = document.createElement('div');
        bar.id  = 'marsislav-tabs';

        var btnTheme = document.createElement('button');
        btnTheme.type      = 'button';
        btnTheme.className = 'ms-tab';
        btnTheme.dataset.tab = 'theme';
        btnTheme.textContent = TAB_THEME;

        var btnWP = document.createElement('button');
        btnWP.type      = 'button';
        btnWP.className = 'ms-tab';
        btnWP.dataset.tab = 'wp';
        btnWP.textContent = TAB_WP;

        bar.appendChild( btnTheme );
        bar.appendChild( btnWP );

        if ( info && info.parentNode ) {
            info.parentNode.insertBefore( bar, info.nextSibling );
        } else {
            outer.insertBefore( bar, outer.firstChild );
        }

        bar.addEventListener( 'click', function(e) {
            var btn = e.target.closest('.ms-tab');
            if ( ! btn ) return;
            switchTab( btn.dataset.tab );
        } );
    }

    /* Превключване между табовете */
    function switchTab( tab ) {
        activeTab = tab;
        sessionStorage.setItem( STORAGE_KEY, tab );

        /* Обновяване на визуалното активно състояние */
        document.querySelectorAll('.ms-tab').forEach( function(b) {
            b.classList.toggle( 'is-active', b.dataset.tab === tab );
        } );

        applyVisibility();
    }

    /**
     * Скриваме / показваме панелите и самостоятелните секции
     * (тези без панел — директно в корена на Customizer).
     *
     * Всеки .control-section (панел или секция) има data-type и id
     * в DOM-а на controls iframe.
     */
    function applyVisibility() {
        var isTheme = activeTab === 'theme';

        /* Панели */
        api.panel.each( function( panel ) {
            var isOurs = THEME_PANELS.indexOf( panel.id ) !== -1;
            var el = panel.headContainer && panel.headContainer[0];
            if ( ! el ) {
                el = document.getElementById( 'accordion-panel-' + panel.id );
            }
            if ( el ) {
                el.style.display = ( isOurs === isTheme ) ? '' : 'none';
            }
        } );

        /* Самостоятелни секции (без панел — напр. Site Identity, Menus…) */
        api.section.each( function( section ) {
            if ( section.panel() ) return; /* в панел — управлява се по-горе */

            /* Нашите "отсиротени" секции — нямат, пропускаме */
            var el = section.headContainer && section.headContainer[0];
            if ( ! el ) {
                el = document.getElementById( 'accordion-section-' + section.id );
            }
            if ( el ) {
                /* Standalone секции → показваме само в WP таб */
                el.style.display = isTheme ? 'none' : '';
            }
        } );
    }

    /* ── Вход ── */
    api.bind( 'ready', function() {
        injectCSS();
        injectTabs();

        /* Активираме запомнения таб */
        var btn = document.querySelector('.ms-tab[data-tab="' + activeTab + '"]');
        if ( btn ) btn.classList.add('is-active');
        applyVisibility();

        /* При навигация назад към корена — преприлагаме visibility */
        api.state('paneVisible').bind( function() {
            setTimeout( applyVisibility, 80 );
        } );

        /* Когато panel/section се expand → изчакваме return и пак прилагаме */
        api.panel.each( function(p) {
            p.expanded.bind( function(open) {
                if (!open) setTimeout( applyVisibility, 80 );
            });
        });
        api.section.each( function(s) {
            s.expanded.bind( function(open) {
                if (!open) setTimeout( applyVisibility, 80 );
            });
        });
    } );

}( jQuery, wp.customize ) );
