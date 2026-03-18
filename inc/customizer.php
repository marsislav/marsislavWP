<?php
/**
 * marsislav Theme Customizer
 *
 * Registers all top-level panels, their sections, settings, and controls.
 * Also handles sanitize callbacks and preview JS for panels defined here.
 *
 * Panel map (priority order):
 *   25   Header       — General, Top Bar, Mobile Menu Colors, Header Search
 *   35   Sidebar      — Sidebar Design (via colors-customizer.php), Position per context
 *   40   Main Content — Content, Cards, Images, Blog Meta (via blog-customizer.php)
 *   45   Footer       — Layout & Text, Menu & Credits, Widget Areas
 *  140   Theme Design — Colors, Backgrounds, Buttons, Inputs, Utilities (via colors-customizer.php)
 *
 * @package marsislav
 */

if ( ! defined( 'ABSPATH' ) ) exit;


// =============================================================================
// 1. PANELS, CORE TRANSPORT, AND SELECTIVE REFRESH
// =============================================================================

/**
 * Register top-level Customizer panels and configure core settings transport.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function marsislav_customize_register( $wp_customize ) {

	// Core settings transport.
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// ── Panel: Header ─────────────────────────────────────────────────────────
	$wp_customize->add_panel( 'marsislav_header_panel', array(
		'title'    => esc_html__( 'Header', 'marsislav' ),
		'priority' => 25,
	) );

	// ── Panel: Sidebar ────────────────────────────────────────────────────────
	$wp_customize->add_panel( 'marsislav_sidebar_panel', array(
		'title'    => esc_html__( 'Sidebar', 'marsislav' ),
		'priority' => 35,
	) );

	// ── Panel: Main Content ───────────────────────────────────────────────────
	$wp_customize->add_panel( 'marsislav_content_panel', array(
		'title'    => esc_html__( 'Main Content', 'marsislav' ),
		'priority' => 40,
	) );

	// ── Panel: Footer ─────────────────────────────────────────────────────────
	$wp_customize->add_panel( 'marsislav_footer_panel', array(
		'title'    => esc_html__( 'Footer', 'marsislav' ),
		'priority' => 45,
	) );

	// ── Footer → Section: Layout & Text ──────────────────────────────────────
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
			'one-column' => esc_html__( 'One Column',  'marsislav' ),
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

	// ── Selective refresh partials ────────────────────────────────────────────
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

/** Selective-refresh callback: site title. */
function marsislav_customize_partial_blogname() {
	bloginfo( 'name' );
}

/** Selective-refresh callback: site description. */
function marsislav_customize_partial_blogdescription() {
	bloginfo( 'description' );
}


// =============================================================================
// 2. HEADER PANEL — TOP BAR
// =============================================================================

