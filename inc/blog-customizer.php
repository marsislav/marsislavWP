<?php
/**
 * Blog & Header Customizer Options
 *
 * Adds:
 *  1. Blog panel → Blog Meta section  (show/hide category, author, date, comments)
 *  2. Header panel → Mobile Menu section (text color, icon color, background color)
 *  3. Header panel → Header Search section (show/hide search icon in menu)
 *
 * @package marsislav
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
 * 1. BLOG META OPTIONS
 * ============================================================ */

add_action( 'customize_register', 'marsislav_blog_customizer_register', 20 );

function marsislav_blog_customizer_register( $wp_customize ) {

	// ── Section: Blog Meta ─────────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_blog_meta_section', array(
		'title'    => esc_html__( 'Blog Meta', 'marsislav' ),
		'panel'    => 'marsislav_content_panel',
		'priority' => 50,
	) );

	// Show Category
	$wp_customize->add_setting( 'blog_show_category', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'blog_show_category', array(
		'label'   => esc_html__( 'Show Category', 'marsislav' ),
		'section' => 'marsislav_blog_meta_section',
		'type'    => 'checkbox',
	) );

	// Show Author
	$wp_customize->add_setting( 'blog_show_author', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'blog_show_author', array(
		'label'   => esc_html__( 'Show Author', 'marsislav' ),
		'section' => 'marsislav_blog_meta_section',
		'type'    => 'checkbox',
	) );

	// Show Date
	$wp_customize->add_setting( 'blog_show_date', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'blog_show_date', array(
		'label'   => esc_html__( 'Show Publish Date', 'marsislav' ),
		'section' => 'marsislav_blog_meta_section',
		'type'    => 'checkbox',
	) );

	// Show Comments
	$wp_customize->add_setting( 'blog_show_comments', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'blog_show_comments', array(
		'label'   => esc_html__( 'Show Comments Link', 'marsislav' ),
		'section' => 'marsislav_blog_meta_section',
		'type'    => 'checkbox',
	) );

	// ── Section: Mobile Menu Colors ────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_mobile_menu_section', array(
		'title'    => esc_html__( 'Mobile Menu Colors', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 60,
	) );

	// Mobile menu background color
	$wp_customize->add_setting( 'mobile_menu_bg_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_menu_bg_color', array(
		'label'   => esc_html__( 'Menu Background Color', 'marsislav' ),
		'section' => 'marsislav_mobile_menu_section',
	) ) );

	// Mobile menu text color
	$wp_customize->add_setting( 'mobile_menu_text_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_menu_text_color', array(
		'label'   => esc_html__( 'Menu Text Color', 'marsislav' ),
		'section' => 'marsislav_mobile_menu_section',
	) ) );

	// Hamburger / close icon color
	$wp_customize->add_setting( 'mobile_menu_icon_color', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_menu_icon_color', array(
		'label'       => esc_html__( 'Hamburger Icon & Text Color', 'marsislav' ),
		'description' => esc_html__( 'Color of the hamburger button lines and "Menu" label.', 'marsislav' ),
		'section'     => 'marsislav_mobile_menu_section',
	) ) );

	// ── Section: Header Search ─────────────────────────────────────────────
	$wp_customize->add_section( 'marsislav_header_search_section', array(
		'title'    => esc_html__( 'Header Search', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 70,
	) );

	// Show search icon in header/menu
	$wp_customize->add_setting( 'header_show_search', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'header_show_search', array(
		'label'       => esc_html__( 'Show Search Icon in Menu', 'marsislav' ),
		'description' => esc_html__( 'Displays a search icon next to the navigation menu. Click opens a search overlay.', 'marsislav' ),
		'section'     => 'marsislav_header_search_section',
		'type'        => 'checkbox',
	) );
}

/* ============================================================
 * 2. SANITIZE CHECKBOX HELPER
 * ============================================================ */

if ( ! function_exists( 'marsislav_sanitize_checkbox' ) ) {
	function marsislav_sanitize_checkbox( $checked ) {
		return ( isset( $checked ) && true == $checked ) ? true : false;
	}
}

/* ============================================================
 * 3. OUTPUT MOBILE MENU COLOR CSS
 * ============================================================ */

add_action( 'wp_head', 'marsislav_mobile_menu_colors_css' );

function marsislav_mobile_menu_colors_css() {
	$bg   = get_theme_mod( 'mobile_menu_bg_color', '' );
	$text = get_theme_mod( 'mobile_menu_text_color', '' );
	$icon = get_theme_mod( 'mobile_menu_icon_color', '' );

	if ( ! $bg && ! $text && ! $icon ) return;

	$css = '<style id="marsislav-mobile-menu-colors">';
	$css .= '@media (max-width: 899px) {';

	if ( $bg ) {
		$css .= '#site-navigation .primary-menu.mobile-open,
		         #site-navigation .nav-menu.mobile-open {
		             background-color: ' . esc_attr( $bg ) . ';
		         }';
	}

	if ( $text ) {
		$css .= '#site-navigation .primary-menu.mobile-open a,
		         #site-navigation .nav-menu.mobile-open a {
		             color: ' . esc_attr( $text ) . ';
		         }';
	}

	if ( $icon ) {
		$css .= '.menu-toggle,
		         .menu-toggle .menu-text {
		             color: ' . esc_attr( $icon ) . ';
		         }
		         .hamburger-lines span {
		             background-color: ' . esc_attr( $icon ) . ';
		         }';
	}

	$css .= '}';
	$css .= '</style>';

	echo $css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/* ============================================================
 * 4. LIVE PREVIEW (postMessage) for mobile menu colors
 * ============================================================ */

add_action( 'customize_preview_init', 'marsislav_blog_customizer_preview_js' );

function marsislav_blog_customizer_preview_js() {
	wp_enqueue_script(
		'marsislav-blog-customizer-preview',
		get_template_directory_uri() . '/js/blog-customizer-preview.js',
		array( 'customize-preview' ),
		filemtime( get_template_directory() . '/js/blog-customizer-preview.js' ),
		true
	);
}
