<?php
/**
 * Theme Design Customizer — colors, backgrounds, typography, and design elements.
 *
 * Registers the Theme Design panel and all its sections:
 * Colors, Element Backgrounds, Breadcrumbs, Scroll to Top, Dark Mode.
 *
 * @package marsislav
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
 * Sanitize helpers
 * ============================================================ */

function marsislav_sanitize_hex_color_blank( $color ) {
    if ( '' === $color ) return '';
    return sanitize_hex_color( $color );
}

function marsislav_sanitize_bg_type( $val ) {
    // 'transparent' removed intentionally
    return in_array( $val, array( 'solid', 'gradient', 'image' ), true ) ? $val : 'solid';
}

function marsislav_sanitize_gradient_dir( $val ) {
    $valid = array(
        'to bottom', 'to top', 'to right', 'to left',
        'to bottom right', 'to bottom left', 'to top right', 'to top left',
        '45deg', '135deg', '225deg', '315deg',
    );
    return in_array( $val, $valid, true ) ? $val : 'to bottom';
}

function marsislav_sanitize_bg_repeat( $val ) {
    $valid = array( 'no-repeat', 'repeat', 'repeat-x', 'repeat-y' );
    return in_array( $val, $valid, true ) ? $val : 'no-repeat';
}

function marsislav_sanitize_bg_size( $val ) {
    $valid = array( 'cover', 'contain', 'auto' );
    return in_array( $val, $valid, true ) ? $val : 'cover';
}

function marsislav_sanitize_opacity( $val ) {
    $val = floatval( $val );
    return ( $val >= 0 && $val <= 100 ) ? $val : 100;
}

function marsislav_sanitize_border_radius( $val ) {
    return min( absint( $val ), 200 );
}

function marsislav_sanitize_radius_corner( $val ) {
    if ( '' === $val || null === $val ) return '';
    return min( absint( $val ), 200 );
}


function marsislav_sanitize_shadow_type( $val ) {
    return in_array( $val, array( 'none', 'outset', 'inset' ), true ) ? $val : 'none';
}

function marsislav_sanitize_border_style( $val ) {
    $valid = array( 'none', 'solid', 'dashed', 'dotted', 'double', 'groove', 'ridge', 'inset', 'outset' );
    return in_array( $val, $valid, true ) ? $val : 'none';
}

function marsislav_sanitize_int_range( $val ) {
    return intval( $val );
}

/* ============================================================
 * Helper: register Background controls for a section
 * ============================================================ */

function marsislav_register_bg_controls( $wp_customize, $section, $area, $priority_start = 10 ) {

    $p = $priority_start;

    $gradient_choices = array(
        'to bottom'       => 'Top → Bottom',
        'to top'          => 'Bottom → Top',
        'to right'        => 'Left → Right',
        'to left'         => 'Right → Left',
        'to bottom right' => 'Diagonal ↘ Down-Right',
        'to bottom left'  => 'Diagonal ↙ Down-Left',
        'to top right'    => 'Diagonal ↗ Up-Right',
        'to top left'     => 'Diagonal ↖ Up-Left',
        '45deg'           => '45°',
        '135deg'          => '135°',
        '225deg'          => '225°',
        '315deg'          => '315°',
    );

    // Background Type
    $wp_customize->add_setting( 'bg_' . $area . '_type', array(
        'default'           => 'solid',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_bg_type',
    ) );
    $wp_customize->add_control( 'bg_' . $area . '_type', array(
        'label'    => __( 'Background Type', 'marsislav' ),
        'section'  => $section,
        'type'     => 'select',
        'priority' => $p++,
        'choices'  => array(
            'solid'    => __( 'Solid Color', 'marsislav' ),
            'gradient' => __( 'Gradient',    'marsislav' ),
            'image'    => __( 'Image',        'marsislav' ),
        ),
    ) );

    // Solid color
    $wp_customize->add_setting( 'bg_' . $area . '_color', array(
        'default'           => '',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_color', array(
        'label'    => __( 'Color', 'marsislav' ),
        'section'  => $section,
        'priority' => $p++,
    ) ) );


    // Gradient color 1
    $wp_customize->add_setting( 'bg_' . $area . '_grad1', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_grad1', array(
        'label'    => __( 'Gradient — Color 1', 'marsislav' ),
        'section'  => $section,
        'priority' => $p++,
    ) ) );

    // Gradient color 2
    $wp_customize->add_setting( 'bg_' . $area . '_grad2', array(
        'default'           => '#eeeeee',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_' . $area . '_grad2', array(
        'label'    => __( 'Gradient — Color 2', 'marsislav' ),
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
        'label'    => __( 'Gradient Direction', 'marsislav' ),
        'section'  => $section,
        'type'     => 'select',
        'priority' => $p++,
        'choices'  => $gradient_choices,
    ) );

    // Image
    $wp_customize->add_setting( 'bg_' . $area . '_image', array(
        'default'           => '',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bg_' . $area . '_image', array(
        'label'    => __( 'Background Image', 'marsislav' ),
        'section'  => $section,
        'priority' => $p++,
    ) ) );

    // Image repeat
    $wp_customize->add_setting( 'bg_' . $area . '_repeat', array(
        'default'           => 'no-repeat',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_bg_repeat',
    ) );
    $wp_customize->add_control( 'bg_' . $area . '_repeat', array(
        'label'    => __( 'Image Repeat', 'marsislav' ),
        'section'  => $section,
        'type'     => 'select',
        'priority' => $p++,
        'choices'  => array(
            'no-repeat' => __( 'No Repeat',           'marsislav' ),
            'repeat'    => __( 'Repeat (X and Y)',     'marsislav' ),
            'repeat-x'  => __( 'Repeat Horizontally', 'marsislav' ),
            'repeat-y'  => __( 'Repeat Vertically',   'marsislav' ),
        ),
    ) );

    // Image size
    $wp_customize->add_setting( 'bg_' . $area . '_size', array(
        'default'           => 'cover',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_bg_size',
    ) );
    $wp_customize->add_control( 'bg_' . $area . '_size', array(
        'label'    => __( 'Image Size', 'marsislav' ),
        'section'  => $section,
        'type'     => 'select',
        'priority' => $p++,
        'choices'  => array(
            'cover'   => __( 'Cover (fill)',   'marsislav' ),
            'contain' => __( 'Contain (fit)',  'marsislav' ),
            'auto'    => __( 'Original size',  'marsislav' ),
        ),
    ) );

    return $p;
}