/**
 * Register the Top Bar section inside the Header panel.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function marsislav_topbar_customizer( $wp_customize ) {

	// ── Section: Top Bar (priority 10 inside Header panel) ───────────────────
	$wp_customize->add_section( 'marsislav_topbar_section', array(
		'title'    => esc_html__( 'Top Bar', 'marsislav' ),
		'panel'    => 'marsislav_header_panel',
		'priority' => 10,
	) );

	// Enable / Disable.
	$wp_customize->add_setting( 'topbar_enable', array(
		'default'           => false,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'topbar_enable', array(
		'label'   => esc_html__( 'Enable Top Bar', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
		'type'    => 'checkbox',
	) );

	// Layout (1 or 2 columns).
	$wp_customize->add_setting( 'topbar_layout', array(
		'default'           => 'one',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_topbar_layout',
	) );
	$wp_customize->add_control( 'topbar_layout', array(
		'label'   => esc_html__( 'Layout', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
		'type'    => 'radio',
		'choices' => array(
			'one' => esc_html__( '1 Column',  'marsislav' ),
			'two' => esc_html__( '2 Columns', 'marsislav' ),
		),
	) );

	// Marquee enable.
	$wp_customize->add_setting( 'topbar_marquee', array(
		'default'           => false,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_validate_boolean',
	) );
	$wp_customize->add_control( 'topbar_marquee', array(
		'label'   => esc_html__( 'Enable Marquee Text', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
		'type'    => 'checkbox',
	) );

	// Marquee speed.
	$wp_customize->add_setting( 'topbar_marquee_speed', array(
		'default'           => 18,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'topbar_marquee_speed', array(
		'label'       => esc_html__( 'Marquee Speed (seconds)', 'marsislav' ),
		'description' => esc_html__( 'Lower = faster. Recommended: 8–30s.', 'marsislav' ),
		'section'     => 'marsislav_topbar_section',
		'type'        => 'range',
		'input_attrs' => array( 'min' => 3, 'max' => 60, 'step' => 1 ),
	) );

	// Single-column text.
	$wp_customize->add_setting( 'topbar_text', array(
		'default'           => esc_html__( 'Welcome to our website', 'marsislav' ),
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'topbar_text', array(
		'label'   => esc_html__( 'Top Bar Text', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
		'type'    => 'text',
	) );

	// Background color.
	$wp_customize->add_setting( 'topbar_bg_color', array(
		'default'           => '#1f2937',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_bg_color', array(
		'label'   => esc_html__( 'Background Color', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
	) ) );

	// Text color.
	$wp_customize->add_setting( 'topbar_text_color', array(
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'topbar_text_color', array(
		'label'   => esc_html__( 'Text Color', 'marsislav' ),
		'section' => 'marsislav_topbar_section',
	) ) );

	// Column 1 text (left, 2-column mode).
	$wp_customize->add_setting( 'topbar_col1_text', array(
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'topbar_col1_text', array(
		'label'       => esc_html__( 'Column 1 Text (left)', 'marsislav' ),
		'description' => esc_html__( 'Shown in left column when 2 Columns is selected. Supports HTML.', 'marsislav' ),
		'section'     => 'marsislav_topbar_section',
		'type'        => 'textarea',
	) );

	// Column 2 text (right, 2-column mode).
	$wp_customize->add_setting( 'topbar_col2_text', array(
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'topbar_col2_text', array(
		'label'       => esc_html__( 'Column 2 Text (right)', 'marsislav' ),
		'description' => esc_html__( 'Shown in right column when 2 Columns is selected. Supports HTML.', 'marsislav' ),
		'section'     => 'marsislav_topbar_section',
		'type'        => 'textarea',
	) );
}
add_action( 'customize_register', 'marsislav_topbar_customizer' );

/**
 * Output inline CSS for Top Bar colors on the front end and in the Customizer preview.
 */
function marsislav_topbar_inline_css() {
	$bg    = get_theme_mod( 'topbar_bg_color',      '#1f2937' );
	$color = get_theme_mod( 'topbar_text_color',    '#ffffff' );
	$speed = max( 3, min( 60, absint( get_theme_mod( 'topbar_marquee_speed', 18 ) ) ) );
	?>
	<style id="marsislav-topbar-colors">
		#site-topbar {
			background-color: <?php echo esc_attr( $bg ); ?>;
			color: <?php echo esc_attr( $color ); ?>;
		}
		#site-topbar a {
			color: <?php echo esc_attr( $color ); ?>;
		}
		#site-topbar .topbar-marquee span {
			color: <?php echo esc_attr( $color ); ?>;
			animation-duration: <?php echo esc_attr( $speed ); ?>s;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'marsislav_topbar_inline_css' );

/**
 * Enqueue Top Bar Customizer preview JS.
 */
