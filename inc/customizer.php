<?php
/**
 * Marsislav Theme Customizer 
 *
 * Content:
 *  1.  Sanitazing functions
 *  2.  Helper functions
 *  3.  Panels and sections
 *  4.  Settings and controls (Header, Sidebar, Content, Footer, Design)
 *  5.  Dynamic css generator CSS (wp_head)
 *  6.  Live Preview JS
 *
 * @package marsislav
 */

if ( ! defined( 'ABSPATH' ) ) exit;


// =============================================================================
// 1. Sanitazing funcions
// =============================================================================

/** Checkbox / bool */
function marsislav_sanitize_checkbox( $val ) {
	return (bool) $val;
}

/** Hex color or empty string */
function marsislav_sanitize_hex_color_blank( $color ) {
	if ( '' === $color ) return '';
	return sanitize_hex_color( $color );
}

/** Background: solid | gradient | image | transparent */
function marsislav_sanitize_bg_type( $val ) {
	return in_array( $val, array( 'solid', 'gradient', 'image', 'transparent' ), true ) ? $val : 'solid';
}

/** Gradient direction */
function marsislav_sanitize_gradient_dir( $val ) {
	$valid = array(
		'to bottom', 'to top', 'to right', 'to left',
		'to bottom right', 'to bottom left', 'to top right', 'to top left',
		'45deg', '135deg', '225deg', '315deg',
	);
	return in_array( $val, $valid, true ) ? $val : 'to bottom';
}

/** Repeat of Background image */
function marsislav_sanitize_bg_repeat( $val ) {
	$valid = array( 'no-repeat', 'repeat', 'repeat-x', 'repeat-y' );
	return in_array( $val, $valid, true ) ? $val : 'no-repeat';
}

/** Size of Background image */
function marsislav_sanitize_bg_size( $val ) {
	$valid = array( 'cover', 'contain', 'auto' );
	return in_array( $val, $valid, true ) ? $val : 'cover';
}

/** Transparency 0–100 */
function marsislav_sanitize_opacity( $val ) {
	$val = floatval( $val );
	return ( $val >= 0 && $val <= 100 ) ? $val : 100;
}

/** Border-radius 0–200 */
function marsislav_sanitize_border_radius( $val ) {
	return min( absint( $val ), 200 );
}

/** Border-radius on one side (or empry) */
function marsislav_sanitize_radius_corner( $val ) {
	if ( '' === $val || null === $val ) return '';
	return min( absint( $val ), 200 );
}

/** Type of shadow */
function marsislav_sanitize_shadow_type( $val ) {
	return in_array( $val, array( 'none', 'outset', 'inset' ), true ) ? $val : 'none';
}

/** Border style */
function marsislav_sanitize_border_style( $val ) {
	$valid = array( 'none', 'solid', 'dashed', 'dotted', 'double', 'groove', 'ridge', 'inset', 'outset' );
	return in_array( $val, $valid, true ) ? $val : 'none';
}

/** Layout of footer bar */
function marsislav_sanitize_footer_layout( $value ) {
	$valid = array( 'one-column', 'two-column' );
	return in_array( $value, $valid, true ) ? $value : 'one-column';
}

/** Layout of top bar */
function marsislav_sanitize_topbar_layout( $val ) {
	return in_array( $val, array( 'one', 'two' ), true ) ? $val : 'one';
}

/** Columns of footer widget area */
function marsislav_sanitize_footer_columns( $val ) {
	return in_array( (string) $val, array( '1', '2', '3', '4' ), true ) ? $val : '3';
}

/** Sidebar positions*/
function marsislav_sanitize_sidebar_position( $val ) {
	return in_array( $val, array( 'left', 'right', 'disabled' ), true ) ? $val : 'right';
}

/** ID of sidebar widget area */
function marsislav_sanitize_sidebar_id( $val ) {
	$valid = array_keys( marsislav_sidebar_choices_for( '' ) );
	return in_array( $val, $valid, true ) ? $val : 'sidebar-blog';
}


// =============================================================================
// 2. Helper functions for control registration
// =============================================================================

/**
 * Registers controls for background (Background Type, Color, Gradient, Image).
 * Also supports a "Transparent" option for each element.
 */
function marsislav_register_bg_controls( $wp_customize, $section, $area, $priority_start = 10 ) {
	$p = $priority_start;

	$gradient_choices = array(
		'to bottom'       => __( 'Top → Bottom',       'marsislav' ),
		'to top'          => __( 'Bottom → Top',       'marsislav' ),
		'to right'        => __( 'Left → Right',       'marsislav' ),
		'to left'         => __( 'Right → Left',       'marsislav' ),
		'to bottom right' => __( 'Diagonal ↘',         'marsislav' ),
		'to bottom left'  => __( 'Diagonal ↙',         'marsislav' ),
		'to top right'    => __( 'Diagonal ↗',         'marsislav' ),
		'to top left'     => __( 'Diagonal ↖',         'marsislav' ),
		'45deg'           => '45°',
		'135deg'          => '135°',
		'225deg'          => '225°',
		'315deg'          => '315°',
	);

	// Background type (including Transparent)
	$wp_customize->add_setting( 'bg_' . $area . '_type', array(
		'default'           => 'solid',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_bg_type',
	) );
	$wp_customize->add_control( 'bg_' . $area . '_type', array(
		'label'    => __( 'Background type', 'marsislav' ),
		'section'  => $section,
		'type'     => 'select',
		'priority' => $p++,
		'choices'  => array(
			'solid'       => __( 'Solid color',   'marsislav' ),
			'gradient'    => __( 'Gradient',       'marsislav' ),
			'image'       => __( 'Image',         'marsislav' ),
			'transparent' => __( 'Transparent',      'marsislav' ),
		),
	) );

	// Solid color
	$wp_customize->add_setting( 'bg_' . $area . '_color', array(
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_color', array(
		'label'    => __( 'Background color', 'marsislav' ),
		'section'  => $section,
		'priority' => $p++,
	) ) );

	// Gradient  color 1
	$wp_customize->add_setting( 'bg_' . $area . '_grad1', array(
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_grad1', array(
		'label'    => __( 'Gradient  color 1', 'marsislav' ),
		'section'  => $section,
		'priority' => $p++,
	) ) );

	// Gradient  color 2
	$wp_customize->add_setting( 'bg_' . $area . '_grad2', array(
		'default'           => '#eeeeee',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_grad2', array(
		'label'    => __( 'Gradient  color 2', 'marsislav' ),
		'section'  => $section,
		'priority' => $p++,
	) ) );

	// Gradient direction
	$wp_customize->add_setting( 'bg_' . $area . '_grad_dir', array(
		'default'           => 'to bottom',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_gradient_dir',
	) );
	$wp_customize->add_control( 'bg_' . $area . '_grad_dir', array(
		'label'    => __( 'Gradient direction', 'marsislav' ),
		'section'  => $section,
		'type'     => 'select',
		'priority' => $p++,
		'choices'  => $gradient_choices,
	) );

	// Background image
	$wp_customize->add_setting( 'bg_' . $area . '_image', array(
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bg_' . $area . '_image', array(
		'label'    => __( 'Background image', 'marsislav' ),
		'section'  => $section,
		'priority' => $p++,
	) ) );

	// Background image repeat
	$wp_customize->add_setting( 'bg_' . $area . '_repeat', array(
		'default'           => 'no-repeat',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_bg_repeat',
	) );
	$wp_customize->add_control( 'bg_' . $area . '_repeat', array(
		'label'    => __( 'Background image repeat', 'marsislav' ),
		'section'  => $section,
		'type'     => 'select',
		'priority' => $p++,
		'choices'  => array(
			'no-repeat' => __( 'No repeat',      'marsislav' ),
			'repeat'    => __( 'Repeat (X & Y)',   'marsislav' ),
			'repeat-x'  => __( 'Horizontal',        'marsislav' ),
			'repeat-y'  => __( 'Vertical',          'marsislav' ),
		),
	) );

	// Size of background image
	$wp_customize->add_setting( 'bg_' . $area . '_size', array(
		'default'           => 'cover',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_bg_size',
	) );
	$wp_customize->add_control( 'bg_' . $area . '_size', array(
		'label'    => __( 'Size of background image', 'marsislav' ),
		'section'  => $section,
		'type'     => 'select',
		'priority' => $p++,
		'choices'  => array(
			'cover'   => __( 'Cover',  'marsislav' ),
			'contain' => __( 'Contain', 'marsislav' ),
			'auto'    => __( 'Original size','marsislav' ),
		),
	) );

	return $p;
}

/**
 * Registers border radius controls (global + 4 corners).
 */
