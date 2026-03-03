/**
 * Dynamic Sidebar Preview
 */
( function( $, api ) {
    var settings = marsislavSidebarVars.settings;

    $.each( settings, function( index, key ) {
        api( key, function( value ) {
            value.bind( function( newVal ) {
                var $container = $( '#content-sidebar-wrap' );

                if ( ! $container.length ) return;

                // Сменяме само класа за подредба — CSS-ът се грижи за всичко останало
                $container.removeClass( 'layout-left layout-right layout-disabled' )
                          .addClass( 'layout-' + newVal );
                // Забележка: НЕ използваме $sidebar.hide()/show() защото
                // това крие #secondary с display:none inline и може да засегне
                // footer-а и околните елементи. CSS класът layout-disabled
                // вече скрива sidebar-а коректно.
            } );
        } );
    } );
}( jQuery, wp.customize ) );