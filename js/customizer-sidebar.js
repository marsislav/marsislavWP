/**
 * Dynamic Sidebar Preview
 */
( function( $, api ) {
    var settings = marsislavSidebarVars.settings;

    $.each( settings, function( index, key ) {
        api( key, function( value ) {
            value.bind( function( newVal ) {
                var $container = $( '#content-sidebar-wrap' );
                var $sidebar = $( '#secondary' );

                if ( ! $container.length ) return;

                // Сменяме класа за подредба
                $container.removeClass( 'layout-left layout-right layout-disabled' )
                          .addClass( 'layout-' + newVal );

                // Скриваме или показваме сайдбара веднага без рефреш
                if ( newVal === 'disabled' ) {
                    $sidebar.hide();
                } else {
                    $sidebar.show();
                }
            } );
        } );
    } );
}( jQuery, wp.customize ) );