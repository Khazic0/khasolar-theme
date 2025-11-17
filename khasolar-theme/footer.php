<?php
/**
 * The footer template file
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container">

			<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
				<div class="footer-widgets">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<div class="footer-widget-area">
							<?php dynamic_sidebar( 'footer-1' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<div class="footer-widget-area">
							<?php dynamic_sidebar( 'footer-2' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<div class="footer-widget-area">
							<?php dynamic_sidebar( 'footer-3' ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="site-info">
				<p>
					&copy; <?php echo esc_html( date( 'Y' ) ); ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
					<?php esc_html_e( '- Giải pháp năng lượng mặt trời toàn diện', 'khasolar-theme' ); ?>
				</p>
				<p>
					<?php
					printf(
						/* translators: %s: WordPress link */
						esc_html__( 'Powered by %s', 'khasolar-theme' ),
						'<a href="' . esc_url( __( 'https://wordpress.org/', 'khasolar-theme' ) ) . '">WordPress</a>'
					);
					?>
					|
					<?php
					printf(
						/* translators: %s: Theme name */
						esc_html__( 'Theme: %s', 'khasolar-theme' ),
						'Kha Solar Theme'
					);
					?>
				</p>
			</div>

		</div>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
