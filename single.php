<?php
/**
 * Single post template
 * @package marsislav
 */

get_header();

$sidebar_pos = marsislav_get_sidebar_position();
?>

<!-- Reading progress bar -->
<div id="reading-progress-bar" aria-hidden="true"></div>

<div id="content-sidebar-wrap" class="container-wide layout-<?php echo esc_attr( $sidebar_pos ); ?>">

    <main id="primary" class="site-main single-post-main">
        <?php do_action( 'marsislav_before_content' ); ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <?php
            $categories   = get_the_category();
            $tags         = get_the_tags();
            $author_id    = get_the_author_meta( 'ID' );
            $author_bio   = get_the_author_meta( 'description' );
            $word_count   = str_word_count( strip_tags( get_the_content() ) );
            $read_minutes = max( 1, round( $word_count / 200 ) );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>

                <!-- Post Hero -->
                <header class="single-hero">

                    <?php if ( $categories ) : ?>
                        <div class="single-cats">
                            <?php foreach ( $categories as $cat ) : ?>
                                <a class="single-cat-link" href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                                    <?php echo esc_html( $cat->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( (bool) get_theme_mod( 'show_title_post', true ) ) : ?>
                    <h1 class="single-title"><?php the_title(); ?></h1>
                    <?php endif; ?>

                    <div class="single-meta">
                        <a class="single-meta__author-link" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
                            <?php echo get_avatar( $author_id, 32, '', '', array( 'class' => 'single-meta__avatar' ) ); ?>
                            <span><?php the_author(); ?></span>
                        </a>
                        <span class="single-meta__sep" aria-hidden="true">&middot;</span>
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                        <span class="single-meta__sep" aria-hidden="true">&middot;</span>
                        <span class="single-meta__readtime">
                            <?php echo esc_html( $read_minutes . ' ' . __( 'min read', 'marsislav' ) ); ?>
                        </span>
                    </div>

                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="single-featured-image">
                        <?php the_post_thumbnail( 'full' ); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Body -->
                <div class="single-body">

                    <div class="single-content entry-content">
                        <?php
                        the_content();
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'marsislav' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </div>

                    <?php if ( $tags ) : ?>
                        <div class="single-tags">
                            <span class="single-tags__label"><?php esc_html_e( 'Tags:', 'marsislav' ); ?></span>
                            <?php foreach ( $tags as $tag ) : ?>
                                <a class="single-tag" href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">
                                    #<?php echo esc_html( $tag->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Author Box -->
                    <div class="single-author-box">
                        <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="single-author-box__avatar-link">
                            <?php echo get_avatar( $author_id, 72, '', '', array( 'class' => 'single-author-box__avatar' ) ); ?>
                        </a>
                        <div class="single-author-box__info">
                            <span class="single-author-box__label"><?php esc_html_e( 'Written by', 'marsislav' ); ?></span>
                            <a class="single-author-box__name" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
                                <?php the_author(); ?>
                            </a>
                            <?php if ( $author_bio ) : ?>
                                <p class="single-author-box__bio"><?php echo esc_html( $author_bio ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div><!-- .single-body -->

            </article>

            <!-- Post Navigation -->
            <?php
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            if ( $prev_post || $next_post ) :
            ?>
            <nav class="single-post-nav" aria-label="<?php esc_attr_e( 'Post navigation', 'marsislav' ); ?>">
                <?php if ( $prev_post ) : ?>
                    <a class="single-post-nav__item single-post-nav__prev" href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>">
                        <span class="single-post-nav__label">&larr; <?php esc_html_e( 'Previous', 'marsislav' ); ?></span>
                        <span class="single-post-nav__title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
                    </a>
                <?php else : ?>
                    <span></span>
                <?php endif; ?>
                <?php if ( $next_post ) : ?>
                    <a class="single-post-nav__item single-post-nav__next" href="<?php echo esc_url( get_permalink( $next_post ) ); ?>">
                        <span class="single-post-nav__label"><?php esc_html_e( 'Next', 'marsislav' ); ?> &rarr;</span>
                        <span class="single-post-nav__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
                    </a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <?php comments_template(); ?>
            <?php endif; ?>

        <?php endwhile; ?>

    </main>

    <?php if ( marsislav_get_sidebar_position() !== 'disabled' && is_active_sidebar( marsislav_get_sidebar_id() ) ) : ?>
        <aside id="secondary" class="widget-area sidebar-column">
            <?php dynamic_sidebar( marsislav_get_sidebar_id() ); ?>
        </aside>
    <?php endif; ?>

</div>

<script>
(function() {
    var bar = document.getElementById('reading-progress-bar');
    if (!bar) return;
    function updateBar() {
        var scrollTop = window.scrollY || document.documentElement.scrollTop;
        var docH = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var pct  = docH > 0 ? (scrollTop / docH) * 100 : 0;
        bar.style.width = Math.min(pct, 100) + '%';
    }
    window.addEventListener('scroll', updateBar, { passive: true });
    updateBar();
})();
</script>

<?php get_footer();
