/* global wp, jQuery, marsislavSidebar */
( function( $, api ) {

    api.bind( 'preview-ready', function() {

        var ctxKey = marsislavSidebar.ctxKey;

        function applyLayout( position ) {
            var $wrap = $( '#content-sidebar-wrap' );
            if ( ! $wrap.length ) { return; }
            $wrap
                .removeClass( 'layout-left layout-right layout-disabled' )
                .addClass( 'layout-' + position );
        }

        api( ctxKey, function( setting ) {
            setting.bind( function( newVal ) {
                applyLayout( newVal );
            } );
        } );

    } );

}( jQuery, wp.customize ) );
