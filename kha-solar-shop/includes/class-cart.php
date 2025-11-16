<?php
/**
 * Shopping Cart Class
 *
 * Lightweight session-based cart system for better performance.
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
 * Cart class.
 */
class Cart {

	/**
	 * Session key for cart data.
	 *
	 * @var string
	 */
	private $session_key = 'kha_solar_cart';

	/**
	 * Shipping fee threshold.
	 *
	 * @var int
	 */
	private $free_shipping_threshold = 5000000;

	/**
	 * Flat shipping fee.
	 *
	 * @var int
	 */
	private $flat_shipping_fee = 200000;

	/**
	 * Initialize cart.
	 */
	public function __construct() {
		// Start session if not already started.
		if ( ! session_id() && ! headers_sent() ) {
			session_start();
		}

		// Initialize cart if not exists.
		if ( ! isset( $_SESSION[ $this->session_key ] ) ) {
			$_SESSION[ $this->session_key ] = array();
		}
	}

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_action( 'init', array( $this, 'start_session' ) );
		add_shortcode( 'kha_cart', array( $this, 'cart_shortcode' ) );
	}

	/**
	 * Start session.
	 */
	public function start_session() {
		if ( ! session_id() && ! headers_sent() ) {
			session_start();
		}

		if ( ! isset( $_SESSION[ $this->session_key ] ) ) {
			$_SESSION[ $this->session_key ] = array();
		}
	}

	/**
	 * Add product to cart.
	 *
	 * @param int $product_id Product ID.
	 * @param int $quantity   Quantity to add.
	 * @return bool|array True on success, array with error on failure.
	 */
	public function add_to_cart( $product_id, $quantity = 1 ) {
		$product_id = absint( $product_id );
		$quantity   = absint( $quantity );

		if ( $quantity < 1 ) {
			return array(
				'success' => false,
				'message' => __( 'Số lượng không hợp lệ.', 'kha-solar' ),
			);
		}

		// Validate product exists.
		$product = get_post( $product_id );
		if ( ! $product || 'kha_product' !== $product->post_type ) {
			return array(
				'success' => false,
				'message' => __( 'Sản phẩm không tồn tại.', 'kha-solar' ),
			);
		}

		// Get current quantity in cart.
		$current_quantity = $this->get_item_quantity( $product_id );
		$new_quantity     = $current_quantity + $quantity;

		// Validate stock.
		$stock_check = $this->validate_stock( $product_id, $new_quantity );
		if ( ! $stock_check['valid'] ) {
			return array(
				'success' => false,
				'message' => $stock_check['message'],
			);
		}

		// Get product data.
		$price      = get_post_meta( $product_id, '_price', true );
		$sale_price = get_post_meta( $product_id, '_sale_price', true );
		$is_bundle  = get_post_meta( $product_id, '_is_bundle', true ) === 'yes';

		$final_price = $sale_price ? $sale_price : $price;

		// Apply bundle discount if applicable.
		if ( $is_bundle ) {
			$bundle_discount = $this->apply_bundle_discount( $product_id );
			if ( $bundle_discount > 0 ) {
				$final_price = $final_price - ( $final_price * $bundle_discount / 100 );
			}
		}

		// Add to cart.
		$cart_key = 'product_' . $product_id;

		if ( isset( $_SESSION[ $this->session_key ][ $cart_key ] ) ) {
			// Update existing item.
			$_SESSION[ $this->session_key ][ $cart_key ]['quantity'] = $new_quantity;
		} else {
			// Add new item.
			$_SESSION[ $this->session_key ][ $cart_key ] = array(
				'product_id' => $product_id,
				'quantity'   => $quantity,
				'price'      => floatval( $final_price ),
				'is_bundle'  => $is_bundle,
				'added_at'   => time(),
			);
		}

		return array(
			'success' => true,
			'message' => __( 'Đã thêm sản phẩm vào giỏ hàng.', 'kha-solar' ),
		);
	}

	/**
	 * Update item quantity in cart.
	 *
	 * @param int $product_id Product ID.
	 * @param int $quantity   New quantity.
	 * @return bool|array True on success, array with error on failure.
	 */
	public function update_quantity( $product_id, $quantity ) {
		$product_id = absint( $product_id );
		$quantity   = absint( $quantity );
		$cart_key   = 'product_' . $product_id;

		if ( ! isset( $_SESSION[ $this->session_key ][ $cart_key ] ) ) {
			return array(
				'success' => false,
				'message' => __( 'Sản phẩm không có trong giỏ hàng.', 'kha-solar' ),
			);
		}

		if ( $quantity < 1 ) {
			return $this->remove_item( $product_id );
		}

		// Validate stock.
		$stock_check = $this->validate_stock( $product_id, $quantity );
		if ( ! $stock_check['valid'] ) {
			return array(
				'success' => false,
				'message' => $stock_check['message'],
			);
		}

		$_SESSION[ $this->session_key ][ $cart_key ]['quantity'] = $quantity;

		return array(
			'success' => true,
			'message' => __( 'Đã cập nhật số lượng.', 'kha-solar' ),
		);
	}

	/**
	 * Remove item from cart.
	 *
	 * @param int $product_id Product ID.
	 * @return array Result.
	 */
	public function remove_item( $product_id ) {
		$product_id = absint( $product_id );
		$cart_key   = 'product_' . $product_id;

		if ( isset( $_SESSION[ $this->session_key ][ $cart_key ] ) ) {
			unset( $_SESSION[ $this->session_key ][ $cart_key ] );
			return array(
				'success' => true,
				'message' => __( 'Đã xóa sản phẩm khỏi giỏ hàng.', 'kha-solar' ),
			);
		}

		return array(
			'success' => false,
			'message' => __( 'Sản phẩm không có trong giỏ hàng.', 'kha-solar' ),
		);
	}

	/**
	 * Clear all items from cart.
	 *
	 * @return array Result.
	 */
	public function clear_cart() {
		$_SESSION[ $this->session_key ] = array();

		return array(
			'success' => true,
			'message' => __( 'Đã xóa toàn bộ giỏ hàng.', 'kha-solar' ),
		);
	}

	/**
	 * Get cart contents.
	 *
	 * @return array Cart items.
	 */
	public function get_cart_contents() {
		if ( ! isset( $_SESSION[ $this->session_key ] ) || empty( $_SESSION[ $this->session_key ] ) ) {
			return array();
		}

		$cart = $_SESSION[ $this->session_key ];
		$items = array();

		foreach ( $cart as $cart_key => $item ) {
			$product_id = $item['product_id'];
			$product    = get_post( $product_id );

			// Skip if product no longer exists.
			if ( ! $product ) {
				$this->remove_item( $product_id );
				continue;
			}

			$thumbnail = get_the_post_thumbnail_url( $product_id, 'thumbnail' );
			$stock_status = get_post_meta( $product_id, '_stock_status', true );

			$items[ $cart_key ] = array(
				'product_id'   => $product_id,
				'title'        => $product->post_title,
				'permalink'    => get_permalink( $product_id ),
				'thumbnail'    => $thumbnail ? $thumbnail : '',
				'quantity'     => $item['quantity'],
				'price'        => $item['price'],
				'subtotal'     => $item['price'] * $item['quantity'],
				'is_bundle'    => $item['is_bundle'],
				'stock_status' => $stock_status,
				'added_at'     => $item['added_at'],
			);
		}

		return $items;
	}

	/**
	 * Get cart subtotal (before shipping).
	 *
	 * @return float Cart subtotal.
	 */
	public function get_cart_subtotal() {
		$items = $this->get_cart_contents();
		$subtotal = 0;

		foreach ( $items as $item ) {
			$subtotal += $item['subtotal'];
		}

		return floatval( $subtotal );
	}

	/**
	 * Get shipping fee.
	 *
	 * @return float Shipping fee.
	 */
	public function get_shipping_fee() {
		$subtotal = $this->get_cart_subtotal();

		if ( $subtotal >= $this->free_shipping_threshold ) {
			return 0;
		}

		return floatval( $this->flat_shipping_fee );
	}

	/**
	 * Get cart total (subtotal + shipping).
	 *
	 * @return float Cart total.
	 */
	public function get_cart_total() {
		return $this->get_cart_subtotal() + $this->get_shipping_fee();
	}

	/**
	 * Get total item count in cart.
	 *
	 * @return int Item count.
	 */
	public function get_cart_count() {
		$items = $this->get_cart_contents();
		$count = 0;

		foreach ( $items as $item ) {
			$count += $item['quantity'];
		}

		return absint( $count );
	}

	/**
	 * Get quantity of specific item in cart.
	 *
	 * @param int $product_id Product ID.
	 * @return int Quantity.
	 */
	public function get_item_quantity( $product_id ) {
		$cart_key = 'product_' . absint( $product_id );

		if ( isset( $_SESSION[ $this->session_key ][ $cart_key ] ) ) {
			return absint( $_SESSION[ $this->session_key ][ $cart_key ]['quantity'] );
		}

		return 0;
	}

	/**
	 * Validate stock availability.
	 *
	 * @param int $product_id Product ID.
	 * @param int $quantity   Requested quantity.
	 * @return array Validation result.
	 */
	public function validate_stock( $product_id, $quantity ) {
		$stock_status = get_post_meta( $product_id, '_stock_status', true );

		// Out of stock.
		if ( 'outofstock' === $stock_status ) {
			return array(
				'valid'   => false,
				'message' => __( 'Sản phẩm hiện đã hết hàng.', 'kha-solar' ),
			);
		}

		// Check stock quantity if managing stock.
		$manage_stock = get_post_meta( $product_id, '_manage_stock', true ) === 'yes';

		if ( $manage_stock ) {
			$stock_quantity = get_post_meta( $product_id, '_stock_quantity', true );
			$stock_quantity = absint( $stock_quantity );

			if ( $quantity > $stock_quantity ) {
				return array(
					'valid'   => false,
					'message' => sprintf(
						/* translators: %d: available stock quantity */
						__( 'Chỉ còn %d sản phẩm trong kho.', 'kha-solar' ),
						$stock_quantity
					),
				);
			}
		}

		// Allow backorders.
		if ( 'onbackorder' === $stock_status ) {
			return array(
				'valid'   => true,
				'message' => __( 'Sản phẩm đặt trước (giao hàng sau).', 'kha-solar' ),
			);
		}

		return array(
			'valid'   => true,
			'message' => '',
		);
	}

	/**
	 * Apply bundle discount.
	 *
	 * @param int $product_id Product ID.
	 * @return float Discount percentage.
	 */
	public function apply_bundle_discount( $product_id ) {
		$is_bundle = get_post_meta( $product_id, '_is_bundle', true ) === 'yes';

		if ( ! $is_bundle ) {
			return 0;
		}

		// Get bundle discount (default 10% for bundles).
		$bundle_discount = get_post_meta( $product_id, '_bundle_discount', true );
		$bundle_discount = $bundle_discount ? floatval( $bundle_discount ) : 10;

		return $bundle_discount;
	}

	/**
	 * Check if cart is empty.
	 *
	 * @return bool True if empty.
	 */
	public function is_empty() {
		return empty( $_SESSION[ $this->session_key ] );
	}

	/**
	 * Get free shipping threshold.
	 *
	 * @return int Threshold amount.
	 */
	public function get_free_shipping_threshold() {
		return $this->free_shipping_threshold;
	}

	/**
	 * Get remaining amount for free shipping.
	 *
	 * @return float Remaining amount.
	 */
	public function get_remaining_for_free_shipping() {
		$subtotal = $this->get_cart_subtotal();
		$remaining = $this->free_shipping_threshold - $subtotal;

		return $remaining > 0 ? $remaining : 0;
	}

	/**
	 * Cart shortcode.
	 *
	 * @return string Cart page HTML.
	 */
	public function cart_shortcode() {
		ob_start();
		include KHA_PLUGIN_DIR . 'templates/cart.php';
		return ob_get_clean();
	}
}
