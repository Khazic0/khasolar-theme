<?php
/**
 * Fired during plugin activation and deactivation.
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
 * Activator class.
 *
 * Handles plugin activation and deactivation tasks.
 */
class Activator {

	/**
	 * Plugin activation tasks.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		// Create database tables.
		self::create_tables();

		// Create necessary pages.
		self::create_pages();

		// Set default options.
		self::set_default_options();

		// Create product image sizes.
		self::create_image_sizes();

		// Create default taxonomy terms.
		self::create_default_terms();

		// Flush rewrite rules.
		self::flush_rewrite_rules();

		// Set activation flag.
		update_option( 'kha_solar_activated', true );
		update_option( 'kha_solar_version', KHA_VERSION );
	}

	/**
	 * Plugin deactivation tasks.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate() {
		// Flush rewrite rules.
		flush_rewrite_rules();

		// Remove activation flag.
		delete_option( 'kha_solar_activated' );
	}

	/**
	 * Create database tables.
	 *
	 * @since 1.0.0
	 */
	private static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_prefix    = $wpdb->prefix . KHA_DB_PREFIX;

		// Cart table.
		$cart_table = $table_prefix . 'cart';
		$cart_sql   = "CREATE TABLE IF NOT EXISTS {$cart_table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned DEFAULT NULL,
			session_id varchar(255) NOT NULL,
			product_id bigint(20) unsigned NOT NULL,
			quantity int(11) NOT NULL DEFAULT 1,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY user_id (user_id),
			KEY session_id (session_id),
			KEY product_id (product_id)
		) {$charset_collate};";

		// Wishlist table.
		$wishlist_table = $table_prefix . 'wishlist';
		$wishlist_sql   = "CREATE TABLE IF NOT EXISTS {$wishlist_table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL,
			product_id bigint(20) unsigned NOT NULL,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY user_product (user_id, product_id),
			KEY user_id (user_id),
			KEY product_id (product_id)
		) {$charset_collate};";

		// Product views table.
		$views_table = $table_prefix . 'product_views';
		$views_sql   = "CREATE TABLE IF NOT EXISTS {$views_table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			product_id bigint(20) unsigned NOT NULL,
			view_count bigint(20) unsigned NOT NULL DEFAULT 0,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY product_id (product_id)
		) {$charset_collate};";

		// Orders table.
		$orders_table = $table_prefix . 'orders';
		$orders_sql   = "CREATE TABLE IF NOT EXISTS {$orders_table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			order_number varchar(50) NOT NULL,
			user_id bigint(20) unsigned DEFAULT NULL,
			session_id varchar(255) DEFAULT NULL,
			customer_name varchar(255) NOT NULL,
			customer_email varchar(255) NOT NULL,
			customer_phone varchar(50) NOT NULL,
			customer_address text NOT NULL,
			customer_city varchar(100) DEFAULT NULL,
			customer_province varchar(100) DEFAULT NULL,
			customer_notes text DEFAULT NULL,
			subtotal decimal(10,2) NOT NULL DEFAULT 0.00,
			tax decimal(10,2) NOT NULL DEFAULT 0.00,
			shipping decimal(10,2) NOT NULL DEFAULT 0.00,
			total decimal(10,2) NOT NULL DEFAULT 0.00,
			status varchar(50) NOT NULL DEFAULT 'pending',
			payment_method varchar(50) DEFAULT NULL,
			payment_status varchar(50) NOT NULL DEFAULT 'pending',
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY order_number (order_number),
			KEY user_id (user_id),
			KEY status (status),
			KEY created_at (created_at)
		) {$charset_collate};";

		// Order items table.
		$order_items_table = $table_prefix . 'order_items';
		$order_items_sql   = "CREATE TABLE IF NOT EXISTS {$order_items_table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			order_id bigint(20) unsigned NOT NULL,
			product_id bigint(20) unsigned NOT NULL,
			product_name varchar(255) NOT NULL,
			product_sku varchar(100) DEFAULT NULL,
			quantity int(11) NOT NULL DEFAULT 1,
			price decimal(10,2) NOT NULL DEFAULT 0.00,
			subtotal decimal(10,2) NOT NULL DEFAULT 0.00,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY order_id (order_id),
			KEY product_id (product_id)
		) {$charset_collate};";

		// Product comparisons table (for tracking popular comparisons).
		$comparisons_table = $table_prefix . 'comparisons';
		$comparisons_sql   = "CREATE TABLE IF NOT EXISTS {$comparisons_table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned DEFAULT NULL,
			session_id varchar(255) NOT NULL,
			product_ids text NOT NULL,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY user_id (user_id),
			KEY session_id (session_id)
		) {$charset_collate};";

		// Execute SQL.
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $cart_sql );
		dbDelta( $wishlist_sql );
		dbDelta( $views_sql );
		dbDelta( $orders_sql );
		dbDelta( $order_items_sql );
		dbDelta( $comparisons_sql );
	}

	/**
	 * Create necessary pages.
	 *
	 * @since 1.0.0
	 */
	private static function create_pages() {
		$pages = array(
			'shop'       => array(
				'title'   => __( 'Shop', 'kha-solar' ),
				'content' => '[kha_solar_products]',
				'option'  => 'kha_solar_shop_page_id',
			),
			'cart'       => array(
				'title'   => __( 'Cart', 'kha-solar' ),
				'content' => '[kha_solar_cart]',
				'option'  => 'kha_solar_cart_page_id',
			),
			'checkout'   => array(
				'title'   => __( 'Checkout', 'kha-solar' ),
				'content' => '[kha_solar_checkout]',
				'option'  => 'kha_solar_checkout_page_id',
			),
			'calculator' => array(
				'title'   => __( 'Solar Calculator', 'kha-solar' ),
				'content' => '[kha_solar_calculator]',
				'option'  => 'kha_solar_calculator_page_id',
			),
			'comparison' => array(
				'title'   => __( 'Product Comparison', 'kha-solar' ),
				'content' => '[kha_solar_comparison]',
				'option'  => 'kha_solar_comparison_page_id',
			),
		);

		foreach ( $pages as $slug => $page_data ) {
			// Check if page already exists.
			$page_id = get_option( $page_data['option'] );

			if ( ! $page_id || ! get_post( $page_id ) ) {
				// Create page.
				$page_id = wp_insert_post(
					array(
						'post_title'   => $page_data['title'],
						'post_content' => $page_data['content'],
						'post_status'  => 'publish',
						'post_type'    => 'page',
						'post_name'    => $slug,
					)
				);

				if ( $page_id && ! is_wp_error( $page_id ) ) {
					update_option( $page_data['option'], $page_id );
				}
			}
		}
	}

	/**
	 * Set default plugin options.
	 *
	 * @since 1.0.0
	 */
	private static function set_default_options() {
		$defaults = array(
			// Currency settings.
			'kha_solar_currency_symbol'    => '₫',
			'kha_solar_currency_position'  => 'right',
			'kha_solar_thousand_separator' => '.',
			'kha_solar_decimal_separator'  => ',',
			'kha_solar_decimals'           => 0,

			// Product settings.
			'kha_solar_products_per_page'  => 12,
			'kha_solar_product_image_size' => 'medium',

			// Cart settings.
			'kha_solar_enable_cart'        => 'yes',
			'kha_solar_cart_session_time'  => 7, // Days.

			// Checkout settings.
			'kha_solar_enable_guest_checkout' => 'yes',
			'kha_solar_order_prefix'       => 'KHS-',

			// Email settings.
			'kha_solar_admin_email'        => get_option( 'admin_email' ),
			'kha_solar_enable_order_emails' => 'yes',

			// Calculator settings.
			'kha_solar_avg_daily_usage'    => 15, // kWh.
			'kha_solar_avg_sunlight_hours' => 4.5,
			'kha_solar_system_efficiency'  => 0.8,

			// Chat settings.
			'kha_solar_enable_chat'        => 'yes',
			'kha_solar_zalo_phone'         => '',
			'kha_solar_messenger_id'       => '',
		);

		foreach ( $defaults as $option => $value ) {
			if ( false === get_option( $option ) ) {
				add_option( $option, $value );
			}
		}
	}

	/**
	 * Register product image sizes.
	 *
	 * @since 1.0.0
	 */
	private static function create_image_sizes() {
		// Product thumbnail - 300x300.
		add_image_size( 'kha-product-thumbnail', 300, 300, true );

		// Product medium - 600x600.
		add_image_size( 'kha-product-medium', 600, 600, true );

		// Product large - 1200x1200.
		add_image_size( 'kha-product-large', 1200, 1200, true );

		// Product gallery thumbnail - 150x150.
		add_image_size( 'kha-product-gallery-thumb', 150, 150, true );
	}

	/**
	 * Create default taxonomy terms.
	 *
	 * Creates default product categories and brands.
	 *
	 * @since 1.0.0
	 */
	private static function create_default_terms() {
		// Register taxonomies first.
		if ( class_exists( 'KhaSolar\Product_Post_Type' ) ) {
			$product_post_type = new Product_Post_Type();
			$product_post_type->register_taxonomies();
		}

		// Default product categories.
		$categories = array(
			'inverter'       => __( 'Inverter (Biến tần)', 'kha-solar' ),
			'solar-battery'  => __( 'Pin Năng Lượng Mặt Trời', 'kha-solar' ),
			'solar-panel'    => __( 'Tấm Pin Mặt Trời', 'kha-solar' ),
			'accessories'    => __( 'Phụ Kiện & Thiết Bị Kèm Theo', 'kha-solar' ),
			'combo-packages' => __( 'Combo & Gói Lắp Đặt', 'kha-solar' ),
		);

		foreach ( $categories as $slug => $name ) {
			if ( ! term_exists( $slug, 'kha_product_cat' ) ) {
				wp_insert_term(
					$name,
					'kha_product_cat',
					array(
						'slug' => $slug,
					)
				);
			}
		}

		// Default brands.
		$brands = array(
			'growatt' => 'Growatt',
			'deye'    => 'Deye',
			'sma'     => 'SMA',
			'huawei'  => 'Huawei',
			'jinko'   => 'Jinko Solar',
			'longi'   => 'Longi Solar',
		);

		foreach ( $brands as $slug => $name ) {
			if ( ! term_exists( $slug, 'kha_brand' ) ) {
				wp_insert_term(
					$name,
					'kha_brand',
					array(
						'slug' => $slug,
					)
				);
			}
		}
	}

	/**
	 * Flush rewrite rules after registration.
	 *
	 * @since 1.0.0
	 */
	private static function flush_rewrite_rules() {
		// Register post types temporarily to flush rules.
		if ( class_exists( 'KhaSolar\Product_Post_Type' ) ) {
			$product_post_type = new Product_Post_Type();
			$product_post_type->register_post_type();
		} else {
			// Fallback registration.
			register_post_type(
				'kha_product',
				array(
					'public'      => true,
					'has_archive' => true,
					'rewrite'     => array( 'slug' => 'products' ),
				)
			);
		}

		flush_rewrite_rules();
	}
}
