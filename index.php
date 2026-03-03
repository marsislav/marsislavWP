<?php
/**
 * The main template file
 * @package marsislav
 */

get_header();

$sidebar_pos = marsislav_get_sidebar_position();
?>

<div id="content-sidebar-wrap" class="container-wide layout-<?php echo esc_attr( $sidebar_pos ); ?>">

    <main id="primary" class="site-main">
        <?php
        if ( have_posts() ) :
            if ( is_home() && ! is_front_page() ) : ?>
                <header><h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1></header>
            <?php endif;
            while ( have_posts() ) : the_post();
                get_template_part( 'template-parts/content', get_post_type() );
            endwhile;
            the_posts_navigation();
        else :
            get_template_part( 'template-parts/content', 'none' );
        endif;
        ?>
    </main>

    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <aside id="secondary" class="widget-area sidebar-column">
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </aside>
    <?php endif; ?>

</div>

<?php get_footer();
