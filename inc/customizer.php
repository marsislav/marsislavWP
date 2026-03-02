<?php
/**
 * marsislav Theme Customizer
 *
 * @package marsislav
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function marsislav_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// =========================================================
	// Footer Section
	// =========================================================
	$wp_customize->add_section(
		'marsislav_footer_section',
		array(
			'title'    => esc_html__( 'Footer', 'marsislav' ),
			'priority' => 120,
		)
	);

	// Footer Layout (1 or 2 columns)
	$wp_customize->add_setting(
		'footer_layout',
		array(
			'default'           => 'one-column',
			'sanitize_callback' => 'marsislav_sanitize_footer_layout',
		)
	);
	$wp_customize->add_control(
		'footer_layout',
		array(
			'label'   => esc_html__( 'Footer Layout', 'marsislav' ),
			'section' => 'marsislav_footer_section',
			'type'    => 'radio',
			'choices' => array(
				'one-column' => esc_html__( 'Една колона', 'marsislav' ),
				'two-column' => esc_html__( 'Две колони', 'marsislav' ),
			),
		)
	);

	// Copyright Text (Column 1)
	$wp_customize->add_setting(
		'footer_copyright_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'footer_copyright_text',
		array(
			'label'       => esc_html__( 'Текст за Copyright (Колона 1)', 'marsislav' ),
			'description' => esc_html__( 'Можете да използвате HTML. Ако е празно, ще се покаже © Година Сайт.', 'marsislav' ),
			'section'     => 'marsislav_footer_section',
			'type'        => 'textarea',
		)
	);

	// Column 2 Text
	$wp_customize->add_setting(
		'footer_col2_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'footer_col2_text',
		array(
			'label'       => esc_html__( 'Текст за Колона 2', 'marsislav' ),
			'description' => esc_html__( 'Показва се само при избор на "Две колони". Поддържа HTML.', 'marsislav' ),
			'section'     => 'marsislav_footer_section',
			'type'        => 'textarea',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'marsislav_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'marsislav_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'marsislav_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function marsislav_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function marsislav_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function marsislav_customize_preview_js() {
	wp_enqueue_script( 'marsislav-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'marsislav_customize_preview_js' );

/**
 * Sanitize footer layout choice.
 */
function marsislav_sanitize_footer_layout( $value ) {
	$valid = array( 'one-column', 'two-column' );
	return in_array( $value, $valid, true ) ? $value : 'one-column';
}
