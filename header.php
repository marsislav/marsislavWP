<?php
/**
 * The header for my theme
 *
 * @package marsislav
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$topbar_enabled = (bool) get_theme_mod( 'topbar_enable', false );
$topbar_layout  = (string) get_theme_mod( 'topbar_layout', 'one' );
$topbar_marquee = (bool) get_theme_mod( 'topbar_marquee', false );
$topbar_text    = (string) get_theme_mod( 'topbar_text', 'Welcome to our website' );
$topbar_col1    = (string) get_theme_mod( 'topbar_col1_text', '' );
$topbar_col2    = (string) get_theme_mod( 'topbar_col2_text', '' );
// $topbar_hide built via esc_attr below
?>
<div id="site-topbar" class="topbar layout-<?php echo esc_attr( $topbar_layout ); ?>"<?php if ( ! $topbar_enabled ) : ?> style="display:none"<?php endif; ?>>
    <div class="topbar-inner">

        <?php if ( $topbar_layout === 'two' ) : ?>
            <?php
            $col1_content = trim( wp_strip_all_tags( $topbar_col1 ? $topbar_col1 : $topbar_text ) );
            $col2_content = trim( wp_strip_all_tags( $topbar_col2 ) );
            $col1_empty   = empty( $col1_content );
            $col2_empty   = empty( $col2_content );
            $col1_class   = 'topbar-col topbar-col-1' . ( $col2_empty ? ' topbar-col-full' : '' );
            $col2_class   = 'topbar-col topbar-col-2' . ( $col1_empty ? ' topbar-col-full' : '' );
            ?>
            <!-- Two-column layout -->
            <div class="<?php echo esc_attr( $col1_class ); ?>">
                <?php if ( $topbar_marquee ) : ?>
                    <div class="topbar-marquee">
                        <span><?php echo wp_kses_post( $topbar_col1 ? $topbar_col1 : $topbar_text ); ?></span>
                    </div>
                <?php else : ?>
                    <div class="topbar-text"><?php echo wp_kses_post( $topbar_col1 ? $topbar_col1 : $topbar_text ); ?></div>
                <?php endif; ?>
            </div>
            <div class="<?php echo esc_attr( $col2_class ); ?>">
                <div class="topbar-text topbar-col2-text"><?php echo wp_kses_post( $topbar_col2 ); ?></div>
            </div>
        <?php else : ?>
            <!-- Single column layout -->
            <?php if ( $topbar_marquee ) : ?>
                <div class="topbar-marquee">
                    <span><?php echo wp_kses_post( $topbar_text ); ?></span>
                </div>
            <?php else : ?>
                <div class="topbar-text"><?php echo wp_kses_post( $topbar_text ); ?></div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
<div id="page" class="site">

    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e( 'Skip to content', 'marsislav' ); ?>
    </a>

    <header id="masthead" class="site-header">
        <div class="header-inner container-wide">
            
            <!-- Branding -->
            <div class="site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>

                <div class="branding-text">
                    <?php if ( is_front_page() && is_home() ) : ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                    <?php else : ?>
                        <p class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php
                    $description = get_bloginfo( 'description', 'display' );
                    if ( $description || is_customize_preview() ) : ?>
                        <p class="site-description"><?php echo esc_html( $description ); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Navigation -->
            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-text"><?php esc_html_e( 'Меню', 'marsislav' ); ?></span>
                    <span class="hamburger-lines">
                        <span></span><span></span><span></span>
                    </span>
                </button>

                <?php
                wp_nav_menu( array(
                    'theme_location'  => 'menu-1',
                    'menu_id'         => 'primary-menu',
                    'container'       => false,
                    'menu_class'      => 'primary-menu',
                    'fallback_cb'     => false,
                ) );
                ?>
            </nav>

        </div><!-- .header-inner -->
    </header><!-- #masthead -->

    <div id="content" class="site-content">
