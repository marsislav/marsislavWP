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

                // Only change the layout class — CSS handles the rest
                $container.removeClass( 'layout-left layout-right layout-disabled' )
                          .addClass( 'layout-' + newVal );
                // Note: do NOT use $sidebar.hide()/show() because
                // it hides #secondary with inline display:none and may affect
                // the footer and surrounding elements. The CSS class layout-disabled
                // already hides the sidebar correctly.
            } );
        } );
    } );
}( jQuery, wp.customize ) );