function marsislav_register_radius_controls( $wp_customize, $section, $key, $default_global = 0, $priority_start = 200 ) {
	$p = $priority_start;

	$wp_customize->add_setting( $key, array(
		'default'           => $default_global,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_border_radius',
	) );
	$wp_customize->add_control( $key, array(
		'label'       => __( 'Border radius — Global (px)', 'marsislav' ),
		'description' => __( 'Applied to all 4 corners. Replace individually below.', 'marsislav' ),
		'section'     => $section,
		'type'        => 'range',
		'priority'    => $p++,
		'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 1 ),
	) );

	$corners = array(
		'_tl' => __( 'Up-left (px)',  'marsislav' ),
		'_tr' => __( 'Up-right (px)', 'marsislav' ),
		'_br' => __( 'Down-right (px)', 'marsislav' ),
		'_bl' => __( 'Down-left (px)',  'marsislav' ),
	);

	foreach ( $corners as $suffix => $label ) {
		$wp_customize->add_setting( $key . $suffix, array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'marsislav_sanitize_radius_corner',
		) );
		$wp_customize->add_control( $key . $suffix, array(
			'label'       => $label,
			'section'     => $section,
			'type'        => 'number',
			'priority'    => $p++,
			'input_attrs' => array( 'min' => 0, 'max' => 200, 'step' => 1, 'placeholder' => __( 'Global', 'marsislav' ) ),
		) );
	}

	return $p;
}

/**
 * Registers box-shadow controls.
 */
function marsislav_register_shadow_controls( $wp_customize, $section, $key, $priority_start = 310 ) {
	$p = $priority_start;

	$wp_customize->add_setting( $key . '_shadow_type', array(
		'default'           => 'none',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_shadow_type',
	) );
	$wp_customize->add_control( $key . '_shadow_type', array(
		'label'    => __( 'Shadow — type', 'marsislav' ),
		'section'  => $section,
		'type'     => 'select',
		'priority' => $p++,
		'choices'  => array(
			'none'   => __( 'Without shadow',      'marsislav' ),
			'outset' => __( 'Outset (normal)', 'marsislav' ),
			'inset'  => __( 'Inset (internal)','marsislav' ),
		),
	) );

	$shadow_fields = array(
		$key . '_shadow_color'   => array( __( 'Shadow color', 'marsislav' ),     'WP_Customize_Color_Control', 'sanitize_hex_color', '#000000' ),
	);

	$wp_customize->add_setting( $key . '_shadow_color', array(
		'default'           => '#000000',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key . '_shadow_color', array(
		'label'    => __( 'Shadow color', 'marsislav' ),
		'section'  => $section,
		'priority' => $p++,
	) ) );

	$numeric = array(
		$key . '_shadow_opacity' => array( __( 'Opacity (%)',   'marsislav' ), 20,  0,  100, 1 ),
		$key . '_shadow_x'       => array( __( 'Offset X (px)',        'marsislav' ), 0, -50,  50, 1 ),
		$key . '_shadow_y'       => array( __( 'Offset Y (px)',        'marsislav' ), 4, -50,  50, 1 ),
		$key . '_shadow_blur'    => array( __( 'Blur (px)',            'marsislav' ), 8,   0, 100, 1 ),
		$key . '_shadow_spread'  => array( __( 'Spread (px)',          'marsislav' ), 0, -50,  50, 1 ),
	);

	foreach ( $numeric as $nkey => $info ) {
		$wp_customize->add_setting( $nkey, array(
			'default'           => $info[1],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( $nkey, array(
			'label'       => $info[0],
			'section'     => $section,
			'type'        => 'range',
			'priority'    => $p++,
			'input_attrs' => array( 'min' => $info[2], 'max' => $info[3], 'step' => $info[4] ),
		) );
	}

	return $p;
}

/**
 * Controllers for borders.
 */
function marsislav_register_border_controls( $wp_customize, $section, $key, $priority_start = 440 ) {
	$p = $priority_start;

	$wp_customize->add_setting( $key . '_border_style', array(
		'default'           => 'none',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_border_style',
	) );
	$wp_customize->add_control( $key . '_border_style', array(
		'label'    => __( 'Border style', 'marsislav' ),
		'section'  => $section,
		'type'     => 'select',
		'priority' => $p++,
		'choices'  => array(
			'none'   => __( 'Without', 'marsislav' ),
			'solid'  => 'Solid',
			'dashed' => 'Dashed',
			'dotted' => 'Dotted',
			'double' => 'Double',
		),
	) );

	$wp_customize->add_setting( $key . '_border_color', array(
		'default'           => '#e5e7eb',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key . '_border_color', array(
		'label'    => __( 'Border color', 'marsislav' ),
		'section'  => $section,
		'priority' => $p++,
	) ) );

	$wp_customize->add_setting( $key . '_border_width', array(
		'default'           => 1,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( $key . '_border_width', array(
		'label'       => __( 'Width (px)', 'marsislav' ),
		'section'     => $section,
		'type'        => 'range',
		'priority'    => $p++,
		'input_attrs' => array( 'min' => 0, 'max' => 20, 'step' => 1 ),
	) );

	foreach ( array(
		'_border_top'    => __( 'Top width (px)',  'marsislav' ),
		'_border_right'  => __( 'Right width (px)',  'marsislav' ),
		'_border_bottom' => __( 'Bottom width (px)',  'marsislav' ),
		'_border_left'   => __( 'Left width (px)',   'marsislav' ),
	) as $suffix => $label ) {
		$wp_customize->add_setting( $key . $suffix, array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'marsislav_sanitize_radius_corner',
		) );
		$wp_customize->add_control( $key . $suffix, array(
			'label'       => $label,
			'section'     => $section,
			'type'        => 'number',
			'priority'    => $p++,
			'input_attrs' => array( 'min' => 0, 'max' => 50, 'step' => 1, 'placeholder' => __( 'Global', 'marsislav' ) ),
		) );
	}

	return $p;
}

/**
 * Registers typography controls (text color, links, headings).
 */
function marsislav_register_typography_controls( $wp_customize, $section, $elem_key, $priority_start = 550 ) {
	$p   = $priority_start;
	$pre = 'typo_' . $elem_key . '_';

	// Separator label
	$wp_customize->add_setting( 'marsislav_heading_typo_' . $elem_key, array(
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => '__return_empty_string',
	) );
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'marsislav_heading_typo_' . $elem_key, array(
		'label'    => '— ' . __( 'TYPOGRAPHY', 'marsislav' ) . ' —',
		'section'  => $section,
		'type'     => 'hidden',
		'priority' => $p++,
	) ) );

	$controls = array(
		$pre . 'text'       => __( 'Color of text',        'marsislav' ),
		$pre . 'link'       => __( 'Color of links',      'marsislav' ),
		$pre . 'link_hover' => __( 'Color of links (hover)', 'marsislav' ),
		$pre . 'h1'         => __( 'H1 color',              'marsislav' ),
		$pre . 'h2'         => __( 'H2 color',              'marsislav' ),
		$pre . 'h3'         => __( 'H3 color',              'marsislav' ),
		$pre . 'h4'         => __( 'H4 color',              'marsislav' ),
		$pre . 'h5'         => __( 'H5 color',              'marsislav' ),
		$pre . 'h6'         => __( 'H6 color',              'marsislav' ),
	);

	foreach ( $controls as $key => $label ) {
		$wp_customize->add_setting( $key, array(
			'default'           => '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array(
			'label'    => $label,
			'section'  => $section,
			'priority' => $p++,
		) ) );
	}

	return $p;
}

/**
 * Helper: list of sidebar widget areas.
 */
function marsislav_sidebar_choices_for( $context ) {
	return array(
		'disabled'        => __( 'Off (Full Width)', 'marsislav' ),
		'sidebar-blog'    => __( 'Blog Sidebar',          'marsislav' ),
		'sidebar-post'    => __( 'Post Sidebar',          'marsislav' ),
		'sidebar-page'    => __( 'Page Sidebar',          'marsislav' ),
		'sidebar-shop'    => __( 'Shop Sidebar',          'marsislav' ),
		'sidebar-product' => __( 'Product Sidebar',       'marsislav' ),
	);
}


// =============================================================================
// 3. Panels
// =============================================================================

