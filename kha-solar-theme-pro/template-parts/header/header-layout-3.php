<?php
/**
 * Header Layout 3 - Split (Logo Left, Menu Right)
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>
<header id="masthead" class="site-header header-layout-3">
	<!-- Main Header - Split Layout -->
	<div class="header-main">
		<div class="container-fluid">
			<div class="header-content split-layout">
				<!-- Left Side: Logo + Contact -->
				<div class="header-left">
					<div class="site-branding">
						<?php khasolar_pro_site_branding(); ?>
					</div>
					<?php khasolar_pro_contact_info(); ?>
				</div>

				<!-- Right Side: Navigation + Actions -->
				<div class="header-right">
					<nav id="site-navigation" class="main-navigation">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
							<span class="hamburger">
								<span></span>
								<span></span>
								<span></span>
							</span>
							<span class="menu-text"><?php esc_html_e( 'Menu', 'kha-solar-theme-pro' ); ?></span>
						</button>

						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'primary-menu',
								'container'      => false,
							)
						);
						?>
					</nav>

					<div class="header-actions">
						<?php
						if ( khasolar_pro_get_option( 'enable_search_icon', true ) ) {
							khasolar_pro_search_icon();
						}

						if ( khasolar_pro_get_option( 'enable_mini_cart', true ) ) {
							khasolar_pro_mini_cart();
						}
						?>

						<?php khasolar_pro_social_links(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
