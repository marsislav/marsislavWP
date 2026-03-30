<?php
/**
 * Table Customizer — border, padding, row colors, th styling.
 *
 * Registers the "Tables" section inside the Main Content panel.
 * Also outputs the inline CSS and the live-preview JS for the Customizer.
 *
 * @package marsislav
 */

if ( ! defined( 'ABSPATH' ) ) exit;


// =============================================================================
// 1. SANITIZE HELPERS
// =============================================================================

function marsislav_sanitize_table_border_style( $val ) {
	$valid = array( 'none', 'solid', 'dashed', 'dotted', 'double', 'groove', 'ridge' );
	return in_array( $val, $valid, true ) ? $val : 'solid';
}

function marsislav_sanitize_font_weight( $val ) {
	$valid = array( '300', '400', '500', '600', '700', '800', '900' );
	return in_array( (string) $val, $valid, true ) ? $val : '600';
}


// =============================================================================
// 2. CUSTOMIZER REGISTRATION
// =============================================================================

function marsislav_table_customizer( $wp_customize ) {

	$wp_customize->add_section( 'marsislav_table_section', array(
		'title'    => esc_html__( 'Tables', 'marsislav' ),
		'panel'    => 'marsislav_content_panel',
		'priority' => 60,
	) );

	// ── Border width ──────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_border_width', array(
		'default'           => 1,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'table_border_width', array(
		'label'       => esc_html__( 'Border Width (px)', 'marsislav' ),
		'section'     => 'marsislav_table_section',
		'type'        => 'range',
		'priority'    => 10,
		'input_attrs' => array( 'min' => 0, 'max' => 10, 'step' => 1 ),
	) );

	// ── Border style ──────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_border_style', array(
		'default'           => 'solid',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_table_border_style',
	) );
	$wp_customize->add_control( 'table_border_style', array(
		'label'    => esc_html__( 'Border Style', 'marsislav' ),
		'section'  => 'marsislav_table_section',
		'type'     => 'select',
		'priority' => 20,
		'choices'  => array(
			'none'   => esc_html__( 'None',   'marsislav' ),
			'solid'  => esc_html__( 'Solid',  'marsislav' ),
			'dashed' => esc_html__( 'Dashed', 'marsislav' ),
			'dotted' => esc_html__( 'Dotted', 'marsislav' ),
			'double' => esc_html__( 'Double', 'marsislav' ),
			'groove' => esc_html__( 'Groove', 'marsislav' ),
			'ridge'  => esc_html__( 'Ridge',  'marsislav' ),
		),
	) );

	// ── Border color ──────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_border_color', array(
		'default'           => '#dddddd',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'table_border_color', array(
		'label'    => esc_html__( 'Border Color', 'marsislav' ),
		'section'  => 'marsislav_table_section',
		'priority' => 30,
	) ) );

	// ── Cell padding vertical ─────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_padding_v', array(
		'default'           => 10,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'table_padding_v', array(
		'label'       => esc_html__( 'Cell Padding — Vertical (px)', 'marsislav' ),
		'section'     => 'marsislav_table_section',
		'type'        => 'range',
		'priority'    => 40,
		'input_attrs' => array( 'min' => 0, 'max' => 40, 'step' => 1 ),
	) );

	// ── Cell padding horizontal ───────────────────────────────────────────────
	$wp_customize->add_setting( 'table_padding_h', array(
		'default'           => 14,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'table_padding_h', array(
		'label'       => esc_html__( 'Cell Padding — Horizontal (px)', 'marsislav' ),
		'section'     => 'marsislav_table_section',
		'type'        => 'range',
		'priority'    => 50,
		'input_attrs' => array( 'min' => 0, 'max' => 60, 'step' => 1 ),
	) );

	// ── Odd row background ────────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_row_odd_bg', array(
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'table_row_odd_bg', array(
		'label'    => esc_html__( 'Odd Row Background', 'marsislav' ),
		'section'  => 'marsislav_table_section',
		'priority' => 60,
	) ) );

	// ── Even row background ───────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_row_even_bg', array(
		'default'           => '#f9f9f9',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'table_row_even_bg', array(
		'label'    => esc_html__( 'Even Row Background', 'marsislav' ),
		'section'  => 'marsislav_table_section',
		'priority' => 70,
	) ) );

	// ── Hover row background ──────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_row_hover_bg', array(
		'default'           => '#eef4ff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'table_row_hover_bg', array(
		'label'       => esc_html__( 'Row Hover Background', 'marsislav' ),
		'description' => esc_html__( 'Color shown when hovering over a row.', 'marsislav' ),
		'section'     => 'marsislav_table_section',
		'priority'    => 80,
	) ) );

	// ── th background ─────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_th_bg', array(
		'default'           => '#2d3748',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'table_th_bg', array(
		'label'    => esc_html__( 'Header Cell (th) Background', 'marsislav' ),
		'section'  => 'marsislav_table_section',
		'priority' => 90,
	) ) );

	// ── th text color ─────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_th_color', array(
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'table_th_color', array(
		'label'    => esc_html__( 'Header Cell (th) Text Color', 'marsislav' ),
		'section'  => 'marsislav_table_section',
		'priority' => 100,
	) ) );

	// ── th font size ──────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_th_font_size', array(
		'default'           => 14,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'table_th_font_size', array(
		'label'       => esc_html__( 'Header Cell (th) Font Size (px)', 'marsislav' ),
		'section'     => 'marsislav_table_section',
		'type'        => 'range',
		'priority'    => 110,
		'input_attrs' => array( 'min' => 10, 'max' => 30, 'step' => 1 ),
	) );

	// ── th font weight ────────────────────────────────────────────────────────
	$wp_customize->add_setting( 'table_th_font_weight', array(
		'default'           => '600',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_font_weight',
	) );
	$wp_customize->add_control( 'table_th_font_weight', array(
		'label'    => esc_html__( 'Header Cell (th) Font Weight', 'marsislav' ),
		'section'  => 'marsislav_table_section',
		'type'     => 'select',
		'priority' => 120,
		'choices'  => array(
			'300' => esc_html__( '300 — Light',      'marsislav' ),
			'400' => esc_html__( '400 — Normal',     'marsislav' ),
			'500' => esc_html__( '500 — Medium',     'marsislav' ),
			'600' => esc_html__( '600 — Semi-Bold',  'marsislav' ),
			'700' => esc_html__( '700 — Bold',       'marsislav' ),
			'800' => esc_html__( '800 — Extra-Bold', 'marsislav' ),
			'900' => esc_html__( '900 — Black',      'marsislav' ),
		),
	) );
}
add_action( 'customize_register', 'marsislav_table_customizer', 25 );


