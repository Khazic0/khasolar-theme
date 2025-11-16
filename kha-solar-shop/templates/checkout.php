<?php
/**
 * Checkout Template
 *
 * @package KhaSolar
 * @since   1.0.0
 */

$cart = new KhaSolar\Cart();
$cart_items = $cart->get_cart_items();
$cart_total = $cart->get_cart_total();

if ( empty( $cart_items ) ) {
	wp_redirect( kha_solar_get_page_url( 'cart' ) );
	exit;
}
?>

<div class="kha-checkout-wrapper" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
	<h1 style="text-align: center; margin-bottom: 40px;"><?php esc_html_e( 'Checkout', 'kha-solar' ); ?></h1>

	<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">

		<!-- Billing Information -->
		<div style="background: white; padding: 40px; border-radius: 12px;">
			<h2 style="margin-top: 0; margin-bottom: 25px;"><?php esc_html_e( 'Billing Information', 'kha-solar' ); ?></h2>

			<form id="kha-checkout-form" method="post">
				<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
					<div>
						<label style="display: block; font-weight: 600; margin-bottom: 8px;">
							<?php esc_html_e( 'Full Name', 'kha-solar' ); ?> <span style="color: red;">*</span>
						</label>
						<input type="text" name="customer_name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
					</div>

					<div>
						<label style="display: block; font-weight: 600; margin-bottom: 8px;">
							<?php esc_html_e( 'Phone Number', 'kha-solar' ); ?> <span style="color: red;">*</span>
						</label>
						<input type="tel" name="customer_phone" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
					</div>
				</div>

				<div style="margin-bottom: 20px;">
					<label style="display: block; font-weight: 600; margin-bottom: 8px;">
						<?php esc_html_e( 'Email Address', 'kha-solar' ); ?> <span style="color: red;">*</span>
					</label>
					<input type="email" name="customer_email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
				</div>

				<div style="margin-bottom: 20px;">
					<label style="display: block; font-weight: 600; margin-bottom: 8px;">
						<?php esc_html_e( 'Address', 'kha-solar' ); ?> <span style="color: red;">*</span>
					</label>
					<textarea name="customer_address" required rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
				</div>

				<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
					<div>
						<label style="display: block; font-weight: 600; margin-bottom: 8px;"><?php esc_html_e( 'City/District', 'kha-solar' ); ?></label>
						<input type="text" name="customer_city" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
					</div>

					<div>
						<label style="display: block; font-weight: 600; margin-bottom: 8px;"><?php esc_html_e( 'Province', 'kha-solar' ); ?></label>
						<input type="text" name="customer_province" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
					</div>
				</div>

				<div style="margin-bottom: 20px;">
					<label style="display: block; font-weight: 600; margin-bottom: 8px;"><?php esc_html_e( 'Order Notes (Optional)', 'kha-solar' ); ?></label>
					<textarea name="customer_notes" rows="3" placeholder="<?php esc_attr_e( 'Notes about your order, e.g. special delivery instructions', 'kha-solar' ); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
				</div>

				<div style="padding: 20px; background: #f5f5f5; border-radius: 8px; margin-bottom: 20px;">
					<h3 style="margin-top: 0; margin-bottom: 15px; font-size: 16px;"><?php esc_html_e( 'Payment Method', 'kha-solar' ); ?></h3>
					<label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
						<input type="radio" name="payment_method" value="cod" checked>
						<span><?php esc_html_e( 'Cash on Delivery', 'kha-solar' ); ?></span>
					</label>
				</div>

				<button type="submit" style="width: 100%; padding: 16px; background: #FF9800; color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 700; cursor: pointer;">
					<?php esc_html_e( 'Place Order', 'kha-solar' ); ?>
				</button>
			</form>
		</div>

		<!-- Order Summary -->
		<div>
			<div style="background: white; padding: 30px; border-radius: 12px; position: sticky; top: 20px;">
				<h3 style="margin-top: 0; margin-bottom: 20px;"><?php esc_html_e( 'Order Summary', 'kha-solar' ); ?></h3>

				<div style="margin-bottom: 20px;">
					<?php foreach ( $cart_items as $item ) : ?>
						<div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">
							<span>
								<?php echo esc_html( get_the_title( $item->product_id ) ); ?>
								<strong> × <?php echo esc_html( $item->quantity ); ?></strong>
							</span>
							<span style="font-weight: 600;">
								<?php
								$price = get_post_meta( $item->product_id, '_kha_product_price', true );
								echo kha_solar_format_price( floatval( $price ) * $item->quantity );
								?>
							</span>
						</div>
					<?php endforeach; ?>
				</div>

				<div style="padding-top: 15px; border-top: 2px solid #e0e0e0;">
					<div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; color: #FF9800;">
						<span><?php esc_html_e( 'Total:', 'kha-solar' ); ?></span>
						<span><?php echo kha_solar_format_price( $cart_total ); ?></span>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<script>
jQuery(document).ready(function($) {
	$('#kha-checkout-form').on('submit', function(e) {
		e.preventDefault();

		var formData = $(this).serializeArray();
		var orderData = {
			subtotal: <?php echo floatval( $cart_total ); ?>,
			total: <?php echo floatval( $cart_total ); ?>,
			items: <?php echo json_encode( array_map( function( $item ) {
				$price = get_post_meta( $item->product_id, '_kha_product_price', true );
				return array(
					'product_id'   => $item->product_id,
					'product_name' => get_the_title( $item->product_id ),
					'quantity'     => $item->quantity,
					'price'        => floatval( $price ),
					'subtotal'     => floatval( $price ) * $item->quantity,
				);
			}, $cart_items ) ); ?>
		};

		formData.forEach(function(field) {
			orderData[field.name] = field.value;
		});

		$.ajax({
			url: khaSolar.ajaxUrl,
			type: 'POST',
			data: {
				action: 'kha_create_order',
				nonce: khaSolar.nonce,
				order_data: orderData
			},
			success: function(response) {
				if (response.success) {
					alert('Order placed successfully!');
					window.location.href = khaSolar.cartUrl;
				} else {
					alert(response.data.message || 'Failed to place order');
				}
			},
			error: function() {
				alert('Error occurred while placing order');
			}
		});
	});
});
</script>
