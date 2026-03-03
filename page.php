<?php
/**
 * Page template
 * @package marsislav
 */

get_header();

$sidebar_pos = marsislav_get_sidebar_position();
?>

<div id="content-sidebar-wrap" class="container-wide layout-<?php echo esc_attr( $sidebar_pos ); ?>">

    <main id="primary" class="site-main">
        <?php
        while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if ( ! is_front_page() ) : ?>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header>
                <?php endif; ?>
                <div class="entry-content">
                    <?php the_content();
                    wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'marsislav' ), 'after' => '</div>' ) ); ?>
                </div>
                <?php if ( get_edit_post_link() ) : ?>
                    <footer class="entry-footer">
                        <?php edit_post_link( sprintf( wp_kses( __( 'Edit <span class="screen-reader-text">%s</span>', 'marsislav' ), array( 'span' => array( 'class' => array() ) ) ), wp_kses_post( get_the_title() ) ) ); ?>
                    </footer>
                <?php endif; ?>
            </article>
            <?php if ( comments_open() || get_comments_number() ) : comments_template(); endif;
        endwhile; ?>
    </main>

    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <aside id="secondary" class="widget-area sidebar-column">
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </aside>
    <?php endif; ?>

</div>

<?php get_footer();
