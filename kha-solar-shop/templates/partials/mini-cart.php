<?php
/**
 * Mini Cart Template
 *
 * Slide-out cart panel for header.
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
$cart_total = $cart->get_cart_total();
$cart_count = $cart->get_cart_count();
?>

<!-- Mini Cart Trigger -->
<div class="kha-mini-cart-trigger">
	<button class="kha-cart-icon" id="kha-open-mini-cart">
		<span class="dashicons dashicons-cart"></span>
		<?php if ( $cart_count > 0 ) : ?>
			<span class="kha-cart-badge" id="kha-cart-badge"><?php echo esc_html( $cart_count ); ?></span>
		<?php endif; ?>
	</button>
</div>

<!-- Mini Cart Panel -->
<div class="kha-mini-cart-panel" id="kha-mini-cart-panel">

	<!-- Overlay -->
	<div class="kha-mini-cart-overlay" id="kha-close-mini-cart"></div>

	<!-- Content -->
	<div class="kha-mini-cart-content">

		<!-- Header -->
		<div class="kha-mini-cart-header">
			<h3>
				<?php esc_html_e( 'Giỏ Hàng', 'kha-solar' ); ?>
				<span class="kha-mini-cart-count">(<span id="kha-mini-cart-count"><?php echo esc_html( $cart_count ); ?></span>)</span>
			</h3>
			<button class="kha-mini-cart-close" id="kha-close-mini-cart-btn">
				<span class="dashicons dashicons-no-alt"></span>
			</button>
		</div>

		<!-- Items -->
		<div class="kha-mini-cart-items" id="kha-mini-cart-items">
			<?php if ( ! empty( $cart_items ) ) : ?>
				<?php foreach ( $cart_items as $cart_key => $item ) : ?>
					<div class="kha-mini-cart-item" data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>">

						<!-- Image -->
						<div class="kha-mini-item-image">
							<?php if ( $item['thumbnail'] ) : ?>
								<img src="<?php echo esc_url( $item['thumbnail'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
							<?php else : ?>
								<div class="kha-no-image"><span class="dashicons dashicons-camera"></span></div>
							<?php endif; ?>
						</div>

						<!-- Info -->
						<div class="kha-mini-item-info">
							<h4 class="kha-mini-item-title"><?php echo esc_html( $item['title'] ); ?></h4>
							<div class="kha-mini-item-meta">
								<?php echo esc_html( $item['quantity'] ); ?> × <?php echo kha_solar_format_price( $item['price'] ); ?>
							</div>
							<div class="kha-mini-item-subtotal">
								= <?php echo kha_solar_format_price( $item['subtotal'] ); ?>
							</div>
						</div>

						<!-- Remove -->
						<button class="kha-mini-item-remove" data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>" title="<?php esc_attr_e( 'Xóa', 'kha-solar' ); ?>">
							<span class="dashicons dashicons-trash"></span>
						</button>

					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="kha-mini-cart-empty">
					<div class="kha-empty-icon">🛒</div>
					<p><?php esc_html_e( 'Giỏ hàng trống', 'kha-solar' ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<!-- Footer -->
		<?php if ( ! empty( $cart_items ) ) : ?>
			<div class="kha-mini-cart-footer">

				<!-- Total -->
				<div class="kha-mini-cart-total">
					<span><?php esc_html_e( 'Tổng:', 'kha-solar' ); ?></span>
					<span id="kha-mini-cart-total" class="kha-total-amount"><?php echo kha_solar_format_price( $cart_total ); ?></span>
				</div>

				<!-- Actions -->
				<div class="kha-mini-cart-actions">
					<a href="<?php echo esc_url( home_url( '/gio-hang/' ) ); ?>" class="kha-btn kha-btn-secondary kha-btn-block">
						<?php esc_html_e( 'Xem giỏ hàng', 'kha-solar' ); ?>
					</a>
					<a href="#" class="kha-btn kha-btn-primary kha-btn-block">
						<?php esc_html_e( 'Thanh toán', 'kha-solar' ); ?>
					</a>
				</div>

			</div>
		<?php endif; ?>

	</div>

</div>
