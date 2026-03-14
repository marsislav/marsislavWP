<?php
/**
 * marsislav functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package marsislav
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function marsislav_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on marsislav, use a find and replace
		* to change 'marsislav' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'marsislav', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1'      => esc_html__( 'Primary', 'marsislav' ),
			'footer-menu' => esc_html__( 'Footer Menu', 'marsislav' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'marsislav_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Block editor support.
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	// Gutenberg layout settings (content width + wide width).
	add_theme_support( 'editor-content-width', 760 );

	// Editor styling so the block editor matches the front end.
	add_editor_style( 'style.css' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'marsislav_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function marsislav_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'marsislav_content_width', 1400 );
}
add_action( 'after_setup_theme', 'marsislav_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function marsislav_widgets_init() {
	$sidebars = array(
		'sidebar-blog'    => __( 'Sidebar – Blog / Archive', 'marsislav' ),
		'sidebar-post'    => __( 'Sidebar – Single Post', 'marsislav' ),
		'sidebar-page'    => __( 'Sidebar – Page', 'marsislav' ),
		'sidebar-shop'    => __( 'Sidebar – Shop (WooCommerce)', 'marsislav' ),
		'sidebar-product' => __( 'Sidebar – Product Page', 'marsislav' ),
	);

	foreach ( $sidebars as $id => $name ) {
		register_sidebar( array(
			'name'          => $name,
			'id'            => $id,
			'description'   => sprintf( __( 'Widgets for: %s', 'marsislav' ), $name ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	}
}
add_action( 'widgets_init', 'marsislav_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function marsislav_scripts() {
	wp_enqueue_style( 'marsislav-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'marsislav-style', 'rtl', 'replace' );

	wp_enqueue_script( 'marsislav-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	// Scroll to top button (only if enabled)
	if ( (bool) get_theme_mod( 'scroll_to_top_enable', true ) ) {
		wp_enqueue_script( 'marsislav-scroll-top', get_template_directory_uri() . '/js/scroll-to-top.js', array(), _S_VERSION, true );
	}

	// Dark Mode — loaded in <head> to prevent flash
	if ( (bool) get_theme_mod( 'dark_mode_enable', true ) ) {
		wp_enqueue_script( 'marsislav-dark-mode', get_template_directory_uri() . '/js/dark-mode.js', array(), _S_VERSION, false );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Header Search Overlay
	if ( (bool) get_theme_mod( 'header_show_search', true ) ) {
		wp_enqueue_script( 'marsislav-search-overlay', get_template_directory_uri() . '/js/search-overlay.js', array(), _S_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'marsislav_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/breadcrumbs.php';
if ( class_exists( 'WooCommerce' ) ) {
    require get_template_directory() . '/inc/woocommerce.php';
}
require get_template_directory() . '/inc/colors-customizer.php';
require get_template_directory() . '/inc/blog-customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
function marsislav_header_scripts() {
    // Scroll class added to header — navigation.js handles mobile menu toggle
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var header = document.querySelector('.site-header');
        if ( ! header ) return;
        window.addEventListener('scroll', function() {
            header.classList.toggle('scrolled', window.pageYOffset > 80);
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'marsislav_header_scripts');

// Menu registration is handled in marsislav_setup() above

// Customizer – footer menu & credits toggle
function marsislav_footer_customizer( $wp_customize ) {
    // ── Section: Menu & Credits (inside Footer panel) ───────────────────
    $wp_customize->add_section( 'marsislav_footer_menu_section', array(
        'title'    => esc_html__( 'Menu & Credits', 'marsislav' ),
        'panel'    => 'marsislav_footer_panel',
        'priority' => 20,
    ) );

    // Show footer menu
    $wp_customize->add_setting( 'show_footer_menu', array(
        'default'           => true,
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
    ) );
    $wp_customize->add_control( 'show_footer_menu', array(
        'label'   => esc_html__( 'Show Footer Menu', 'marsislav' ),
        'section' => 'marsislav_footer_menu_section',
        'type'    => 'checkbox',
        'priority' => 10,
    ) );
}
add_action( 'customize_register', 'marsislav_footer_customizer' );

function marsislav_footer_credits_customizer( $wp_customize ) {

    // Field 1: Powered by / left side
    $wp_customize->add_setting( 'footer_powered_text', array(
        'default'           => esc_html__( 'Proudly powered by %s', 'marsislav' ),
        'sanitize_callback' => 'wp_kses_post',          // allows HTML (links, strong, etc.)
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'footer_powered_text', array(
        'label'       => esc_html__( 'Powered by text (left part)', 'marsislav' ),
        'description' => esc_html__( 'You can use %s for the CMS name. Example: Proudly powered by %s', 'marsislav' ),
        'section'     => 'marsislav_footer_menu_section',
        'type'        => 'textarea',
        'priority'    => 20,
        'input_attrs' => array(
            'rows' => 3,
        ),
    ) );

    // Field 2: Theme credits / right side
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
        'input_attrs' => array(
            'rows' => 3,
        ),
    ) );

    // Option to toggle footer credits
    $wp_customize->add_setting( 'show_footer_credits', array(
        'default'           => true,
        'sanitize_callback' => 'marsislav_sanitize_checkbox',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'show_footer_credits', array(
        'label'       => esc_html__( 'Show Footer Credits', 'marsislav' ),
        'section'     => 'marsislav_footer_menu_section',
        'type'        => 'checkbox',
        'priority'    => 40,
    ) );
}
add_action( 'customize_register', 'marsislav_footer_credits_customizer' );

/**
 * Register block styles.
 */
