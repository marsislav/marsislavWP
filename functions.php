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


// =============================================================================
// 1. THEME SETUP
// =============================================================================

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
	 */
	load_theme_textdomain( 'marsislav', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Navigation menus.
	register_nav_menus( array(
		'menu-1'      => esc_html__( 'Primary',     'marsislav' ),
		'footer-menu' => esc_html__( 'Footer Menu', 'marsislav' ),
	) );

	// Switch default core markup to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Custom background.
	add_theme_support(
		'custom-background',
		apply_filters( 'marsislav_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) )
	);

	// Selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Block editor support.
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-content-width', 760 );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'custom-units' );

	// Editor styling so the block editor matches the front end.
	add_editor_style( 'style.css' );

	// Custom logo.
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );
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


// =============================================================================
// 2. WIDGET AREAS
// =============================================================================

/**
 * Register sidebar widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function marsislav_widgets_init() {
	$sidebars = array(
		'sidebar-blog'    => __( 'Sidebar – Blog / Archive',    'marsislav' ),
		'sidebar-post'    => __( 'Sidebar – Single Post',       'marsislav' ),
		'sidebar-page'    => __( 'Sidebar – Page',              'marsislav' ),
		'sidebar-shop'    => __( 'Sidebar – Shop (WooCommerce)','marsislav' ),
		'sidebar-product' => __( 'Sidebar – Product Page',      'marsislav' ),
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
 * Register footer widget areas (columns 1–4).
 */
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


// =============================================================================
// 3. SCRIPTS & STYLES
// =============================================================================

/**
 * Enqueue scripts and styles.
 */
function marsislav_scripts() {
	wp_enqueue_style( 'marsislav-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'marsislav-style', 'rtl', 'replace' );

	wp_enqueue_script( 'marsislav-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	// Scroll to top button (only if enabled).
	if ( (bool) get_theme_mod( 'scroll_to_top_enable', true ) ) {
		wp_enqueue_script( 'marsislav-scroll-top', get_template_directory_uri() . '/js/scroll-to-top.js', array(), _S_VERSION, true );
	}

	// Dark Mode — loaded in <head> to prevent flash.
	if ( (bool) get_theme_mod( 'dark_mode_enable', true ) ) {
		wp_enqueue_script( 'marsislav-dark-mode', get_template_directory_uri() . '/js/dark-mode.js', array(), _S_VERSION, false );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Header Search Overlay (only if enabled).
	if ( (bool) get_theme_mod( 'header_show_search', true ) ) {
		wp_enqueue_script( 'marsislav-search-overlay', get_template_directory_uri() . '/js/search-overlay.js', array(), _S_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'marsislav_scripts' );

/**
 * Output inline JS that adds the .scrolled class to the site header on scroll.
 * Placed in wp_footer so it never blocks rendering.
 */
function marsislav_header_scripts() {
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
add_action( 'wp_footer', 'marsislav_header_scripts' );


// =============================================================================
// 4. BLOCK STYLES & PATTERNS
// =============================================================================

/**
 * Register custom block styles.
 */
function marsislav_register_block_styles() {
	register_block_style( 'core/button', array(
		'name'  => 'marsislav-outline',
		'label' => esc_html__( 'Outline', 'marsislav' ),
	) );

	register_block_style( 'core/quote', array(
		'name'  => 'marsislav-large',
		'label' => esc_html__( 'Large', 'marsislav' ),
	) );
}
add_action( 'init', 'marsislav_register_block_styles' );

/**
 * Register custom block patterns.
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


// =============================================================================
// 5. SIDEBAR ENGINE
// =============================================================================

/**
 * Determine the sidebar position for the current page context.
 *
 * @return string 'left' | 'right' | 'disabled'
 */
function marsislav_get_sidebar_position() {
	// WooCommerce contexts.
	if ( function_exists( 'is_shop' ) ) {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			return get_theme_mod( 'sidebar_pos_shop', 'right' );
		}
		if ( is_product() ) {
			return get_theme_mod( 'sidebar_pos_product', 'disabled' );
		}
	}

	// Standard WordPress contexts.
	if ( is_front_page() && ! is_home() )           return get_theme_mod( 'sidebar_pos_home', 'disabled' );
	if ( is_home() || is_archive() || is_search() )  return get_theme_mod( 'sidebar_pos_blog', 'right' );
	if ( is_singular( 'post' ) )                     return get_theme_mod( 'sidebar_pos_post', 'right' );
	if ( is_page() )                                 return get_theme_mod( 'sidebar_pos_page', 'disabled' );

	return 'disabled';
}

/**
 * Determine which sidebar widget area to render for the current page context.
 *
 * @return string Sidebar ID.
 */
function marsislav_get_sidebar_id() {
	// WooCommerce contexts.
	if ( function_exists( 'is_shop' ) ) {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			return get_theme_mod( 'sidebar_id_shop', 'sidebar-shop' );
		}
		if ( is_product() ) {
			return get_theme_mod( 'sidebar_id_product', 'sidebar-product' );
		}
	}

	// Standard WordPress contexts.
	if ( is_front_page() && ! is_home() )           return get_theme_mod( 'sidebar_id_home', 'sidebar-blog' );
	if ( is_home() || is_archive() || is_search() )  return get_theme_mod( 'sidebar_id_blog', 'sidebar-blog' );
	if ( is_singular( 'post' ) )                     return get_theme_mod( 'sidebar_id_post', 'sidebar-post' );
	if ( is_page() )                                 return get_theme_mod( 'sidebar_id_page', 'sidebar-page' );

	return 'sidebar-blog';
}

/**
 * Add sidebar-related body classes for CSS targeting.
 *
 * @param  array $classes Existing body classes.
 * @return array
 */
function marsislav_sidebar_body_class( $classes ) {
	$position  = marsislav_get_sidebar_position();
	$classes[] = 'sidebar-' . $position;
	$classes[] = ( 'disabled' === $position ) ? 'no-sidebar' : 'has-sidebar';
	return $classes;
}
add_filter( 'body_class', 'marsislav_sidebar_body_class' );


// =============================================================================
// 6. REQUIRED FILES
// =============================================================================

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php'; // Единен файл за всички Customizer настройки
require get_template_directory() . '/inc/breadcrumbs.php';
require get_template_directory() . '/inc/table-customizer.php';

if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