function marsislav_customize_register( $wp_customize ) {

	// Core transport
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Panel: Header (priority 25)
	$wp_customize->add_panel( 'marsislav_header_panel', array(
		'title'    => __( 'Header', 'marsislav' ),
		'priority' => 25,
	) );

	// Panel: Sidebar (priority 35)
	$wp_customize->add_panel( 'marsislav_sidebar_panel', array(
		'title'    => __( 'Sidebar', 'marsislav' ),
		'priority' => 35,
	) );

	// Panel: Main Content (priority 40)
	$wp_customize->add_panel( 'marsislav_content_panel', array(
		'title'    => __( 'Main Content', 'marsislav' ),
		'priority' => 40,
	) );

	// Panel: Footer (priority 45)
	$wp_customize->add_panel( 'marsislav_footer_panel', array(
		'title'    => __( 'Footer', 'marsislav' ),
		'priority' => 45,
	) );

	// Panel: Theme Design (priority 140)
	$wp_customize->add_panel( 'marsislav_design_panel', array(
		'title'    => __( 'Theme Design', 'marsislav' ),
		'priority' => 140,
	) );

	// Selective refresh
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => function() { bloginfo( 'name' ); },
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => function() { bloginfo( 'description' ); },
		) );
	}
}
add_action( 'customize_register', 'marsislav_customize_register' );


// =============================================================================
// 4A. HEADER PANEL — SECTIONS AND SETTINGS
// =============================================================================

function marsislav_header_customizer( $wp_customize ) {

	// ── General Settings (priority 5) ────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_sec_general', array(
		'title'    => __( 'General settings', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 5,
	) );

	$wp_customize->add_setting( 'header_sticky', array(
		'default'           => true,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'header_sticky', array(
		'label'   => __( 'Sticky header', 'marsislav' ),
		'section' => 'marsislav_sec_general',
		'type'    => 'checkbox',
	) );

	// ── Header Design — background, radius, shadow, border (priority 8) ──────────────
	$wp_customize->add_section( 'marsislav_elem_header', array(
		'title'    => __( 'Header Design', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 8,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_header', 'header', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_header', 'radius_header', 0, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_header', 'header', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_header', 'header', 430 );
	marsislav_register_typography_controls( $wp_customize, 'marsislav_elem_header', 'header', 550 );

	// ── Top Bar (priority 10) ────────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_topbar_section', array(
		'title'    => __( 'Top Bar', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'topbar_enable', array(
		'default'           => false,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'topbar_enable', array(
		'label'   => __( 'Enable Top Bar', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'topbar_layout', array(
		'default'           => 'one',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_topbar_layout',
	) );
	$wp_customize->add_control( 'topbar_layout', array(
		'label'   => __( 'Layout', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
		'type'    => 'radio',
		'choices' => array(
			'one' => __( '1 column',  'marsislav' ),
			'two' => __( '2 columns',  'marsislav' ),
		),
	) );

	$wp_customize->add_setting( 'topbar_marquee', array(
		'default'           => false,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'topbar_marquee', array(
		'label'   => __( 'Marquee text', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'topbar_marquee_speed', array(
		'default'           => 18,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'topbar_marquee_speed', array(
		'label'       => __( 'Speed of marquee text (seconds)', 'marsislav' ),
		'description' => __( 'Less = faster. Recommended: 8–30s.', 'marsislav' ),
		'section'     => 'marsislav_topbar_section',
		'type'        => 'range',
		'input_attrs' => array( 'min' => 3, 'max' => 60, 'step' => 1 ),
	) );

	$wp_customize->add_setting( 'topbar_text', array(
		'default'           => __( 'Welcome to our website', 'marsislav' ),
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'topbar_text', array(
		'label'   => __( 'Text (1 column)', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'topbar_bg_color', array(
		'default'           => '#1f2937',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_bg_color', array(
		'label'   => __( 'Background', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
	) ) );

	$wp_customize->add_setting( 'topbar_text_color', array(
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_text_color', array(
		'label'   => __( 'Text color', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
	) ) );

	$wp_customize->add_setting( 'topbar_col1_text', array(
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'topbar_col1_text', array(
		'label'       => __( 'Column 1 (left) — 2 columns', 'marsislav' ),
		'description' => __( 'Displayed when selecting 2 columns. Supports HTML.', 'marsislav' ),
		'section'     => 'marsislav_topbar_section',
		'type'        => 'textarea',
	) );

	$wp_customize->add_setting( 'topbar_col2_text', array(
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'topbar_col2_text', array(
		'label'       => __( 'Column 2 (right) — 2 columns', 'marsislav' ),
		'description' => __( 'Displayed when selecting 2 columns. Supports HTML.', 'marsislav' ),
		'section'     => 'marsislav_topbar_section',
		'type'        => 'textarea',
	) );

	// ── Mobile Menu (priority 60) ─────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_mobile_menu_section', array(
		'title'    => __( 'Mobile Menu', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 60,
	) );

	// Mobile menu background
	$wp_customize->add_setting( 'mobile_menu_bg_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_menu_bg_color', array(
		'label'       => __( 'Menu background', 'marsislav' ),
		'description' => __( 'Background of the sidebar panel on mobile..', 'marsislav' ),
		'section'     => 'marsislav_mobile_menu_section',
	) ) );

	// Text color
	$wp_customize->add_setting( 'mobile_menu_text_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_menu_text_color', array(
		'label'   => __( 'Links color', 'marsislav' ),
		'section' => 'marsislav_mobile_menu_section',
	) ) );

	// Link color on hover 
	$wp_customize->add_setting( 'mobile_menu_text_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_menu_text_hover_color', array(
		'label'   => __( 'Link color (hover)', 'marsislav' ),
		'section' => 'marsislav_mobile_menu_section',
	) ) );

	// Color of hamburger icon
	$wp_customize->add_setting( 'mobile_menu_icon_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_menu_icon_color', array(
		'label'       => __( 'Color of hamburger icon', 'marsislav' ),
		'description' => __( 'The color of the three lines and the “Menu” label.', 'marsislav' ),
		'section'     => 'marsislav_mobile_menu_section',
	) ) );

	// ── Dropdown menus (priority 65) ───────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_submenu_section', array(
		'title'    => __( 'Submenus', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 65,
	) );

	// Dropdown menus  (desktop)
	$wp_customize->add_setting( 'submenu_bg_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'submenu_bg_color', array(
		'label'       => __( 'Dropdown menus background (desktop)', 'marsislav' ),
		'section'     => 'marsislav_submenu_section',
	) ) );

	// Link color for dropdown menus
	$wp_customize->add_setting( 'submenu_text_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'submenu_text_color', array(
		'label'   => __( 'Link color for dropdown menus', 'marsislav' ),
		'section' => 'marsislav_submenu_section',
	) ) );

	// Link color for dropdown menus
	$wp_customize->add_setting( 'submenu_text_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'submenu_text_hover_color', array(
		'label'   => __( 'Link color for dropdown menus — hover', 'marsislav' ),
		'section' => 'marsislav_submenu_section',
	) ) );

	// Background on hover
	$wp_customize->add_setting( 'submenu_bg_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'submenu_bg_hover_color', array(
		'label'   => __( 'Background color for dropdown menus on hover', 'marsislav' ),
		'section' => 'marsislav_submenu_section',
	) ) );

	// Dropdown menus border
	$wp_customize->add_setting( 'submenu_border_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'submenu_border_color', array(
		'label'   => __( 'Border color', 'marsislav' ),
		'section' => 'marsislav_submenu_section',
	) ) );

	// Dropdown menus border radius
	$wp_customize->add_setting( 'submenu_border_radius', array(
		'default'           => 6,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_border_radius',
	) );
	$wp_customize->add_control( 'submenu_border_radius', array(
		'label'       => __( 'Border radius (px)', 'marsislav' ),
		'section'     => 'marsislav_submenu_section',
		'type'        => 'range',
		'input_attrs' => array( 'min' => 0, 'max' => 30, 'step' => 1 ),
	) );

	// Background of the mobile submenus.
	$wp_customize->add_setting( 'mobile_submenu_bg_color', array(
		'default'           => '',
		'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_submenu_bg_color', array(
		'label'       => __( 'Background of the submenu (mobile)', 'marsislav' ),
		'section'     => 'marsislav_submenu_section',
	) ) );

	// ── Header Search (priority 70) ───────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_header_search_section', array(
		'title'    => __( 'Header Search', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 70,
	) );

	$wp_customize->add_setting( 'header_show_search', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'header_show_search', array(
		'label'       => __( 'Show search icon', 'marsislav' ),
		'description' => __( 'Displays the search icon next to the navigation.', 'marsislav' ),
		'section'     => 'marsislav_header_search_section',
		'type'        => 'checkbox',
	) );
}
add_action( 'customize_register', 'marsislav_header_customizer', 15 );


// =============================================================================
// 4B. SIDEBAR PANEL — POSITIONS
// =============================================================================

