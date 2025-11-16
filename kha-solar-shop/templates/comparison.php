<?php
/**
 * Product Comparison Template
 *
 * @package KhaSolar
 * @since   1.0.0
 */

// Get product IDs from URL or localStorage (handled by JS)
$product_ids = isset( $_GET['products'] ) ? explode( ',', sanitize_text_field( $_GET['products'] ) ) : array();

get_header();
?>

<div class="kha-comparison-page">
	<div class="container">
		<div class="kha-comparison-header">
			<h1><?php esc_html_e( 'So Sánh Sản Phẩm', 'kha-solar' ); ?></h1>
			<div class="kha-comparison-actions">
				<button class="kha-comparison-print" onclick="window.print()">
					<span class="dashicons dashicons-printer"></span>
					<?php esc_html_e( 'In So Sánh', 'kha-solar' ); ?>
				</button>
				<button class="kha-comparison-share">
					<span class="dashicons dashicons-share"></span>
					<?php esc_html_e( 'Chia Sẻ', 'kha-solar' ); ?>
				</button>
				<button class="kha-comparison-clear-all">
					<span class="dashicons dashicons-trash"></span>
					<?php esc_html_e( 'Xóa Tất Cả', 'kha-solar' ); ?>
				</button>
			</div>
		</div>

		<div id="kha-comparison-content" class="kha-comparison-content">
			<!-- Content loaded via AJAX or from initial load -->
			<div class="kha-comparison-empty">
				<div class="kha-comparison-empty-icon">📊</div>
				<h2><?php esc_html_e( 'Bạn chưa chọn sản phẩm nào để so sánh', 'kha-solar' ); ?></h2>
				<p><?php esc_html_e( 'Hãy chọn từ danh sách sản phẩm để bắt đầu so sánh.', 'kha-solar' ); ?></p>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'kha_product' ) ); ?>" class="kha-back-to-shop">
					<?php esc_html_e( 'Về Trang Sản Phẩm', 'kha-solar' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>

<!-- Share Modal -->
<div id="kha-share-modal" class="kha-modal" style="display: none;">
	<div class="kha-modal-overlay"></div>
	<div class="kha-modal-content">
		<div class="kha-modal-header">
			<h3><?php esc_html_e( 'Chia Sẻ So Sánh', 'kha-solar' ); ?></h3>
			<button class="kha-modal-close">×</button>
		</div>
		<div class="kha-modal-body">
			<p><?php esc_html_e( 'Sao chép liên kết này để chia sẻ:', 'kha-solar' ); ?></p>
			<div class="kha-share-url-wrapper">
				<input type="text" id="kha-share-url" readonly>
				<button class="kha-copy-url">
					<span class="dashicons dashicons-clipboard"></span>
					<?php esc_html_e( 'Sao Chép', 'kha-solar' ); ?>
				</button>
			</div>
			<div class="kha-share-socials">
				<button class="kha-share-facebook">
					<span class="dashicons dashicons-facebook"></span>
					Facebook
				</button>
				<button class="kha-share-zalo">
					📱 Zalo
				</button>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
