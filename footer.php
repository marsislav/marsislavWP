<?php
/**
 * The template for displaying the footer
 *
 * @package marsislav
 */
$footer_layout          = get_theme_mod( 'footer_layout', 'one-column' );
$copyright_text         = get_theme_mod( 'footer_copyright_text', sprintf( '&copy; %s %s. All rights reserved.', gmdate('Y'), get_bloginfo('name') ) );
$footer_col2_text       = get_theme_mod( 'footer_col2_text', '' );
$footer_sidebar_enable  = get_theme_mod( 'footer_sidebar_enable', true );
$footer_sidebar_columns = (int) get_theme_mod( 'footer_sidebar_columns', 3 );
$footer_sidebar_columns = max( 1, min( 4, $footer_sidebar_columns ) );

// Проверяваме дали поне една от 4-те зони е активна
$has_footer_widgets = false;
for ( $i = 1; $i <= 4; $i++ ) {
    if ( is_active_sidebar( 'footer-sidebar-' . $i ) ) {
        $has_footer_widgets = true;
        break;
    }
}
?>

<footer id="colophon" class="site-footer footer-<?php echo esc_attr( $footer_layout ); ?>">

    <?php if ( $footer_sidebar_enable && ( $has_footer_widgets || is_customize_preview() ) ) : ?>
    <div id="footer-sidebar-area"
         class="footer-sidebar-area footer-columns-<?php echo esc_attr( $footer_sidebar_columns ); ?>"
         data-columns="<?php echo esc_attr( $footer_sidebar_columns ); ?>">

        <?php
        // Рендираме ВИНАГИ всичките 4 колони в HTML-а.
        // CSS и JS управляват кои са видими спрямо footer_sidebar_columns.
        for ( $i = 1; $i <= 4; $i++ ) :
            $hidden = ( $i > $footer_sidebar_columns ) ? ' style="display:none"' : '';
        ?>
            <div class="footer-sidebar-col footer-sidebar-col-<?php echo esc_attr( $i ); ?>" data-col="<?php echo esc_attr( $i ); ?>"<?php echo $hidden; ?>>
                <?php if ( is_active_sidebar( 'footer-sidebar-' . $i ) ) : ?>
                    <?php dynamic_sidebar( 'footer-sidebar-' . $i ); ?>
                <?php elseif ( is_customize_preview() ) : ?>
                    <div class="footer-sidebar-placeholder">
                        <p><?php printf( esc_html__( 'Footer Колона %d — добави widget', 'marsislav' ), $i ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>

    </div><!-- #footer-sidebar-area -->
    <?php endif; ?>

	<div class="site-info">
		<?php if ( 'two-column' === $footer_layout ) : ?>
			<div class="footer-col footer-col-1">
				<div class="footer-copyright">
					<?php echo wp_kses_post( $copyright_text ); ?>
				</div>
			</div>
			<div class="footer-col footer-col-2">
				<div class="footer-col2-content">
					<?php echo wp_kses_post( $footer_col2_text ); ?>
				</div>
			</div>
		<?php else : ?>
			<div class="footer-copyright">
				<?php echo wp_kses_post( $copyright_text ); ?>
			</div>
		<?php endif; ?>
	</div><!-- .site-info -->
</footer><!-- #colophon -->

    </div><!-- #content -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
