/**
 * Scroll To Top button
 * @package marsislav
 */
( function () {
    var btn = document.getElementById( 'marsislav-scroll-top' );
    if ( ! btn ) return;

    var THRESHOLD = 300;

    function onScroll() {
        if ( window.scrollY > THRESHOLD ) {
            btn.classList.add( 'is-visible' );
        } else {
            btn.classList.remove( 'is-visible' );
        }
    }

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    onScroll(); // Check on page load

    btn.addEventListener( 'click', function () {
        window.scrollTo( { top: 0, behavior: 'smooth' } );
    } );

    btn.addEventListener( 'keydown', function ( e ) {
        if ( e.key === 'Enter' || e.key === ' ' ) {
            e.preventDefault();
            window.scrollTo( { top: 0, behavior: 'smooth' } );
        }
    } );
}() );
