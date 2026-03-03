<?php
/**
 * The template for displaying the footer
 *
 * @package marsislav
 */
$footer_layout = get_theme_mod( 'footer_layout', 'one-column' );
$copyright_text = get_theme_mod( 'footer_copyright_text', sprintf( '&copy; %s %s. All rights reserved.', date('Y'), get_bloginfo('name') ) );
$footer_col2_text = get_theme_mod( 'footer_col2_text', '' );
?>

<footer id="colophon" class="site-footer footer-<?php echo esc_attr( $footer_layout ); ?>">
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
