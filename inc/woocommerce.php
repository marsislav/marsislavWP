<?php
/**
 * WooCommerce integrations
 *
 * @package marsislav
 */

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'WooCommerce' ) ) return;

/**
 * Замества "On sale" значката с реалния процент отстъпка.
 * Работи за прости продукти и за variable продукти (показва максималния %).
 */
function marsislav_sale_flash( $html, $post, $product ) {

    if ( ! $product->is_on_sale() ) {
        return $html;
    }

    $percentage = 0;

    if ( $product->is_type( 'variable' ) ) {

        // При variable — намираме най-голямата отстъпка сред вариантите
        $max_discount = 0;
        foreach ( $product->get_children() as $child_id ) {
            $variation     = wc_get_product( $child_id );
            $regular_price = (float) $variation->get_regular_price();
            $sale_price    = (float) $variation->get_sale_price();

            if ( $regular_price > 0 && $sale_price > 0 && $sale_price < $regular_price ) {
                $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                if ( $discount > $max_discount ) {
                    $max_discount = $discount;
                }
            }
        }
        $percentage = $max_discount;

    } elseif ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {

        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();

        if ( $regular_price > 0 && $sale_price >= 0 ) {
            $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
        }
    }

    if ( $percentage > 0 ) {
        return '<span class="onsale marsislav-sale-badge">-' . $percentage . '%</span>';
    }

    // Ако не можем да изчислим — оставяме оригиналния текст
    return $html;
}
add_filter( 'woocommerce_sale_flash', 'marsislav_sale_flash', 10, 3 );


/**
 * Добавя икона на количката в навигацията (само ако WooCommerce е активен)
 */
function marsislav_cart_nav_item( $items, $args ) {
    if ( 'menu-1' !== $args->theme_location ) {
        return $items;
    }

    $count    = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $cart_url = wc_get_cart_url();

    $items .= '<li class="menu-item marsislav-cart-item' . ( $count > 0 ? ' has-items' : '' ) . '">'
        . '<a href="' . esc_url( $cart_url ) . '" class="marsislav-cart-link" aria-label="' . esc_attr__( 'Количка', 'marsislav' ) . '">'
        . '<span class="cart-icon">'
        . '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>'
        . '</span>'
        . ( $count > 0 ? '<span class="cart-count">' . esc_html( $count ) . '</span>' : '' )
        . '</a>'
        . marsislav_cart_dropdown()
        . '</li>';

    return $items;
}
add_filter( 'wp_nav_menu_items', 'marsislav_cart_nav_item', 10, 2 );


/**
 * Генерира dropdown с продуктите в количката
 */
function marsislav_cart_dropdown() {
    if ( ! WC()->cart || WC()->cart->is_empty() ) {
        return '';
    }

    $items_html = '';
    foreach ( WC()->cart->get_cart() as $key => $item ) {
        $product  = $item['data'];
        $name     = $product->get_name();
        $qty      = $item['quantity'];
        $price    = WC()->cart->get_product_price( $product );
        $img      = $product->get_image( array( 52, 52 ) );
        $link     = $product->get_permalink();

        $items_html .= '<div class="cart-dropdown-item">'
            . '<a href="' . esc_url( $link ) . '" class="cart-item-img">' . $img . '</a>'
            . '<div class="cart-item-details">'
            . '<a href="' . esc_url( $link ) . '" class="cart-item-name">' . esc_html( $name ) . '</a>'
            . '<span class="cart-item-qty-price">' . esc_html( $qty ) . ' &times; ' . $price . '</span>'
            . '</div>'
            . '</div>';
    }

    $total = WC()->cart->get_cart_total();

    return '<div class="marsislav-cart-dropdown">'
        . '<div class="cart-dropdown-items">' . $items_html . '</div>'
        . '<div class="cart-dropdown-footer">'
        . '<span class="cart-total-label">' . esc_html__( 'Общо:', 'marsislav' ) . '</span>'
        . '<span class="cart-total-value">' . $total . '</span>'
        . '</div>'
        . '<a href="' . esc_url( wc_get_cart_url() ) . '" class="cart-dropdown-btn">' . esc_html__( 'Виж количката', 'marsislav' ) . '</a>'
        . '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="cart-dropdown-btn cart-checkout-btn">' . esc_html__( 'Плати', 'marsislav' ) . '</a>'
        . '</div>';
}


/**
 * Обновява броя в количката при AJAX добавяне (без reload)
 */
function marsislav_cart_count_fragments( $fragments ) {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

    $fragments['span.cart-count'] = '<span class="cart-count">' . esc_html( $count ) . '</span>';
    $fragments['.marsislav-cart-item'] = '<li class="menu-item marsislav-cart-item' . ( $count > 0 ? ' has-items' : '' ) . '">'
        . ( function_exists( 'wc_get_cart_url' ) ? '<a href="' . esc_url( wc_get_cart_url() ) . '"' : '<a href="#"' )
        . ' class="marsislav-cart-link" aria-label="' . esc_attr__( 'Количка', 'marsislav' ) . '">'
        . '<span class="cart-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span>'
        . ( $count > 0 ? '<span class="cart-count">' . esc_html( $count ) . '</span>' : '' )
        . '</a>'
        . marsislav_cart_dropdown()
        . '</li>';

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'marsislav_cart_count_fragments' );
