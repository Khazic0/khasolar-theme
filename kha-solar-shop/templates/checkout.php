<?php
/**
 * Checkout Template
 *
 * Single-page checkout process.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cart = new KhaSolar\Cart();
$cart_items = $cart->get_cart_contents();
$cart_count = $cart->get_cart_count();
$cart_subtotal = $cart->get_cart_subtotal();
$shipping_fee = $cart->get_shipping_fee();
$cart_total = $cart->get_cart_total();

// Redirect to cart if empty.
if ( empty( $cart_items ) ) {
	wp_redirect( home_url( '/gio-hang/' ) );
	exit;
}

get_header();
?>

<div class="kha-checkout-page">
	<div class="container">

		<div class="kha-checkout-header">
			<h1><?php esc_html_e( 'Thanh Toán', 'kha-solar' ); ?></h1>
		</div>

		<form id="kha-checkout-form" class="kha-checkout-form" method="post">

			<div class="kha-checkout-layout">

				<!-- Left Column: Customer Information -->
				<div class="kha-checkout-main">

					<!-- Customer Information -->
					<div class="kha-checkout-section">
						<h2 class="kha-section-title">
							<span class="kha-section-icon">👤</span>
							<?php esc_html_e( 'Thông Tin Khách Hàng', 'kha-solar' ); ?>
						</h2>

						<div class="kha-form-row kha-form-row-2">
							<div class="kha-form-field">
								<label for="customer_name">
									<?php esc_html_e( 'Họ và tên', 'kha-solar' ); ?>
									<span class="kha-required">*</span>
								</label>
								<input
									type="text"
									id="customer_name"
									name="customer_name"
									required
									class="kha-input"
									placeholder="<?php esc_attr_e( 'Nguyễn Văn A', 'kha-solar' ); ?>"
								>
							</div>

							<div class="kha-form-field">
								<label for="customer_phone">
									<?php esc_html_e( 'Số điện thoại', 'kha-solar' ); ?>
									<span class="kha-required">*</span>
								</label>
								<input
									type="tel"
									id="customer_phone"
									name="customer_phone"
									required
									class="kha-input kha-phone-input"
									placeholder="0912-345-678"
									pattern="[0-9]{10,11}"
								>
								<span class="kha-field-hint"><?php esc_html_e( 'Định dạng: 0912-345-678', 'kha-solar' ); ?></span>
							</div>
						</div>

						<div class="kha-form-field">
							<label for="customer_email">
								<?php esc_html_e( 'Email', 'kha-solar' ); ?>
							</label>
							<input
								type="email"
								id="customer_email"
								name="customer_email"
								class="kha-input"
								placeholder="<?php esc_attr_e( 'email@example.com', 'kha-solar' ); ?>"
							>
						</div>
					</div>

					<!-- Shipping Address -->
					<div class="kha-checkout-section">
						<h2 class="kha-section-title">
							<span class="kha-section-icon">📍</span>
							<?php esc_html_e( 'Địa Chỉ Giao Hàng', 'kha-solar' ); ?>
						</h2>

						<div class="kha-form-field">
							<label for="customer_address">
								<?php esc_html_e( 'Địa chỉ', 'kha-solar' ); ?>
								<span class="kha-required">*</span>
							</label>
							<input
								type="text"
								id="customer_address"
								name="customer_address"
								required
								class="kha-input"
								placeholder="<?php esc_attr_e( '123 Nguyễn Huệ', 'kha-solar' ); ?>"
							>
						</div>

						<div class="kha-form-row kha-form-row-3">
							<div class="kha-form-field">
								<label for="customer_province">
									<?php esc_html_e( 'Tỉnh/Thành phố', 'kha-solar' ); ?>
									<span class="kha-required">*</span>
								</label>
								<select
									id="customer_province"
									name="customer_province"
									required
									class="kha-select"
								>
									<option value=""><?php esc_html_e( 'Chọn tỉnh/thành phố', 'kha-solar' ); ?></option>
									<!-- Provinces will be loaded via JavaScript -->
								</select>
							</div>

							<div class="kha-form-field">
								<label for="customer_district">
									<?php esc_html_e( 'Quận/Huyện', 'kha-solar' ); ?>
									<span class="kha-required">*</span>
								</label>
								<select
									id="customer_district"
									name="customer_district"
									required
									class="kha-select"
									disabled
								>
									<option value=""><?php esc_html_e( 'Chọn quận/huyện', 'kha-solar' ); ?></option>
								</select>
							</div>

							<div class="kha-form-field">
								<label for="customer_ward">
									<?php esc_html_e( 'Phường/Xã', 'kha-solar' ); ?>
								</label>
								<select
									id="customer_ward"
									name="customer_ward"
									class="kha-select"
									disabled
								>
									<option value=""><?php esc_html_e( 'Chọn phường/xã', 'kha-solar' ); ?></option>
								</select>
							</div>
						</div>

						<div class="kha-form-field">
							<label for="order_notes">
								<?php esc_html_e( 'Ghi chú đơn hàng', 'kha-solar' ); ?>
							</label>
							<textarea
								id="order_notes"
								name="order_notes"
								class="kha-textarea"
								rows="3"
								placeholder="<?php esc_attr_e( 'Ghi chú về đơn hàng, ví dụ: thời gian giao hàng mong muốn', 'kha-solar' ); ?>"
							></textarea>
						</div>
					</div>

					<!-- Payment Method -->
					<div class="kha-checkout-section">
						<h2 class="kha-section-title">
							<span class="kha-section-icon">💳</span>
							<?php esc_html_e( 'Phương Thức Thanh Toán', 'kha-solar' ); ?>
						</h2>

						<div class="kha-payment-methods">
							<label class="kha-payment-method kha-payment-active">
								<input
									type="radio"
									name="payment_method"
									value="cod"
									checked
									class="kha-radio"
								>
								<div class="kha-payment-info">
									<span class="kha-payment-title">
										<?php esc_html_e( 'Thanh toán khi nhận hàng (COD)', 'kha-solar' ); ?>
									</span>
									<span class="kha-payment-desc">
										<?php esc_html_e( 'Phí COD: 0₫', 'kha-solar' ); ?>
									</span>
								</div>
							</label>
						</div>
					</div>

					<!-- Terms and Conditions -->
					<div class="kha-checkout-section">
						<label class="kha-terms-checkbox">
							<input
								type="checkbox"
								name="terms_accepted"
								id="terms_accepted"
								required
								class="kha-checkbox"
							>
							<span>
								<?php esc_html_e( 'Tôi đồng ý với', 'kha-solar' ); ?>
								<a href="#" target="_blank"><?php esc_html_e( 'điều khoản và điều kiện', 'kha-solar' ); ?></a>
							</span>
						</label>
					</div>

				</div>

				<!-- Right Column: Order Summary -->
				<div class="kha-checkout-sidebar">
					<div class="kha-order-summary">
						<h2 class="kha-summary-title">
							<span class="kha-section-icon">📦</span>
							<?php
							printf(
								/* translators: %d: number of products */
								esc_html__( 'Đơn Hàng Của Bạn (%d sản phẩm)', 'kha-solar' ),
								$cart_count
							);
							?>
						</h2>

						<!-- Order Items -->
						<div class="kha-summary-items">
							<?php foreach ( $cart_items as $item ) : ?>
								<div class="kha-summary-item">
									<div class="kha-summary-item-info">
										<span class="kha-summary-item-name"><?php echo esc_html( $item['title'] ); ?></span>
										<span class="kha-summary-item-qty">× <?php echo esc_html( $item['quantity'] ); ?></span>
									</div>
									<div class="kha-summary-item-price">
										<?php echo kha_solar_format_price( $item['subtotal'] ); ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<!-- Order Totals -->
						<div class="kha-summary-totals">
							<div class="kha-summary-row">
								<span><?php esc_html_e( 'Tạm tính:', 'kha-solar' ); ?></span>
								<span><?php echo kha_solar_format_price( $cart_subtotal ); ?></span>
							</div>

							<div class="kha-summary-row">
								<span><?php esc_html_e( 'Phí vận chuyển:', 'kha-solar' ); ?></span>
								<span>
									<?php if ( $shipping_fee > 0 ) : ?>
										<?php echo kha_solar_format_price( $shipping_fee ); ?>
									<?php else : ?>
										<span class="kha-text-success"><?php esc_html_e( 'Miễn phí', 'kha-solar' ); ?></span>
									<?php endif; ?>
								</span>
							</div>

							<?php if ( $shipping_fee === 0 && $cart_subtotal >= $cart->get_free_shipping_threshold() ) : ?>
								<div class="kha-summary-note">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Đơn hàng > 5.000.000₫', 'kha-solar' ); ?>
								</div>
							<?php endif; ?>

							<div class="kha-summary-divider"></div>

							<div class="kha-summary-row kha-summary-total">
								<span><?php esc_html_e( 'Tổng cộng:', 'kha-solar' ); ?></span>
								<span class="kha-total-amount"><?php echo kha_solar_format_price( $cart_total ); ?></span>
							</div>
						</div>

						<!-- Submit Button -->
						<button type="submit" class="kha-btn kha-btn-checkout" id="kha-place-order">
							<?php esc_html_e( 'Đặt Hàng', 'kha-solar' ); ?>
							<span class="dashicons dashicons-arrow-right-alt2"></span>
						</button>

						<!-- Security Info -->
						<div class="kha-checkout-security">
							<span class="dashicons dashicons-lock"></span>
							<?php esc_html_e( 'Giao dịch an toàn và bảo mật', 'kha-solar' ); ?>
						</div>
					</div>
				</div>

			</div>

			<!-- Hidden Fields -->
			<input type="hidden" name="action" value="kha_process_checkout">
			<?php wp_nonce_field( 'kha_checkout_nonce', 'checkout_nonce' ); ?>

		</form>

	</div>
</div>

<?php get_footer(); ?>
