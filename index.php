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

        <?php do_action( 'marsislav_before_content' ); ?>

        <?php if ( have_posts() ) : ?>

            <?php if ( is_home() && ! is_front_page() ) : ?>
                <?php if ( (bool) get_theme_mod( 'show_title_home', true ) ) : ?>
                <header class="blog-index-header">
                    <h1 class="blog-index-title"><?php single_post_title(); ?></h1>
                    <div class="blog-title-divider"></div>
                </header>
                <?php endif; ?>
            <?php endif; ?>

            <div class="posts-grid">
                <?php
                $post_count = 0;
                while ( have_posts() ) : the_post();
                    $post_count++;
                    ?>
                    <div class="post-card-wrapper <?php echo $post_count === 1 ? 'post-card-featured' : ''; ?>">
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
