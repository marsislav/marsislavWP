<?php
/**
 * Breadcrumbs
 * @package marsislav
 */
if ( ! defined( "ABSPATH" ) ) exit;

function marsislav_get_breadcrumbs() {
    $crumbs = array();
    $crumbs[] = array( "label" => esc_html__( "Home", "marsislav" ), "url" => home_url( "/" ) );

    if ( is_front_page() || is_home() ) return $crumbs;

    // WooCommerce
    if ( function_exists( "is_woocommerce" ) ) {
        if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
            $shop_id = wc_get_page_id( "shop" );
            if ( $shop_id && $shop_id !== get_the_ID() ) {
                $crumbs[] = array( "label" => get_the_title( $shop_id ), "url" => get_permalink( $shop_id ) );
            }
        }
        if ( is_product_category() ) {
            $term = get_queried_object();
            if ( $term && $term->parent ) {
                $parent = get_term( $term->parent, "product_cat" );
                if ( ! is_wp_error( $parent ) ) {
                    $crumbs[] = array( "label" => esc_html( $parent->name ), "url" => esc_url( get_term_link( $parent ) ) );
                }
            }
            $crumbs[] = array( "label" => esc_html( $term->name ), "url" => "" );
            return $crumbs;
        }
        if ( is_product() ) {
            $terms = get_the_terms( get_the_ID(), "product_cat" );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $term = current( $terms );
                if ( $term->parent ) {
                    $parent = get_term( $term->parent, "product_cat" );
                    if ( ! is_wp_error( $parent ) ) {
                        $crumbs[] = array( "label" => esc_html( $parent->name ), "url" => esc_url( get_term_link( $parent ) ) );
                    }
                }
                $crumbs[] = array( "label" => esc_html( $term->name ), "url" => esc_url( get_term_link( $term ) ) );
            }
            $crumbs[] = array( "label" => esc_html( get_the_title() ), "url" => "" );
            return $crumbs;
        }
    }

    // Single post
    if ( is_single() ) {
        $post_type = get_post_type();
        if ( 'post' === $post_type ) {
            $cats = get_the_category();
            if ( $cats ) {
                // Walk full ancestor chain of first category
                $cat_chain = array();
                $current   = $cats[0];
                while ( $current ) {
                    array_unshift( $cat_chain, $current );
                    $cat_chain[0] = $current;
                    if ( $current->parent ) {
                        $current = get_category( $current->parent );
                        if ( is_wp_error( $current ) ) break;
                    } else {
                        break;
                    }
                }
                foreach ( $cat_chain as $c ) {
                    $crumbs[] = array( "label" => esc_html( $c->name ), "url" => esc_url( get_category_link( $c ) ) );
                }
            }
        } else {
            $obj = get_post_type_object( $post_type );
            if ( $obj && $obj->has_archive ) {
                $crumbs[] = array( "label" => esc_html( $obj->labels->name ), "url" => esc_url( get_post_type_archive_link( $post_type ) ) );
            }
            // CPT page ancestors
            $ancestors = get_post_ancestors( get_the_ID() );
            foreach ( array_reverse( $ancestors ) as $ancestor_id ) {
                $crumbs[] = array( "label" => esc_html( get_the_title( $ancestor_id ) ), "url" => esc_url( get_permalink( $ancestor_id ) ) );
            }
        }
        $crumbs[] = array( "label" => esc_html( get_the_title() ), "url" => "" );
        return $crumbs;
    }

    // Page — all ancestors
    if ( is_page() ) {
        $ancestors = get_post_ancestors( get_the_ID() );
        foreach ( array_reverse( $ancestors ) as $ancestor_id ) {
            $crumbs[] = array( "label" => esc_html( get_the_title( $ancestor_id ) ), "url" => esc_url( get_permalink( $ancestor_id ) ) );
        }
        $crumbs[] = array( "label" => esc_html( get_the_title() ), "url" => "" );
        return $crumbs;
    }

    // Category — full ancestor chain
    if ( is_category() ) {
        $cat   = get_queried_object();
        $chain = array();
        $cur   = $cat;
        while ( $cur ) {
            array_unshift( $chain, $cur );
            if ( $cur->parent ) {
                $parent = get_category( $cur->parent );
                if ( is_wp_error( $parent ) ) break;
                $cur = $parent;
            } else {
                break;
            }
        }
        foreach ( $chain as $i => $c ) {
            $is_last  = ( $i === count( $chain ) - 1 );
            $crumbs[] = array( "label" => esc_html( $c->name ), "url" => $is_last ? "" : esc_url( get_category_link( $c ) ) );
        }
        return $crumbs;
    }

    if ( is_tag() ) {
        $crumbs[] = array( "label" => esc_html( single_tag_title( "", false ) ), "url" => "" );
        return $crumbs;
    }
    if ( is_author() ) {
        $crumbs[] = array( "label" => esc_html( get_the_author() ), "url" => "" );
        return $crumbs;
    }
    if ( is_search() ) {
        $crumbs[] = array( "label" => sprintf( esc_html__( "Search: %s", "marsislav" ), esc_html( get_search_query() ) ), "url" => "" );
        return $crumbs;
    }
    if ( is_404() ) {
        $crumbs[] = array( "label" => esc_html__( "Page Not Found", "marsislav" ), "url" => "" );
        return $crumbs;
    }
    return $crumbs;
}

function marsislav_breadcrumbs() {
    if ( is_front_page() ) return;
    if ( ! (bool) get_theme_mod( "breadcrumbs_enable", true ) ) return;

    $crumbs = marsislav_get_breadcrumbs();
    if ( count( $crumbs ) <= 1 ) return;

    $sep   = "<span class=\"bc-sep\" aria-hidden=\"true\">&#8250;</span>";
    $total = count( $crumbs );

    $schema_items = array();
    foreach ( $crumbs as $i => $crumb ) {
        $schema_items[] = array(
            "@type"    => "ListItem",
            "position" => $i + 1,
            "name"     => $crumb["label"],
            "item"     => $crumb["url"] ? $crumb["url"] : get_permalink(),
        );
    }
    $schema = array(
        "@context"        => "https://schema.org",
        "@type"           => "BreadcrumbList",
        "itemListElement" => $schema_items,
    );

    echo "<nav class=\"marsislav-breadcrumbs\" aria-label=\"" . esc_attr__( "Breadcrumb Navigation", "marsislav" ) . "\">\n"; // phpcs:ignore
    echo "<script type=\"application/ld+json\">" . wp_json_encode( $schema ) . "</script>\n"; // phpcs:ignore
    echo "<ol class=\"bc-list\">\n"; // phpcs:ignore

    foreach ( $crumbs as $i => $crumb ) {
        $is_last = ( $i === $total - 1 );
        if ( $is_last ) {
            echo "<li class=\"bc-item bc-item--current\"><span class=\"bc-current\" aria-current=\"page\">" . esc_html( $crumb["label"] ) . "</span></li>\n"; // phpcs:ignore
        } else {
            echo "<li class=\"bc-item\"><a href=\"" . esc_url( $crumb["url"] ) . "\" class=\"bc-link\">" . esc_html( $crumb["label"] ) . "</a>" . $sep . "</li>\n"; // phpcs:ignore
        }
    }

    echo "</ol></nav>\n"; // phpcs:ignore
}
add_action( "marsislav_before_content", "marsislav_breadcrumbs" );