/* ============================================================
 * Helper: register Border Radius controls for a section
 * ============================================================ */

function marsislav_register_radius_controls( $wp_customize, $section, $key, $default_global = 0, $priority_start = 200 ) {

    $p = $priority_start;

    // Global radius
    $wp_customize->add_setting( $key, array(
        'default'           => $default_global,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_border_radius',
    ) );
    $wp_customize->add_control( $key, array(
        'label'       => __( 'Border Radius — Global (px)', 'marsislav' ),
        'description' => __( 'Applies to all 4 corners. Override individually below.', 'marsislav' ),
        'section'     => $section,
        'type'        => 'range',
        'priority'    => $p++,
        'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 1 ),
    ) );

    // Per-corner overrides
    $corners = array(
        '_tl' => __( 'Top-Left (px)', 'marsislav' ),
        '_tr' => __( 'Top-Right (px)', 'marsislav' ),
        '_br' => __( 'Bottom-Right (px)', 'marsislav' ),
        '_bl' => __( 'Bottom-Left (px)', 'marsislav' ),
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

/* ============================================================
 * Helper: register Box Shadow controls for a section
 * ============================================================ */

function marsislav_register_shadow_controls( $wp_customize, $section, $key, $priority_start = 310 ) {

    $p = $priority_start;

    $wp_customize->add_setting( $key . '_shadow_type', array(
        'default'           => 'none',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_shadow_type',
    ) );
    $wp_customize->add_control( $key . '_shadow_type', array(
        'label'    => __( 'Box Shadow — Type', 'marsislav' ),
        'section'  => $section,
        'type'     => 'select',
        'priority' => $p++,
        'choices'  => array(
            'none'   => __( 'None',           'marsislav' ),
            'outset' => __( 'Outset (normal)', 'marsislav' ),
            'inset'  => __( 'Inset (inner)',   'marsislav' ),
        ),
    ) );

    $wp_customize->add_setting( $key . '_shadow_color', array(
        'default'           => '#000000',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key . '_shadow_color', array(
        'label'    => __( 'Shadow Color', 'marsislav' ),
        'section'  => $section,
        'priority' => $p++,
    ) ) );

    $wp_customize->add_setting( $key . '_shadow_opacity', array(
        'default'           => 20,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_opacity',
    ) );
    $wp_customize->add_control( $key . '_shadow_opacity', array(
        'label'       => __( 'Shadow Opacity (0–100%)', 'marsislav' ),
        'section'     => $section,
        'type'        => 'range',
        'priority'    => $p++,
        'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 1 ),
    ) );

    $wp_customize->add_setting( $key . '_shadow_x', array(
        'default'           => 0,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_int_range',
    ) );
    $wp_customize->add_control( $key . '_shadow_x', array(
        'label'       => __( 'Shadow Horizontal Offset (px)', 'marsislav' ),
        'section'     => $section,
        'type'        => 'number',
        'priority'    => $p++,
        'input_attrs' => array( 'min' => -100, 'max' => 100, 'step' => 1 ),
    ) );

    $wp_customize->add_setting( $key . '_shadow_y', array(
        'default'           => 4,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_int_range',
    ) );
    $wp_customize->add_control( $key . '_shadow_y', array(
        'label'       => __( 'Shadow Vertical Offset (px)', 'marsislav' ),
        'section'     => $section,
        'type'        => 'number',
        'priority'    => $p++,
        'input_attrs' => array( 'min' => -100, 'max' => 100, 'step' => 1 ),
    ) );

    $wp_customize->add_setting( $key . '_shadow_blur', array(
        'default'           => 8,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( $key . '_shadow_blur', array(
        'label'       => __( 'Shadow Blur Radius (px)', 'marsislav' ),
        'section'     => $section,
        'type'        => 'range',
        'priority'    => $p++,
        'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 1 ),
    ) );

    $wp_customize->add_setting( $key . '_shadow_spread', array(
        'default'           => 0,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_int_range',
    ) );
    $wp_customize->add_control( $key . '_shadow_spread', array(
        'label'       => __( 'Shadow Spread (px)', 'marsislav' ),
        'section'     => $section,
        'type'        => 'number',
        'priority'    => $p++,
        'input_attrs' => array( 'min' => -50, 'max' => 50, 'step' => 1 ),
    ) );

    return $p;
}

/* ============================================================
 * Helper: register Border controls for a section
 * ============================================================ */

function marsislav_register_border_controls( $wp_customize, $section, $key, $priority_start = 430 ) {

    $p = $priority_start;

    $border_styles = array(
        'none'   => __( 'None',   'marsislav' ),
        'solid'  => __( 'Solid',  'marsislav' ),
        'dashed' => __( 'Dashed', 'marsislav' ),
        'dotted' => __( 'Dotted', 'marsislav' ),
        'double' => __( 'Double', 'marsislav' ),
        'groove' => __( 'Groove', 'marsislav' ),
        'ridge'  => __( 'Ridge',  'marsislav' ),
        'inset'  => __( 'Inset',  'marsislav' ),
        'outset' => __( 'Outset', 'marsislav' ),
    );

    // Style
    $wp_customize->add_setting( $key . '_border_style', array(
        'default'           => 'none',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_border_style',
    ) );
    $wp_customize->add_control( $key . '_border_style', array(
        'label'    => __( 'Border Style', 'marsislav' ),
        'section'  => $section,
        'type'     => 'select',
        'priority' => $p++,
        'choices'  => $border_styles,
    ) );

    // Color
    $wp_customize->add_setting( $key . '_border_color', array(
        'default'           => '#e5e7eb',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_hex_color_blank',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key . '_border_color', array(
        'label'    => __( 'Border Color', 'marsislav' ),
        'section'  => $section,
        'priority' => $p++,
    ) ) );

    // Global width
    $wp_customize->add_setting( $key . '_border_width', array(
        'default'           => 1,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( $key . '_border_width', array(
        'label'       => __( 'Border Width — All Sides (px)', 'marsislav' ),
        'description' => __( 'Override per-side below.', 'marsislav' ),
        'section'     => $section,
        'type'        => 'range',
        'priority'    => $p++,
        'input_attrs' => array( 'min' => 0, 'max' => 20, 'step' => 1 ),
    ) );

    // Per-side widths
    $sides = array(
        '_border_top'    => __( 'Border Top Width (px)',    'marsislav' ),
        '_border_right'  => __( 'Border Right Width (px)',  'marsislav' ),
        '_border_bottom' => __( 'Border Bottom Width (px)', 'marsislav' ),
        '_border_left'   => __( 'Border Left Width (px)',   'marsislav' ),
    );

    foreach ( $sides as $suffix => $label ) {
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
            'input_attrs' => array( 'min' => 0, 'max' => 20, 'step' => 1, 'placeholder' => __( 'All', 'marsislav' ) ),
        ) );
    }

    return $p;
}


/* ============================================================
 * Helper: register Typography (text/link/heading colors) per element
 *
 * $elem_key  — used to namespace settings, e.g. 'header'
 * $css_scope — CSS selector that scopes the rules, e.g. 'body #masthead'
 * ============================================================ */

function marsislav_register_typography_controls( $wp_customize, $section, $elem_key, $priority_start = 550 ) {

    $p   = $priority_start;
    $pre = 'typo_' . $elem_key . '_'; // setting key prefix

    // Section heading label
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
        $pre . 'text'       => __( 'Text Color',         'marsislav' ),
        $pre . 'link'       => __( 'Link Color',         'marsislav' ),
        $pre . 'link_hover' => __( 'Link Color — Hover', 'marsislav' ),
        $pre . 'h1'         => __( 'H1 Color',           'marsislav' ),
        $pre . 'h2'         => __( 'H2 Color',           'marsislav' ),
        $pre . 'h3'         => __( 'H3 Color',           'marsislav' ),
        $pre . 'h4'         => __( 'H4 Color',           'marsislav' ),
        $pre . 'h5'         => __( 'H5 Color',           'marsislav' ),
        $pre . 'h6'         => __( 'H6 Color',           'marsislav' ),
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

/* ============================================================
 * Main register function
 * ============================================================ */

function marsislav_colors_customizer( $wp_customize ) {

    /* ── Panel: Theme Design ─────────────────────────────────────── */
    $wp_customize->add_panel( 'marsislav_design_panel', array(
        'title'    => __( 'Theme Design', 'marsislav' ),
        'priority' => 140,
    ) );

    /* ── General Settings (inside Header panel) ─────────────────── */
    $wp_customize->add_section( 'marsislav_sec_general', array(
        'title'    => __( 'General Settings', 'marsislav' ),
        'panel'    => 'marsislav_header_panel',
        'priority' => 5,
    ) );

    $wp_customize->add_setting( 'header_sticky', array(
        'default'           => true,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'header_sticky', array(
        'label'   => __( 'Sticky Header', 'marsislav' ),
        'section' => 'marsislav_sec_general',
        'type'    => 'checkbox',
    ) );

    /* ── Colors: Text, Links & Headings ──────────────────────────── */
    $wp_customize->add_section( 'marsislav_sec_colors', array(
        'title'       => __( 'Colors (Text & Links)', 'marsislav' ),
        'panel'       => 'marsislav_design_panel',
        'priority'    => 10,
        'description' => __( 'Global text, link and heading colors.', 'marsislav' ),
    ) );

    $color_settings = array(
        'color_body_text'         => array( __( 'Body Text',               'marsislav' ), '#1f2937' ),
        'color_body_link'         => array( __( 'Links',                   'marsislav' ), '#2563eb' ),
        'color_body_link_hover'   => array( __( 'Links — Hover',           'marsislav' ), '#1d4ed8' ),
        'color_nav_link'          => array( __( 'Navigation Links',        'marsislav' ), '#1f2937' ),
        'color_nav_link_hover'    => array( __( 'Navigation Links — Hover','marsislav' ), '#2563eb' ),
        'color_footer_text'       => array( __( 'Footer Text',             'marsislav' ), '#1f2937' ),
        'color_footer_link'       => array( __( 'Footer Links',            'marsislav' ), '#2563eb' ),
        'color_footer_link_hover' => array( __( 'Footer Links — Hover',    'marsislav' ), '#1d4ed8' ),
        'color_h1'                => array( __( 'Heading H1',              'marsislav' ), '#1f2937' ),
        'color_h2'                => array( __( 'Heading H2',              'marsislav' ), '#1f2937' ),
        'color_h3'                => array( __( 'Heading H3',              'marsislav' ), '#1f2937' ),
        'color_h4'                => array( __( 'Heading H4',              'marsislav' ), '#1f2937' ),
        'color_h5'                => array( __( 'Heading H5',              'marsislav' ), '#1f2937' ),
        'color_h6'                => array( __( 'Heading H6',              'marsislav' ), '#1f2937' ),
    );

    $p = 10;
    foreach ( $color_settings as $key => $info ) {
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

    /* ── Element Sections (Background, Radius, Shadow, Border, Typography) ── */

    $elements = array(
        // key => [ label, section_priority, bg_area_key, radius_key, radius_default, css_selector, panel ]
        'global'         => array( __( 'Global Background',  'marsislav' ),    20, 'global',         'radius_global',         0,  'body',                                                                      'marsislav_design_panel'  ),
        'header'         => array( __( 'Header Design',      'marsislav' ),    20, 'header',         'radius_header',         0,  'body #masthead',                                                            'marsislav_header_panel'  ),
        'content'        => array( __( 'Content Design',     'marsislav' ),    10, 'content',        'radius_content',        0,  'body #primary,body #content',                                               'marsislav_content_panel' ),
        'sidebar'        => array( __( 'Sidebar Design',     'marsislav' ),    20, 'sidebar',        'radius_sidebar',        0,  'body #secondary',                                                           'marsislav_sidebar_panel' ),
        'footer_widgets' => array( __( 'Widget Area Design', 'marsislav' ),    40, 'footer_widgets', 'radius_footer_widgets', 0,  'body #footer-sidebar-area',                                                 'marsislav_footer_panel'  ),
        'footer'         => array( __( 'Footer Design',      'marsislav' ),    50, 'footer',         'radius_footer',         0,  'body #colophon',                                                            'marsislav_footer_panel'  ),
        'copyright'      => array( __( 'Copyright Bar Design','marsislav' ),   60, 'copyright',      'radius_copyright',      0,  'body #colophon .site-info',                                                 'marsislav_footer_panel'  ),
        'buttons'        => array( __( 'Buttons',            'marsislav' ),    30, 'buttons',        'radius_buttons',        4,  'a.button,.button,button,input[type="submit"],input[type="button"]',         'marsislav_design_panel'  ),
        'inputs'         => array( __( 'Input Fields',       'marsislav' ),    40, 'inputs',         'radius_inputs',         4,  'input[type="text"],input[type="email"],input[type="search"],textarea',      'marsislav_design_panel'  ),
        'cards'          => array( __( 'Cards / Posts',      'marsislav' ),    20, 'cards',          'radius_cards',          8,  '.post,.card,.entry,article',                                                'marsislav_content_panel' ),
        'images'         => array( __( 'Images',             'marsislav' ),    30, 'images',         'radius_images',         0,  'img',                                                                       'marsislav_content_panel' ),
    );

    foreach ( $elements as $elem_key => $elem ) {
        list( $elem_label, $elem_priority, $bg_area, $radius_key, $radius_default, $css_sel, $elem_panel ) = $elem;

        $sec = 'marsislav_elem_' . $elem_key;

        $wp_customize->add_section( $sec, array(
            'title'    => $elem_label,
            'panel'    => $elem_panel,
            'priority' => $elem_priority,
        ) );

        // --- Background ---
        $wp_customize->add_setting( 'marsislav_heading_bg_' . $elem_key, array(
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => '__return_empty_string',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'marsislav_heading_bg_' . $elem_key, array(
            'label'    => '— ' . __( 'BACKGROUND', 'marsislav' ) . ' —',
            'section'  => $sec,
            'type'     => 'hidden',
            'priority' => 1,
        ) ) );
        marsislav_register_bg_controls( $wp_customize, $sec, $bg_area, 10 );

        // --- Border Radius ---
        $wp_customize->add_setting( 'marsislav_heading_radius_' . $elem_key, array(
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => '__return_empty_string',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'marsislav_heading_radius_' . $elem_key, array(
            'label'    => '— ' . __( 'BORDER RADIUS', 'marsislav' ) . ' —',
            'section'  => $sec,
            'type'     => 'hidden',
            'priority' => 200,
        ) ) );
        marsislav_register_radius_controls( $wp_customize, $sec, $radius_key, $radius_default, 210 );

        // Skip shadow & border for 'global' (body background doesn't meaningfully use box-shadow)
        if ( 'global' === $elem_key ) continue;

        // --- Box Shadow ---
        $wp_customize->add_setting( 'marsislav_heading_shadow_' . $elem_key, array(
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => '__return_empty_string',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'marsislav_heading_shadow_' . $elem_key, array(
            'label'    => '— ' . __( 'BOX SHADOW', 'marsislav' ) . ' —',
            'section'  => $sec,
            'type'     => 'hidden',
            'priority' => 310,
        ) ) );
        marsislav_register_shadow_controls( $wp_customize, $sec, $elem_key, 320 );

        // --- Border ---
        $wp_customize->add_setting( 'marsislav_heading_border_' . $elem_key, array(
            'default'           => '',
            'transport'         => 'postMessage',
            'sanitize_callback' => '__return_empty_string',
        ) );
        $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'marsislav_heading_border_' . $elem_key, array(
            'label'    => '— ' . __( 'BORDER', 'marsislav' ) . ' —',
            'section'  => $sec,
            'type'     => 'hidden',
            'priority' => 430,
        ) ) );
        marsislav_register_border_controls( $wp_customize, $sec, $elem_key, 440 );

        // --- Typography (text, links, headings) ---
        // Only for container elements that actually hold text content
        $typo_elements = array( 'header', 'content', 'sidebar', 'footer_widgets', 'footer', 'copyright', 'cards' );
        if ( in_array( $elem_key, $typo_elements, true ) ) {
            marsislav_register_typography_controls( $wp_customize, $sec, $elem_key, 550 );
        }
    }

    /* ── Page / Post Title visibility ───────────────────────────── */
    $wp_customize->add_section( 'marsislav_sec_page_title', array(
        'title'       => __( 'Page Title', 'marsislav' ),
        'panel'       => 'marsislav_design_panel',
        'priority'    => 55,
        'description' => __( 'Show or hide the H1 title on each content type.', 'marsislav' ),
    ) );

    foreach ( array(
        'show_title_page'     => __( 'Show title on Pages',        'marsislav' ),
        'show_title_post'     => __( 'Show title on Single Posts', 'marsislav' ),
        'show_title_archive'  => __( 'Show title on Archives',     'marsislav' ),
        'show_title_category' => __( 'Show title on Categories',   'marsislav' ),
        'show_title_home'     => __( 'Show title on Blog Home',    'marsislav' ),
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

    /* ── Breadcrumbs ─────────────────────────────────────────────── */
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
        'breadcrumbs_bg'               => array( __( 'Background',       'marsislav' ), '#f3f4f6' ),
        'breadcrumbs_text_color'       => array( __( 'Text Color',       'marsislav' ), '#6b7280' ),
        'breadcrumbs_link_color'       => array( __( 'Link Color',       'marsislav' ), '#2563eb' ),
        'breadcrumbs_link_hover_color' => array( __( 'Link Hover Color', 'marsislav' ), '#1d4ed8' ),
    ) as $key => $info ) {
        $wp_customize->add_setting( $key, array( 'default' => $info[1], 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color' ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 'label' => $info[0], 'section' => 'marsislav_sec_breadcrumbs', 'priority' => $p++ ) ) );
    }

    /* ── Scroll to Top Button ────────────────────────────────────── */
    $wp_customize->add_section( 'marsislav_sec_scroll_top', array(
        'title'    => __( 'Scroll-to-Top Button', 'marsislav' ),
        'panel'    => 'marsislav_design_panel',
        'priority' => 140,
    ) );

    $wp_customize->add_setting( 'scroll_to_top_enable', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'scroll_to_top_enable', array(
        'label'   => __( 'Show "Scroll to Top" Button', 'marsislav' ),
        'section' => 'marsislav_sec_scroll_top',
        'type'    => 'checkbox',
    ) );

    $p = 20;
    foreach ( array(
        'scroll_to_top_bg'       => array( __( 'Background',        'marsislav' ), '#2563eb' ),
        'scroll_to_top_color'    => array( __( 'Icon Color',        'marsislav' ), '#ffffff' ),
        'scroll_to_top_bg_hover' => array( __( 'Background — Hover','marsislav' ), '#1d4ed8' ),
    ) as $key => $info ) {
        $wp_customize->add_setting( $key, array( 'default' => $info[1], 'transport' => 'postMessage', 'sanitize_callback' => 'sanitize_hex_color' ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 'label' => $info[0], 'section' => 'marsislav_sec_scroll_top', 'priority' => $p++ ) ) );
    }

    /* ── Dark Mode Toggle ────────────────────────────────────────── */
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
        'label'   => __( 'Show Dark Mode Toggle Button', 'marsislav' ),
        'section' => 'marsislav_sec_dark_mode',
        'type'    => 'checkbox',
    ) );

}
add_action( 'customize_register', 'marsislav_colors_customizer' );


/* ============================================================
 * Dynamic CSS helpers
 * ============================================================ */

function marsislav_get_bg_css( $area ) {
    $type    = (string) get_theme_mod( 'bg_' . $area . '_type',     'solid' );
    $color   = (string) get_theme_mod( 'bg_' . $area . '_color',    '' );
    $grad1   = (string) get_theme_mod( 'bg_' . $area . '_grad1',    '#ffffff' );
    $grad2   = (string) get_theme_mod( 'bg_' . $area . '_grad2',    '#eeeeee' );
    $dir     = (string) get_theme_mod( 'bg_' . $area . '_grad_dir', 'to bottom' );
    $img_url_raw = esc_url( (string) get_theme_mod( 'bg_' . $area . '_image', '' ) );
    $repeat  = (string) get_theme_mod( 'bg_' . $area . '_repeat',   'no-repeat' );
    $size    = (string) get_theme_mod( 'bg_' . $area . '_size',     'cover' );

    $imp = ( 'global' !== $area ) ? ' !important' : '';

    if ( 'solid' === $type && $color ) {
        $hex = sanitize_hex_color( $color );
        return 'background:' . $hex . $imp . ';';
    }

    if ( 'gradient' === $type ) {
        return 'background:linear-gradient(' . esc_attr( $dir ) . ',' . sanitize_hex_color( $grad1 ) . ',' . sanitize_hex_color( $grad2 ) . ')' . $imp . ';';
    }

    if ( 'image' === $type && $img_url_raw ) {
        if ( true ) {
            $out  = 'background-image:url(' . $img_url_raw . ')' . $imp . ';';
            $out .= 'background-repeat:' . esc_attr( $repeat ) . $imp . ';';
            $out .= 'background-size:' . esc_attr( $size ) . $imp . ';';
            $out .= 'background-position:center center' . $imp . ';';
            return $out;
        }
    }

    return '';
}

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

function marsislav_get_shadow_css( $key ) {
    $type = (string) get_theme_mod( $key . '_shadow_type', 'none' );
    if ( 'none' === $type ) return '';

    $color   = (string) get_theme_mod( $key . '_shadow_color',   '#000000' );
    $opacity = marsislav_sanitize_opacity( get_theme_mod( $key . '_shadow_opacity', 20 ) );
    $x       = (int) get_theme_mod( $key . '_shadow_x',     0 );
    $y       = (int) get_theme_mod( $key . '_shadow_y',     4 );
    $blur    = (int) get_theme_mod( $key . '_shadow_blur',  8 );
    $spread  = (int) get_theme_mod( $key . '_shadow_spread', 0 );

    $hex = sanitize_hex_color( $color );
    if ( ! $hex ) $hex = '#000000';
    list( $r, $g, $b ) = array_map( 'hexdec', str_split( ltrim( $hex, '#' ), 2 ) );
    $rgba = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . round( $opacity / 100, 2 ) . ')';

    $inset = ( 'inset' === $type ) ? 'inset ' : '';
    return 'box-shadow:' . $inset . $x . 'px ' . $y . 'px ' . $blur . 'px ' . $spread . 'px ' . $rgba . ' !important;';
}

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


/* ============================================================
 * Dynamic CSS output (wp_head)
 * ============================================================ */

function marsislav_dynamic_css() {
    $css = '';

    // Sticky header
    if ( ! (bool) get_theme_mod( 'header_sticky', true ) ) {
        $css .= 'body #masthead{position:relative !important;top:auto !important;}';
    }

    // Element map: elem_key => [ bg_area, radius_key, radius_default, css_selector ]
    $element_map = array(
        'global'         => array( 'global',         'radius_global',         0,  'body'                                                                    ),
        'header'         => array( 'header',         'radius_header',         0,  'body #masthead'                                                           ),
        'content'        => array( 'content',        'radius_content',        0,  'body #primary,body #content'                                              ),
        'sidebar'        => array( 'sidebar',        'radius_sidebar',        0,  'body #secondary'                                                          ),
        'footer_widgets' => array( 'footer_widgets', 'radius_footer_widgets', 0,  'body #footer-sidebar-area'                                                ),
        'footer'         => array( 'footer',         'radius_footer',         0,  'body #colophon'                                                           ),
        'copyright'      => array( 'copyright',      'radius_copyright',      0,  'body #colophon .site-info'                                                ),
        'buttons'        => array( 'buttons',        'radius_buttons',        4,  'a.button,.button,button,input[type="submit"],input[type="button"]'         ),
        'inputs'         => array( 'inputs',         'radius_inputs',         4,  'input[type="text"],input[type="email"],input[type="search"],textarea'      ),
        'cards'          => array( 'cards',          'radius_cards',          8,  '.post,.card,.entry,article'                                               ),
        'images'         => array( 'images',         'radius_images',         0,  'img'                                                                      ),
    );

    foreach ( $element_map as $elem_key => $data ) {
        list( $bg_area, $radius_key, $radius_default, $selector ) = $data;

        $parts = '';

        // Background
        $bg = marsislav_get_bg_css( $bg_area );
        if ( $bg ) $parts .= $bg;

        // Radius
        $radius = marsislav_get_radius_css( $radius_key, $radius_default );
        if ( $radius ) $parts .= $radius;

        if ( 'global' !== $elem_key ) {
            // Shadow
            $shadow = marsislav_get_shadow_css( $elem_key );
            if ( $shadow ) $parts .= $shadow;

            // Border
            $border = marsislav_get_border_css( $elem_key );
            if ( $border ) $parts .= $border;
        }

        if ( $parts ) {
            $css .= $selector . '{' . $parts . '}';
        }
    }

    // Text & link colors
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

    // Per-element typography colors
    $typo_elements = array(
        'header'         => 'body #masthead',
        'content'        => 'body #primary,body #content',
        'sidebar'        => 'body #secondary',
        'footer_widgets' => 'body #footer-sidebar-area',
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

    // Breadcrumbs
    $bc_bg    = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_bg',               '#f3f4f6' ) );
    $bc_text  = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_text_color',       '#6b7280' ) );
    $bc_link  = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_link_color',       '#2563eb' ) );
    $bc_hover = sanitize_hex_color( (string) get_theme_mod( 'breadcrumbs_link_hover_color', '#1d4ed8' ) );
    if ( $bc_bg )    $css .= '.marsislav-breadcrumbs{background:' . $bc_bg . ' !important;}';
    if ( $bc_text )  $css .= '.marsislav-breadcrumbs,.bc-sep,.bc-current{color:' . $bc_text . ' !important;}';
    if ( $bc_link )  $css .= '.marsislav-breadcrumbs .bc-link{color:' . $bc_link . ' !important;}';
    if ( $bc_hover ) $css .= '.marsislav-breadcrumbs .bc-link:hover{color:' . $bc_hover . ' !important;}';

    // Scroll to top
    $stt_bg    = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_bg',       '#2563eb' ) );
    $stt_color = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_color',    '#ffffff' ) );
    $stt_hover = sanitize_hex_color( (string) get_theme_mod( 'scroll_to_top_bg_hover', '#1d4ed8' ) );
    if ( $stt_bg )    $css .= '#marsislav-scroll-top{background:' . $stt_bg . ' !important;}';
    if ( $stt_color ) $css .= '#marsislav-scroll-top{color:' . $stt_color . ' !important;}#marsislav-scroll-top svg{stroke:' . $stt_color . ' !important;}';
    if ( $stt_hover ) $css .= '#marsislav-scroll-top:hover{background:' . $stt_hover . ' !important;}';

    // Dark mode CSS variable



    if ( $css ) {
        echo '<style id="marsislav-dynamic-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
add_action( 'wp_head', 'marsislav_dynamic_css', 99 );


/* ============================================================
 * Customizer preview JS
 * ============================================================ */


/* ============================================================
 * Reset All — enqueue button script in Customizer controls
 * ============================================================ */

function marsislav_reset_enqueue() {
    wp_enqueue_script(
        'marsislav-customizer-reset',
        get_template_directory_uri() . '/js/customizer-reset.js',
        array( 'customize-controls', 'jquery' ),
        _S_VERSION,
        true
    );

    // Static defaults that can't easily be generated in JS
    // (dynamic ones like bg_*, radius_*, shadow_*, border_*, typo_* are built in the JS itself)
    $static_defaults = array(
        // General
        'header_sticky'           => true,
        // Breadcrumbs
        'breadcrumbs_enable'       => true,
        // Scroll-to-top
        'scroll_to_top_enable'     => true,
        // Dark mode
        'dark_mode_enable'         => true,

        // Footer
        'footer_layout'            => 'one-column',
        'footer_copyright_text'    => '',
        'footer_col2_text'         => '',
        'show_footer_menu'         => true,
        'show_footer_credits'      => true,
        'footer_sidebar_enable'    => true,
        'footer_sidebar_columns'   => '3',
        // Topbar
        'topbar_enable'            => false,
        'topbar_layout'            => 'one',
        'topbar_marquee'           => false,
        'topbar_marquee_speed'     => 18,
        'topbar_text'              => 'Welcome to our website',
        'topbar_text_color'        => '#ffffff',
        'topbar_bg_color'          => '#1f2937',
        'topbar_col1_text'         => '',
        'topbar_col2_text'         => '',
        // Sidebar
        'sidebar_blog'             => 'right',
        'sidebar_post'             => 'right',
        'sidebar_page'             => 'disabled',
        'sidebar_home'             => 'disabled',
        'sidebar_shop'             => 'right',
        'sidebar_product'          => 'disabled',
    );

    wp_localize_script( 'marsislav-customizer-reset', 'marsislavResetData', array(
        'staticDefaults' => $static_defaults,
    ) );
}
add_action( 'customize_controls_enqueue_scripts', 'marsislav_reset_enqueue' );

function marsislav_colors_preview_js() {
    wp_enqueue_script(
        'marsislav-customizer-colors',
        get_template_directory_uri() . '/js/customizer-colors.js',
        array( 'customize-preview', 'jquery' ),
        _S_VERSION,
        true
    );

    // Pre-seed all currently saved image URLs (now stored as URL strings directly)
    $areas    = array( 'global', 'header', 'content', 'sidebar', 'footer_widgets', 'footer', 'copyright', 'buttons', 'inputs', 'cards', 'images' );
    $img_urls = array();   // area => url
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
add_action( 'customize_preview_init', 'marsislav_colors_preview_js' );


/* ============================================================
 * AJAX — attachment URL
 * ============================================================ */