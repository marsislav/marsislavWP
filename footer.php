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

<?php
$waves_enable = (bool) get_theme_mod( 'footer_waves_enable', false );
$wave_color1  = sanitize_hex_color( get_theme_mod( 'footer_wave_color1', '#1e90ff' ) ) ?: '#1e90ff';
$wave_color2  = sanitize_hex_color( get_theme_mod( 'footer_wave_color2', '#3aa0ff' ) ) ?: '#3aa0ff';
$wave_color3  = sanitize_hex_color( get_theme_mod( 'footer_wave_color3', '#63b3ff' ) ) ?: '#63b3ff';
?>
<footer id="colophon" class="site-footer footer-<?php echo esc_attr( $footer_layout ); ?><?php echo $waves_enable ? ' has-waves' : ''; ?>">

    <?php if ( $waves_enable ) : ?>
    <div class="footer-waves" aria-hidden="true">
        <div class="footer-wave footer-wave-1">
            <svg viewBox="0 0 2400 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,30 C150,80 350,0 600,30 C850,60 1050,20 1200,30 C1350,40 1550,0 1800,30 C2050,60 2250,20 2400,30 L2400,100 L0,100 Z" fill="<?php echo esc_attr( $wave_color1 ); ?>"></path>
            </svg>
        </div>
        <div class="footer-wave footer-wave-2">
            <svg viewBox="0 0 2400 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,40 C200,0 400,80 600,40 C800,0 1000,80 1200,40 C1400,0 1600,80 1800,40 C2000,0 2200,80 2400,40 L2400,100 L0,100 Z" fill="<?php echo esc_attr( $wave_color2 ); ?>"></path>
            </svg>
        </div>
        <div class="footer-wave footer-wave-3">
            <svg viewBox="0 0 2400 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,50 C150,100 350,20 600,50 C850,80 1050,30 1200,50 C1350,70 1550,20 1800,50 C2050,80 2250,30 2400,50 L2400,100 L0,100 Z" fill="<?php echo esc_attr( $wave_color3 ); ?>"></path>
            </svg>
        </div>
    </div>
    <?php endif; ?>

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

	<?php
	$show_footer_menu = get_theme_mod( 'show_footer_menu', true );
	if ( $show_footer_menu && has_nav_menu( 'footer-menu' ) ) : ?>
	<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'marsislav' ); ?>">
		<?php wp_nav_menu( array(
			'theme_location' => 'footer-menu',
			'menu_class'     => 'footer-menu',
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
		) ); ?>
	</nav>
	<?php endif; ?>

	<div class="site-info">
		<?php if ( 'two-column' === $footer_layout ) : ?>
			<div class="info-content">	
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
<script>
// Responsive tables
(function() {
	var table = document.querySelector('table');
	if ( ! table ) return;
	var headers = Array.from( table.querySelectorAll('thead th') ).map(function(th) { return th.textContent.trim(); });
	table.querySelectorAll('tbody tr').forEach(function(row) {
		row.querySelectorAll('td').forEach(function(td, i) {
			td.setAttribute('data-label', headers[i] || '');
		});
	});
})();
</script>

<?php wp_footer(); ?>

</body>
</html>