function marsislav_sidebar_settings( $wp_customize ) {

	$position_choices = array(
		'right'    => __( 'Right',                  'marsislav' ),
		'left'     => __( 'Left',                   'marsislav' ),
		'disabled' => __( 'Off (Full Width)',   'marsislav' ),
	);

	$sidebar_widget_choices = marsislav_sidebar_choices_for( '' );

	// Sidebar Design section (background, radius, etc.)
	$wp_customize->add_section( 'marsislav_elem_sidebar', array(
		'title'    => __( 'Sidebar Design', 'marsislav' ),
		'panel'    => 'marsislav_sidebar_panel',
		'priority' => 20,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_sidebar', 'sidebar', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_sidebar', 'radius_sidebar', 0, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_sidebar', 'sidebar', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_sidebar', 'sidebar', 430 );
	marsislav_register_typography_controls( $wp_customize, 'marsislav_elem_sidebar', 'sidebar', 550 );

	$contexts = array(
		'blog'    => array( __( 'Position: Blog / Archive',    'marsislav' ), 'right',    'sidebar-blog',    30 ),
		'post'    => array( __( 'Position: Single Post',       'marsislav' ), 'right',    'sidebar-post',    40 ),
		'page'    => array( __( 'Position: Page',              'marsislav' ), 'disabled', 'sidebar-page',    50 ),
		'home'    => array( __( 'Position: Home Page',         'marsislav' ), 'disabled', 'sidebar-blog',    60 ),
		'shop'    => array( __( 'Position: Shop (WooCommerce)','marsislav' ), 'right',    'sidebar-shop',    70 ),
		'product' => array( __( 'Position: Product Page',      'marsislav' ), 'disabled', 'sidebar-product', 80 ),
	);

	foreach ( $contexts as $ctx => $config ) {
		list( $title, $pos_default, $id_default, $priority ) = $config;

		$section_id  = 'marsislav_sidebar_section_' . $ctx;
		$pos_key     = 'sidebar_pos_' . $ctx;
		$sidebar_key = 'sidebar_id_'  . $ctx;

		$wp_customize->add_section( $section_id, array(
			'title'    => $title,
			'panel'    => 'marsislav_sidebar_panel',
			'priority' => $priority,
		) );

		$wp_customize->add_setting( $pos_key, array(
			'default'           => $pos_default,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'marsislav_sanitize_sidebar_position',
		) );
		$wp_customize->add_control( $pos_key, array(
			'label'    => __( 'Position', 'marsislav' ),
			'section'  => $section_id,
			'type'     => 'select',
			'choices'  => $position_choices,
			'priority' => 10,
		) );

		$wp_customize->add_setting( $sidebar_key, array(
			'default'           => $id_default,
			'transport'         => 'refresh',
			'sanitize_callback' => 'marsislav_sanitize_sidebar_id',
		) );
		$wp_customize->add_control( $sidebar_key, array(
			'label'    => __( 'Widget Area', 'marsislav' ),
			'section'  => $section_id,
			'type'     => 'select',
			'choices'  => $sidebar_widget_choices,
			'priority' => 20,
		) );
	}
}
add_action( 'customize_register', 'marsislav_sidebar_settings' );


// =============================================================================
// 4C. MAIN CONTENT PANEL
// =============================================================================

function marsislav_content_customizer( $wp_customize ) {

	// Content Design
	$wp_customize->add_section( 'marsislav_elem_content', array(
		'title'    => __( 'Content Design', 'marsislav' ),
		'panel'    => 'marsislav_content_panel',
		'priority' => 10,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_content', 'content', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_content', 'radius_content', 0, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_content', 'content', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_content', 'content', 430 );
	marsislav_register_typography_controls( $wp_customize, 'marsislav_elem_content', 'content', 550 );

	// Cards / Posts Design
	$wp_customize->add_section( 'marsislav_elem_cards', array(
		'title'    => __( 'Cards / Posts Design', 'marsislav' ),
		'panel'    => 'marsislav_content_panel',
		'priority' => 20,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_cards', 'cards', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_cards', 'radius_cards', 8, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_cards', 'cards', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_cards', 'cards', 430 );
	marsislav_register_typography_controls( $wp_customize, 'marsislav_elem_cards', 'cards', 550 );

	// Images
	$wp_customize->add_section( 'marsislav_elem_images', array(
		'title'    => __( 'Images', 'marsislav' ),
		'panel'    => 'marsislav_content_panel',
		'priority' => 30,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_images', 'images', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_images', 'radius_images', 0, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_images', 'images', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_images', 'images', 430 );

	// Blog Meta
	$wp_customize->add_section( 'marsislav_blog_meta_section', array(
		'title'    => __( 'Blog Meta', 'marsislav' ),
		'panel'    => 'marsislav_content_panel',
		'priority' => 50,
	) );

	foreach ( array(
		'blog_show_category' => __( 'Show category',      'marsislav' ),
		'blog_show_author'   => __( 'Show the author',          'marsislav' ),
		'blog_show_date'     => __( 'Show the date',           'marsislav' ),
		'blog_show_comments' => __( 'Show the comments link', 'marsislav' ),
	) as $key => $label ) {
		$wp_customize->add_setting( $key, array(
			'default'           => true,
			'sanitize_callback' => 'marsislav_sanitize_checkbox',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $label,
			'section' => 'marsislav_blog_meta_section',
			'type'    => 'checkbox',
		) );
	}
}
add_action( 'customize_register', 'marsislav_content_customizer', 20 );


// =============================================================================
// 4D. FOOTER PANEL
// =============================================================================

function marsislav_footer_customizer( $wp_customize ) {

	// ── Layout & Text (priority 10) ───────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_footer_section', array(
		'title'    => __( 'Layout & text', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'footer_layout', array(
		'default'           => 'one-column',
		'sanitize_callback' => 'marsislav_sanitize_footer_layout',
	) );
	$wp_customize->add_control( 'footer_layout', array(
		'label'    => __( 'Styles of copyright bar', 'marsislav' ),
		'section'  => 'marsislav_footer_section',
		'type'     => 'radio',
		'priority' => 10,
		'choices'  => array(
			'one-column' => __( 'One column',  'marsislav' ),
			'two-column' => __( 'Two columns',   'marsislav' ),
		),
	) );

	$wp_customize->add_setting( 'footer_copyright_text', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'footer_copyright_text', array(
		'label'       => __( 'Copyright text (Column 1)', 'marsislav' ),
		'description' => __( 'HTML allowed. Leave empty for default - © year, WEB site.', 'marsislav' ),
		'section'     => 'marsislav_footer_section',
		'type'        => 'textarea',
		'priority'    => 20,
	) );

	$wp_customize->add_setting( 'footer_col2_text', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'footer_col2_text', array(
		'label'       => __( 'Column  2 text' , 'marsislav' ),
		'description' => __( 'Only for “Two columns”. HTML allowed.', 'marsislav' ),
		'section'     => 'marsislav_footer_section',
		'type'        => 'textarea',
		'priority'    => 30,
	) );

	// ── Menu & Credits (priority 20) ─────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_footer_menu_section', array(
		'title'    => __( 'Menu & Credits', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 20,
	) );

	$wp_customize->add_setting( 'show_footer_menu', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'show_footer_menu', array(
		'label'    => __( 'Show Footer Menu', 'marsislav' ),
		'section'  => 'marsislav_footer_menu_section',
		'type'     => 'checkbox',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'footer_powered_text', array(
		'default'           => __( 'Proudly powered by %s', 'marsislav' ),
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'footer_powered_text', array(
		'label'       => __( '"Powered by" text', 'marsislav' ),
		'description' => __( 'Use %s for the CMS name.', 'marsislav' ),
		'section'     => 'marsislav_footer_menu_section',
		'type'        => 'textarea',
		'priority'    => 20,
		'input_attrs' => array( 'rows' => 2 ),
	) );

	$wp_customize->add_setting( 'footer_credits_text', array(
		'default'           => __( 'Theme: %1$s by %2$s.', 'marsislav' ),
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'footer_credits_text', array(
		'label'       => __( 'Credits text', 'marsislav' ),
		'description' => __( '%1$s = theme, %2$s = author/link.', 'marsislav' ),
		'section'     => 'marsislav_footer_menu_section',
		'type'        => 'textarea',
		'priority'    => 30,
		'input_attrs' => array( 'rows' => 2 ),
	) );

	$wp_customize->add_setting( 'show_footer_credits', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'show_footer_credits', array(
		'label'    => __( 'Show Footer Credits', 'marsislav' ),
		'section'  => 'marsislav_footer_menu_section',
		'type'     => 'checkbox',
		'priority' => 40,
	) );

	// ── Widget Areas (priority 30) ────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_footer_widgets_section', array(
		'title'    => __( 'Widget Areas', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'footer_sidebar_enable', array(
		'default'           => true,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'footer_sidebar_enable', array(
		'label'    => __( 'Show Footer Widget Areas', 'marsislav' ),
		'section'  => 'marsislav_footer_widgets_section',
		'type'     => 'checkbox',
		'priority' => 5,
	) );

	$wp_customize->add_setting( 'footer_sidebar_columns', array(
		'default'           => '3',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_footer_columns',
	) );
	$wp_customize->add_control( 'footer_sidebar_columns', array(
		'label'    => __( 'Number of column/s', 'marsislav' ),
		'section'  => 'marsislav_footer_widgets_section',
		'type'     => 'select',
		'choices'  => array(
			'1' => __( '1 column',  'marsislav' ),
			'2' => __( '2 columns',  'marsislav' ),
			'3' => __( '3 columns',  'marsislav' ),
			'4' => __( '4 columns',  'marsislav' ),
		),
		'priority' => 6,
	) );

	// ── Widget Area Design (priority 35) ──────────────────────────────────────
	// The background is inherited from Footer Design if not set.
	$wp_customize->add_section( 'marsislav_elem_footer_widgets', array(
		'title'       => __( 'Widget Area Design', 'marsislav' ),
		'panel'       => 'marsislav_footer_panel',
		'priority'    => 35,
		'description' => __( 'If no background is set, the Widget Area inherits the background from Footer Design.', 'marsislav' ),
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_footer_widgets', 'footer_widgets', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_footer_widgets', 'radius_footer_widgets', 0, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_footer_widgets', 'footer_widgets', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_footer_widgets', 'footer_widgets', 430 );
	marsislav_register_typography_controls( $wp_customize, 'marsislav_elem_footer_widgets', 'footer_widgets', 550 );

	// ── Footer Design (priority 40) ───────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_elem_footer', array(
		'title'    => __( 'Footer Design', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 40,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_footer', 'footer', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_footer', 'radius_footer', 0, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_footer', 'footer', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_footer', 'footer', 430 );
	marsislav_register_typography_controls( $wp_customize, 'marsislav_elem_footer', 'footer', 550 );

	// ── Copyright Bar Design (priority 45) ───────────────────────────────────
	$wp_customize->add_section( 'marsislav_elem_copyright', array(
		'title'    => __( 'Copyright Bar Design', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 45,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_copyright', 'copyright', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_copyright', 'radius_copyright', 0, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_copyright', 'copyright', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_copyright', 'copyright', 430 );
	marsislav_register_typography_controls( $wp_customize, 'marsislav_elem_copyright', 'copyright', 550 );

	// ── Footer Waves (priority 50) ────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_footer_waves_section', array(
		'title'    => __( 'Footer Waves', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 50,
	) );

	$wp_customize->add_setting( 'footer_waves_enable', array(
		'default'           => false,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'footer_waves_enable', array(
		'label'    => __( 'Enable Footer Waves', 'marsislav' ),
		'section'  => 'marsislav_footer_waves_section',
		'type'     => 'checkbox',
		'priority' => 10,
	) );

	foreach ( array(
		'footer_wave_color1' => array( __( 'Color of wave 1', 'marsislav' ), '#1e90ff', 20 ),
		'footer_wave_color2' => array( __( 'Color of wave 2', 'marsislav' ), '#3aa0ff', 30 ),
		'footer_wave_color3' => array( __( 'Color of wave 3', 'marsislav' ), '#63b3ff', 40 ),
	) as $key => $info ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $info[1],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array(
			'label'    => $info[0],
			'section'  => 'marsislav_footer_waves_section',
			'priority' => $info[2],
		) ) );
	}
}
add_action( 'customize_register', 'marsislav_footer_customizer' );


// =============================================================================
// 4E. THEME DESIGN PANEL
// =============================================================================

function marsislav_design_customizer( $wp_customize ) {

	// ── Global Background (priority 20) ──────────────────────────────────────
	$wp_customize->add_section( 'marsislav_elem_global', array(
		'title'    => __( 'Global Background', 'marsislav' ),
		'panel'    => 'marsislav_design_panel',
		'priority' => 20,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_global', 'global', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_global', 'radius_global', 0, 200 );

	// ── Colors: Text, Links & Headings (priority 10) ──────────────────────────
	$wp_customize->add_section( 'marsislav_sec_colors', array(
		'title'       => __( 'Colors (Text & Links)', 'marsislav' ),
		'panel'       => 'marsislav_design_panel',
		'priority'    => 10,
		'description' => __( 'Global colors for text, links, and headings.', 'marsislav' ),
	) );

	$p = 10;
	foreach ( array(
		'color_body_text'         => array( __( 'Page text',      'marsislav' ), '#1f2937' ),
		'color_body_link'         => array( __( 'Links',                  'marsislav' ), '#2563eb' ),
		'color_body_link_hover'   => array( __( 'Links — Hover',          'marsislav' ), '#1d4ed8' ),
		'color_nav_link'          => array( __( 'Navigation links',     'marsislav' ), '#1f2937' ),
		'color_nav_link_hover'    => array( __( 'Navigation links — Hover',     'marsislav' ), '#2563eb' ),
		'color_footer_text'       => array( __( 'Footer text',             'marsislav' ), '#1f2937' ),
		'color_footer_link'       => array( __( 'Footer links',           'marsislav' ), '#2563eb' ),
		'color_footer_link_hover' => array( __( 'Footer links — Hover',   'marsislav' ), '#1d4ed8' ),
		'color_h1'                => array( __( 'Title H1',              'marsislav' ), '#1f2937' ),
		'color_h2'                => array( __( 'Title H2',              'marsislav' ), '#1f2937' ),
		'color_h3'                => array( __( 'Title H3',              'marsislav' ), '#1f2937' ),
		'color_h4'                => array( __( 'Title H4',              'marsislav' ), '#1f2937' ),
		'color_h5'                => array( __( 'Title H5',              'marsislav' ), '#1f2937' ),
		'color_h6'                => array( __( 'Title H6',              'marsislav' ), '#1f2937' ),
	) as $key => $info ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $info[1],
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array(
			'label'    => $info[0],
			'section'  => 'marsislav_sec_colors',
			'priority' => $p++,
		) ) );
	}

	// ── Buttons (priority 30) ─────────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_elem_buttons', array(
		'title'    => __( 'Buttons', 'marsislav' ),
		'panel'    => 'marsislav_design_panel',
		'priority' => 30,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_buttons', 'buttons', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_buttons', 'radius_buttons', 4, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_buttons', 'buttons', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_buttons', 'buttons', 430 );

	// ── Input Fields (priority 40) ────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_elem_inputs', array(
		'title'    => __( 'Input Fields', 'marsislav' ),
		'panel'    => 'marsislav_design_panel',
		'priority' => 40,
	) );
	marsislav_register_bg_controls( $wp_customize, 'marsislav_elem_inputs', 'inputs', 10 );
	marsislav_register_radius_controls( $wp_customize, 'marsislav_elem_inputs', 'radius_inputs', 4, 200 );
	marsislav_register_shadow_controls( $wp_customize, 'marsislav_elem_inputs', 'inputs', 310 );
	marsislav_register_border_controls( $wp_customize, 'marsislav_elem_inputs', 'inputs', 430 );

	// ── Page Title (priority 55) ──────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_sec_page_title', array(
		'title'       => __( 'Page Title', 'marsislav' ),
		'panel'       => 'marsislav_design_panel',
		'priority'    => 55,
		'description' => __( 'Show/hide the H1 heading for each page type.', 'marsislav' ),
	) );

	foreach ( array(
		'show_title_page'     => __( 'Show on Pages',        'marsislav' ),
		'show_title_post'     => __( 'Show on Single Posts', 'marsislav' ),
		'show_title_archive'  => __( 'Show on Archives',     'marsislav' ),
		'show_title_category' => __( 'Show on Categories',   'marsislav' ),
		'show_title_home'     => __( 'Show on Blog Home',    'marsislav' ),
	) as $key => $label ) {
		$wp_customize->add_setting( $key, array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'marsislav_sanitize_checkbox',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $label,
			'section' => 'marsislav_sec_page_title',
			'type'    => 'checkbox',
		) );
	}

	// ── Breadcrumbs (priority 60) ─────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_sec_breadcrumbs', array(
		'title'    => __( 'Breadcrumbs', 'marsislav' ),
		'panel'    => 'marsislav_design_panel',
		'priority' => 60,
	) );

	$wp_customize->add_setting( 'breadcrumbs_enable', array(
		'default'           => true,
		'transport'         => 'refresh',
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'breadcrumbs_enable', array(
		'label'   => __( 'Show Breadcrumbs', 'marsislav' ),
		'section' => 'marsislav_sec_breadcrumbs',
		'type'    => 'checkbox',
	) );

	$p = 20;
	foreach ( array(
		'breadcrumbs_bg'               => array( __( 'Background',               'marsislav' ), '#f3f4f6' ),
		'breadcrumbs_text_color'       => array( __( 'Text color',     'marsislav' ), '#6b7280' ),
		'breadcrumbs_link_color'       => array( __( 'Links color',   'marsislav' ), '#2563eb' ),
		'breadcrumbs_link_hover_color' => array( __( 'Links — Hover',   'marsislav' ), '#1d4ed8' ),
	) as $key => $info ) {
		$wp_customize->add_setting( $key, array( 'default' => $info[1], 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 'label' => $info[0], 'section' => 'marsislav_sec_breadcrumbs', 'priority' => $p++ ) ) );
	}

	// ── Dark Mode (priority 70) ───────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_sec_dark_mode', array(
		'title'    => __( 'Dark Mode', 'marsislav' ),
		'panel'    => 'marsislav_design_panel',
		'priority' => 70,
	) );

	$wp_customize->add_setting( 'dark_mode_enable', array(
		'default'           => true,
		'transport'         => 'refresh',
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'dark_mode_enable', array(
		'label'   => __( 'Show Dark Mode button', 'marsislav' ),
		'section' => 'marsislav_sec_dark_mode',
		'type'    => 'checkbox',
	) );

	// ── Scroll-to-Top (priority 80) ───────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_sec_scroll_top', array(
		'title'    => __( 'Scroll-to-Top button', 'marsislav' ),
		'panel'    => 'marsislav_design_panel',
		'priority' => 80,
	) );

	$wp_customize->add_setting( 'scroll_to_top_enable', array(
		'default'           => true,
		'transport'         => 'refresh',
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'scroll_to_top_enable', array(
		'label'   => __( 'Show Scroll-to-Top button', 'marsislav' ),
		'section' => 'marsislav_sec_scroll_top',
		'type'    => 'checkbox',
	) );

	$p = 20;
	foreach ( array(
		'scroll_to_top_bg'       => array( __( 'Background',            'marsislav' ), '#2563eb' ),
		'scroll_to_top_color'    => array( __( 'Icon color','marsislav' ), '#ffffff' ),
		'scroll_to_top_bg_hover' => array( __( 'Background — Hover',    'marsislav' ), '#1d4ed8' ),
	) as $key => $info ) {
		$wp_customize->add_setting( $key, array( 'default' => $info[1], 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 'label' => $info[0], 'section' => 'marsislav_sec_scroll_top', 'priority' => $p++ ) ) );
	}
}
add_action( 'customize_register', 'marsislav_design_customizer', 25 );


