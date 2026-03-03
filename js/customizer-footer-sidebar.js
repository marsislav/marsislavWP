/**
 * Footer Sidebar — Dynamic Customizer Preview
 * Всичките 4 колони са винаги в DOM-а.
 * CSS класът footer-columns-X контролира grid layout-а.
 * JS само показва/скрива колоните над избрания брой.
 */
( function( $, api ) {

    // --- Брой колони ---
    api( 'footer_sidebar_columns', function( value ) {
        value.bind( function( newVal ) {
            var $area = $( '#footer-sidebar-area' );
            if ( ! $area.length ) return;

            var cols = parseInt( newVal, 10 ) || 3;

            // Обновяваме CSS класа за grid
            $area.removeClass( 'footer-columns-1 footer-columns-2 footer-columns-3 footer-columns-4' )
                 .addClass( 'footer-columns-' + cols )
                 .attr( 'data-columns', cols );

            // Показваме/скриваме колоните по data-col атрибут
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

    // --- Включи/изключи footer sidebar ---
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