function marsislav_topbar_preview_js() {
	wp_enqueue_script(
		'marsislav-customizer-topbar',
		get_template_directory_uri() . '/js/customizer-topbar.js',
		array( 'customize-preview', 'jquery' ),
		_S_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'marsislav_topbar_preview_js' );


// =============================================================================
// 3. SIDEBAR PANEL — POSITION SETTINGS
// =============================================================================

/**
 * Register per-context sidebar position/widget-area sections inside the Sidebar panel.
 *
 * Panel structure:
 *   Sidebar (panel)
 *   ├── Sidebar Design          ← priority 10  (added via colors-customizer.php)
 *   ├── Position: Blog          ← priority 30
 *   ├── Position: Single Post   ← priority 40
 *   ├── Position: Page          ← priority 50
 *   ├── Position: Home Page     ← priority 60
 *   ├── Position: Shop          ← priority 70
 *   └── Position: Product Page  ← priority 80
 *
 * Each section contains two controls:
 *   • Position    (Left | Right | Disabled)
 *   • Widget Area (which sidebar widget area to render)
 *
 * @param WP_Customize_Manager $wp_customize
 */
function marsislav_sidebar_settings( $wp_customize ) {

	$position_choices = array(
		'right'    => esc_html__( 'Right',               'marsislav' ),
		'left'     => esc_html__( 'Left',                'marsislav' ),
		'disabled' => esc_html__( 'Disabled (Full Width)', 'marsislav' ),
	);

	$sidebar_widget_choices = marsislav_sidebar_choices_for( '' );

	// Context definitions: key => [ section title, position default, sidebar-id default, priority ]
	$contexts = array(
		'blog'    => array( esc_html__( 'Position: Blog / Archive',    'marsislav' ), 'right',    'sidebar-blog',    30 ),
		'post'    => array( esc_html__( 'Position: Single Post',        'marsislav' ), 'right',    'sidebar-post',    40 ),
		'page'    => array( esc_html__( 'Position: Page',               'marsislav' ), 'disabled', 'sidebar-page',    50 ),
		'home'    => array( esc_html__( 'Position: Home Page',          'marsislav' ), 'disabled', 'sidebar-blog',    60 ),
		'shop'    => array( esc_html__( 'Position: Shop (WooCommerce)', 'marsislav' ), 'right',    'sidebar-shop',    70 ),
		'product' => array( esc_html__( 'Position: Product Page',       'marsislav' ), 'disabled', 'sidebar-product', 80 ),
	);

	foreach ( $contexts as $ctx => $config ) {
		list( $title, $pos_default, $id_default, $priority ) = $config;

		$section_id  = 'marsislav_sidebar_section_' . $ctx;
		$pos_key     = 'sidebar_pos_' . $ctx;
		$sidebar_key = 'sidebar_id_'  . $ctx;

		// Section.
		$wp_customize->add_section( $section_id, array(
			'title'    => $title,
			'panel'    => 'marsislav_sidebar_panel',
			'priority' => $priority,
		) );

		// Position control.
		$wp_customize->add_setting( $pos_key, array(
			'default'           => $pos_default,
			'transport'         => 'postMessage',
			'sanitize_callback' => 'marsislav_sanitize_sidebar_position',
		) );
		$wp_customize->add_control( $pos_key, array(
			'label'    => esc_html__( 'Position', 'marsislav' ),
			'section'  => $section_id,
			'type'     => 'select',
			'choices'  => $position_choices,
			'priority' => 10,
		) );

		// Widget Area control.
		$wp_customize->add_setting( $sidebar_key, array(
			'default'           => $id_default,
			'transport'         => 'refresh',
			'sanitize_callback' => 'marsislav_sanitize_sidebar_id',
		) );
		$wp_customize->add_control( $sidebar_key, array(
			'label'    => esc_html__( 'Widget Area', 'marsislav' ),
			'section'  => $section_id,
			'type'     => 'select',
			'choices'  => $sidebar_widget_choices,
			'priority' => 20,
		) );
	}
}
add_action( 'customize_register', 'marsislav_sidebar_settings' );


// =============================================================================
// 4. FOOTER PANEL — MENU & CREDITS + WIDGET AREAS
// =============================================================================

/**
 * Register the Menu & Credits section inside the Footer panel.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function marsislav_footer_customizer( $wp_customize ) {

	// ── Section: Menu & Credits (priority 20 inside Footer panel) ────────────
	$wp_customize->add_section( 'marsislav_footer_menu_section', array(
		'title'    => esc_html__( 'Menu & Credits', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 20,
	) );

	// Show footer menu.
	$wp_customize->add_setting( 'show_footer_menu', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'show_footer_menu', array(
		'label'    => esc_html__( 'Show Footer Menu', 'marsislav' ),
		'section'  => 'marsislav_footer_menu_section',
		'type'     => 'checkbox',
		'priority' => 10,
	) );

	// Powered-by text (left side).
	$wp_customize->add_setting( 'footer_powered_text', array(
		'default'           => esc_html__( 'Proudly powered by %s', 'marsislav' ),
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'footer_powered_text', array(
		'label'       => esc_html__( 'Powered by text (left part)', 'marsislav' ),
		'description' => esc_html__( 'You can use %s for the CMS name. Example: Proudly powered by %s', 'marsislav' ),
		'section'     => 'marsislav_footer_menu_section',
		'type'        => 'textarea',
		'priority'    => 20,
		'input_attrs' => array( 'rows' => 3 ),
	) );

	// Theme credits text (right side).
	$wp_customize->add_setting( 'footer_credits_text', array(
		'default'           => esc_html__( 'Theme: %1$s by %2$s.', 'marsislav' ),
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'footer_credits_text', array(
		'label'       => esc_html__( 'Theme credits (right part)', 'marsislav' ),
		'description' => esc_html__( 'You can use %1$s for the theme name and %2$s for the author/link. Example: Theme: %1$s by %2$s.', 'marsislav' ),
		'section'     => 'marsislav_footer_menu_section',
		'type'        => 'textarea',
		'priority'    => 30,
		'input_attrs' => array( 'rows' => 3 ),
	) );

	// Show footer credits toggle.
	$wp_customize->add_setting( 'show_footer_credits', array(
		'default'           => true,
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'show_footer_credits', array(
		'label'    => esc_html__( 'Show Footer Credits', 'marsislav' ),
		'section'  => 'marsislav_footer_menu_section',
		'type'     => 'checkbox',
		'priority' => 40,
	) );
}
add_action( 'customize_register', 'marsislav_footer_customizer' );

/**
 * Register the Widget Areas section inside the Footer panel.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function marsislav_footer_sidebar_customizer( $wp_customize ) {

	// ── Section: Widget Areas (priority 30 inside Footer panel) ──────────────
	$wp_customize->add_section( 'marsislav_footer_widgets_section', array(
		'title'    => esc_html__( 'Widget Areas', 'marsislav' ),
		'panel'    => 'marsislav_footer_panel',
		'priority' => 30,
	) );

	// Enable / disable footer widget areas.
	$wp_customize->add_setting( 'footer_sidebar_enable', array(
		'default'           => true,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'footer_sidebar_enable', array(
		'label'    => esc_html__( 'Show Footer Widget Areas', 'marsislav' ),
		'section'  => 'marsislav_footer_widgets_section',
		'type'     => 'checkbox',
		'priority' => 5,
	) );

	// Number of columns.
	$wp_customize->add_setting( 'footer_sidebar_columns', array(
		'default'           => '3',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'marsislav_sanitize_footer_columns',
	) );
	$wp_customize->add_control( 'footer_sidebar_columns', array(
		'label'    => esc_html__( 'Number of Columns', 'marsislav' ),
		'section'  => 'marsislav_footer_widgets_section',
		'type'     => 'select',
		'choices'  => array(
			'1' => esc_html__( '1 Column',  'marsislav' ),
			'2' => esc_html__( '2 Columns', 'marsislav' ),
			'3' => esc_html__( '3 Columns', 'marsislav' ),
			'4' => esc_html__( '4 Columns', 'marsislav' ),
		),
		'priority' => 6,
	) );
}
add_action( 'customize_register', 'marsislav_footer_sidebar_customizer' );

/**
 * Enqueue footer widget-areas Customizer preview JS.
 */
function marsislav_footer_sidebar_preview_js() {
	wp_enqueue_script(
		'marsislav-customizer-footer-sidebar',
		get_template_directory_uri() . '/js/customizer-footer-sidebar.js',
		array( 'customize-preview', 'jquery' ),
		_S_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'marsislav_footer_sidebar_preview_js' );


// =============================================================================
// 5. CUSTOMIZER PREVIEW JS (core / general)
// =============================================================================

/**
 * Enqueue the core Customizer preview JS (blogname, blogdescription, etc.).
 */
function marsislav_customize_preview_js() {
	wp_enqueue_script(
		'marsislav-customizer',
		get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ),
		_S_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'marsislav_customize_preview_js' );


// =============================================================================
// 6. SANITIZE CALLBACKS
// =============================================================================

/**
 * Sanitize a checkbox / boolean value.
 *
 * @param  mixed $val
 * @return bool
 */
function marsislav_sanitize_checkbox( $val ) {
	return (bool) $val;
}

/**
 * Sanitize the footer copyright-bar layout choice.
 *
 * @param  string $value
 * @return string
 */
function marsislav_sanitize_footer_layout( $value ) {
	$valid = array( 'one-column', 'two-column' );
	return in_array( $value, $valid, true ) ? $value : 'one-column';
}

/**
 * Sanitize the top bar layout choice (one | two).
 *
 * @param  string $val
 * @return string
 */
function marsislav_sanitize_topbar_layout( $val ) {
	return in_array( $val, array( 'one', 'two' ), true ) ? $val : 'one';
}

/**
 * Sanitize the footer sidebar column count (1–4).
 *
 * @param  mixed $val
 * @return string
 */
function marsislav_sanitize_footer_columns( $val ) {
	return in_array( (string) $val, array( '1', '2', '3', '4' ), true ) ? $val : '3';
}

/**
 * Sanitize a sidebar position value (left | right | disabled).
 *
 * @param  string $val
 * @return string
 */
function marsislav_sanitize_sidebar_position( $val ) {
	return in_array( $val, array( 'left', 'right', 'disabled' ), true ) ? $val : 'right';
}

/**
 * Sanitize a sidebar widget-area ID.
 *
 * @param  string $val
 * @return string
 */
function marsislav_sanitize_sidebar_id( $val ) {
	$valid = array( 'disabled', 'sidebar-blog', 'sidebar-post', 'sidebar-page', 'sidebar-shop', 'sidebar-product' );
	return in_array( $val, $valid, true ) ? $val : 'sidebar-blog';
}
