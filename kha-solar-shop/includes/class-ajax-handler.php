<?php
/**
 * AJAX handler for all plugin AJAX requests.
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
 * Ajax_Handler class.
 *
 * Handles all AJAX requests for the plugin.
 */
class Ajax_Handler {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Cart actions.
		add_action( 'wp_ajax_kha_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_kha_add_to_cart', array( $this, 'add_to_cart' ) );

		add_action( 'wp_ajax_kha_update_cart', array( $this, 'update_cart' ) );
		add_action( 'wp_ajax_nopriv_kha_update_cart', array( $this, 'update_cart' ) );

		add_action( 'wp_ajax_kha_remove_from_cart', array( $this, 'remove_from_cart' ) );
		add_action( 'wp_ajax_nopriv_kha_remove_from_cart', array( $this, 'remove_from_cart' ) );

		add_action( 'wp_ajax_kha_get_cart_count', array( $this, 'get_cart_count' ) );
		add_action( 'wp_ajax_nopriv_kha_get_cart_count', array( $this, 'get_cart_count' ) );

		// Search actions.
		add_action( 'wp_ajax_kha_search_products', array( $this, 'search_products' ) );
		add_action( 'wp_ajax_nopriv_kha_search_products', array( $this, 'search_products' ) );

		// Wishlist actions.
		add_action( 'wp_ajax_kha_add_to_wishlist', array( $this, 'add_to_wishlist' ) );
		add_action( 'wp_ajax_kha_remove_from_wishlist', array( $this, 'remove_from_wishlist' ) );

		// Order actions.
		add_action( 'wp_ajax_kha_create_order', array( $this, 'create_order' ) );
		add_action( 'wp_ajax_nopriv_kha_create_order', array( $this, 'create_order' ) );

		// Calculator actions.
		add_action( 'wp_ajax_kha_calculate_solar', array( $this, 'calculate_solar' ) );
		add_action( 'wp_ajax_nopriv_kha_calculate_solar', array( $this, 'calculate_solar' ) );

		// Product view tracking.
		add_action( 'wp_ajax_kha_track_view', array( $this, 'track_product_view' ) );
		add_action( 'wp_ajax_nopriv_kha_track_view', array( $this, 'track_product_view' ) );

