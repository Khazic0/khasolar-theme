<?php
/**
 * Cart functionality.
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
 * Cart class.
 *
 * Handles shopping cart functionality.
 */
class Cart {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'init', array( $this, 'start_session' ) );
		add_shortcode( 'kha_solar_cart', array( $this, 'cart_shortcode' ) );
	}

	/**
	 * Start session for guest users.
	 *
	 * @since 1.0.0
	 */
	public function start_session() {
		if ( ! session_id() && ! headers_sent() ) {
			session_start();
		}

		if ( ! isset( $_SESSION['kha_cart_session_id'] ) ) {
			$_SESSION['kha_cart_session_id'] = $this->generate_session_id();
		}
	}

	/**
	 * Generate unique session ID.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function generate_session_id() {
		return md5( uniqid( wp_rand(), true ) );
	}

	/**
	 * Get current session ID.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function get_session_id() {
		return isset( $_SESSION['kha_cart_session_id'] ) ? $_SESSION['kha_cart_session_id'] : '';
	}

	/**
	 * Add product to cart.
	 *
	 * @param int $product_id Product ID.
	 * @param int $quantity   Quantity to add.
	 * @return bool|int
	 * @since 1.0.0
	 */
	public function add_to_cart( $product_id, $quantity = 1 ) {
		global $wpdb;

		$product_id = absint( $product_id );
		$quantity   = absint( $quantity );

		if ( ! $product_id || $quantity < 1 ) {
			return false;
		}

		$table_name = $wpdb->prefix . KHA_DB_PREFIX . 'cart';
		$user_id    = get_current_user_id();
		$session_id = $this->get_session_id();

		// Check if product already in cart.
		$existing = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} WHERE product_id = %d AND session_id = %s",
				$product_id,
				$session_id
			)
		);

		if ( $existing ) {
			// Update quantity.
			return $wpdb->update(
				$table_name,
				array( 'quantity' => $existing->quantity + $quantity ),
				array( 'id' => $existing->id ),
				array( '%d' ),
				array( '%d' )
			);
		}

		// Insert new item.
		return $wpdb->insert(
			$table_name,
			array(
				'user_id'    => $user_id ? $user_id : null,
				'session_id' => $session_id,
				'product_id' => $product_id,
				'quantity'   => $quantity,
			),
			array( '%d', '%s', '%d', '%d' )
		);
	}

	/**
	 * Update cart item quantity.
	 *
	 * @param int $cart_item_id Cart item ID.
	 * @param int $quantity     New quantity.
	 * @return bool|int
	 * @since 1.0.0
	 */
	public function update_quantity( $cart_item_id, $quantity ) {
		global $wpdb;

		$cart_item_id = absint( $cart_item_id );
		$quantity     = absint( $quantity );

		if ( ! $cart_item_id ) {
			return false;
		}

		$table_name = $wpdb->prefix . KHA_DB_PREFIX . 'cart';

		if ( $quantity < 1 ) {
			// Remove item if quantity is 0.
			return $this->remove_item( $cart_item_id );
		}

		return $wpdb->update(
			$table_name,
			array( 'quantity' => $quantity ),
			array( 'id' => $cart_item_id ),
			array( '%d' ),
			array( '%d' )
		);
	}

	/**
	 * Remove item from cart.
	 *
	 * @param int $cart_item_id Cart item ID.
	 * @return bool|int
	 * @since 1.0.0
	 */
	public function remove_item( $cart_item_id ) {
		global $wpdb;

		$cart_item_id = absint( $cart_item_id );

		if ( ! $cart_item_id ) {
			return false;
		}

		$table_name = $wpdb->prefix . KHA_DB_PREFIX . 'cart';
		$session_id = $this->get_session_id();

		return $wpdb->delete(
			$table_name,
			array(
				'id'         => $cart_item_id,
				'session_id' => $session_id,
			),
			array( '%d', '%s' )
		);
	}

	/**
	 * Get cart items.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_cart_items() {
		global $wpdb;

		$table_name = $wpdb->prefix . KHA_DB_PREFIX . 'cart';
		$session_id = $this->get_session_id();

		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} WHERE session_id = %s ORDER BY created_at DESC",
				$session_id
			)
		);

		return $items ? $items : array();
	}

	/**
	 * Get cart count.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function get_cart_count() {
		$items = $this->get_cart_items();
		$count = 0;

		foreach ( $items as $item ) {
			$count += $item->quantity;
		}

		return $count;
	}

	/**
	 * Get cart total.
	 *
	 * @return float
	 * @since 1.0.0
	 */
	public function get_cart_total() {
		$items = $this->get_cart_items();
		$total = 0;

		foreach ( $items as $item ) {
			$price = get_post_meta( $item->product_id, '_kha_product_price', true );
			$total += floatval( $price ) * $item->quantity;
		}

		return $total;
	}

	/**
	 * Clear cart.
	 *
	 * @return bool|int
	 * @since 1.0.0
	 */
	public function clear_cart() {
		global $wpdb;

		$table_name = $wpdb->prefix . KHA_DB_PREFIX . 'cart';
		$session_id = $this->get_session_id();

		return $wpdb->delete(
			$table_name,
			array( 'session_id' => $session_id ),
			array( '%s' )
		);
	}

	/**
	 * Cart shortcode.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function cart_shortcode() {
		ob_start();
		kha_solar_get_template( 'cart.php' );
		return ob_get_clean();
	}
}
