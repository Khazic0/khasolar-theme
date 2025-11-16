<?php
/**
 * Cart Template
 *
 * @package KhaSolar
 * @since   1.0.0
 */

$cart = new KhaSolar\Cart();
$cart_items = $cart->get_cart_items();
$cart_total = $cart->get_cart_total();
?>

<div class="kha-cart-wrapper">
	<h1 style="text-align: center; margin-bottom: 40px;"><?php esc_html_e( 'Shopping Cart', 'kha-solar' ); ?></h1>

	<?php if ( ! empty( $cart_items ) ) : ?>

		<div class="kha-cart-table">
			<table>
				<thead>
					<tr>
						<th><?php esc_html_e( 'Product', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Price', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Quantity', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Subtotal', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Remove', 'kha-solar' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $cart_items as $item ) : ?>
						<?php
						$product = get_post( $item->product_id );
						if ( ! $product ) {
							continue;
						}

						$price    = get_post_meta( $item->product_id, '_kha_product_price', true );
						$subtotal = floatval( $price ) * $item->quantity;
						?>
						<tr>
							<td>
								<div style="display: flex; align-items: center; gap: 15px;">
									<?php if ( has_post_thumbnail( $item->product_id ) ) : ?>
										<?php echo get_the_post_thumbnail( $item->product_id, 'kha-product-thumbnail', array( 'class' => 'kha-cart-item-image' ) ); ?>
									<?php endif; ?>
									<a href="<?php echo esc_url( get_permalink( $item->product_id ) ); ?>" class="kha-cart-item-name">
										<?php echo esc_html( get_the_title( $item->product_id ) ); ?>
									</a>
								</div>
							</td>
							<td><?php echo kha_solar_format_price( $price ); ?></td>
							<td>
								<div class="kha-cart-quantity">
									<button type="button" class="kha-qty-btn kha-qty-decrease">−</button>
									<input type="number" class="kha-qty-input" value="<?php echo esc_attr( $item->quantity ); ?>" min="1" data-cart-item-id="<?php echo esc_attr( $item->id ); ?>">
									<button type="button" class="kha-qty-btn kha-qty-increase">+</button>
								</div>
							</td>
							<td><strong><?php echo kha_solar_format_price( $subtotal ); ?></strong></td>
							<td>
								<button type="button" class="kha-cart-remove" data-cart-item-id="<?php echo esc_attr( $item->id ); ?>">
									<?php esc_html_e( 'Remove', 'kha-solar' ); ?>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="kha-cart-summary">
			<h3><?php esc_html_e( 'Cart Summary', 'kha-solar' ); ?></h3>

			<div class="kha-summary-row">
				<span><?php esc_html_e( 'Subtotal:', 'kha-solar' ); ?></span>
				<span><?php echo kha_solar_format_price( $cart_total ); ?></span>
			</div>

			<div class="kha-summary-row total">
				<span><?php esc_html_e( 'Total:', 'kha-solar' ); ?></span>
				<span><?php echo kha_solar_format_price( $cart_total ); ?></span>
			</div>

			<a href="<?php echo esc_url( kha_solar_get_page_url( 'checkout' ) ); ?>" class="kha-checkout-btn">
				<?php esc_html_e( 'Proceed to Checkout', 'kha-solar' ); ?>
			</a>

			<a href="<?php echo esc_url( kha_solar_get_page_url( 'shop' ) ); ?>" class="kha-continue-shopping" style="display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none;">
				<?php esc_html_e( '← Continue Shopping', 'kha-solar' ); ?>
			</a>
		</div>

	<?php else : ?>

		<div class="kha-empty-cart">
			<div class="kha-empty-cart-icon">🛒</div>
			<h2><?php esc_html_e( 'Your cart is empty', 'kha-solar' ); ?></h2>
			<p><?php esc_html_e( 'Add some products to get started!', 'kha-solar' ); ?></p>
			<a href="<?php echo esc_url( kha_solar_get_page_url( 'shop' ) ); ?>" class="kha-continue-shopping">
				<?php esc_html_e( 'Start Shopping', 'kha-solar' ); ?>
			</a>
		</div>

	<?php endif; ?>
</div>
