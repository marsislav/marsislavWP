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
			'menu-1' => esc_html__( 'Primary', 'marsislav' ),
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
	$GLOBALS['content_width'] = apply_filters( 'marsislav_content_width', 640 );
}
add_action( 'after_setup_theme', 'marsislav_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function marsislav_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'marsislav' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'marsislav' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'marsislav_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function marsislav_scripts() {
	wp_enqueue_style( 'marsislav-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'marsislav-style', 'rtl', 'replace' );

	wp_enqueue_script( 'marsislav-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
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

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
///////////Miro
function marsislav_header_scripts() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const header = document.querySelector('.site-header');
        let lastScroll = 0;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 80) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            lastScroll = currentScroll;
        });

        // Mobile menu toggle
        const toggle = document.querySelector('.menu-toggle');
        const menu = document.querySelector('#primary-menu');

        toggle?.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', !expanded);
            document.querySelector('.main-navigation').classList.toggle('toggled');
        });

        // Close on outside click (optional)
        document.addEventListener('click', e => {
            if (!menu?.contains(e.target) && !toggle?.contains(e.target)) {
                toggle?.setAttribute('aria-expanded', 'false');
                document.querySelector('.main-navigation')?.classList.remove('toggled');
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'marsislav_header_scripts');

// Регистрация на менюта
function marsislav_register_menus() {
    register_nav_menus( array(
        'menu-1'      => esc_html__( 'Primary Menu', 'marsislav' ),
        'footer-menu' => esc_html__( 'Footer Menu',   'marsislav' ),
    ) );
}
add_action( 'after_setup_theme', 'marsislav_register_menus' );

// Customizer – footer toggle
function marsislav_footer_customizer( $wp_customize ) {
    $wp_customize->add_section( 'marsislav_footer_section', array(
        'title'    => esc_html__( 'Footer Settings', 'marsislav' ),
        'priority' => 160,
    ));

    $wp_customize->add_setting( 'show_footer_menu', array(
        'default'           => true,
        'sanitize_callback' => function($val) { return (bool) $val; },
    ));

    $wp_customize->add_control( 'show_footer_menu', array(
        'label'       => esc_html__( 'Показвай Footer Menu', 'marsislav' ),
        'section'     => 'marsislav_footer_section',
        'type'        => 'checkbox',
    ));
}
add_action( 'customize_register', 'marsislav_footer_customizer' );

/*
 * Customizer - Footer Credits Fields
 */
function marsislav_footer_credits_customizer( $wp_customize ) {

    // Уверете се, че секцията съществува (ако вече имаш marsislav_footer_section)
    if ( ! $wp_customize->get_section( 'marsislav_footer_section' ) ) {
        $wp_customize->add_section( 'marsislav_footer_section', array(
            'title'       => esc_html__( 'Footer Settings', 'marsislav' ),
            'priority'    => 160,
            'description' => esc_html__( 'Customize footer credits and visibility.', 'marsislav' ),
        ) );
    }

    // Поле 1: Powered by / лява част
    $wp_customize->add_setting( 'footer_powered_text', array(
        'default'           => esc_html__( 'Proudly powered by %s', 'marsislav' ),
        'sanitize_callback' => 'wp_kses_post',          // позволява HTML (линкове, strong и т.н.)
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'footer_powered_text', array(
        'label'       => esc_html__( 'Powered by text (left part)', 'marsislav' ),
        'description' => esc_html__( 'Може да използвате %s за името на CMS. Пример: Proudly powered by %s', 'marsislav' ),
        'section'     => 'marsislav_footer_section',
        'type'        => 'textarea',
        'input_attrs' => array(
            'rows' => 3,
        ),
    ) );

    // Поле 2: Theme credits / дясна част
    $wp_customize->add_setting( 'footer_credits_text', array(
        'default'           => esc_html__( 'Theme: %1$s by %2$s.', 'marsislav' ),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'footer_credits_text', array(
        'label'       => esc_html__( 'Theme credits (right part)', 'marsislav' ),
        'description' => esc_html__( 'Може да използвате %1$s за името на темата и %2$s за автора/линк. Пример: Theme: %1$s by %2$s.', 'marsislav' ),
        'section'     => 'marsislav_footer_section',
        'type'        => 'textarea',
        'input_attrs' => array(
            'rows' => 3,
        ),
    ) );

    // Опция за показване на credits изобщо (в случай че искаш да скриеш всичко)
    $wp_customize->add_setting( 'show_footer_credits', array(
        'default'           => true,
        'sanitize_callback' => function( $val ) { return (bool) $val; },
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'show_footer_credits', array(
        'label'       => esc_html__( 'Показвай footer credits', 'marsislav' ),
        'section'     => 'marsislav_footer_section',
        'type'        => 'checkbox',
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
