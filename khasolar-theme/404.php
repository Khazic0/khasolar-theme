<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<section class="error-404 not-found" style="text-align: center; padding: 4rem 0;">

			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! Không tìm thấy trang', 'khasolar-theme' ); ?></h1>
			</header>

			<div class="page-content">
				<p><?php esc_html_e( 'Có vẻ như không có gì ở đây. Thử tìm kiếm?', 'khasolar-theme' ); ?></p>

				<div style="max-width: 600px; margin: 2rem auto;">
					<?php get_search_form(); ?>
				</div>

				<h2><?php esc_html_e( 'Hoặc xem các trang sau:', 'khasolar-theme' ); ?></h2>

				<div style="margin: 2rem 0;">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
						<?php esc_html_e( 'Về trang chủ', 'khasolar-theme' ); ?>
					</a>

					<?php
					$shop_page_id = get_option( 'kha_solar_shop_page_id' );
					if ( $shop_page_id ) :
						$shop_url = get_permalink( $shop_page_id );
						?>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-secondary">
							<?php esc_html_e( 'Xem sản phẩm', 'khasolar-theme' ); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>

		</section>
	</div>
</main>

<?php
get_footer();
