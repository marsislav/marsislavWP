<?php
/**
 * Search results template
 * @package marsislav
 */

get_header();

$sidebar_pos = marsislav_get_sidebar_position();
?>

<div id="content-sidebar-wrap" class="container-wide layout-<?php echo esc_attr( $sidebar_pos ); ?>">

    <main id="primary" class="site-main">
        <?php do_action( 'marsislav_before_content' ); ?>

        <?php if ( have_posts() ) : ?>

            <header class="page-entry-header search-entry-header">
                <h1 class="entry-title page-title-h1">
                    <?php
                    printf(
                        esc_html__( 'Search Results for: %s', 'marsislav' ),
                        '<span>' . esc_html( get_search_query() ) . '</span>'
                    );
                    ?>
                </h1>
            </header>

            <?php while ( have_posts() ) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <header class="entry-header">
                        <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
                    </header>

                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>

                </article>

            <?php endwhile; ?>

            <?php the_posts_navigation(); ?>

        <?php else : ?>

            <article class="no-results">
                <h2><?php esc_html_e( 'Nothing found', 'marsislav' ); ?></h2>
                <p><?php esc_html_e( 'Sorry, no results matched your search.', 'marsislav' ); ?></p>
            </article>

        <?php endif; ?>

    </main>

    <?php if ( $sidebar_pos !== 'disabled' && is_active_sidebar( marsislav_get_sidebar_id() ) ) : ?>
        <aside id="secondary" class="widget-area sidebar-column">
            <?php dynamic_sidebar( marsislav_get_sidebar_id() ); ?>
        </aside>
    <?php endif; ?>

</div>

<?php get_footer(); ?>