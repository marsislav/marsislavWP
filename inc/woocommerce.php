<?php
/**
 * WooCommerce integrations
 *
 * @package marsislav
 */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'WooCommerce' ) ) return;

/* ============================================================
 * Helper — Cart SVG icon
 * ============================================================ */

function marsislav_cart_svg() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" '
         . 'viewBox="0 0 24 24" fill="none" stroke="currentColor" '
         . 'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" '
         . 'aria-hidden="true" focusable="false">'
         . '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>'
         . '<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>'
         . '</svg>';
}

/* ============================================================
 * Helper — allowed tags for prices (WC HTML)
 * ============================================================ */

function marsislav_price_kses( $html ) {
    $allowed = array(
        'span'  => array( 'class' => array(), 'aria-hidden' => array() ),
        'del'   => array( 'aria-hidden' => array() ),
        'ins'   => array(),
        'bdi'   => array(),
        'small' => array( 'class' => array() ),
    );
    return wp_kses( $html, $allowed );
}

/* ============================================================
 * Percentage discount instead of "On sale"
 * ============================================================ */

function marsislav_sale_flash( $html, $post, $product ) {
    if ( ! $product->is_on_sale() ) {
        return $html;
    }

    $percentage = 0;

    if ( $product->is_type( 'variable' ) ) {
        $max = 0;
        foreach ( $product->get_children() as $child_id ) {
            $v = wc_get_product( $child_id );
            if ( ! $v ) continue;
            $r = (float) $v->get_regular_price();
            $s = (float) $v->get_sale_price();
            if ( $r > 0 && $s >= 0 && $s < $r ) {
                $d = round( ( ( $r - $s ) / $r ) * 100 );
                if ( $d > $max ) $max = $d;
            }
        }
        $percentage = $max;

    } elseif ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
        $r = (float) $product->get_regular_price();
        $s = (float) $product->get_sale_price();
        if ( $r > 0 ) {
            $percentage = round( ( ( $r - $s ) / $r ) * 100 );
        }
    }

    if ( $percentage > 0 ) {
        return '<span class="onsale marsislav-sale-badge">-' . absint( $percentage ) . '%</span>';
    }

    return $html;
}
add_filter( 'woocommerce_sale_flash', 'marsislav_sale_flash', 10, 3 );


/* ============================================================
 * Cart icon in navigation
 * ============================================================ */

function marsislav_cart_nav_item( $items, $args ) {
    if ( 'menu-1' !== $args->theme_location ) {
        return $items;
    }

    $count    = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $cart_url = wc_get_cart_url();

    $badge = $count > 0
        ? '<span class="cart-count">' . absint( $count ) . '</span>'
        : '';

    $items .= '<li class="menu-item marsislav-cart-item' . ( $count > 0 ? ' has-items' : '' ) . '">'
            .   '<a href="' . esc_url( $cart_url ) . '" class="marsislav-cart-link" '
            .       'aria-label="' . esc_attr__( 'Cart', 'marsislav' ) . '">'
            .     '<span class="cart-icon">' . marsislav_cart_svg() . '</span>'
            .     $badge
            .   '</a>'
            .   marsislav_build_cart_dropdown()
            . '</li>';

    return $items;
}
add_filter( 'wp_nav_menu_items', 'marsislav_cart_nav_item', 10, 2 );


/* ============================================================
 * Dropdown HTML
 * ============================================================ */

function marsislav_build_cart_dropdown() {
    if ( ! WC()->cart || WC()->cart->is_empty() ) {
        return '';
    }

    $img_allowed = array(
        'img' => array(
            'src'    => array(),
            'alt'    => array(),
            'width'  => array(),
            'height' => array(),
            'class'  => array(),
            'loading'=> array(),
        ),
    );

    $items_html = '';
    foreach ( WC()->cart->get_cart() as $item ) {
        $product = $item['data'];
        if ( ! $product ) continue;

        $qty    = absint( $item['quantity'] );
        $price  = marsislav_price_kses( WC()->cart->get_product_price( $product ) );
        $img    = wp_kses( $product->get_image( array( 52, 52 ) ), $img_allowed );
        $link   = esc_url( $product->get_permalink() );
        $name   = esc_html( $product->get_name() );

        $items_html .= '<div class="cart-dropdown-item">'
                     .   '<a href="' . $link . '" class="cart-item-img" tabindex="-1">' . $img . '</a>'
                     .   '<div class="cart-item-details">'
                     .     '<a href="' . $link . '" class="cart-item-name">' . $name . '</a>'
                     .     '<span class="cart-item-qty-price">' . $qty . ' &times; ' . $price . '</span>'
                     .   '</div>'
                     . '</div>';
    }

    $total = marsislav_price_kses( WC()->cart->get_cart_total() );

    return '<div class="marsislav-cart-dropdown" role="dialog" aria-label="' . esc_attr__( 'Cart Contents', 'marsislav' ) . '">'
         .   '<div class="cart-dropdown-items">' . $items_html . '</div>'
         .   '<div class="cart-dropdown-footer">'
         .     '<span class="cart-total-label">' . esc_html__( 'Total:', 'marsislav' ) . '</span>'
         .     '<span class="cart-total-value">' . $total . '</span>'
         .   '</div>'
         .   '<div class="cart-dropdown-actions">'
         .     '<a href="' . esc_url( wc_get_cart_url() ) . '" class="cart-dropdown-btn">'
         .       esc_html__( 'View Cart', 'marsislav' )
         .     '</a>'
         .     '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="cart-dropdown-btn cart-checkout-btn">'
         .       esc_html__( 'Checkout', 'marsislav' )
         .     '</a>'
         .   '</div>'
         . '</div>';
}


/* ============================================================
 * AJAX fragment — updates on add-to-cart without reload
 * Selector is .marsislav-cart-item (entire <li>)
 * ============================================================ */

function marsislav_cart_fragments( $fragments ) {
    $count    = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $cart_url = wc_get_cart_url();

    $badge = $count > 0
        ? '<span class="cart-count">' . absint( $count ) . '</span>'
        : '';

    ob_start();
    echo '<li class="menu-item marsislav-cart-item' . ( $count > 0 ? ' has-items' : '' ) . '">';
    echo '<a href="' . esc_url( $cart_url ) . '" class="marsislav-cart-link" '
       . 'aria-label="' . esc_attr__( 'Cart', 'marsislav' ) . '">';
    echo '<span class="cart-icon">' . marsislav_cart_svg() . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo wp_kses(
        $badge,
        array( 'span' => array( 'class' => array() ) )
    );
    echo '</a>';
    echo marsislav_build_cart_dropdown(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo '</li>';
    $fragments['.marsislav-cart-item'] = ob_get_clean();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'marsislav_cart_fragments' );