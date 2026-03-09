<?php
/**
 * Archive template
 * @package marsislav
 */

get_header();

$sidebar_pos = marsislav_get_sidebar_position();
?>

<div id="content-sidebar-wrap" class="container-wide layout-<?php echo esc_attr( $sidebar_pos ); ?>">

    <main id="primary" class="site-main">
        <?php do_action( 'marsislav_before_content' ); ?>

        <?php if ( have_posts() ) : ?>

            <?php
            $show_archive_title = ( is_category() || is_tag() || is_tax() )
                ? (bool) get_theme_mod( 'show_title_category', true )
                : (bool) get_theme_mod( 'show_title_archive', true );
            ?>
            <?php if ( $show_archive_title ) : ?>
            <header class="archive-header">
                <div class="archive-header-inner">
                    <span class="archive-label"><?php esc_html_e( 'Archive', 'marsislav' ); ?></span>
                    <?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
                    <?php the_archive_description( '<p class="archive-description">', '</p>' ); ?>
                    <div class="archive-title-divider"></div>
                </div>
            </header>
            <?php endif; ?>

            <div class="posts-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="post-card-wrapper">
                        <?php get_template_part( 'template-parts/content', get_post_type() ); ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="posts-pagination-wrap">
                <?php the_posts_navigation( array(
                    'prev_text' => '&larr; ' . __( 'Older posts', 'marsislav' ),
                    'next_text' => __( 'Newer posts', 'marsislav' ) . ' &rarr;',
                ) ); ?>
            </div>

        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>

    </main>

    <?php if ( marsislav_get_sidebar_position() !== 'disabled' && is_active_sidebar( marsislav_get_sidebar_id() ) ) : ?>
        <aside id="secondary" class="widget-area sidebar-column">
            <?php dynamic_sidebar( marsislav_get_sidebar_id() ); ?>
        </aside>
    <?php endif; ?>

</div>

<?php get_footer();
