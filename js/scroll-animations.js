/**
 * Scroll Animations
 * @package marsislav
 */
( function () {
    if ( ! window.IntersectionObserver ) return;
    if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) return;

    var style = getComputedStyle( document.documentElement )
        .getPropertyValue( '--ms-anim-style' ).trim().replace( /[^a-z-]/g, '' );
    if ( style && style !== 'fade-up' ) {
        document.body.classList.add( 'ms-anim-' + style );
    }

    var SELECTORS = [
        '.entry', '.post', 'article', '.widget',
        '.woocommerce ul.products li.product',
        '.woocommerce-loop-product',
        '.site-main > *',
        '.footer-sidebar-area .widget',
        '.marsislav-breadcrumbs',
    ].join( ',' );

    var observer = new IntersectionObserver( function ( entries ) {
        entries.forEach( function ( entry ) {
            if ( entry.isIntersecting ) {
                entry.target.classList.add( 'ms-animated' );
                observer.unobserve( entry.target );
            }
        } );
    }, { threshold: 0.07, rootMargin: '0px 0px -36px 0px' } );

    document.addEventListener( 'DOMContentLoaded', function () {
        var els = document.querySelectorAll( SELECTORS );
        for ( var i = 0; i < els.length; i++ ) {
            var rect = els[i].getBoundingClientRect();
            if ( rect.top < window.innerHeight * 0.9 ) {
                els[i].classList.add( 'ms-animated' );
            } else {
                els[i].classList.add( 'ms-will-animate' );
                observer.observe( els[i] );
            }
        }
    } );
}() );
