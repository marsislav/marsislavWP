/**
 * marsislav — Table Customizer Live Preview
 * Runs inside the preview iframe — rebuilds inline CSS on every setting change.
 */
( function ( $ ) {
	'use strict';

	var vals = {};

	var settings = [
		'table_border_width',
		'table_border_style',
		'table_border_color',
		'table_padding_v',
		'table_padding_h',
		'table_row_odd_bg',
		'table_row_even_bg',
		'table_row_hover_bg',
		'table_th_bg',
		'table_th_color',
		'table_th_font_size',
		'table_th_font_weight',
	];

	var defaults = {
		table_border_width:   1,
		table_border_style:   'solid',
		table_border_color:   '#dddddd',
		table_padding_v:      10,
		table_padding_h:      14,
		table_row_odd_bg:     '#ffffff',
		table_row_even_bg:    '#f9f9f9',
		table_row_hover_bg:   '#eef4ff',
		table_th_bg:          '#2d3748',
		table_th_color:       '#ffffff',
		table_th_font_size:   14,
		table_th_font_weight: '600',
	};

	settings.forEach( function ( key ) { vals[ key ] = defaults[ key ]; } );

	settings.forEach( function ( key ) {
		wp.customize( key, function ( setting ) {
			setting.bind( function ( newVal ) {
				vals[ key ] = newVal;
				applyStyles();
			} );
		} );
	} );

	function applyStyles() {
		var bw = parseInt( vals.table_border_width, 10 );
		var bs = vals.table_border_style;
		var bc = vals.table_border_color;
		var pv = parseInt( vals.table_padding_v, 10 );
		var ph = parseInt( vals.table_padding_h, 10 );

		var borderVal = ( bs === 'none' || bw === 0 )
			? 'none'
			: bw + 'px ' + bs + ' ' + bc;

		var css = '/* Marsislav Table Styles */\n'
			+ '.entry-content table,.wp-block-table table{'
			+ 'width:100%;border-collapse:collapse;border:' + borderVal + ';}\n'

			+ '.entry-content table th,.entry-content table td,'
			+ '.wp-block-table table th,.wp-block-table table td{'
			+ 'padding:' + pv + 'px ' + ph + 'px;border:' + borderVal + ';}\n'

			+ '.entry-content table thead th,.wp-block-table table thead th{'
			+ 'background-color:' + vals.table_th_bg + ';'
			+ 'color:' + vals.table_th_color + ';'
			+ 'font-size:' + vals.table_th_font_size + 'px;'
			+ 'font-weight:' + vals.table_th_font_weight + ';}\n'

			+ '.entry-content table tbody tr:nth-child(odd),.wp-block-table table tbody tr:nth-child(odd){'
			+ 'background-color:' + vals.table_row_odd_bg + ';}\n'

			+ '.entry-content table tbody tr:nth-child(even),.wp-block-table table tbody tr:nth-child(even){'
			+ 'background-color:' + vals.table_row_even_bg + ';}\n'

			+ '.entry-content table tbody tr,.wp-block-table table tbody tr{'
			+ 'transition:transform .15s ease,background-color .15s ease,box-shadow .15s ease;'
			+ 'will-change:transform;}\n'

			+ '.entry-content table tbody tr:hover,.wp-block-table table tbody tr:hover{'
			+ 'background-color:' + vals.table_row_hover_bg + ' !important;'
			+ 'transform:scaleY(1.025);box-shadow:0 2px 8px rgba(0,0,0,.08);'
			+ 'position:relative;z-index:1;}\n';

		var tag = document.getElementById( 'marsislav-table-styles' );
		if ( ! tag ) {
			tag = document.createElement( 'style' );
			tag.id = 'marsislav-table-styles';
			document.head.appendChild( tag );
		}
		tag.textContent = css;
	}

} )( jQuery );
