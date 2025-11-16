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

		// Autocomplete search.
		add_action( 'wp_ajax_kha_autocomplete_search', array( $this, 'autocomplete_search' ) );
		add_action( 'wp_ajax_nopriv_kha_autocomplete_search', array( $this, 'autocomplete_search' ) );

		// Product filtering.
		add_action( 'wp_ajax_kha_filter_products', array( $this, 'filter_products' ) );
		add_action( 'wp_ajax_nopriv_kha_filter_products', array( $this, 'filter_products' ) );
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

	/**
	 * Autocomplete search for products.
	 *
	 * @since 1.0.0
	 */
	public function autocomplete_search() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		$search_term = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

		if ( empty( $search_term ) || strlen( $search_term ) < 2 ) {
			wp_send_json_success(
				array(
					'products'   => array(),
					'categories' => array(),
					'popular'    => array(),
				)
			);
		}

		$search = new Search();
		$suggestions = $search->get_autocomplete_suggestions( $search_term, 8 );

		// Track the search.
		$result_count = count( $suggestions['products'] );
		$search->track_search( $search_term, $result_count );

		wp_send_json_success( $suggestions );
	}

	/**
	 * Filter products with AJAX.
	 *
	 * @since 1.0.0
	 */
	public function filter_products() {
		check_ajax_referer( 'kha_solar_nonce', 'nonce' );

		$filters = isset( $_POST['filters'] ) ? $_POST['filters'] : array();

		// Build query args
		$args = array(
			'post_type'      => 'kha_product',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'paged'          => isset( $filters['paged'] ) ? absint( $filters['paged'] ) : 1,
		);

		// Search query
		if ( ! empty( $filters['search'] ) ) {
			$args['s'] = sanitize_text_field( $filters['search'] );
		}

		// Order by
		if ( ! empty( $filters['orderby'] ) ) {
			switch ( $filters['orderby'] ) {
				case 'price':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_price';
					$args['order']    = 'ASC';
					break;
				case 'price-desc':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_price';
					$args['order']    = 'DESC';
					break;
				case 'popularity':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_view_count';
					$args['order']    = 'DESC';
					break;
				case 'rating':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_average_rating';
					$args['order']    = 'DESC';
					break;
				default:
					$args['orderby'] = 'date';
					$args['order']   = 'DESC';
			}
		}

		// Taxonomy filters
		$tax_query = array();

		if ( ! empty( $filters['categories'] ) && is_array( $filters['categories'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'kha_product_cat',
				'field'    => 'term_id',
				'terms'    => array_map( 'absint', $filters['categories'] ),
			);
		}

		if ( ! empty( $filters['brands'] ) && is_array( $filters['brands'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'kha_brand',
				'field'    => 'term_id',
				'terms'    => array_map( 'absint', $filters['brands'] ),
			);
		}

		if ( count( $tax_query ) > 1 ) {
			$tax_query['relation'] = 'AND';
		}

		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = $tax_query;
		}

		// Meta query
		$meta_query = array();

		// Price range
		if ( ! empty( $filters['priceMin'] ) || ! empty( $filters['priceMax'] ) ) {
			$price_query = array( 'key' => '_price', 'type' => 'NUMERIC' );

			if ( ! empty( $filters['priceMin'] ) && ! empty( $filters['priceMax'] ) ) {
				$price_query['value']   = array( floatval( $filters['priceMin'] ), floatval( $filters['priceMax'] ) );
				$price_query['compare'] = 'BETWEEN';
			} elseif ( ! empty( $filters['priceMin'] ) ) {
				$price_query['value']   = floatval( $filters['priceMin'] );
				$price_query['compare'] = '>=';
			} elseif ( ! empty( $filters['priceMax'] ) ) {
				$price_query['value']   = floatval( $filters['priceMax'] );
				$price_query['compare'] = '<=';
			}

			$meta_query[] = $price_query;
		}

		// Power ranges
		if ( ! empty( $filters['powerRanges'] ) && is_array( $filters['powerRanges'] ) ) {
			$power_query = array( 'relation' => 'OR' );

			foreach ( $filters['powerRanges'] as $range ) {
				switch ( $range ) {
					case '0-3000':
						$power_query[] = array(
							'key'     => '_power_output',
							'value'   => 3000,
							'type'    => 'NUMERIC',
							'compare' => '<',
						);
						break;
					case '3000-5000':
						$power_query[] = array(
							'key'     => '_power_output',
							'value'   => array( 3000, 5000 ),
							'type'    => 'NUMERIC',
							'compare' => 'BETWEEN',
						);
						break;
					case '5000-10000':
						$power_query[] = array(
							'key'     => '_power_output',
							'value'   => array( 5000, 10000 ),
							'type'    => 'NUMERIC',
							'compare' => 'BETWEEN',
						);
						break;
					case '10000+':
						$power_query[] = array(
							'key'     => '_power_output',
							'value'   => 10000,
							'type'    => 'NUMERIC',
							'compare' => '>',
						);
						break;
				}
			}

			if ( count( $power_query ) > 1 ) {
				$meta_query[] = $power_query;
			}
		}

		// Stock status
		if ( ! empty( $filters['stockStatus'] ) && is_array( $filters['stockStatus'] ) ) {
			$meta_query[] = array(
				'key'     => '_stock_status',
				'value'   => array_map( 'sanitize_text_field', $filters['stockStatus'] ),
				'compare' => 'IN',
			);
		}

		// On sale
		if ( ! empty( $filters['onSale'] ) ) {
			$meta_query[] = array(
				'key'     => '_sale_price',
				'value'   => 0,
				'type'    => 'NUMERIC',
				'compare' => '>',
			);
		}

		// New products (last 30 days)
		if ( ! empty( $filters['isNew'] ) ) {
			$args['date_query'] = array(
				array(
					'after' => '30 days ago',
				),
			);
		}

		if ( count( $meta_query ) > 1 ) {
			$meta_query['relation'] = 'AND';
		}

		if ( ! empty( $meta_query ) ) {
			$args['meta_query'] = $meta_query;
		}

		// Execute query
		$products_query = new \WP_Query( $args );

		// Build products HTML
		$products_html = '';
		if ( $products_query->have_posts() ) {
			while ( $products_query->have_posts() ) {
				$products_query->the_post();
				$products_html .= $this->render_product_card( get_the_ID() );
			}
			wp_reset_postdata();
		} else {
			$products_html = $this->render_no_products();
		}

		// Build pagination HTML
		$pagination_html = '';
		if ( $products_query->max_num_pages > 1 ) {
			$pagination_html = paginate_links(
				array(
					'total'     => $products_query->max_num_pages,
					'current'   => $args['paged'],
					'type'      => 'list',
					'prev_text' => '&laquo; ' . __( 'Trước', 'kha-solar' ),
					'next_text' => __( 'Sau', 'kha-solar' ) . ' &raquo;',
				)
			);
		}

		wp_send_json_success(
			array(
				'products'      => $products_html,
				'pagination'    => $pagination_html,
				'found_posts'   => $products_query->found_posts,
				'max_num_pages' => $products_query->max_num_pages,
			)
		);
	}

	/**
	 * Render product card HTML.
	 *
	 * @param int $product_id Product ID.
	 * @return string Product card HTML.
	 */
	private function render_product_card( $product_id ) {
		$thumbnail    = get_the_post_thumbnail_url( $product_id, 'medium' );
		$price        = get_post_meta( $product_id, '_price', true );
		$regular_price = get_post_meta( $product_id, '_regular_price', true );
		$sale_price   = get_post_meta( $product_id, '_sale_price', true );
		$on_sale      = kha_is_product_on_sale( $product_id );
		$power        = get_post_meta( $product_id, '_power_output', true );
		$brand        = kha_get_product_brand( $product_id );

		$html = '<article class="kha-product-card">';
		$html .= '<a href="' . get_permalink( $product_id ) . '" class="kha-product-link">';

		// Image
		$html .= '<div class="kha-product-image">';
		if ( $thumbnail ) {
			$html .= '<img src="' . esc_url( $thumbnail ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy">';
		} else {
			$html .= '<div class="kha-no-image"><span class="dashicons dashicons-camera"></span></div>';
		}

		// Sale badge
		if ( $on_sale && $regular_price && $sale_price ) {
			$discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
			$html .= '<span class="kha-sale-badge">-' . $discount . '%</span>';
		}

		$html .= '</div>';

		// Info
		$html .= '<div class="kha-product-info">';
		$html .= '<h3 class="kha-product-title">' . get_the_title() . '</h3>';

		// Price
		$html .= '<div class="kha-product-price">';
		if ( $on_sale && $sale_price ) {
			$html .= '<span class="kha-price-sale">' . kha_solar_format_price( $sale_price ) . '</span>';
			$html .= '<span class="kha-price-regular">' . kha_solar_format_price( $regular_price ) . '</span>';
		} elseif ( $price ) {
			$html .= '<span class="kha-price-current">' . kha_solar_format_price( $price ) . '</span>';
		} else {
			$html .= '<span class="kha-price-contact">' . __( 'Liên Hệ', 'kha-solar' ) . '</span>';
		}
		$html .= '</div>';

		// Meta
		$html .= '<div class="kha-product-meta">';
		if ( $power ) {
			$power_formatted = $power >= 1000 ? ( $power / 1000 ) . ' kW' : $power . ' W';
			$html .= '<span class="kha-meta-item"><span class="dashicons dashicons-admin-plugins"></span> ' . $power_formatted . '</span>';
		}
		if ( $brand ) {
			$html .= '<span class="kha-meta-item"><span class="dashicons dashicons-admin-home"></span> ' . esc_html( $brand->name ) . '</span>';
		}
		$html .= '</div>';

		// Stock status
		$html .= '<div class="kha-product-stock">';
		$html .= kha_get_product_stock_status( $product_id );
		$html .= '</div>';

		$html .= '</div>'; // .kha-product-info
		$html .= '</a>';
		$html .= '</article>';

		return $html;
	}

	/**
	 * Render no products message.
	 *
	 * @return string No products HTML.
	 */
	private function render_no_products() {
		$html = '<div class="kha-no-products">';
		$html .= '<div class="kha-no-products-icon">📦</div>';
		$html .= '<h3>' . __( 'Không tìm thấy sản phẩm nào', 'kha-solar' ) . '</h3>';
		$html .= '<p>' . __( 'Vui lòng thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác.', 'kha-solar' ) . '</p>';
		$html .= '</div>';

		return $html;
	}
}
