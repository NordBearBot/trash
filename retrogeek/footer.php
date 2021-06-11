<?php
/**
 * The template for displaying the footer.
 *
 * Description: The area of the page that contains the widgetized footer.
 *
 * @package retrogeek
 * @since 22.02.2021
 */

?>

</div><!-- .site-content -->

<footer id="colophon" class="site-footer" >
	<div class="row">
		<div class="two columns">&nbsp;</div>
		<div class="eight columns">
			<div class="footer-widgets">
			<?php if ( is_active_sidebar( 'rg_footer_left' ) ) : ?>
				<div id="footer-widgets_left" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'rg_footer_left' ); ?>
				</div><!-- #primary-sidebar -->
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'rg_footer_middle' ) ) : ?>
				<div id="footer-widgets_middle" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'rg_footer_middle' ); ?>
				</div><!-- #primary-sidebar -->
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'rg_footer_right' ) ) : ?>
				<div id="footer-widgets_right" class="widget-area" role="complementary">
					<?php dynamic_sidebar( 'rg_footer_right' ); ?>
				</div><!-- #primary-sidebar -->
			<?php endif; ?>
			<div class="u-cf">&nbsp;</div>
			</div>

			<div class="site-info">

			<?php
			if ( function_exists( 'the_privacy_policy_link' ) ) {
				the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
			}
			?>
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'retrogeek' ) ); ?>" class="imprint">
				<?php
				/* translators: %s: WordPress */
				printf( esc_attr__( 'Proudly powered by %s and RetroGeek-Theme', 'retrogeek' ), 'WordPress' );
				?>
			</a>
			</div><!-- .site-info -->
		</div><!-- eight columns -->
	</div><!-- row -->
</footer><!-- .site-footer -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>