// =============================================================================
// 5. CSS HELPER FUNCTIONS
// =============================================================================

/**
 * Generates CSS for the background of a given area.
 * If type = 'transparent' → returns transparent !important.
 * If type = 'solid' and no color is set (or it is inherit) → inherits from parent.
 */
function marsislav_get_bg_css( $area, $inherit_area = '' ) {
	$type    = (string) get_theme_mod( 'bg_' . $area . '_type',     'solid' );
	$color   = (string) get_theme_mod( 'bg_' . $area . '_color',    '' );
	$grad1   = (string) get_theme_mod( 'bg_' . $area . '_grad1',    '#ffffff' );
	$grad2   = (string) get_theme_mod( 'bg_' . $area . '_grad2',    '#eeeeee' );
	$dir     = (string) get_theme_mod( 'bg_' . $area . '_grad_dir', 'to bottom' );
	$img_url = esc_url( (string) get_theme_mod( 'bg_' . $area . '_image', '' ) );
	$repeat  = (string) get_theme_mod( 'bg_' . $area . '_repeat',   'no-repeat' );
	$size    = (string) get_theme_mod( 'bg_' . $area . '_size',     'cover' );

	$imp = ( 'global' !== $area ) ? ' !important' : '';

	// Transparent background
	if ( 'transparent' === $type ) {
		return 'background:transparent' . $imp . ';';
	}

	if ( 'solid' === $type ) {
		if ( $color ) {
			$hex = sanitize_hex_color( $color );
			if ( $hex ) return 'background:' . $hex . $imp . ';';
		}
		// No color — inherits from the parent area if set.
		if ( $inherit_area ) {
			return marsislav_get_bg_css( $inherit_area );
		}
		return '';
	}

	if ( 'gradient' === $type ) {
		return 'background:linear-gradient(' . esc_attr( $dir ) . ',' . sanitize_hex_color( $grad1 ) . ',' . sanitize_hex_color( $grad2 ) . ')' . $imp . ';';
	}

	if ( 'image' === $type && $img_url ) {
		$out  = 'background-image:url(' . $img_url . ')' . $imp . ';';
		$out .= 'background-repeat:' . esc_attr( $repeat ) . $imp . ';';
		$out .= 'background-size:' . esc_attr( $size ) . $imp . ';';
		$out .= 'background-position:center center' . $imp . ';';
		return $out;
	}

	return '';
}

