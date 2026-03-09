<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package marsislav
 */

$has_thumb = has_post_thumbnail();
$categories = get_the_category();
$is_singular = is_singular();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' . ( $has_thumb ? ' has-thumbnail' : ' no-thumbnail' ) ); ?>>

    <?php if ( $has_thumb && ! $is_singular ) : ?>
        <div class="post-card__thumb">
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail( 'large' ); ?>
            </a>
            <?php if ( $categories ) : ?>
                <span class="post-card__category">
                    <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                        <?php echo esc_html( $categories[0]->name ); ?>
                    </a>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="post-card__body">

        <header class="post-card__header">

            <?php if ( $is_singular ) : ?>
                <?php if ( $categories ) : ?>
                    <div class="post-card__cats">
                        <?php foreach ( $categories as $cat ) : ?>
                            <a class="post-card__cat-link" href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                                <?php echo esc_html( $cat->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php the_title( '<h1 class="post-card__title entry-title">', '</h1>' ); ?>
            <?php else : ?>
                <?php if ( $categories && ! $has_thumb ) : ?>
                    <span class="post-card__cat-inline">
                        <a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>">
                            <?php echo esc_html( $categories[0]->name ); ?>
                        </a>
                    </span>
                <?php endif; ?>
                <?php the_title( '<h2 class="post-card__title entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
            <?php endif; ?>

            <?php if ( 'post' === get_post_type() ) : ?>
                <div class="post-card__meta entry-meta">
                    <span class="post-card__date">
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </span>
                    <span class="post-card__sep" aria-hidden="true">&middot;</span>
                    <span class="post-card__author">
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                            <?php the_author(); ?>
                        </a>
                    </span>
                    <?php if ( ! $is_singular && has_excerpt() ) : ?>
                        <span class="post-card__sep" aria-hidden="true">&middot;</span>
                        <span class="post-card__readtime">
                            <?php
                            $word_count = str_word_count( strip_tags( get_the_content() ) );
                            $minutes    = max( 1, round( $word_count / 200 ) );
                            echo $minutes . ' ' . esc_html__( 'min read', 'marsislav' );
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </header>

        <?php if ( $is_singular ) : ?>
            <?php if ( $has_thumb ) : ?>
                <div class="post-card__thumb post-card__thumb--single">
                    <?php the_post_thumbnail( 'full' ); ?>
                </div>
            <?php endif; ?>
            <div class="post-card__content entry-content">
                <?php
                the_content(
                    sprintf(
                        wp_kses(
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'marsislav' ),
                            array( 'span' => array( 'class' => array() ) )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'marsislav' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
        <?php else : ?>
            <?php if ( has_excerpt() ) : ?>
                <div class="post-card__excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
            <a class="post-card__read-more" href="<?php the_permalink(); ?>">
                <?php esc_html_e( 'Read more', 'marsislav' ); ?> &rarr;
            </a>
        <?php endif; ?>

        <footer class="post-card__footer entry-footer">
            <?php marsislav_entry_footer(); ?>
        </footer>

    </div>

</article>

