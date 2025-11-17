<?php
/**
 * The header template file
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<header id="masthead" class="site-header">
		<div class="container">
			<div class="header-content">

				<div class="site-branding">
					<?php
					// Display custom logo or site title.
					if ( has_custom_logo() ) :
						the_custom_logo();
					else :
						?>
						<div class="site-branding-text">
							<?php if ( is_front_page() && is_home() ) : ?>
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php else : ?>
								<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php endif; ?>

							<?php
							$description = get_bloginfo( 'description', 'display' );
							if ( $description || is_customize_preview() ) :
								?>
								<p class="site-description"><?php echo esc_html( $description ); ?></p>
							<?php endif; ?>
						</div>
						<?php
					endif;
					?>
				</div>

				<nav id="site-navigation" class="main-navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span class="dashicons dashicons-menu"></span>
						<?php esc_html_e( 'Menu', 'khasolar-theme' ); ?>
					</button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
							'fallback_cb'    => 'khasolar_default_menu',
						)
					);
					?>

					<?php
					// Display mini cart if function exists (from Kha Solar Shop plugin).
					if ( function_exists( 'kha_mini_cart' ) ) :
						kha_mini_cart();
					endif;
					?>
				</nav>

			</div>
		</div>
	</header>

	<div id="content" class="site-content">
<?php
/**
 * Default menu fallback.
 */
function khasolar_default_menu() {
	echo '<ul id="primary-menu" class="menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Trang chủ', 'khasolar-theme' ) . '</a></li>';

	// Add shop page if it exists.
	$shop_page_id = get_option( 'kha_solar_shop_page_id' );
	if ( $shop_page_id ) {
		$shop_url = get_permalink( $shop_page_id );
		echo '<li><a href="' . esc_url( $shop_url ) . '">' . esc_html__( 'Sản phẩm', 'khasolar-theme' ) . '</a></li>';
	}

	// Add calculator page if it exists.
	$calc_page_id = get_option( 'kha_solar_calculator_page_id' );
	if ( $calc_page_id ) {
		$calc_url = get_permalink( $calc_page_id );
		echo '<li><a href="' . esc_url( $calc_url ) . '">' . esc_html__( 'Tính toán Solar', 'khasolar-theme' ) . '</a></li>';
	}

	// Add comparison page if it exists.
	$compare_page_id = get_option( 'kha_solar_comparison_page_id' );
	if ( $compare_page_id ) {
		$compare_url = get_permalink( $compare_page_id );
		echo '<li><a href="' . esc_url( $compare_url ) . '">' . esc_html__( 'So sánh', 'khasolar-theme' ) . '</a></li>';
	}

	echo '<li><a href="' . esc_url( home_url( '/blog' ) ) . '">' . esc_html__( 'Blog', 'khasolar-theme' ) . '</a></li>';
	echo '<li><a href="' . esc_url( home_url( '/lien-he' ) ) . '">' . esc_html__( 'Liên hệ', 'khasolar-theme' ) . '</a></li>';
	echo '</ul>';
}
