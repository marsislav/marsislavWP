<?php
/**
 * marsislav Theme Customizer — Main panels & footer settings
 *
 * @package marsislav
 */

/**
 * Register top-level Customizer panels and core transport settings.
 *
 * Panel map (priority order):
 *   25  Header      — General, Top Bar, Header Design
 *   35  Sidebar     — Sidebar Design, Position per context
 *   40  Main Content — Content, Cards, Images
 *   45  Footer      — Layout, Menu & Credits, Widgets, Designs
 *  140  Theme Design — Colors, Backgrounds, Buttons, Inputs, Utilities
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function marsislav_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// ── Panel: Header ─────────────────────────────────────────────────────
	$wp_customize->add_panel( 'marsislav_header_panel', array(
		'title'    => esc_html__( 'Header', 'marsislav' ),
		'priority' => 25,
	) );

	// ── Panel: Sidebar ────────────────────────────────────────────────────
	$wp_customize->add_panel( 'marsislav_sidebar_panel', array(
		'title'    => esc_html__( 'Sidebar', 'marsislav' ),
		'priority' => 35,
	) );

	// ── Panel: Main Content ───────────────────────────────────────────────
	$wp_customize->add_panel( 'marsislav_content_panel', array(
		'title'    => esc_html__( 'Main Content', 'marsislav' ),
		'priority' => 40,
	) );

	// ── Panel: Footer ─────────────────────────────────────────────────────
	$wp_customize->add_panel( 'marsislav_footer_panel', array(
		'title'    => esc_html__( 'Footer', 'marsislav' ),
		'priority' => 45,
	) );

	// ── Footer → Layout & Text ───────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_footer_section', array(
		'title'    => esc_html__( 'Layout & Text', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 10,
	) );

	$wp_customize->add_setting( 'footer_layout', array(
		'default'           => 'one-column',
		'sanitize_callback' => 'marsislav_sanitize_footer_layout',
	) );
	$wp_customize->add_control( 'footer_layout', array(
		'label'    => esc_html__( 'Copyright Bar Layout', 'marsislav' ),
		'section'  => 'marsislav_footer_section',
		'type'     => 'radio',
		'priority' => 10,
		'choices'  => array(
			'one-column' => esc_html__( 'One Column', 'marsislav' ),
			'two-column' => esc_html__( 'Two Columns', 'marsislav' ),
		),
	) );

	$wp_customize->add_setting( 'footer_copyright_text', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'footer_copyright_text', array(
		'label'       => esc_html__( 'Copyright Text (Column 1)', 'marsislav' ),
		'description' => esc_html__( 'HTML allowed. Leave empty to show © Year Site Name.', 'marsislav' ),
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
		'label'       => esc_html__( 'Column 2 Text', 'marsislav' ),
		'description' => esc_html__( 'Shown only when "Two Columns" is selected. HTML allowed.', 'marsislav' ),
		'section'     => 'marsislav_footer_section',
		'type'        => 'textarea',
		'priority'    => 30,
	) );

	// Selective refresh partials
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'marsislav_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'marsislav_customize_partial_blogdescription',
		) );
	}
}
add_action( 'customize_register', 'marsislav_customize_register' );

function marsislav_customize_partial_blogname() {
	bloginfo( 'name' );
}

function marsislav_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

function marsislav_customize_preview_js() {
	wp_enqueue_script( 'marsislav-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'marsislav_customize_preview_js' );

function marsislav_sanitize_footer_layout( $value ) {
	$valid = array( 'one-column', 'two-column' );
	return in_array( $value, $valid, true ) ? $value : 'one-column';
}