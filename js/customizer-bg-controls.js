/**
 * Controls-side: resolves bg image attachment ID -> URL
 * and pushes it directly to the preview iframe.
 *
 * @package marsislav
 */
( function( $, api ) {

    var AREAS = ['global','header','content','sidebar','footer_widgets','footer','copyright','buttons','inputs','cards','images'];

    function pushUrl( area, imgId ) {
        if ( ! imgId ) {
            api.previewer.send( 'marsislav-bg-img-url', { area: area, url: '' } );
            return;
        }

        // wp.media.attachment() model is populated when the user picks from library
        var att = wp.media.attachment( imgId );
        var url = att.get('url') || att.get('source_url') || '';

        if ( url ) {
            api.previewer.send( 'marsislav-bg-img-url', { area: area, url: url } );
        } else {
            // Fetch the model then push
            att.fetch({
                success: function( m ) {
                    api.previewer.send( 'marsislav-bg-img-url', {
                        area: area,
                        url: m.get('url') || m.get('source_url') || ''
                    });
                },
                error: function() {
                    api.previewer.send( 'marsislav-bg-img-url', { area: area, url: '' } );
                }
            });
        }
    }

    api.bind( 'ready', function() {
        AREAS.forEach( function( area ) {
            api( 'bg_' + area + '_image', function( setting ) {
                // Fire once immediately for the current value
                pushUrl( area, parseInt( setting.get(), 10 ) || 0 );
                // Fire on every future change
                setting.bind( function( val ) {
                    pushUrl( area, parseInt( val, 10 ) || 0 );
                });
            });
        });
    });

}( jQuery, wp.customize ) );
