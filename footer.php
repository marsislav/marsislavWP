<?php
/**
 * The template for displaying the footer
 *
 * @package marsislav
 */
$footer_layout          = (string) get_theme_mod( 'footer_layout', 'one-column' );
$copyright_text         = (string) get_theme_mod( 'footer_copyright_text', sprintf( '&copy; %s %s. All rights reserved.', gmdate('Y'), get_bloginfo('name') ) );
$footer_col2_text       = (string) get_theme_mod( 'footer_col2_text', '' );
$footer_sidebar_enable  = get_theme_mod( 'footer_sidebar_enable', true );
$footer_sidebar_columns = (int) get_theme_mod( 'footer_sidebar_columns', 3 );
$footer_sidebar_columns = max( 1, min( 4, $footer_sidebar_columns ) );

// Check if at least one of the 4 widget areas is active
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
        // Always render all 4 columns in HTML.
        // CSS and JS control which ones are visible based on footer_sidebar_columns.
        for ( $i = 1; $i <= 4; $i++ ) :
        ?>
            <div class="footer-sidebar-col footer-sidebar-col-<?php echo esc_attr( $i ); ?>" data-col="<?php echo esc_attr( $i ); ?>"<?php if ( $i > $footer_sidebar_columns ) : ?> style="display:none"<?php endif; ?>>
                <?php if ( is_active_sidebar( 'footer-sidebar-' . $i ) ) : ?>
                    <?php dynamic_sidebar( 'footer-sidebar-' . $i ); ?>
                <?php elseif ( is_customize_preview() ) : ?>
                    <div class="footer-sidebar-placeholder">
                        <p><?php printf( esc_html__( 'Footer Column %d — add widget', 'marsislav' ), $i ); ?></p>
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

<?php if ( (bool) get_theme_mod( 'scroll_to_top_enable', true ) ) : ?>
<button id="marsislav-scroll-top" class="marsislav-scroll-top"
        aria-label="<?php esc_attr_e( 'Back to top', 'marsislav' ); ?>"
        title="<?php esc_attr_e( 'Back to top', 'marsislav' ); ?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="2.5"
         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
        <polyline points="18 15 12 9 6 15"/>
    </svg>
</button>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>