		// Admin-only actions.
		add_action( 'wp_ajax_kha_get_gallery_images', array( $this, 'get_gallery_images' ) );
		add_action( 'wp_ajax_kha_get_bundle_products', array( $this, 'get_bundle_products' ) );
	}

	/**
	 * Add product to cart via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function add_to_cart() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$quantity   = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;

		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid product ID.', 'kha-solar' ) ) );
		}

		$cart   = new Cart();
		$result = $cart->add_to_cart( $product_id, $quantity );

		if ( $result ) {
			wp_send_json_success(
				array(
					'message'    => __( 'Product added to cart!', 'kha-solar' ),
					'cart_count' => $cart->get_cart_count(),
					'cart_total' => kha_solar_format_price( $cart->get_cart_total() ),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to add product to cart.', 'kha-solar' ) ) );
		}
	}

	/**
	 * Update cart item quantity via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function update_cart() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		$cart_item_id = isset( $_POST['cart_item_id'] ) ? absint( $_POST['cart_item_id'] ) : 0;
		$quantity     = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 0;

		if ( ! $cart_item_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid cart item.', 'kha-solar' ) ) );
		}

		$cart   = new Cart();
		$result = $cart->update_quantity( $cart_item_id, $quantity );

		if ( $result !== false ) {
			wp_send_json_success(
				array(
					'message'    => __( 'Cart updated successfully!', 'kha-solar' ),
					'cart_count' => $cart->get_cart_count(),
					'cart_total' => kha_solar_format_price( $cart->get_cart_total() ),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to update cart.', 'kha-solar' ) ) );
		}
	}

	/**
	 * Remove item from cart via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function remove_from_cart() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		$cart_item_id = isset( $_POST['cart_item_id'] ) ? absint( $_POST['cart_item_id'] ) : 0;

		if ( ! $cart_item_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid cart item.', 'kha-solar' ) ) );
		}

		$cart   = new Cart();
		$result = $cart->remove_item( $cart_item_id );

		if ( $result ) {
			wp_send_json_success(
				array(
					'message'    => __( 'Item removed from cart.', 'kha-solar' ),
					'cart_count' => $cart->get_cart_count(),
					'cart_total' => kha_solar_format_price( $cart->get_cart_total() ),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to remove item from cart.', 'kha-solar' ) ) );
		}
	}

	/**
	 * Get cart count via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function get_cart_count() {
		$cart = new Cart();

		wp_send_json_success(
			array(
				'count' => $cart->get_cart_count(),
				'total' => kha_solar_format_price( $cart->get_cart_total() ),
			)
		);
	}

	/**
	 * Search products via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function search_products() {
		// Check nonce based on context (admin or frontend)
		if ( is_admin() && isset( $_POST['nonce'] ) ) {
			check_ajax_referer( 'kha_product_data_nonce', 'nonce' );
		} else {
			check_ajax_referer( 'kha_solar_nonce', 'nonce' );
		}

		$search_term = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';
		$exclude     = isset( $_POST['exclude'] ) ? (array) $_POST['exclude'] : array();

		if ( empty( $search_term ) ) {
			wp_send_json_success( array() );
		}

		// Admin bundle search
		if ( is_admin() && current_user_can( 'edit_posts' ) ) {
			$args = array(
				'post_type'      => 'kha_product',
				's'              => $search_term,
				'posts_per_page' => 10,
				'post_status'    => 'publish',
			);

			if ( ! empty( $exclude ) ) {
				$args['post__not_in'] = array_map( 'absint', $exclude );
			}

			$query = new \WP_Query( $args );
			$products = array();

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$product_id = get_the_ID();
					$thumbnail  = get_the_post_thumbnail_url( $product_id, 'thumbnail' );
					$price      = get_post_meta( $product_id, '_price', true );

					$products[] = array(
						'id'    => $product_id,
						'title' => get_the_title(),
						'image' => $thumbnail ? $thumbnail : '',
						'price' => $price ? kha_solar_format_price( $price ) : '',
					);
				}
				wp_reset_postdata();
			}

			wp_send_json_success( $products );
		}

		// Frontend search
		$search  = new Search();
		$results = $search->search( $search_term );

		wp_send_json_success( array( 'products' => $results ) );
	}

	/**
	 * Add product to wishlist via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function add_to_wishlist() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Please login to add to wishlist.', 'kha-solar' ) ) );
		}

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid product ID.', 'kha-solar' ) ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . KHA_DB_PREFIX . 'wishlist';
		$user_id    = get_current_user_id();

		$result = $wpdb->insert(
			$table_name,
			array(
				'user_id'    => $user_id,
				'product_id' => $product_id,
			),
			array( '%d', '%d' )
		);

		if ( $result ) {
			wp_send_json_success( array( 'message' => __( 'Added to wishlist!', 'kha-solar' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Already in wishlist or failed to add.', 'kha-solar' ) ) );
		}
	}

	/**
	 * Remove product from wishlist via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function remove_from_wishlist() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => __( 'Please login.', 'kha-solar' ) ) );
		}

		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid product ID.', 'kha-solar' ) ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . KHA_DB_PREFIX . 'wishlist';
		$user_id    = get_current_user_id();

		$result = $wpdb->delete(
			$table_name,
			array(
				'user_id'    => $user_id,
				'product_id' => $product_id,
			),
			array( '%d', '%d' )
		);

		if ( $result ) {
			wp_send_json_success( array( 'message' => __( 'Removed from wishlist!', 'kha-solar' ) ) );
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to remove from wishlist.', 'kha-solar' ) ) );
		}
	}

	/**
	 * Create order via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function create_order() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		$order_data = isset( $_POST['order_data'] ) ? $_POST['order_data'] : array();

		if ( empty( $order_data ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid order data.', 'kha-solar' ) ) );
		}

		$order  = new Order();
		$result = $order->create_order( $order_data );

		if ( $result ) {
			wp_send_json_success(
				array(
					'message'  => __( 'Order created successfully!', 'kha-solar' ),
					'order_id' => $result,
				)
			);
		} else {
			wp_send_json_error( array( 'message' => __( 'Failed to create order.', 'kha-solar' ) ) );
		}
	}

	/**
	 * Calculate solar system requirements via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function calculate_solar() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		$daily_usage = isset( $_POST['daily_usage'] ) ? floatval( $_POST['daily_usage'] ) : 0;

		if ( ! $daily_usage ) {
			wp_send_json_error( array( 'message' => __( 'Invalid daily usage.', 'kha-solar' ) ) );
		}

		$calculator = new Calculator();
		$result     = $calculator->calculate( $daily_usage );

		wp_send_json_success( $result );
	}

	/**
	 * Track product view via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function track_product_view() {
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

		if ( ! $product_id ) {
			wp_send_json_error();
		}

		global $wpdb;
		$table_name = $wpdb->prefix . KHA_DB_PREFIX . 'product_views';

		// Check if product exists in table.
		$existing = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} WHERE product_id = %d",
				$product_id
			)
		);

		if ( $existing ) {
			// Update count.
			$wpdb->update(
				$table_name,
				array( 'view_count' => $existing->view_count + 1 ),
				array( 'product_id' => $product_id ),
				array( '%d' ),
				array( '%d' )
			);
		} else {
			// Insert new.
			$wpdb->insert(
				$table_name,
				array(
					'product_id' => $product_id,
					'view_count' => 1,
				),
				array( '%d', '%d' )
			);
		}

		wp_send_json_success();
	}

	/**
	 * Get gallery images for admin.
	 *
	 * @since 1.0.0
	 */
	public function get_gallery_images() {
		check_ajax_referer( 'kha_product_data_nonce', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized.', 'kha-solar' ) ) );
		}

		$image_ids = isset( $_POST['image_ids'] ) ? (array) $_POST['image_ids'] : array();

		if ( empty( $image_ids ) ) {
			wp_send_json_success( array() );
		}

		$images = array();

		foreach ( $image_ids as $image_id ) {
			$image_id = absint( $image_id );
			$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );

			if ( $image_url ) {
				$images[] = array(
					'id'  => $image_id,
					'url' => $image_url,
				);
			}
		}

		wp_send_json_success( $images );
	}

	/**
	 * Get bundle products for admin.
	 *
	 * @since 1.0.0
	 */
	public function get_bundle_products() {
		check_ajax_referer( 'kha_product_data_nonce', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized.', 'kha-solar' ) ) );
		}

		$product_ids = isset( $_POST['product_ids'] ) ? (array) $_POST['product_ids'] : array();

		if ( empty( $product_ids ) ) {
			wp_send_json_success( array() );
		}

		$products = array();

		foreach ( $product_ids as $product_id ) {
			$product_id = absint( $product_id );
			$product    = get_post( $product_id );

			if ( ! $product || 'kha_product' !== $product->post_type ) {
				continue;
			}

			$thumbnail = get_the_post_thumbnail_url( $product_id, 'thumbnail' );
			$price     = get_post_meta( $product_id, '_price', true );

			$products[] = array(
				'id'    => $product_id,
				'title' => $product->post_title,
				'image' => $thumbnail ? $thumbnail : '',
				'price' => $price ? kha_solar_format_price( $price ) : '',
			);
		}

		wp_send_json_success( $products );
	}
}