// =============================================================================
// 3. INLINE CSS OUTPUT
// =============================================================================

function marsislav_table_styles_css() {

	$border_w  = absint( get_theme_mod( 'table_border_width', 1 ) );
	$border_s  = get_theme_mod( 'table_border_style', 'solid' );
	$border_c  = get_theme_mod( 'table_border_color', '#dddddd' );
	$pad_v     = absint( get_theme_mod( 'table_padding_v', 10 ) );
	$pad_h     = absint( get_theme_mod( 'table_padding_h', 14 ) );
	$odd_bg    = get_theme_mod( 'table_row_odd_bg', '#ffffff' );
	$even_bg   = get_theme_mod( 'table_row_even_bg', '#f9f9f9' );
	$hover_bg  = get_theme_mod( 'table_row_hover_bg', '#eef4ff' );
	$th_bg     = get_theme_mod( 'table_th_bg', '#2d3748' );
	$th_color  = get_theme_mod( 'table_th_color', '#ffffff' );
	$th_size   = absint( get_theme_mod( 'table_th_font_size', 14 ) );
	$th_weight = get_theme_mod( 'table_th_font_weight', '600' );

	$border_val = ( 'none' === $border_s || 0 === $border_w )
		? 'none'
		: $border_w . 'px ' . $border_s . ' ' . $border_c;

	$css = '
/* ── Marsislav Table Styles ──────────────────────────────── */
.entry-content table,
.wp-block-table table {
	width: 100%;
	border-collapse: collapse;
	border: ' . esc_attr( $border_val ) . ';
}

.entry-content table th,
.entry-content table td,
.wp-block-table table th,
.wp-block-table table td {
	padding: ' . esc_attr( $pad_v ) . 'px ' . esc_attr( $pad_h ) . 'px;
	border: ' . esc_attr( $border_val ) . ';
}

.entry-content table thead th,
.wp-block-table table thead th {
	background-color: ' . esc_attr( $th_bg ) . ';
	color: ' . esc_attr( $th_color ) . ';
	font-size: ' . esc_attr( $th_size ) . 'px;
	font-weight: ' . esc_attr( $th_weight ) . ';
}

.entry-content table tbody tr:nth-child(odd),
.wp-block-table table tbody tr:nth-child(odd) {
	background-color: ' . esc_attr( $odd_bg ) . ';
}

.entry-content table tbody tr:nth-child(even),
.wp-block-table table tbody tr:nth-child(even) {
	background-color: ' . esc_attr( $even_bg ) . ';
}

.entry-content table tbody tr,
.wp-block-table table tbody tr {
	transition: transform 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
	will-change: transform;
}

.entry-content table tbody tr:hover,
.wp-block-table table tbody tr:hover {
	background-color: ' . esc_attr( $hover_bg ) . ' !important;
	transform: scaleY(1.025);
	box-shadow: 0 2px 8px rgba(0,0,0,0.08);
	position: relative;
	z-index: 1;
}
';

	echo '<style id="marsislav-table-styles">' . $css . '</style>'; // phpcs:ignore
}
add_action( 'wp_head', 'marsislav_table_styles_css' );


// =============================================================================
// 4. CUSTOMIZER LIVE-PREVIEW JS
// =============================================================================

function marsislav_table_customize_preview_js() {
	wp_enqueue_script(
		'marsislav-customizer-tables',
		get_template_directory_uri() . '/js/customizer-tables.js',
		array( 'customize-preview', 'jquery' ),
		_S_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'marsislav_table_customize_preview_js' );
