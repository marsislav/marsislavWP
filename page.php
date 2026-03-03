<?php
/**
 * The template for displaying all pages
 *
 * @package marsislav
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    while ( have_posts() ) :
        the_post();
        ?>

        <div class="page-container">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <?php if ( ! is_front_page() ) : ?>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header><!-- .entry-header -->
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'marsislav' ),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div><!-- .entry-content -->

                <?php if ( get_edit_post_link() ) : ?>
                    <footer class="entry-footer">
                        <?php
                        edit_post_link(
                            sprintf(
                                wp_kses(
                                    __( 'Edit <span class="screen-reader-text">%s</span>', 'marsislav' ),
                                    array( 'span' => array( 'class' => array() ) )
                                ),
                                wp_kses_post( get_the_title() )
                            )
                        );
                        ?>
                    </footer><!-- .entry-footer -->
                <?php endif; ?>

            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // Comments
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        </div><!-- .page-container -->

    <?php
    endwhile; // End of the loop.
    ?>

</main><!-- #main -->

<?php
get_footer();