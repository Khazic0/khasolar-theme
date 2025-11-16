<?php
/**
 * Shopping Cart Template
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
$cart_subtotal = $cart->get_cart_subtotal();
$shipping_fee = $cart->get_shipping_fee();
$cart_total = $cart->get_cart_total();
$cart_count = $cart->get_cart_count();
$remaining_for_free_shipping = $cart->get_remaining_for_free_shipping();

get_header();
?>

<div class="kha-cart-page">
	<div class="container">

		<?php if ( ! empty( $cart_items ) ) : ?>

			<!-- Cart Header -->
			<div class="kha-cart-header">
				<h1><?php esc_html_e( 'Giỏ Hàng Của Bạn', 'kha-solar' ); ?> (<?php echo esc_html( $cart_count ); ?> <?php esc_html_e( 'sản phẩm', 'kha-solar' ); ?>)</h1>
			</div>

			<!-- Free Shipping Progress -->
			<?php if ( $remaining_for_free_shipping > 0 ) : ?>
				<div class="kha-shipping-notice">
					<span class="dashicons dashicons-info"></span>
					<?php
					printf(
						/* translators: %s: remaining amount for free shipping */
						esc_html__( 'Mua thêm %s để được miễn phí vận chuyển!', 'kha-solar' ),
						'<strong>' . kha_solar_format_price( $remaining_for_free_shipping ) . '</strong>'
					);
					?>
					<div class="kha-shipping-progress">
						<?php
						$progress = ( $cart_subtotal / $cart->get_free_shipping_threshold() ) * 100;
						$progress = min( $progress, 100 );
						?>
						<div class="kha-progress-bar" style="width: <?php echo esc_attr( $progress ); ?>%"></div>
					</div>
				</div>
			<?php else : ?>
				<div class="kha-shipping-notice kha-free-shipping">
					<span class="dashicons dashicons-yes-alt"></span>
					<?php esc_html_e( 'Bạn đã đủ điều kiện để được miễn phí vận chuyển!', 'kha-solar' ); ?>
				</div>
			<?php endif; ?>

			<div class="kha-cart-layout">

				<!-- Cart Items -->
				<div class="kha-cart-items-wrapper">
					<?php foreach ( $cart_items as $cart_key => $item ) : ?>
						<div class="kha-cart-item" data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>">

							<!-- Product Image -->
							<div class="kha-cart-item-image">
								<?php if ( $item['thumbnail'] ) : ?>
									<img src="<?php echo esc_url( $item['thumbnail'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
								<?php else : ?>
									<div class="kha-no-image"><span class="dashicons dashicons-camera"></span></div>
								<?php endif; ?>
							</div>

							<!-- Product Info -->
							<div class="kha-cart-item-info">
								<h3 class="kha-cart-item-title">
									<a href="<?php echo esc_url( $item['permalink'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
								</h3>

								<?php if ( $item['is_bundle'] ) : ?>
									<span class="kha-bundle-badge"><?php esc_html_e( 'Combo', 'kha-solar' ); ?></span>
								<?php endif; ?>

								<div class="kha-cart-item-price">
									<span class="kha-price-label"><?php esc_html_e( 'Đơn giá:', 'kha-solar' ); ?></span>
									<span class="kha-price-value"><?php echo kha_solar_format_price( $item['price'] ); ?></span>
								</div>

								<!-- Stock Status -->
								<?php if ( 'outofstock' === $item['stock_status'] ) : ?>
									<div class="kha-stock-warning">
										<span class="dashicons dashicons-warning"></span>
										<?php esc_html_e( 'Sản phẩm đã hết hàng', 'kha-solar' ); ?>
									</div>
								<?php elseif ( 'onbackorder' === $item['stock_status'] ) : ?>
									<div class="kha-stock-info">
										<span class="dashicons dashicons-info"></span>
										<?php esc_html_e( 'Đặt trước (giao sau)', 'kha-solar' ); ?>
									</div>
								<?php endif; ?>
							</div>

							<!-- Quantity Controls -->
							<div class="kha-cart-item-quantity">
								<button class="kha-qty-btn kha-qty-decrease" data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>">
									<span class="dashicons dashicons-minus"></span>
								</button>
								<input
									type="number"
									class="kha-qty-input"
									value="<?php echo esc_attr( $item['quantity'] ); ?>"
									min="1"
									data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>"
								>
								<button class="kha-qty-btn kha-qty-increase" data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>">
									<span class="dashicons dashicons-plus"></span>
								</button>
							</div>

							<!-- Subtotal -->
							<div class="kha-cart-item-subtotal">
								<span class="kha-subtotal-label"><?php esc_html_e( 'Tạm tính:', 'kha-solar' ); ?></span>
								<span class="kha-subtotal-value" data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>">
									<?php echo kha_solar_format_price( $item['subtotal'] ); ?>
								</span>
							</div>

							<!-- Remove Button -->
							<button class="kha-cart-item-remove" data-product-id="<?php echo esc_attr( $item['product_id'] ); ?>" title="<?php esc_attr_e( 'Xóa sản phẩm', 'kha-solar' ); ?>">
								<span class="dashicons dashicons-trash"></span>
							</button>

						</div>
					<?php endforeach; ?>
				</div>

				<!-- Cart Summary -->
				<div class="kha-cart-summary">
					<h3><?php esc_html_e( 'Tổng Đơn Hàng', 'kha-solar' ); ?></h3>

					<div class="kha-summary-row">
						<span><?php esc_html_e( 'Tạm tính:', 'kha-solar' ); ?></span>
						<span id="kha-cart-subtotal"><?php echo kha_solar_format_price( $cart_subtotal ); ?></span>
					</div>

					<div class="kha-summary-row">
						<span><?php esc_html_e( 'Phí vận chuyển:', 'kha-solar' ); ?></span>
						<span id="kha-cart-shipping">
							<?php if ( $shipping_fee > 0 ) : ?>
								<?php echo kha_solar_format_price( $shipping_fee ); ?>
							<?php else : ?>
								<span class="kha-text-success"><?php esc_html_e( 'Miễn phí', 'kha-solar' ); ?></span>
							<?php endif; ?>
						</span>
					</div>

					<?php if ( $shipping_fee === 0 && $cart_subtotal >= $cart->get_free_shipping_threshold() ) : ?>
						<div class="kha-summary-note">
							<span class="dashicons dashicons-info"></span>
							<?php esc_html_e( 'Đơn hàng > 5.000.000₫', 'kha-solar' ); ?>
						</div>
					<?php endif; ?>

					<div class="kha-summary-divider"></div>

					<div class="kha-summary-row kha-summary-total">
						<span><?php esc_html_e( 'Tổng cộng:', 'kha-solar' ); ?></span>
						<span id="kha-cart-total" class="kha-total-amount"><?php echo kha_solar_format_price( $cart_total ); ?></span>
					</div>

					<!-- Action Buttons -->
					<div class="kha-cart-actions">
						<a href="#" class="kha-btn kha-btn-primary kha-btn-checkout">
							<?php esc_html_e( 'Thanh toán', 'kha-solar' ); ?>
							<span class="dashicons dashicons-arrow-right-alt2"></span>
						</a>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'kha_product' ) ); ?>" class="kha-btn kha-btn-secondary">
							<span class="dashicons dashicons-arrow-left-alt2"></span>
							<?php esc_html_e( 'Tiếp tục mua', 'kha-solar' ); ?>
						</a>
					</div>

					<!-- Estimated Delivery -->
					<div class="kha-delivery-info">
						<span class="dashicons dashicons-clock"></span>
						<?php
						printf(
							/* translators: delivery estimate */
							esc_html__( 'Dự kiến giao hàng: %s', 'kha-solar' ),
							'<strong>' . esc_html( date_i18n( 'j/n/Y', strtotime( '+7 days' ) ) ) . '</strong>'
						);
						?>
					</div>
				</div>

			</div>

		<?php else : ?>

			<!-- Empty Cart -->
			<div class="kha-cart-empty">
				<div class="kha-empty-icon">🛒</div>
				<h2><?php esc_html_e( 'Giỏ hàng trống', 'kha-solar' ); ?></h2>
				<p><?php esc_html_e( 'Hãy khám phá các sản phẩm của chúng tôi!', 'kha-solar' ); ?></p>
				<div class="kha-empty-actions">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="kha-btn kha-btn-secondary">
						<?php esc_html_e( 'Trang chủ', 'kha-solar' ); ?>
					</a>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'kha_product' ) ); ?>" class="kha-btn kha-btn-primary">
						<?php esc_html_e( 'Sản phẩm', 'kha-solar' ); ?>
					</a>
				</div>
			</div>

		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>