/** Generate  CSS for border-radius */
function marsislav_get_radius_css( $key, $default = 0 ) {
	$global = (int) marsislav_sanitize_border_radius( get_theme_mod( $key, $default ) );
	$tl     = marsislav_sanitize_radius_corner( get_theme_mod( $key . '_tl', '' ) );
	$tr     = marsislav_sanitize_radius_corner( get_theme_mod( $key . '_tr', '' ) );
	$br     = marsislav_sanitize_radius_corner( get_theme_mod( $key . '_br', '' ) );
	$bl     = marsislav_sanitize_radius_corner( get_theme_mod( $key . '_bl', '' ) );

	$r_tl = ( '' !== $tl ) ? (int) $tl : $global;
	$r_tr = ( '' !== $tr ) ? (int) $tr : $global;
	$r_br = ( '' !== $br ) ? (int) $br : $global;
	$r_bl = ( '' !== $bl ) ? (int) $bl : $global;

	if ( 0 === $r_tl && 0 === $r_tr && 0 === $r_br && 0 === $r_bl ) return '';
	if ( $r_tl === $r_tr && $r_tr === $r_br && $r_br === $r_bl ) {
		return 'border-radius:' . $r_tl . 'px !important;';
	}
	return 'border-radius:' . $r_tl . 'px ' . $r_tr . 'px ' . $r_br . 'px ' . $r_bl . 'px !important;';
}

/** Generate CSS for box-shadow */
function marsislav_get_shadow_css( $key ) {
	$type = (string) get_theme_mod( $key . '_shadow_type', 'none' );
	if ( 'none' === $type ) return '';

	$color   = (string) get_theme_mod( $key . '_shadow_color',   '#000000' );
	$opacity = marsislav_sanitize_opacity( get_theme_mod( $key . '_shadow_opacity', 20 ) );
	$x       = (int) get_theme_mod( $key . '_shadow_x',      0 );
	$y       = (int) get_theme_mod( $key . '_shadow_y',      4 );
	$blur    = (int) get_theme_mod( $key . '_shadow_blur',   8 );
	$spread  = (int) get_theme_mod( $key . '_shadow_spread', 0 );

	$hex = sanitize_hex_color( $color );
	if ( ! $hex ) $hex = '#000000';
	list( $r, $g, $b ) = array_map( 'hexdec', str_split( ltrim( $hex, '#' ), 2 ) );
	$rgba  = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . round( $opacity / 100, 2 ) . ')';
	$inset = ( 'inset' === $type ) ? 'inset ' : '';

	return 'box-shadow:' . $inset . $x . 'px ' . $y . 'px ' . $blur . 'px ' . $spread . 'px ' . $rgba . ' !important;';
}

