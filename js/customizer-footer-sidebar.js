/**
 * Footer Sidebar — Dynamic Customizer Preview
 * All 4 columns are always in the DOM.
 * The CSS class footer-columns-X controls the grid layout.
 * JS only shows/hides columns beyond the selected count.
 */
( function( $, api ) {

    // --- Column count ---
    api( 'footer_sidebar_columns', function( value ) {
        value.bind( function( newVal ) {
            var $area = $( '#footer-sidebar-area' );
            if ( ! $area.length ) return;

            var cols = parseInt( newVal, 10 ) || 3;

            // Update the CSS class for the grid
            $area.removeClass( 'footer-columns-1 footer-columns-2 footer-columns-3 footer-columns-4' )
                 .addClass( 'footer-columns-' + cols )
                 .attr( 'data-columns', cols );

            // Show/hide columns by data-col attribute
            $area.find( '.footer-sidebar-col' ).each( function() {
                var colIndex = parseInt( $( this ).attr( 'data-col' ), 10 );
                if ( colIndex <= cols ) {
                    $( this ).show();
                } else {
                    $( this ).hide();
                }
            } );
        } );
    } );

    // --- Enable/disable footer sidebar ---
    api( 'footer_sidebar_enable', function( value ) {
        value.bind( function( newVal ) {
            if ( newVal ) {
                $( '#footer-sidebar-area' ).show();
            } else {
                $( '#footer-sidebar-area' ).hide();
            }
        } );
    } );

}( jQuery, wp.customize ) );
