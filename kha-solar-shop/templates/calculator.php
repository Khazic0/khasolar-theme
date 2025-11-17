<?php
/**
 * Solar System Calculator Template
 *
 * Interactive calculator to help customers determine their solar system needs.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get URL parameters for shareable links.
$default_bill    = isset( $_GET['bill'] ) ? absint( $_GET['bill'] ) : 2000000;
$default_area    = isset( $_GET['area'] ) ? absint( $_GET['area'] ) : 50;
$default_battery = isset( $_GET['battery'] ) && $_GET['battery'] === 'yes' ? 'yes' : 'no';

get_header();
?>

<div class="kha-calculator-page">
	<div class="container">

		<!-- Calculator Header -->
		<div class="kha-calculator-header">
			<h1><?php esc_html_e( 'Tính Toán Hệ Thống Điện Mặt Trời', 'kha-solar' ); ?></h1>
			<p class="kha-calculator-subtitle">
				<?php esc_html_e( 'Nhập thông tin hóa đơn điện và diện tích mái để nhận tư vấn hệ thống phù hợp', 'kha-solar' ); ?>
			</p>
		</div>

		<!-- Calculator Form -->
		<div class="kha-calculator-wrapper">

			<!-- STEP 1: Input Form -->
			<div class="kha-calculator-step kha-step-active" id="kha-calculator-step-1">
				<div class="kha-step-header">
					<span class="kha-step-number">1</span>
					<h2><?php esc_html_e( 'Nhập Thông Tin', 'kha-solar' ); ?></h2>
				</div>

				<div class="kha-calculator-form">

					<!-- Monthly Bill Slider -->
					<div class="kha-form-group">
						<label for="monthly_bill">
							<?php esc_html_e( 'Hóa đơn điện trung bình hàng tháng', 'kha-solar' ); ?>
						</label>
						<div class="kha-slider-wrapper">
							<input
								type="range"
								id="monthly_bill"
								name="monthly_bill"
								class="kha-range-slider"
								min="500000"
								max="10000000"
								step="100000"
								value="<?php echo esc_attr( $default_bill ); ?>"
							>
							<div class="kha-slider-value">
								<span id="monthly_bill_display"><?php echo kha_solar_format_price( $default_bill ); ?></span>
							</div>
						</div>
						<div class="kha-slider-labels">
							<span>500.000₫</span>
							<span>10.000.000₫</span>
						</div>
					</div>

					<!-- Roof Area Slider -->
					<div class="kha-form-group">
						<label for="roof_area">
							<?php esc_html_e( 'Diện tích mái nhà khả dụng (m²)', 'kha-solar' ); ?>
						</label>
						<div class="kha-slider-wrapper">
							<input
								type="range"
								id="roof_area"
								name="roof_area"
								class="kha-range-slider"
								min="10"
								max="200"
								step="5"
								value="<?php echo esc_attr( $default_area ); ?>"
							>
							<div class="kha-slider-value">
								<span id="roof_area_display"><?php echo esc_html( $default_area ); ?> m²</span>
							</div>
						</div>
						<div class="kha-slider-labels">
							<span>10 m²</span>
							<span>200 m²</span>
						</div>
					</div>

					<!-- Battery Option -->
					<div class="kha-form-group">
						<label class="kha-battery-option">
							<input
								type="checkbox"
								id="with_battery"
								name="with_battery"
								value="yes"
								class="kha-checkbox"
								<?php checked( $default_battery, 'yes' ); ?>
							>
							<span class="kha-battery-label">
								<span class="kha-battery-icon">🔋</span>
								<span class="kha-battery-text">
									<strong><?php esc_html_e( 'Kèm theo bộ lưu trữ pin', 'kha-solar' ); ?></strong>
									<small><?php esc_html_e( 'Sử dụng điện khi mất lưới điện (chi phí tăng ~50%)', 'kha-solar' ); ?></small>
								</span>
							</span>
						</label>
					</div>

					<!-- Calculate Button -->
					<div class="kha-form-actions">
						<button type="button" class="kha-btn kha-btn-primary kha-btn-large" id="kha-calculate-btn">
							<span class="dashicons dashicons-chart-line"></span>
							<?php esc_html_e( 'Tính Toán Ngay', 'kha-solar' ); ?>
						</button>
					</div>

				</div>
			</div>

			<!-- STEP 2: Results Display -->
			<div class="kha-calculator-step" id="kha-calculator-step-2">
				<div class="kha-step-header">
					<span class="kha-step-number">2</span>
					<h2><?php esc_html_e( 'Kết Quả Tính Toán', 'kha-solar' ); ?></h2>
				</div>

				<div class="kha-results-container">

					<!-- Consumption Stats -->
					<div class="kha-results-section">
						<h3><?php esc_html_e( 'Mức Tiêu Thụ Điện Năng', 'kha-solar' ); ?></h3>
						<div class="kha-results-grid kha-grid-2">
							<div class="kha-result-card">
								<span class="kha-result-icon">⚡</span>
								<div class="kha-result-content">
									<span class="kha-result-label"><?php esc_html_e( 'Hàng tháng', 'kha-solar' ); ?></span>
									<span class="kha-result-value" id="result_consumption_monthly">0</span>
									<span class="kha-result-unit">kWh</span>
								</div>
							</div>
							<div class="kha-result-card">
								<span class="kha-result-icon">☀️</span>
								<div class="kha-result-content">
									<span class="kha-result-label"><?php esc_html_e( 'Hàng ngày', 'kha-solar' ); ?></span>
									<span class="kha-result-value" id="result_consumption_daily">0</span>
									<span class="kha-result-unit">kWh</span>
								</div>
							</div>
						</div>
					</div>

					<!-- System Specifications -->
					<div class="kha-results-section">
						<h3><?php esc_html_e( 'Thông Số Hệ Thống Đề Xuất', 'kha-solar' ); ?></h3>
						<div class="kha-results-grid kha-grid-3">
							<div class="kha-result-card">
								<span class="kha-result-icon">🔌</span>
								<div class="kha-result-content">
									<span class="kha-result-label"><?php esc_html_e( 'Công suất', 'kha-solar' ); ?></span>
									<span class="kha-result-value" id="result_system_kw">0</span>
									<span class="kha-result-unit">kW</span>
								</div>
							</div>
							<div class="kha-result-card">
								<span class="kha-result-icon">📐</span>
								<div class="kha-result-content">
									<span class="kha-result-label"><?php esc_html_e( 'Diện tích cần', 'kha-solar' ); ?></span>
									<span class="kha-result-value" id="result_panel_area">0</span>
									<span class="kha-result-unit">m²</span>
								</div>
							</div>
							<div class="kha-result-card">
								<span class="kha-result-icon">💰</span>
								<div class="kha-result-content">
									<span class="kha-result-label"><?php esc_html_e( 'Đầu tư ước tính', 'kha-solar' ); ?></span>
									<span class="kha-result-value" id="result_investment">0</span>
									<span class="kha-result-unit">₫</span>
								</div>
							</div>
						</div>
						<!-- Area Warning -->
						<div class="kha-area-warning" id="area_warning" style="display: none;">
							<span class="dashicons dashicons-warning"></span>
							<span><?php esc_html_e( 'Diện tích mái không đủ. Hệ thống đã được điều chỉnh theo diện tích khả dụng.', 'kha-solar' ); ?></span>
						</div>
					</div>

					<!-- Financial Benefits -->
					<div class="kha-results-section kha-highlight-section">
						<h3><?php esc_html_e( 'Lợi Ích Tài Chính', 'kha-solar' ); ?></h3>
						<div class="kha-results-grid kha-grid-2">
							<div class="kha-result-card kha-card-highlight">
								<span class="kha-result-icon">💵</span>
								<div class="kha-result-content">
									<span class="kha-result-label"><?php esc_html_e( 'Tiết kiệm hàng tháng', 'kha-solar' ); ?></span>
									<span class="kha-result-value kha-value-large" id="result_monthly_savings">0</span>
									<span class="kha-result-unit">₫/tháng</span>
								</div>
							</div>
							<div class="kha-result-card kha-card-highlight">
								<span class="kha-result-icon">📈</span>
								<div class="kha-result-content">
									<span class="kha-result-label"><?php esc_html_e( 'Hoàn vốn sau', 'kha-solar' ); ?></span>
									<span class="kha-result-value kha-value-large" id="result_roi_years">0</span>
									<span class="kha-result-unit"><?php esc_html_e( 'năm', 'kha-solar' ); ?></span>
								</div>
							</div>
						</div>
						<div class="kha-lifetime-savings">
							<div class="kha-savings-info">
								<span class="kha-savings-label"><?php esc_html_e( 'Tổng tiết kiệm trong 25 năm:', 'kha-solar' ); ?></span>
								<span class="kha-savings-value" id="result_lifetime_savings">0₫</span>
							</div>
						</div>
					</div>

					<!-- Action Buttons -->
					<div class="kha-results-actions">
						<button type="button" class="kha-btn kha-btn-primary kha-btn-large" id="kha-view-products-btn">
							<span class="dashicons dashicons-cart"></span>
							<?php esc_html_e( 'Xem Sản Phẩm Đề Xuất', 'kha-solar' ); ?>
						</button>
						<button type="button" class="kha-btn kha-btn-secondary" id="kha-recalculate-btn">
							<span class="dashicons dashicons-update"></span>
							<?php esc_html_e( 'Tính Lại', 'kha-solar' ); ?>
						</button>
						<button type="button" class="kha-btn kha-btn-outline" id="kha-share-results-btn">
							<span class="dashicons dashicons-share"></span>
							<?php esc_html_e( 'Chia Sẻ', 'kha-solar' ); ?>
						</button>
					</div>

				</div>
			</div>

			<!-- STEP 3: Product Recommendations -->
			<div class="kha-calculator-step" id="kha-calculator-step-3">
				<div class="kha-step-header">
					<span class="kha-step-number">3</span>
					<h2><?php esc_html_e( 'Sản Phẩm Đề Xuất', 'kha-solar' ); ?></h2>
				</div>

				<div class="kha-products-container">
					<div id="kha-recommended-products">
						<!-- Products will be loaded via AJAX -->
					</div>

					<!-- Bundle Pricing -->
					<div class="kha-bundle-pricing" id="kha-bundle-pricing" style="display: none;">
						<div class="kha-bundle-header">
							<h3><?php esc_html_e( 'Gói Giải Pháp Trọn Bộ', 'kha-solar' ); ?></h3>
							<span class="kha-bundle-badge"><?php esc_html_e( 'Tiết kiệm 10%', 'kha-solar' ); ?></span>
						</div>
						<div class="kha-bundle-details">
							<div class="kha-bundle-row">
								<span><?php esc_html_e( 'Tổng giá sản phẩm:', 'kha-solar' ); ?></span>
								<span id="bundle_total_price">0₫</span>
							</div>
							<div class="kha-bundle-row kha-discount-row">
								<span><?php esc_html_e( 'Giảm giá (10%):', 'kha-solar' ); ?></span>
								<span id="bundle_discount_amount">-0₫</span>
							</div>
							<div class="kha-bundle-divider"></div>
							<div class="kha-bundle-row kha-bundle-total">
								<span><?php esc_html_e( 'Giá gói trọn bộ:', 'kha-solar' ); ?></span>
								<span id="bundle_final_price">0₫</span>
							</div>
						</div>
						<div class="kha-bundle-actions">
							<button type="button" class="kha-btn kha-btn-success kha-btn-large" id="kha-add-bundle-to-cart">
								<span class="dashicons dashicons-cart"></span>
								<?php esc_html_e( 'Thêm Gói Vào Giỏ Hàng', 'kha-solar' ); ?>
							</button>
						</div>
					</div>

					<!-- Contact CTA -->
					<div class="kha-contact-cta">
						<button type="button" class="kha-btn kha-btn-outline kha-btn-large" id="kha-request-consultation-btn">
							<span class="dashicons dashicons-phone"></span>
							<?php esc_html_e( 'Yêu Cầu Tư Vấn', 'kha-solar' ); ?>
						</button>
					</div>

				</div>
			</div>

		</div>

		<!-- Lead Capture Modal -->
		<div class="kha-modal" id="kha-consultation-modal">
			<div class="kha-modal-overlay"></div>
			<div class="kha-modal-content">
				<button type="button" class="kha-modal-close" id="kha-close-consultation-modal">
					<span class="dashicons dashicons-no-alt"></span>
				</button>

				<div class="kha-modal-header">
					<h2><?php esc_html_e( 'Yêu Cầu Tư Vấn', 'kha-solar' ); ?></h2>
					<p><?php esc_html_e( 'Để lại thông tin, chúng tôi sẽ liên hệ tư vấn chi tiết trong 24 giờ', 'kha-solar' ); ?></p>
				</div>

				<form id="kha-lead-form" class="kha-lead-form">

					<div class="kha-form-field">
						<label for="lead_name">
							<?php esc_html_e( 'Họ và tên', 'kha-solar' ); ?>
							<span class="kha-required">*</span>
						</label>
						<input
							type="text"
							id="lead_name"
							name="lead_name"
							class="kha-input"
							required
							placeholder="<?php esc_attr_e( 'Nguyễn Văn A', 'kha-solar' ); ?>"
						>
					</div>

					<div class="kha-form-field">
						<label for="lead_phone">
							<?php esc_html_e( 'Số điện thoại', 'kha-solar' ); ?>
							<span class="kha-required">*</span>
						</label>
						<input
							type="tel"
							id="lead_phone"
							name="lead_phone"
							class="kha-input"
							required
							pattern="[0-9]{10,11}"
							placeholder="0912-345-678"
						>
					</div>

					<div class="kha-form-field">
						<label for="lead_email">
							<?php esc_html_e( 'Email', 'kha-solar' ); ?>
						</label>
						<input
							type="email"
							id="lead_email"
							name="lead_email"
							class="kha-input"
							placeholder="<?php esc_attr_e( 'email@example.com', 'kha-solar' ); ?>"
						>
					</div>

					<div class="kha-form-actions">
						<button type="submit" class="kha-btn kha-btn-primary kha-btn-block">
							<?php esc_html_e( 'Gửi Yêu Cầu', 'kha-solar' ); ?>
							<span class="dashicons dashicons-arrow-right-alt2"></span>
						</button>
					</div>

					<!-- Hidden fields for calculation data -->
					<input type="hidden" name="calc_monthly_bill" id="calc_monthly_bill">
					<input type="hidden" name="calc_roof_area" id="calc_roof_area">
					<input type="hidden" name="calc_with_battery" id="calc_with_battery">
					<input type="hidden" name="calc_system_kw" id="calc_system_kw">

					<?php wp_nonce_field( 'kha_calculator_nonce', 'calculator_nonce' ); ?>

				</form>

			</div>
		</div>

	</div>
</div>

<?php get_footer(); ?>