/** Generate CSS for border */
function marsislav_get_border_css( $key ) {
	$style = (string) get_theme_mod( $key . '_border_style', 'none' );
	if ( 'none' === $style ) return '';

	$color  = sanitize_hex_color( (string) get_theme_mod( $key . '_border_color', '#e5e7eb' ) );
	$width  = (int) get_theme_mod( $key . '_border_width', 1 );
	$top    = marsislav_sanitize_radius_corner( get_theme_mod( $key . '_border_top',    '' ) );
	$right  = marsislav_sanitize_radius_corner( get_theme_mod( $key . '_border_right',  '' ) );
	$bottom = marsislav_sanitize_radius_corner( get_theme_mod( $key . '_border_bottom', '' ) );
	$left   = marsislav_sanitize_radius_corner( get_theme_mod( $key . '_border_left',   '' ) );

	if ( '' === $top && '' === $right && '' === $bottom && '' === $left ) {
		return 'border:' . $width . 'px ' . $style . ' ' . $color . ' !important;';
	}

	$wt = ( '' !== $top    ) ? (int) $top    : $width;
	$wr = ( '' !== $right  ) ? (int) $right  : $width;
	$wb = ( '' !== $bottom ) ? (int) $bottom : $width;
	$wl = ( '' !== $left   ) ? (int) $left   : $width;

	return 'border-style:' . $style . ' !important;border-color:' . $color . ' !important;'
		 . 'border-width:' . $wt . 'px ' . $wr . 'px ' . $wb . 'px ' . $wl . 'px !important;';
}


// =============================================================================
// 6. DYNAMIC CSS (wp_head)
// =============================================================================

function marsislav_dynamic_css() {
	$css = '';

	// Sticky header
	if ( ! (bool) get_theme_mod( 'header_sticky', true ) ) {
		$css .= 'body #masthead{position:relative !important;top:auto !important;}';
	}

	// ── Element backgrounds, radius, shadow, border ───────────────────────────
	// footer_widgets inherits the background from footer if not set.
	$element_map = array(
		// key => [ bg_area, radius_key, radius_default, css_selector, inherit_bg_from ]
		'global'         => array( 'global',         'radius_global',         0, 'body',                                                                       '' ),
		'header'         => array( 'header',         'radius_header',         0, 'body #masthead',                                                              '' ),
		'content'        => array( 'content',        'radius_content',        0, 'body #primary,body #content',                                                 '' ),
		'sidebar'        => array( 'sidebar',        'radius_sidebar',        0, 'body #secondary',                                                             '' ),
		'footer_widgets' => array( 'footer_widgets', 'radius_footer_widgets', 0, 'body #footer-sidebar-area',                                                   'footer' ), // inherits from the footer
		'footer'         => array( 'footer',         'radius_footer',         0, 'body #colophon',                                                              '' ),
		'copyright'      => array( 'copyright',      'radius_copyright',      0, 'body #colophon .site-info',                                                   '' ),
		'buttons'        => array( 'buttons',        'radius_buttons',        4, 'a.button,.button,button,input[type="submit"],input[type="button"]',            '' ),
		'inputs'         => array( 'inputs',         'radius_inputs',         4, 'input[type="text"],input[type="email"],input[type="search"],textarea',         '' ),
		'cards'          => array( 'cards',          'radius_cards',          8, '.post,.card,.entry,article',                                                   '' ),
		'images'         => array( 'images',         'radius_images',         0, 'img',                                                                         '' ),
	);

	foreach ( $element_map as $elem_key => $data ) {
		list( $bg_area, $radius_key, $radius_default, $selector, $inherit_from ) = $data;
		$parts = '';

		// Background (with inheritance support).
		$bg = marsislav_get_bg_css( $bg_area, $inherit_from );
		if ( $bg ) $parts .= $bg;

		// Radius
		$radius = marsislav_get_radius_css( $radius_key, $radius_default );
		if ( $radius ) $parts .= $radius;

		if ( 'global' !== $elem_key ) {
			$shadow = marsislav_get_shadow_css( $elem_key );
			if ( $shadow ) $parts .= $shadow;

			$border = marsislav_get_border_css( $elem_key );
			if ( $border ) $parts .= $border;
		}

		if ( $parts ) {
			$css .= $selector . '{' . $parts . '}';
		}
	}

	// ── Global colors ──────────────────────────────────────────────────────
	$color_map = array(
		'color_body_text'         => array( 'html body,html .site-content',                                           '#1f2937' ),
		'color_body_link'         => array( 'html .site-content a',                                                   '#2563eb' ),
		'color_body_link_hover'   => array( 'html .site-content a:hover',                                             '#1d4ed8' ),
		'color_nav_link'          => array( 'html .primary-menu a',                                                   '#1f2937' ),
		'color_nav_link_hover'    => array( 'html .primary-menu a:hover,html .primary-menu .current-menu-item>a',     '#2563eb' ),
		'color_footer_text'       => array( 'html #colophon,html #colophon .footer-sidebar-area',                     '#1f2937' ),
		'color_footer_link'       => array( 'html #colophon a',                                                       '#2563eb' ),
		'color_footer_link_hover' => array( 'html #colophon a:hover',                                                 '#1d4ed8' ),
		'color_h1'                => array( 'html h1', '#1f2937' ),
		'color_h2'                => array( 'html h2', '#1f2937' ),
		'color_h3'                => array( 'html h3', '#1f2937' ),
		'color_h4'                => array( 'html h4', '#1f2937' ),
		'color_h5'                => array( 'html h5', '#1f2937' ),
		'color_h6'                => array( 'html h6', '#1f2937' ),
	);
	foreach ( $color_map as $key => $map ) {
		$val = sanitize_hex_color( (string) get_theme_mod( $key, $map[1] ) );
		if ( $val ) $css .= $map[0] . '{color:' . $val . ' !important;}';
	}

	// ── Per-element typography ────────────────────────────────────────────────
	$typo_elements = array(
		'header'         => 'body #masthead',
		'content'        => 'body #primary,body #content',
		'sidebar'        => 'body #secondary',
		// Widget area text — specific selector for higher priority
		'footer_widgets' => 'html body #footer-sidebar-area',
		'footer'         => 'body #colophon',
		'copyright'      => 'body #colophon .site-info',
		'cards'          => '.post,.card,.entry,article',
	);

	foreach ( $typo_elements as $elem_key => $scope ) {
		$pre = 'typo_' . $elem_key . '_';

		$text  = sanitize_hex_color( (string) get_theme_mod( $pre . 'text',       '' ) );
		$link  = sanitize_hex_color( (string) get_theme_mod( $pre . 'link',       '' ) );
		$hover = sanitize_hex_color( (string) get_theme_mod( $pre . 'link_hover', '' ) );
		$h1    = sanitize_hex_color( (string) get_theme_mod( $pre . 'h1',         '' ) );
		$h2    = sanitize_hex_color( (string) get_theme_mod( $pre . 'h2',         '' ) );
		$h3    = sanitize_hex_color( (string) get_theme_mod( $pre . 'h3',         '' ) );
		$h4    = sanitize_hex_color( (string) get_theme_mod( $pre . 'h4',         '' ) );
		$h5    = sanitize_hex_color( (string) get_theme_mod( $pre . 'h5',         '' ) );
		$h6    = sanitize_hex_color( (string) get_theme_mod( $pre . 'h6',         '' ) );

		// We use html prefix for better specificity
		if ( $text  ) $css .= 'html ' . $scope . '{color:' . $text . ' !important;}';
		if ( $link  ) $css .= 'html ' . $scope . ' a{color:' . $link . ' !important;}';
		if ( $hover ) $css .= 'html ' . $scope . ' a:hover{color:' . $hover . ' !important;}';
		if ( $h1    ) $css .= 'html ' . $scope . ' h1{color:' . $h1 . ' !important;}';
		if ( $h2    ) $css .= 'html ' . $scope . ' h2{color:' . $h2 . ' !important;}';
		if ( $h3    ) $css .= 'html ' . $scope . ' h3{color:' . $h3 . ' !important;}';
		if ( $h4    ) $css .= 'html ' . $scope . ' h4{color:' . $h4 . ' !important;}';
		if ( $h5    ) $css .= 'html ' . $scope . ' h5{color:' . $h5 . ' !important;}';
		if ( $h6    ) $css .= 'html ' . $scope . ' h6{color:' . $h6 . ' !important;}';
	}

	// ── Breadcrumbs ───────────────────────────────────────────────────────────
	$bc_bg    = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_bg',               '#f3f4f6' ) );
	$bc_text  = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_text_color',       '#6b7280' ) );
	$bc_link  = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_link_color',       '#2563eb' ) );
	$bc_hover = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_link_hover_color', '#1d4ed8' ) );
	if ( $bc_bg )    $css .= '.marsislav-breadcrumbs{background:' . $bc_bg . ' !important;}';
	if ( $bc_text )  $css .= '.marsislav-breadcrumbs,.bc-sep,.bc-current{color:' . $bc_text . ' !important;}';
	if ( $bc_link )  $css .= '.marsislav-breadcrumbs .bc-link{color:' . $bc_link . ' !important;}';
	if ( $bc_hover ) $css .= '.marsislav-breadcrumbs .bc-link:hover{color:' . $bc_hover . ' !important;}';

	// ── Scroll-to-Top ─────────────────────────────────────────────────────────
	$stt_bg    = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_bg',       '#2563eb' ) );
	$stt_color = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_color',    '#ffffff' ) );
	$stt_hover = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_bg_hover', '#1d4ed8' ) );
	if ( $stt_bg )    $css .= '#marsislav-scroll-top{background:' . $stt_bg . ' !important;}';
	if ( $stt_color ) $css .= '#marsislav-scroll-top{color:' . $stt_color . ' !important;}#marsislav-scroll-top svg{stroke:' . $stt_color . ' !important;}';
	if ( $stt_hover ) $css .= '#marsislav-scroll-top:hover{background:' . $stt_hover . ' !important;}';

	// ── Mobile Menu CSS ───────────────────────────────────────────────────────
	$mm_bg    = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'mobile_menu_bg_color',        '' ) );
	$mm_text  = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'mobile_menu_text_color',      '' ) );
	$mm_hover = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'mobile_menu_text_hover_color','') );
	$mm_icon  = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'mobile_menu_icon_color',      '' ) );

	if ( $mm_bg ) {
		$css .= '.marsislav-menu-sidebar{background-color:' . $mm_bg . ' !important;}';
	}
	if ( $mm_text ) {
		$css .= '.marsislav-mobile-nav a,.marsislav-mobile-nav .sub-menu a{color:' . $mm_text . ' !important;}';
	}
	if ( $mm_hover ) {
		$css .= '.marsislav-mobile-nav a:hover,.marsislav-mobile-nav li.current-menu-item>a{color:' . $mm_hover . ' !important;}';
	}
	if ( $mm_icon ) {
		$css .= '.menu-toggle,.menu-toggle .menu-text{color:' . $mm_icon . ' !important;}'
			  . '.hamburger-lines span{background-color:' . $mm_icon . ' !important;}';
	}

	// ── Submenu CSS ───────────────────────────────────────────────────────────
	$sub_bg       = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'submenu_bg_color',          '' ) );
	$sub_text     = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'submenu_text_color',        '' ) );
	$sub_hover    = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'submenu_text_hover_color',  '' ) );
	$sub_bg_hover = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'submenu_bg_hover_color',    '' ) );
	$sub_border   = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'submenu_border_color',      '' ) );
	$sub_radius   = (int) get_theme_mod( 'submenu_border_radius', 6 );
	$mob_sub_bg   = marsislav_sanitize_hex_color_blank( (string) get_theme_mod( 'mobile_submenu_bg_color',   '' ) );

	if ( $sub_bg )       $css .= '.primary-menu .sub-menu{background:' . $sub_bg . ' !important;}';
	if ( $sub_text )     $css .= '.primary-menu .sub-menu a{color:' . $sub_text . ' !important;}';
	if ( $sub_hover )    $css .= '.primary-menu .sub-menu a:hover{color:' . $sub_hover . ' !important;}';
	if ( $sub_bg_hover ) $css .= '.primary-menu .sub-menu li:hover>a{background:' . $sub_bg_hover . ' !important;}';
	if ( $sub_border )   $css .= '.primary-menu .sub-menu{border-color:' . $sub_border . ' !important;}';
	if ( $sub_radius )   $css .= '.primary-menu .sub-menu{border-radius:' . $sub_radius . 'px !important;}';
	if ( $mob_sub_bg )   $css .= '.marsislav-mobile-nav .sub-menu{background:' . $mob_sub_bg . ' !important;}';

	if ( $css ) {
		echo '<style id="marsislav-dynamic-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'marsislav_dynamic_css', 99 );


// =============================================================================
// 7. PREVIEW JS — Live preview
// =============================================================================

/**
 * Loads all Customizer preview scripts.
 * Everything is centralized here.
 */
function marsislav_all_preview_scripts() {

	// Main customizer (blogname, blog description)
	wp_enqueue_script(
		'marsislav-customizer',
		get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ),
		_S_VERSION,
		true
	);

	// Top Bar
	wp_enqueue_script(
		'marsislav-customizer-topbar',
		get_template_directory_uri() . '/js/customizer-topbar.js',
		array( 'customize-preview', 'jquery' ),
		_S_VERSION,
		true
	);

	// Blog Meta + Mobile Menu preview
	wp_enqueue_script(
		'marsislav-blog-customizer-preview',
		get_template_directory_uri() . '/js/blog-customizer-preview.js',
		array( 'customize-preview' ),
		filemtime( get_template_directory() . '/js/blog-customizer-preview.js' ),
		true
	);

	// Sidebar positions
	wp_enqueue_script(
		'marsislav-customizer-sidebar',
		get_template_directory_uri() . '/js/customizer-sidebar.js',
		array( 'customize-preview', 'jquery' ),
		_S_VERSION,
		true
	);

	wp_localize_script( 'marsislav-customizer-sidebar', 'marsislavSidebarVars', array(
		'settings' => array(
			'sidebar_pos_blog', 'sidebar_pos_post', 'sidebar_pos_page',
			'sidebar_pos_home', 'sidebar_pos_shop', 'sidebar_pos_product',
		),
	) );

	// Footer widget areas
	wp_enqueue_script(
		'marsislav-customizer-footer-sidebar',
		get_template_directory_uri() . '/js/customizer-footer-sidebar.js',
		array( 'customize-preview', 'jquery' ),
		_S_VERSION,
		true
	);

	// Footer waves
	wp_enqueue_script(
		'marsislav-customizer-footer-waves',
		get_template_directory_uri() . '/js/customizer-footer-waves.js',
		array( 'customize-preview', 'jquery' ),
		_S_VERSION,
		true
	);

	// Colors, backgrounds, radius, shadow, border, typography
	wp_enqueue_script(
		'marsislav-customizer-colors',
		get_template_directory_uri() . '/js/customizer-colors.js',
		array( 'customize-preview', 'jquery' ),
		_S_VERSION,
		true
	);

	
	$areas    = array( 'global', 'header', 'content', 'sidebar', 'footer_widgets', 'footer', 'copyright', 'buttons', 'inputs', 'cards', 'images' );
	$img_urls = array();
	foreach ( $areas as $area ) {
		$url = esc_url( (string) get_theme_mod( 'bg_' . $area . '_image', '' ) );
		if ( $url ) {
			$img_urls[ '__area__' . $area ] = $url;
		}
	}

	wp_localize_script( 'marsislav-customizer-colors', 'marsislavBgData', array(
		'imgUrls' => $img_urls,
	) );
}
add_action( 'customize_preview_init', 'marsislav_all_preview_scripts' );


