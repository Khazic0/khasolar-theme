<?php
/**
 * Order Confirmation / Thank You Page
 *
 * @package KhaSolar
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get order ID from URL.
$order_id = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;

if ( ! $order_id ) {
	wp_redirect( home_url() );
	exit;
}

// Get order data.
$order_class = new KhaSolar\Order();
$order = $order_class->get_order( $order_id );

if ( ! $order || empty( $order['id'] ) ) {
	wp_redirect( home_url() );
	exit;
}

get_header();
?>

<div class="kha-order-confirmation-page">
	<div class="container">

		<div class="kha-confirmation-content">

			<!-- Success Icon -->
			<div class="kha-success-icon">
				<span class="dashicons dashicons-yes-alt"></span>
			</div>

			<!-- Success Message -->
			<h1 class="kha-confirmation-title">
				<?php esc_html_e( 'Đặt Hàng Thành Công!', 'kha-solar' ); ?>
			</h1>

			<p class="kha-confirmation-message">
				<?php esc_html_e( 'Chúng tôi đã nhận được đơn hàng của bạn và sẽ liên hệ trong thời gian sớm nhất.', 'kha-solar' ); ?>
			</p>

			<!-- Order Details Box -->
			<div class="kha-order-details-box">

				<div class="kha-order-detail-row">
					<span class="kha-detail-label"><?php esc_html_e( 'Mã đơn hàng:', 'kha-solar' ); ?></span>
					<span class="kha-detail-value kha-order-number">#<?php echo esc_html( $order['order_number'] ); ?></span>
				</div>

				<div class="kha-order-detail-row">
					<span class="kha-detail-label"><?php esc_html_e( 'Tổng tiền:', 'kha-solar' ); ?></span>
					<span class="kha-detail-value kha-order-total"><?php echo kha_solar_format_price( $order['order_total'] ); ?></span>
				</div>

				<div class="kha-order-detail-row">
					<span class="kha-detail-label"><?php esc_html_e( 'Phương thức thanh toán:', 'kha-solar' ); ?></span>
					<span class="kha-detail-value"><?php esc_html_e( 'Thanh toán khi nhận hàng (COD)', 'kha-solar' ); ?></span>
				</div>

			</div>

			<!-- Contact Information -->
			<div class="kha-confirmation-contact">
				<div class="kha-contact-item">
					<span class="dashicons dashicons-phone"></span>
					<div class="kha-contact-info">
						<span class="kha-contact-label"><?php esc_html_e( 'Hotline:', 'kha-solar' ); ?></span>
						<span class="kha-contact-value">1900-xxxx</span>
					</div>
				</div>

				<div class="kha-contact-item">
					<span class="dashicons dashicons-email"></span>
					<div class="kha-contact-info">
						<span class="kha-contact-label"><?php esc_html_e( 'Email:', 'kha-solar' ); ?></span>
						<span class="kha-contact-value">support@khasolar.vn</span>
					</div>
				</div>
			</div>

			<!-- Order Items Summary -->
			<div class="kha-order-items-summary">
				<h3><?php esc_html_e( 'Sản phẩm đã đặt:', 'kha-solar' ); ?></h3>
				<div class="kha-summary-items-list">
					<?php if ( ! empty( $order['order_items'] ) ) : ?>
						<?php foreach ( $order['order_items'] as $item ) : ?>
							<div class="kha-summary-item-row">
								<span class="kha-item-name">
									<?php echo esc_html( $item['title'] ); ?>
									<span class="kha-item-qty">× <?php echo esc_html( $item['quantity'] ); ?></span>
								</span>
								<span class="kha-item-price"><?php echo kha_solar_format_price( $item['subtotal'] ); ?></span>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>

			<!-- Action Buttons -->
			<div class="kha-confirmation-actions">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'kha_product' ) ); ?>" class="kha-btn kha-btn-primary">
					<?php esc_html_e( 'Tiếp tục mua', 'kha-solar' ); ?>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</a>
				<a href="<?php echo esc_url( home_url() ); ?>" class="kha-btn kha-btn-secondary">
					<span class="dashicons dashicons-admin-home"></span>
					<?php esc_html_e( 'Về trang chủ', 'kha-solar' ); ?>
				</a>
			</div>

			<!-- Additional Info -->
			<div class="kha-confirmation-note">
				<span class="dashicons dashicons-info"></span>
				<p><?php esc_html_e( 'Nhân viên sẽ liên hệ với bạn trong vòng 24 giờ để xác nhận đơn hàng.', 'kha-solar' ); ?></p>
			</div>

		</div>

	</div>
</div>

<?php get_footer(); ?>
