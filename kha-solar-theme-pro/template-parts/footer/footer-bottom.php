<?php
/**
 * Footer Bottom Area (Copyright, Payment Icons)
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>

<div class="footer-bottom">
	<div class="container">
		<div class="footer-bottom-content">
			<div class="footer-copyright">
				<?php khasolar_pro_copyright(); ?>
			</div>

			<?php if ( khasolar_pro_get_option( 'show_payment_icons', true ) ) : ?>
				<div class="footer-payment">
					<?php khasolar_pro_payment_icons(); ?>
				</div>
			<?php endif; ?>

			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'footer-menu',
						'container'      => 'nav',
						'container_class' => 'footer-navigation',
						'depth'          => 1,
					)
				);
			}
			?>
		</div>
	</div>
</div>
