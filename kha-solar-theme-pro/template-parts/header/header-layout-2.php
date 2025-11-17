<?php
/**
 * Header Layout 2 - Centered
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>
<header id="masthead" class="site-header header-layout-2">
	<!-- Top Bar -->
	<div class="header-top-bar">
		<div class="container">
			<div class="top-bar-content">
				<div class="top-bar-left">
					<?php khasolar_pro_contact_info(); ?>
				</div>
				<div class="top-bar-right">
					<?php khasolar_pro_social_links(); ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Main Header - Centered Logo -->
	<div class="header-main">
		<div class="container">
			<div class="header-content centered">
				<div class="site-branding centered-logo">
					<?php khasolar_pro_site_branding(); ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Navigation Bar -->
	<div class="header-navigation">
		<div class="container">
			<div class="navigation-wrapper">
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
							'menu_class'     => 'primary-menu centered-menu',
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
				</div>
			</div>
		</div>
	</div>
</header>
