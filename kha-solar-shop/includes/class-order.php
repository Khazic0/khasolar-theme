<?php
/**
 * Order Processing Class
 *
 * Handles order processing with custom post type.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

namespace KhaSolar;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Order class.
 */
class Order {

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_shortcode( 'kha_checkout', array( $this, 'checkout_shortcode' ) );
	}

	/**
	 * Register order post type.
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => __( 'Đơn Hàng', 'kha-solar' ),
			'singular_name'      => __( 'Đơn Hàng', 'kha-solar' ),
			'menu_name'          => __( 'Đơn Hàng', 'kha-solar' ),
			'add_new'            => __( 'Thêm Mới', 'kha-solar' ),
			'add_new_item'       => __( 'Thêm Đơn Hàng Mới', 'kha-solar' ),
			'edit_item'          => __( 'Sửa Đơn Hàng', 'kha-solar' ),
			'view_item'          => __( 'Xem Đơn Hàng', 'kha-solar' ),
			'search_items'       => __( 'Tìm Đơn Hàng', 'kha-solar' ),
			'not_found'          => __( 'Không tìm thấy đơn hàng', 'kha-solar' ),
			'not_found_in_trash' => __( 'Không tìm thấy đơn hàng trong thùng rác', 'kha-solar' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-cart',
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'        => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
			'show_in_rest'        => false,
		);

		register_post_type( 'kha_order', $args );
	}

	/**
	 * Register order status taxonomy.
	 */
	public function register_taxonomy() {
		$labels = array(
			'name'          => __( 'Trạng Thái', 'kha-solar' ),
			'singular_name' => __( 'Trạng Thái', 'kha-solar' ),
			'search_items'  => __( 'Tìm Trạng Thái', 'kha-solar' ),
			'all_items'     => __( 'Tất Cả Trạng Thái', 'kha-solar' ),
			'edit_item'     => __( 'Sửa Trạng Thái', 'kha-solar' ),
			'update_item'   => __( 'Cập Nhật Trạng Thái', 'kha-solar' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'rewrite'           => false,
			'show_in_rest'      => false,
		);

		register_taxonomy( 'kha_order_status', array( 'kha_order' ), $args );

		// Register default statuses.
		if ( ! term_exists( 'pending', 'kha_order_status' ) ) {
			wp_insert_term( 'Chờ Xử Lý', 'kha_order_status', array( 'slug' => 'pending' ) );
			wp_insert_term( 'Đang Xử Lý', 'kha_order_status', array( 'slug' => 'processing' ) );
			wp_insert_term( 'Hoàn Thành', 'kha_order_status', array( 'slug' => 'completed' ) );
			wp_insert_term( 'Đã Hủy', 'kha_order_status', array( 'slug' => 'cancelled' ) );
		}
	}

	/**
	 * Generate order number.
	 *
	 * Format: KS-YYYYMMDD-XXXX
	 *
	 * @return string Order number.
	 */
	private function generate_order_number() {
		$date = date( 'Ymd' );

		// Get count of orders created today.
		$args = array(
			'post_type'      => 'kha_order',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'date_query'     => array(
				array(
					'after' => 'today',
				),
			),
			'fields'         => 'ids',
		);

		$today_orders = get_posts( $args );
		$count = count( $today_orders ) + 1;

		return sprintf( 'KS-%s-%04d', $date, $count );
	}

	/**
	 * Create new order.
	 *
	 * @param array $order_data Order data.
	 * @return int|WP_Error Order ID or error.
	 */
	public function create_order( $order_data ) {
		// Validate required fields.
		$required = array( 'customer_name', 'customer_phone', 'customer_address', 'customer_province' );
		foreach ( $required as $field ) {
			if ( empty( $order_data[ $field ] ) ) {
				return new \WP_Error( 'missing_field', sprintf( __( 'Trường %s là bắt buộc.', 'kha-solar' ), $field ) );
			}
		}

		// Validate phone number.
		if ( ! $this->validate_vietnamese_phone( $order_data['customer_phone'] ) ) {
			return new \WP_Error( 'invalid_phone', __( 'Số điện thoại không hợp lệ.', 'kha-solar' ) );
		}

		// Get cart.
		$cart = new Cart();
		$cart_items = $cart->get_cart_contents();

		if ( empty( $cart_items ) ) {
			return new \WP_Error( 'empty_cart', __( 'Giỏ hàng trống.', 'kha-solar' ) );
		}

		// Validate stock for all items.
		foreach ( $cart_items as $item ) {
			$stock_check = $cart->validate_stock( $item['product_id'], $item['quantity'] );
			if ( ! $stock_check['valid'] ) {
				return new \WP_Error( 'out_of_stock', $stock_check['message'] );
			}
		}

		// Generate order number.
		$order_number = $this->generate_order_number();

		// Create order post.
		$order_id = wp_insert_post(
			array(
				'post_type'   => 'kha_order',
				'post_title'  => $order_number,
				'post_status' => 'publish',
			)
		);

		if ( is_wp_error( $order_id ) ) {
			return $order_id;
		}

		// Set order status.
		wp_set_object_terms( $order_id, 'pending', 'kha_order_status' );

		// Save customer information.
		update_post_meta( $order_id, '_customer_name', sanitize_text_field( $order_data['customer_name'] ) );
		update_post_meta( $order_id, '_customer_phone', sanitize_text_field( $order_data['customer_phone'] ) );
		update_post_meta( $order_id, '_customer_email', sanitize_email( $order_data['customer_email'] ?? '' ) );
		update_post_meta( $order_id, '_customer_address', sanitize_textarea_field( $order_data['customer_address'] ) );
		update_post_meta( $order_id, '_customer_province', sanitize_text_field( $order_data['customer_province'] ) );
		update_post_meta( $order_id, '_customer_district', sanitize_text_field( $order_data['customer_district'] ?? '' ) );
		update_post_meta( $order_id, '_customer_ward', sanitize_text_field( $order_data['customer_ward'] ?? '' ) );
		update_post_meta( $order_id, '_order_notes', sanitize_textarea_field( $order_data['order_notes'] ?? '' ) );

		// Save order items.
		$order_items = array();
		foreach ( $cart_items as $item ) {
			$order_items[] = array(
				'product_id' => $item['product_id'],
				'title'      => $item['title'],
				'quantity'   => $item['quantity'],
				'price'      => $item['price'],
				'subtotal'   => $item['subtotal'],
			);
		}
		update_post_meta( $order_id, '_order_items', $order_items );

		// Save order totals.
		$cart_subtotal = $cart->get_cart_subtotal();
		$shipping_fee  = $cart->get_shipping_fee();
		$cart_total    = $cart->get_cart_total();

		update_post_meta( $order_id, '_order_subtotal', $cart_subtotal );
		update_post_meta( $order_id, '_order_shipping', $shipping_fee );
		update_post_meta( $order_id, '_order_total', $cart_total );

		// Save payment method.
		update_post_meta( $order_id, '_payment_method', sanitize_text_field( $order_data['payment_method'] ?? 'cod' ) );

		// Save order status.
		update_post_meta( $order_id, '_order_status', 'pending' );

		// Save created date.
		update_post_meta( $order_id, '_created_date', current_time( 'mysql' ) );

		// Reduce stock quantities.
		foreach ( $cart_items as $item ) {
			$this->reduce_stock( $item['product_id'], $item['quantity'] );
		}

		// Clear cart.
		$cart->clear_cart();

		// Send confirmation email (optional).
		$this->send_order_confirmation( $order_id );

		return $order_id;
	}

	/**
	 * Validate Vietnamese phone number.
	 *
	 * @param string $phone Phone number.
	 * @return bool True if valid.
	 */
	private function validate_vietnamese_phone( $phone ) {
		// Remove all non-numeric characters.
		$phone = preg_replace( '/[^0-9]/', '', $phone );

		// Check format: 0[3|5|7|8|9]xxxxxxxxx (10 digits).
		return preg_match( '/^0[3|5|7|8|9][0-9]{8}$/', $phone );
	}

	/**
	 * Reduce stock quantity.
	 *
	 * @param int $product_id Product ID.
	 * @param int $quantity   Quantity to reduce.
	 */
	private function reduce_stock( $product_id, $quantity ) {
		$manage_stock = get_post_meta( $product_id, '_manage_stock', true ) === 'yes';

		if ( ! $manage_stock ) {
			return;
		}

		$stock_quantity = get_post_meta( $product_id, '_stock_quantity', true );
		$stock_quantity = absint( $stock_quantity );

		$new_quantity = max( 0, $stock_quantity - $quantity );

		update_post_meta( $product_id, '_stock_quantity', $new_quantity );

		// Update stock status if out of stock.
		if ( $new_quantity === 0 ) {
			update_post_meta( $product_id, '_stock_status', 'outofstock' );
		}
	}

	/**
	 * Send order confirmation email.
	 *
	 * @param int $order_id Order ID.
	 */
	private function send_order_confirmation( $order_id ) {
		$customer_email = get_post_meta( $order_id, '_customer_email', true );

		if ( empty( $customer_email ) ) {
			return;
		}

		$order_number   = get_the_title( $order_id );
		$customer_name  = get_post_meta( $order_id, '_customer_name', true );
		$order_total    = get_post_meta( $order_id, '_order_total', true );

		$subject = sprintf( __( 'Xác nhận đơn hàng %s', 'kha-solar' ), $order_number );

		$message = sprintf(
			__( 'Xin chào %s,

Cảm ơn bạn đã đặt hàng tại Kha Solar!

Mã đơn hàng: %s
Tổng tiền: %s

Chúng tôi đã nhận được đơn hàng của bạn và sẽ liên hệ trong thời gian sớm nhất.

Trân trọng,
Kha Solar', 'kha-solar' ),
			$customer_name,
			$order_number,
			kha_solar_format_price( $order_total )
		);

		wp_mail( $customer_email, $subject, $message );
	}

	/**
	 * Get order data.
	 *
	 * @param int $order_id Order ID.
	 * @return array Order data.
	 */
	public function get_order( $order_id ) {
		$order = array(
			'id'               => $order_id,
			'order_number'     => get_the_title( $order_id ),
			'customer_name'    => get_post_meta( $order_id, '_customer_name', true ),
			'customer_phone'   => get_post_meta( $order_id, '_customer_phone', true ),
			'customer_email'   => get_post_meta( $order_id, '_customer_email', true ),
			'customer_address' => get_post_meta( $order_id, '_customer_address', true ),
			'customer_province'=> get_post_meta( $order_id, '_customer_province', true ),
			'customer_district'=> get_post_meta( $order_id, '_customer_district', true ),
			'customer_ward'    => get_post_meta( $order_id, '_customer_ward', true ),
			'order_notes'      => get_post_meta( $order_id, '_order_notes', true ),
			'order_items'      => get_post_meta( $order_id, '_order_items', true ),
			'order_subtotal'   => get_post_meta( $order_id, '_order_subtotal', true ),
			'order_shipping'   => get_post_meta( $order_id, '_order_shipping', true ),
			'order_total'      => get_post_meta( $order_id, '_order_total', true ),
			'payment_method'   => get_post_meta( $order_id, '_payment_method', true ),
			'order_status'     => $this->get_order_status( $order_id ),
			'created_date'     => get_post_meta( $order_id, '_created_date', true ),
		);

		return $order;
	}

	/**
	 * Get order status.
	 *
	 * @param int $order_id Order ID.
	 * @return string Status.
	 */
	public function get_order_status( $order_id ) {
		$terms = get_the_terms( $order_id, 'kha_order_status' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			return $terms[0]->slug;
		}

		return 'pending';
	}

	/**
	 * Update order status.
	 *
	 * @param int    $order_id Order ID.
	 * @param string $status   Status slug.
	 * @return bool Success.
	 */
	public function update_order_status( $order_id, $status ) {
		$result = wp_set_object_terms( $order_id, $status, 'kha_order_status' );

		if ( ! is_wp_error( $result ) ) {
			update_post_meta( $order_id, '_order_status', $status );
			return true;
		}

		return false;
	}

	/**
	 * Checkout shortcode.
	 *
	 * @return string Checkout page HTML.
	 */
	public function checkout_shortcode() {
		ob_start();
		include KHA_PLUGIN_DIR . 'templates/checkout.php';
		return ob_get_clean();
	}
}