/**
 * Loads the Reset button in Customizer Controls (not in Preview).
 */
function marsislav_reset_enqueue() {

	// Reset button for every section
	wp_enqueue_script(
		'marsislav-customizer-reset',
		get_template_directory_uri() . '/js/customizer-reset.js',
		array( 'customize-controls', 'jquery' ),
		_S_VERSION,
		true
	);

	// Static defaults - Reset
	$static_defaults = array(
		'header_sticky'              => true,
		'breadcrumbs_enable'         => true,
		'scroll_to_top_enable'       => true,
		'dark_mode_enable'           => true,
		'footer_layout'              => 'one-column',
		'footer_copyright_text'      => '',
		'footer_col2_text'           => '',
		'show_footer_menu'           => true,
		'show_footer_credits'        => true,
		'footer_sidebar_enable'      => true,
		'footer_sidebar_columns'     => '4',
		'topbar_enable'              => false,
		'topbar_layout'              => 'one',
		'topbar_marquee'             => false,
		'topbar_marquee_speed'       => 18,
		'topbar_text'                => 'Welcome to our website',
		'topbar_text_color'          => '#ffffff',
		'topbar_bg_color'            => '#1f2937',
		'topbar_col1_text'           => '',
		'topbar_col2_text'           => '',
		'sidebar_pos_blog'           => 'right',
		'sidebar_pos_post'           => 'right',
		'sidebar_pos_page'           => 'disabled',
		'sidebar_pos_home'           => 'disabled',
		'sidebar_pos_shop'           => 'right',
		'sidebar_pos_product'        => 'disabled',
		'submenu_border_radius'      => 6,
		'mobile_menu_bg_color'       => '',
		'mobile_menu_text_color'     => '',
		'mobile_menu_icon_color'     => '',
	);

	wp_localize_script( 'marsislav-customizer-reset', 'marsislavResetData', array(
		'staticDefaults' => $static_defaults,
	) );
}
add_action( 'customize_controls_enqueue_scripts', 'marsislav_reset_enqueue' );
