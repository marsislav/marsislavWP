/**
 * Customizer live preview — Footer Waves
 * @package marsislav
 */
( function ( $ ) {

	// Helper: update or create a <style> tag for wave colors
	function updateWaveColor( waveClass, color ) {
		var id = 'ms-wave-color-' + waveClass;
		var $style = $( '#' + id );
		if ( ! $style.length ) {
			$style = $( '<style id="' + id + '"></style>' ).appendTo( 'head' );
		}
		$style.text( '.' + waveClass + ' path { fill: ' + color + ' !important; }' );
	}

	// Enable / disable waves
	wp.customize( 'footer_waves_enable', function ( value ) {
		value.bind( function ( enabled ) {
			if ( enabled ) {
				$( '#colophon' ).addClass( 'has-waves' );
				if ( ! $( '.footer-waves' ).length ) {
					// Reload preview to get server-rendered markup
					wp.customize.preview.send( 'refresh' );
				}
			} else {
				$( '#colophon' ).removeClass( 'has-waves' );
				$( '.footer-waves' ).hide();
			}
		} );
	} );

	// Live color updates
	wp.customize( 'footer_wave_color1', function ( value ) {
		value.bind( function ( color ) { updateWaveColor( 'footer-wave-1', color ); } );
	} );
	wp.customize( 'footer_wave_color2', function ( value ) {
		value.bind( function ( color ) { updateWaveColor( 'footer-wave-2', color ); } );
	} );
	wp.customize( 'footer_wave_color3', function ( value ) {
		value.bind( function ( color ) { updateWaveColor( 'footer-wave-3', color ); } );
	} );

} )( jQuery );
