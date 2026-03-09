<?php
/**
 * The sidebar containing the main widget area.
 * Uses context-aware sidebar ID from Customizer.
 *
 * @package marsislav
 */

$sidebar_id = marsislav_get_sidebar_id();

if ( marsislav_get_sidebar_position() === 'disabled' || ! is_active_sidebar( $sidebar_id ) ) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar( $sidebar_id ); ?>
</aside><!-- #secondary -->
