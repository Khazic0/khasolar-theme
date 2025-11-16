<?php
/**
 * Order processing functionality.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

namespace KhaSolar;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Order class.
 *
 * Handles order processing and management.
 */
class Order {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_shortcode( 'kha_solar_checkout', array( $this, 'checkout_shortcode' ) );
	}

	/**
	 * Create new order.
	 *
	 * @param array $order_data Order data.
	 * @return int|bool Order ID or false on failure.
	 * @since 1.0.0
	 */
	public function create_order( $order_data ) {
		global $wpdb;

		$order_table = $wpdb->prefix . KHA_DB_PREFIX . 'orders';
		$items_table = $wpdb->prefix . KHA_DB_PREFIX . 'order_items';

		// Generate order number.
		$order_prefix = get_option( 'kha_solar_order_prefix', 'KHS-' );
		$order_number = $order_prefix . strtoupper( uniqid() );

		// Prepare order data.
		$order = array(
			'order_number'      => $order_number,
			'user_id'           => get_current_user_id() ? get_current_user_id() : null,
			'session_id'        => isset( $_SESSION['kha_cart_session_id'] ) ? $_SESSION['kha_cart_session_id'] : null,
			'customer_name'     => sanitize_text_field( $order_data['customer_name'] ),
			'customer_email'    => sanitize_email( $order_data['customer_email'] ),
			'customer_phone'    => sanitize_text_field( $order_data['customer_phone'] ),
			'customer_address'  => sanitize_textarea_field( $order_data['customer_address'] ),
			'customer_city'     => sanitize_text_field( $order_data['customer_city'] ?? '' ),
			'customer_province' => sanitize_text_field( $order_data['customer_province'] ?? '' ),
			'customer_notes'    => sanitize_textarea_field( $order_data['customer_notes'] ?? '' ),
			'subtotal'          => floatval( $order_data['subtotal'] ),
			'tax'               => floatval( $order_data['tax'] ?? 0 ),
			'shipping'          => floatval( $order_data['shipping'] ?? 0 ),
			'total'             => floatval( $order_data['total'] ),
			'status'            => 'pending',
			'payment_method'    => sanitize_text_field( $order_data['payment_method'] ?? 'cod' ),
			'payment_status'    => 'pending',
		);

		// Insert order.
		$inserted = $wpdb->insert( $order_table, $order );

		if ( ! $inserted ) {
			return false;
		}

		$order_id = $wpdb->insert_id;

		// Insert order items.
		if ( isset( $order_data['items'] ) && is_array( $order_data['items'] ) ) {
			foreach ( $order_data['items'] as $item ) {
				$wpdb->insert(
					$items_table,
					array(
						'order_id'     => $order_id,
						'product_id'   => absint( $item['product_id'] ),
						'product_name' => sanitize_text_field( $item['product_name'] ),
						'product_sku'  => sanitize_text_field( $item['product_sku'] ?? '' ),
						'quantity'     => absint( $item['quantity'] ),
						'price'        => floatval( $item['price'] ),
						'subtotal'     => floatval( $item['subtotal'] ),
					)
				);
			}
		}

		// Clear cart after order.
		if ( class_exists( 'KhaSolar\Cart' ) ) {
			$cart = new Cart();
			$cart->clear_cart();
		}

		// Send order emails.
		$this->send_order_emails( $order_id );

		return $order_id;
	}

	/**
	 * Get order by ID.
	 *
	 * @param int $order_id Order ID.
	 * @return object|null
	 * @since 1.0.0
	 */
	public function get_order( $order_id ) {
		global $wpdb;

		$order_table = $wpdb->prefix . KHA_DB_PREFIX . 'orders';

		return $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$order_table} WHERE id = %d",
				$order_id
			)
		);
	}

	/**
	 * Get order items.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 * @since 1.0.0
	 */
	public function get_order_items( $order_id ) {
		global $wpdb;

		$items_table = $wpdb->prefix . KHA_DB_PREFIX . 'order_items';

		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$items_table} WHERE order_id = %d",
				$order_id
			)
		);

		return $items ? $items : array();
	}

	/**
	 * Update order status.
	 *
	 * @param int    $order_id Order ID.
	 * @param string $status   New status.
	 * @return bool|int
	 * @since 1.0.0
	 */
	public function update_status( $order_id, $status ) {
		global $wpdb;

		$order_table = $wpdb->prefix . KHA_DB_PREFIX . 'orders';

		return $wpdb->update(
			$order_table,
			array( 'status' => sanitize_key( $status ) ),
			array( 'id' => $order_id ),
			array( '%s' ),
			array( '%d' )
		);
	}

	/**
	 * Send order confirmation emails.
	 *
	 * @param int $order_id Order ID.
	 * @since 1.0.0
	 */
	private function send_order_emails( $order_id ) {
		$enable_emails = get_option( 'kha_solar_enable_order_emails', 'yes' );

		if ( 'yes' !== $enable_emails ) {
			return;
		}

		$order = $this->get_order( $order_id );

		if ( ! $order ) {
			return;
		}

		// Send to customer.
		$customer_subject = sprintf(
			/* translators: %s: Order number */
			__( 'Order Confirmation - %s', 'kha-solar' ),
			$order->order_number
		);

		$customer_message = $this->get_order_email_content( $order, 'customer' );

		wp_mail( $order->customer_email, $customer_subject, $customer_message );

		// Send to admin.
		$admin_email   = get_option( 'kha_solar_admin_email', get_option( 'admin_email' ) );
		$admin_subject = sprintf(
			/* translators: %s: Order number */
			__( 'New Order Received - %s', 'kha-solar' ),
			$order->order_number
		);

		$admin_message = $this->get_order_email_content( $order, 'admin' );

		wp_mail( $admin_email, $admin_subject, $admin_message );
	}

	/**
	 * Get order email content.
	 *
	 * @param object $order Order object.
	 * @param string $type  Email type (customer/admin).
	 * @return string
	 * @since 1.0.0
	 */
	private function get_order_email_content( $order, $type = 'customer' ) {
		$items = $this->get_order_items( $order->id );

		ob_start();
		?>
		<h2><?php echo esc_html( $order->order_number ); ?></h2>

		<h3><?php esc_html_e( 'Order Details', 'kha-solar' ); ?></h3>
		<p>
			<strong><?php esc_html_e( 'Date:', 'kha-solar' ); ?></strong> <?php echo esc_html( $order->created_at ); ?><br>
			<strong><?php esc_html_e( 'Status:', 'kha-solar' ); ?></strong> <?php echo esc_html( ucfirst( $order->status ) ); ?>
		</p>

		<h3><?php esc_html_e( 'Customer Information', 'kha-solar' ); ?></h3>
		<p>
			<strong><?php esc_html_e( 'Name:', 'kha-solar' ); ?></strong> <?php echo esc_html( $order->customer_name ); ?><br>
			<strong><?php esc_html_e( 'Email:', 'kha-solar' ); ?></strong> <?php echo esc_html( $order->customer_email ); ?><br>
			<strong><?php esc_html_e( 'Phone:', 'kha-solar' ); ?></strong> <?php echo esc_html( $order->customer_phone ); ?><br>
			<strong><?php esc_html_e( 'Address:', 'kha-solar' ); ?></strong> <?php echo esc_html( $order->customer_address ); ?>
		</p>

		<h3><?php esc_html_e( 'Order Items', 'kha-solar' ); ?></h3>
		<table border="1" cellpadding="10">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Product', 'kha-solar' ); ?></th>
					<th><?php esc_html_e( 'Quantity', 'kha-solar' ); ?></th>
					<th><?php esc_html_e( 'Price', 'kha-solar' ); ?></th>
					<th><?php esc_html_e( 'Subtotal', 'kha-solar' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $items as $item ) : ?>
				<tr>
					<td><?php echo esc_html( $item->product_name ); ?></td>
					<td><?php echo esc_html( $item->quantity ); ?></td>
					<td><?php echo kha_solar_format_price( $item->price ); ?></td>
					<td><?php echo kha_solar_format_price( $item->subtotal ); ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3"><strong><?php esc_html_e( 'Subtotal:', 'kha-solar' ); ?></strong></td>
					<td><?php echo kha_solar_format_price( $order->subtotal ); ?></td>
				</tr>
				<?php if ( $order->shipping > 0 ) : ?>
				<tr>
					<td colspan="3"><strong><?php esc_html_e( 'Shipping:', 'kha-solar' ); ?></strong></td>
					<td><?php echo kha_solar_format_price( $order->shipping ); ?></td>
				</tr>
				<?php endif; ?>
				<tr>
					<td colspan="3"><strong><?php esc_html_e( 'Total:', 'kha-solar' ); ?></strong></td>
					<td><strong><?php echo kha_solar_format_price( $order->total ); ?></strong></td>
				</tr>
			</tfoot>
		</table>
		<?php
		return ob_get_clean();
	}

	/**
	 * Checkout shortcode.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function checkout_shortcode() {
		ob_start();
		kha_solar_get_template( 'checkout.php' );
		return ob_get_clean();
	}
}
