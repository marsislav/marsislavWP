/**
 * Dark Mode — filter-based approach
 * Darkens EVERYTHING: solid colors, gradients, images.
 * Text stays readable via selective invert.
 * @package marsislav
 */
( function () {
    var STORAGE_KEY = 'marsislav_dark_mode';
    var CLASS       = 'dark-mode';
    var html        = document.documentElement;

    function getInitialMode() {
        var stored = localStorage.getItem( STORAGE_KEY );
        if ( stored === 'on' )  return true;
        if ( stored === 'off' ) return false;
        return window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
    }

    function setDarkMode( on ) {
        if ( on ) {
            html.classList.add( CLASS );
            localStorage.setItem( STORAGE_KEY, 'on' );
        } else {
            html.classList.remove( CLASS );
            localStorage.setItem( STORAGE_KEY, 'off' );
        }
        updateButtons( on );
    }

    function updateButtons( on ) {
        var btns = document.querySelectorAll( '.marsislav-dark-toggle' );
        for ( var i = 0; i < btns.length; i++ ) {
            btns[i].setAttribute( 'aria-pressed', on ? 'true' : 'false' );
            btns[i].setAttribute( 'title', on ? btns[i].dataset.labelLight : btns[i].dataset.labelDark );
        }
    }

    /* Apply IMMEDIATELY — before paint, no flash */
    if ( getInitialMode() ) {
        html.classList.add( CLASS );
    }

    document.addEventListener( 'DOMContentLoaded', function () {
        updateButtons( html.classList.contains( CLASS ) );

        document.addEventListener( 'click', function ( e ) {
            var btn = e.target.closest( '.marsislav-dark-toggle' );
            if ( ! btn ) return;
            setDarkMode( ! html.classList.contains( CLASS ) );
        } );

        if ( window.matchMedia ) {
            window.matchMedia( '(prefers-color-scheme: dark)' ).addEventListener( 'change', function ( e ) {
                if ( localStorage.getItem( STORAGE_KEY ) === null ) {
                    setDarkMode( e.matches );
                }
            } );
        }
    } );
}() );