function marsislav_register_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'marsislav-outline',
			'label' => esc_html__( 'Outline', 'marsislav' ),
		)
	);

	register_block_style(
		'core/quote',
		array(
			'name'  => 'marsislav-large',
			'label' => esc_html__( 'Large', 'marsislav' ),
		)
	);
}
add_action( 'init', 'marsislav_register_block_styles' );

/**
 * Register block patterns.
 */
function marsislav_register_block_patterns() {
	register_block_pattern(
		'marsislav/hero',
		array(
			'title'       => esc_html__( 'Hero Section', 'marsislav' ),
			'description' => esc_html__( 'A simple hero section with heading and button.', 'marsislav' ),
			'categories'  => array( 'featured' ),
			'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}}} --><div class="wp-block-group alignfull" style="padding-top:80px;padding-bottom:80px"><!-- wp:heading {"textAlign":"center","level":1} --><h1 class="has-text-align-center">' . esc_html__( 'Welcome to Our Site', 'marsislav' ) . '</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">' . esc_html__( 'A short description of your site or offer goes here.', 'marsislav' ) . '</p><!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link">' . esc_html__( 'Get Started', 'marsislav' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group -->',
		)
	);
}
add_action( 'init', 'marsislav_register_block_patterns' );


function marsislav_topbar_customizer($wp_customize) {

    // ── Section: Top Bar (inside Header panel) ───────────────────────────
    $wp_customize->add_section('marsislav_topbar_section', array(
        'title'    => __( 'Top Bar', 'marsislav' ),
        'panel'    => 'marsislav_header_panel',
        'priority' => 10,
    ));

    // Enable / Disable
    $wp_customize->add_setting('topbar_enable', array(
        'default'           => false,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean'
    ));

    $wp_customize->add_control('topbar_enable', array(
        'label'   => __('Enable Top Bar', 'marsislav'),
        'section' => 'marsislav_topbar_section',
        'type'    => 'checkbox',
    ));

    // Layout (1 or 2 columns)
    $wp_customize->add_setting('topbar_layout', array(
        'default'           => 'one',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_topbar_layout',
    ));

    $wp_customize->add_control('topbar_layout', array(
        'label'   => __('Layout', 'marsislav'),
        'section' => 'marsislav_topbar_section',
        'type'    => 'radio',
        'choices' => array(
            'one' => '1 Column',
            'two' => '2 Columns',
        ),
    ));

    // Marquee
    $wp_customize->add_setting('topbar_marquee', array(
        'default'           => false,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('topbar_marquee', array(
        'label'   => __('Enable Marquee Text', 'marsislav'),
        'section' => 'marsislav_topbar_section',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('topbar_marquee_speed', array(
        'default'           => 18,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('topbar_marquee_speed', array(
        'label'       => __('Marquee Speed (seconds)', 'marsislav'),
        'description' => __('Lower = faster. Recommended: 8–30s.', 'marsislav'),
        'section'     => 'marsislav_topbar_section',
        'type'        => 'range',
        'input_attrs' => array( 'min' => 3, 'max' => 60, 'step' => 1 ),
    ));

    // Text
    $wp_customize->add_setting('topbar_text', array(
        'default'           => 'Welcome to our website',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('topbar_text', array(
        'label'   => __('Top Bar Text', 'marsislav'),
        'section' => 'marsislav_topbar_section',
        'type'    => 'text',
    ));

    // Background color
    $wp_customize->add_setting('topbar_bg_color', array(
        'default'           => '#1f2937',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'topbar_bg_color',
        array(
            'label'   => __('Background Color', 'marsislav'),
            'section' => 'marsislav_topbar_section',
        )
    ));

    // Text color
    $wp_customize->add_setting('topbar_text_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'topbar_text_color',
        array(
            'label'   => __('Text Color', 'marsislav'),
            'section' => 'marsislav_topbar_section',
        )
    ));

    // Column 1 text (left, used in 2-column mode)
    $wp_customize->add_setting('topbar_col1_text', array(
        'default'           => '',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('topbar_col1_text', array(
        'label'       => __('Column 1 Text (left)', 'marsislav'),
        'description' => __('Shown in left column when 2 columns is selected. Supports HTML.', 'marsislav'),
        'section'     => 'marsislav_topbar_section',
        'type'        => 'textarea',
    ));

    // Column 2 text (right, used in 2-column mode)
    $wp_customize->add_setting('topbar_col2_text', array(
        'default'           => '',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('topbar_col2_text', array(
        'label'       => __('Column 2 Text (right)', 'marsislav'),
        'description' => __('Shown in right column when 2 columns is selected. Supports HTML.', 'marsislav'),
        'section'     => 'marsislav_topbar_section',
        'type'        => 'textarea',
    ));
}
add_action('customize_register', 'marsislav_topbar_customizer');

/**
 * Enqueue topbar customizer preview JS
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

/**
 * Output inline CSS for topbar colors (frontend + customizer preview)
 */
function marsislav_topbar_inline_css() {
    $bg    = get_theme_mod( 'topbar_bg_color', '#1f2937' );
    $color = get_theme_mod( 'topbar_text_color', '#ffffff' );
    $speed = absint( get_theme_mod( 'topbar_marquee_speed', 18 ) );
    $speed = max( 3, min( 60, $speed ) );
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



// =============================================================================
// Sidebar Engine — widget area registration and Customizer integration
// =============================================================================

/**
 * Helper – available sidebar choices for a given context.
 * Returns sidebar-id => label pairs, always including 'disabled'.
 */
function marsislav_sidebar_choices_for( $context ) {
    $choices = array(
        'disabled' => __( 'Disabled (Full Width)', 'marsislav' ),
    );

    // Map context to its "own" sidebar first, then offer the rest.
    $all = array(
        'sidebar-blog'    => __( 'Blog Sidebar', 'marsislav' ),
        'sidebar-post'    => __( 'Post Sidebar', 'marsislav' ),
        'sidebar-page'    => __( 'Page Sidebar', 'marsislav' ),
        'sidebar-shop'    => __( 'Shop Sidebar', 'marsislav' ),
        'sidebar-product' => __( 'Product Sidebar', 'marsislav' ),
    );

    return array_merge( $choices, $all );
}

function marsislav_sidebar_settings( $wp_customize ) {

    $position_choices = array(
        'right'    => __( 'Right', 'marsislav' ),
        'left'     => __( 'Left', 'marsislav' ),
        'disabled' => __( 'Disabled (Full Width)', 'marsislav' ),
    );

    $sidebar_widget_choices = marsislav_sidebar_choices_for( '' );

    // =========================================================
    // Panel structure:
    //
    //   Sidebar (panel)
    //   ├── Sidebar Design          ← priority 10  (added via colors-customizer.php)
    //   ├── Position: Blog          ← priority 30
    //   ├── Position: Single Post   ← priority 40
    //   ├── Position: Page          ← priority 50
    //   ├── Position: Home Page     ← priority 60
    //   ├── Position: Shop          ← priority 70
    //   └── Position: Product Page  ← priority 80
    //
    // Each 'Position:' section has two controls:
    //   • Position    (left | right | disabled)
    //   • Widget Area (which sidebar widget area to render)
    // =========================================================

    $contexts = array(
        // ctx_key => [ section title, position default, sidebar-id default, priority ]
        'blog'    => array( __( 'Position: Blog / Archive',    'marsislav' ), 'right',    'sidebar-blog',    30 ),
        'post'    => array( __( 'Position: Single Post',        'marsislav' ), 'right',    'sidebar-post',    40 ),
        'page'    => array( __( 'Position: Page',               'marsislav' ), 'disabled', 'sidebar-page',    50 ),
        'home'    => array( __( 'Position: Home Page',          'marsislav' ), 'disabled', 'sidebar-blog',    60 ),
        'shop'    => array( __( 'Position: Shop (WooCommerce)', 'marsislav' ), 'right',    'sidebar-shop',    70 ),
        'product' => array( __( 'Position: Product Page',       'marsislav' ), 'disabled', 'sidebar-product', 80 ),
    );

    foreach ( $contexts as $ctx => $config ) {
        list( $title, $pos_default, $id_default, $priority ) = $config;

        $section_id  = 'marsislav_sidebar_section_' . $ctx;
        $pos_key     = 'sidebar_pos_' . $ctx;
        $sidebar_key = 'sidebar_id_' . $ctx;

        /* ---- Section ---- */
        $wp_customize->add_section( $section_id, array(
            'title'    => $title,
            'panel'    => 'marsislav_sidebar_panel',
            'priority' => $priority,
        ) );

        /* ---- Position ---- */
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

        /* ---- Widget Area ---- */
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

/**
 * Sanitize helper callbacks.
 */
/**
 * Sanitize checkbox / boolean values.
 */
function marsislav_sanitize_checkbox( $val ) {
    return (bool) $val;
}

/**
 * Sanitize topbar layout choice (one|two).
 */
function marsislav_sanitize_topbar_layout( $val ) {
    return in_array( $val, array( 'one', 'two' ), true ) ? $val : 'one';
}

/**
 * Sanitize footer sidebar columns (1-4).
 */
function marsislav_sanitize_footer_columns( $val ) {
    return in_array( (string) $val, array( '1', '2', '3', '4' ), true ) ? $val : '3';
}

function marsislav_sanitize_sidebar_position( $val ) {
    return in_array( $val, array( 'left', 'right', 'disabled' ), true ) ? $val : 'right';
}

function marsislav_sanitize_sidebar_id( $val ) {
    $valid = array( 'disabled', 'sidebar-blog', 'sidebar-post', 'sidebar-page', 'sidebar-shop', 'sidebar-product' );
    return in_array( $val, $valid, true ) ? $val : 'sidebar-blog';
}

/**
 * 3. Determine sidebar position (auto-detects current context)
 */
function marsislav_get_sidebar_position() {
    // WooCommerce checks
    if ( function_exists( 'is_shop' ) ) {
        if ( is_shop() || is_product_category() || is_product_tag() ) return get_theme_mod( 'sidebar_pos_shop', 'right' );
        if ( is_product() ) return get_theme_mod( 'sidebar_pos_product', 'disabled' );
    }
    
    // Standard pages
    if ( is_front_page() && ! is_home() )           return get_theme_mod( 'sidebar_pos_home', 'disabled' );
    if ( is_home() || is_archive() || is_search() )  return get_theme_mod( 'sidebar_pos_blog', 'right' );
    if ( is_singular( 'post' ) )                     return get_theme_mod( 'sidebar_pos_post', 'right' );
    if ( is_page() )                                 return get_theme_mod( 'sidebar_pos_page', 'disabled' );
    
    return 'disabled';
}

/**
 * 3b. Determine which sidebar widget area to display for the current context.
 */
function marsislav_get_sidebar_id() {
    // WooCommerce checks
    if ( function_exists( 'is_shop' ) ) {
        if ( is_shop() || is_product_category() || is_product_tag() ) return get_theme_mod( 'sidebar_id_shop', 'sidebar-shop' );
        if ( is_product() ) return get_theme_mod( 'sidebar_id_product', 'sidebar-product' );
    }

    // Standard pages
    if ( is_front_page() && ! is_home() )           return get_theme_mod( 'sidebar_id_home', 'sidebar-blog' );
    if ( is_home() || is_archive() || is_search() )  return get_theme_mod( 'sidebar_id_blog', 'sidebar-blog' );
    if ( is_singular( 'post' ) )                     return get_theme_mod( 'sidebar_id_post', 'sidebar-post' );
    if ( is_page() )                                 return get_theme_mod( 'sidebar_id_page', 'sidebar-page' );

    return 'sidebar-blog';
}

/**
 * Add sidebar-related body classes for CSS targeting.
 */
function marsislav_sidebar_body_class( $classes ) {
    $position = marsislav_get_sidebar_position();
    $classes[] = 'sidebar-' . $position;
    if ( $position === 'disabled' ) {
        $classes[] = 'no-sidebar';
    } else {
        $classes[] = 'has-sidebar';
    }
    return $classes;
}
add_filter( 'body_class', 'marsislav_sidebar_body_class' );

/**
 * Enqueue Customizer preview JS for live sidebar updates.
 */
function marsislav_sidebar_preview_js() {
    wp_enqueue_script( 
        'marsislav-customizer-sidebar', 
        get_template_directory_uri() . '/js/customizer-sidebar.js', 
        array( 'customize-preview', 'jquery' ), 
        _S_VERSION, 
        true 
    );

    // Send all settings to the JS file
    wp_localize_script( 'marsislav-customizer-sidebar', 'marsislavSidebarVars', array(
        'settings' => array( 
            'sidebar_pos_blog', 'sidebar_pos_post', 'sidebar_pos_page', 
            'sidebar_pos_home', 'sidebar_pos_shop', 'sidebar_pos_product' 
        )
    ) );
}
add_action( 'customize_preview_init', 'marsislav_sidebar_preview_js' );


/* ── Footer Widget Areas — register sidebar areas (1–4 columns) ─── */
function marsislav_register_footer_sidebars() {
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( esc_html__( 'Footer Column %d', 'marsislav' ), $i ),
            'id'            => 'footer-sidebar-' . $i,
            'description'   => sprintf( esc_html__( 'Widgets for Footer column %d', 'marsislav' ), $i ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="footer-widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
}
add_action( 'widgets_init', 'marsislav_register_footer_sidebars' );


/* ── Footer Widget Areas — Customizer settings ──────────────────── */
function marsislav_footer_sidebar_customizer( $wp_customize ) {

    // ── Section: Widget Areas (inside Footer panel) ──────────────────────
    $wp_customize->add_section( 'marsislav_footer_widgets_section', array(
        'title'    => esc_html__( 'Widget Areas', 'marsislav' ),
        'panel'    => 'marsislav_footer_panel',
        'priority' => 30,
    ) );

    // Enable/disable footer sidebar
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

    // Number of columns
    $wp_customize->add_setting( 'footer_sidebar_columns', array(
        'default'           => '3',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'marsislav_sanitize_footer_columns',
    ) );
    $wp_customize->add_control( 'footer_sidebar_columns', array(
        'label'   => esc_html__( 'Number of Columns', 'marsislav' ),
        'section' => 'marsislav_footer_widgets_section',
        'type'    => 'select',
        'choices' => array(
            '1' => esc_html__( '1 Column', 'marsislav' ),
            '2' => esc_html__( '2 Columns', 'marsislav' ),
            '3' => esc_html__( '3 Columns', 'marsislav' ),
            '4' => esc_html__( '4 Columns', 'marsislav' ),
        ),
        'priority' => 6,
    ) );
}
add_action( 'customize_register', 'marsislav_footer_sidebar_customizer' );


/* ── Footer Widget Areas — Customizer preview JS ────────────────── */
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