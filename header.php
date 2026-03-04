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

            <?php if ( (bool) get_theme_mod( 'dark_mode_enable', true ) ) : ?>
            <button class="marsislav-dark-toggle"
                    aria-pressed="false"
                    aria-label="<?php esc_attr_e( 'Превключи тъмен режим', 'marsislav' ); ?>"
                    data-label-dark="<?php esc_attr_e( 'Тъмен режим', 'marsislav' ); ?>"
                    data-label-light="<?php esc_attr_e( 'Светъл режим', 'marsislav' ); ?>">
                <span class="dm-icon dm-icon--sun" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                </span>
                <span class="dm-icon dm-icon--moon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </span>
            </button>
            <?php endif; ?>

        </div><!-- .header-inner -->
    </header><!-- #masthead -->

    <div id="content" class="site-content">
