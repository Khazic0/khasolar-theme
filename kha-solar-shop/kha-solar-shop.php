<?php
/**
 * Plugin Name: Kha Solar Shop
 * Plugin URI: https://khasolar.com
 * Description: Custom lightweight e-commerce solution for solar equipment with advanced features including product comparison, solar calculator, and smart search
 * Version: 1.0.0
 * Author: Kha Solar Team
 * Author URI: https://khasolar.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: kha-solar
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 *
 * @package KhaSolar
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'KHA_VERSION', '1.0.0' );

/**
 * Plugin directory path.
 */
define( 'KHA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'KHA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 */
define( 'KHA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Database table prefix.
 */
define( 'KHA_DB_PREFIX', 'kha_' );

/**
 * PSR-4 Autoloader for plugin classes.
 *
 * @param string $class The fully-qualified class name.
 */
function kha_solar_autoloader( $class ) {
	// Project-specific namespace prefix.
	$prefix = 'KhaSolar\\';

	// Base directory for the namespace prefix.
	$base_dir = KHA_PLUGIN_DIR . 'includes/';

	// Does the class use the namespace prefix?
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		// No, move to the next registered autoloader.
		return;
	}

	// Get the relative class name.
	$relative_class = substr( $class, $len );

	// Replace namespace separators with directory separators.
	// Convert to lowercase and add class- prefix for WordPress naming convention.
	$file = $base_dir . 'class-' . strtolower( str_replace( '\\', '-', str_replace( '_', '-', $relative_class ) ) ) . '.php';

	// If the file exists, require it.
	if ( file_exists( $file ) ) {
		require $file;
	}
}
spl_autoload_register( 'kha_solar_autoloader' );

/**
 * Load product helper functions.
 */
require_once KHA_PLUGIN_DIR . 'includes/product-functions.php';

/**
 * The code that runs during plugin activation.
 */
function activate_kha_solar_shop() {
	require_once KHA_PLUGIN_DIR . 'includes/class-activator.php';
	KhaSolar\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_kha_solar_shop() {
	require_once KHA_PLUGIN_DIR . 'includes/class-activator.php';
	KhaSolar\Activator::deactivate();
}

register_activation_hook( __FILE__, 'activate_kha_solar_shop' );
register_deactivation_hook( __FILE__, 'deactivate_kha_solar_shop' );

/**
 * Load plugin text domain for translations.
 */
function kha_solar_load_textdomain() {
	load_plugin_textdomain(
		'kha-solar',
		false,
		dirname( KHA_PLUGIN_BASENAME ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'kha_solar_load_textdomain' );

/**
 * Initialize the plugin.
 */
function kha_solar_init() {
	// Register custom post types.
	if ( class_exists( 'KhaSolar\Product_Post_Type' ) ) {
		$product_post_type = new KhaSolar\Product_Post_Type();
		$product_post_type->init();
	}

	// Initialize cart functionality.
	if ( class_exists( 'KhaSolar\Cart' ) ) {
		$cart = new KhaSolar\Cart();
		$cart->init();
	}

	// Initialize order processing.
	if ( class_exists( 'KhaSolar\Order' ) ) {
		$order = new KhaSolar\Order();
		$order->init();
	}

	// Initialize AJAX handlers.
	if ( class_exists( 'KhaSolar\Ajax_Handler' ) ) {
		$ajax_handler = new KhaSolar\Ajax_Handler();
		$ajax_handler->init();
	}

	// Initialize search functionality.
	if ( class_exists( 'KhaSolar\Search' ) ) {
		$search = new KhaSolar\Search();
		$search->init();
	}

	// Initialize calculator.
	if ( class_exists( 'KhaSolar\Calculator' ) ) {
		$calculator = new KhaSolar\Calculator();
		$calculator->init();
	}

	// Initialize comparison.
	if ( class_exists( 'KhaSolar\Comparison' ) ) {
		$comparison = new KhaSolar\Comparison();
		$comparison->init();
	}

	// Initialize admin functionality.
	if ( is_admin() ) {
		if ( class_exists( 'KhaSolar\Product_Meta_Boxes' ) ) {
			$meta_boxes = new KhaSolar\Product_Meta_Boxes();
			$meta_boxes->init();
		}

		if ( class_exists( 'KhaSolar\Order_Management' ) ) {
			$order_management = new KhaSolar\Order_Management();
			$order_management->init();
		}
	}

	// Initialize chat bubble widget.
	if ( class_exists( 'KhaSolar\Chat_Bubble' ) ) {
		$chat_bubble = new KhaSolar\Chat_Bubble();
		$chat_bubble->init();
	}
}
add_action( 'init', 'kha_solar_init' );

/**
 * Enqueue admin scripts and styles.
 *
 * @param string $hook The current admin page.
 */
function kha_solar_enqueue_admin_assets( $hook ) {
	// Only load on product edit screens and order management pages.
	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}

	if ( 'kha_product' === $screen->post_type || strpos( $hook, 'kha-solar' ) !== false ) {
		// Enqueue admin CSS.
		wp_enqueue_style(
			'kha-solar-admin',
			KHA_PLUGIN_URL . 'admin/css/admin-style.css',
			array(),
			KHA_VERSION
		);

		// Enqueue admin JS.
		wp_enqueue_script(
			'kha-solar-admin',
			KHA_PLUGIN_URL . 'admin/js/admin-script.js',
			array( 'jquery' ),
			KHA_VERSION,
			true
		);

		// Localize script for AJAX.
		wp_localize_script(
			'kha-solar-admin',
			'khaSolarAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'kha_solar_admin_nonce' ),
			)
		);
	}
}
add_action( 'admin_enqueue_scripts', 'kha_solar_enqueue_admin_assets' );

/**
 * Enqueue public scripts and styles.
 */
function kha_solar_enqueue_public_assets() {
	// Only load on relevant pages.
	if ( ! is_singular( 'kha_product' ) && ! is_post_type_archive( 'kha_product' ) && ! kha_solar_is_shop_page() ) {
		return;
	}

	// Enqueue public CSS.
	wp_enqueue_style(
		'kha-solar-shop',
		KHA_PLUGIN_URL . 'public/css/shop.css',
		array(),
		KHA_VERSION
	);

	wp_enqueue_style(
		'kha-solar-cart',
		KHA_PLUGIN_URL . 'public/css/cart.css',
		array(),
		KHA_VERSION
	);

	wp_enqueue_style(
		'kha-solar-calculator',
		KHA_PLUGIN_URL . 'public/css/calculator.css',
		array(),
		KHA_VERSION
	);

	wp_enqueue_style(
		'kha-solar-comparison',
		KHA_PLUGIN_URL . 'public/css/comparison.css',
		array(),
		KHA_VERSION
	);

	// Enqueue public JS.
	wp_enqueue_script(
		'kha-solar-search',
		KHA_PLUGIN_URL . 'public/js/search-autocomplete.js',
		array( 'jquery' ),
		KHA_VERSION,
		true
	);

	wp_enqueue_script(
		'kha-solar-filter',
		KHA_PLUGIN_URL . 'public/js/filter-products.js',
		array( 'jquery' ),
		KHA_VERSION,
		true
	);

	wp_enqueue_script(
		'kha-solar-add-to-cart',
		KHA_PLUGIN_URL . 'public/js/add-to-cart.js',
		array( 'jquery' ),
		KHA_VERSION,
		true
	);

	wp_enqueue_script(
		'kha-solar-cart-handler',
		KHA_PLUGIN_URL . 'public/js/cart-handler.js',
		array( 'jquery' ),
		KHA_VERSION,
		true
	);

	wp_enqueue_script(
		'kha-solar-comparison',
		KHA_PLUGIN_URL . 'public/js/comparison.js',
		array( 'jquery' ),
		KHA_VERSION,
		true
	);

	wp_enqueue_script(
		'kha-solar-calculator',
		KHA_PLUGIN_URL . 'public/js/solar-calculator.js',
		array( 'jquery' ),
		KHA_VERSION,
		true
	);

	// Localize scripts for AJAX.
	wp_localize_script(
		'kha-solar-add-to-cart',
		'khaSolar',
		array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'nonce'       => wp_create_nonce( 'kha_solar_nonce' ),
			'cartUrl'     => kha_solar_get_page_url( 'cart' ),
			'checkoutUrl' => kha_solar_get_page_url( 'checkout' ),
			'i18n'        => array(
				'addedToCart'   => __( 'Product added to cart!', 'kha-solar' ),
				'error'         => __( 'An error occurred. Please try again.', 'kha-solar' ),
				'loading'       => __( 'Loading...', 'kha-solar' ),
				'removeConfirm' => __( 'Are you sure you want to remove this item?', 'kha-solar' ),
			),
		)
	);

	// Localize cart handler.
	wp_localize_script(
		'kha-solar-cart-handler',
		'khaCartConfig',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'kha_solar_nonce' ),
		)
	);

	// Localize calculator.
	wp_localize_script(
		'kha-solar-calculator',
		'khaCalculatorConfig',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'kha_solar_nonce' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'kha_solar_enqueue_public_assets' );

/**
 * Check if current page is a shop page.
 *
 * @return bool
 */
function kha_solar_is_shop_page() {
	$shop_page_id    = get_option( 'kha_solar_shop_page_id' );
	$cart_page_id    = get_option( 'kha_solar_cart_page_id' );
	$checkout_page_id = get_option( 'kha_solar_checkout_page_id' );
	$calc_page_id    = get_option( 'kha_solar_calculator_page_id' );
	$compare_page_id = get_option( 'kha_solar_comparison_page_id' );

	$current_page_id = get_the_ID();

	return in_array(
		$current_page_id,
		array( $shop_page_id, $cart_page_id, $checkout_page_id, $calc_page_id, $compare_page_id ),
		true
	);
}

/**
 * Get page URL by page type.
 *
 * @param string $page_type Type of page (shop, cart, checkout, calculator, comparison).
 * @return string
 */
function kha_solar_get_page_url( $page_type ) {
	$page_id = get_option( 'kha_solar_' . $page_type . '_page_id' );

	if ( $page_id ) {
		return get_permalink( $page_id );
	}

	return home_url( '/' );
}

/**
 * Load template files.
 *
 * @param string $template_name Template file name.
 * @param array  $args          Arguments to pass to template.
 * @param string $template_path Path to templates folder.
 */
function kha_solar_get_template( $template_name, $args = array(), $template_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
	}

	if ( ! $template_path ) {
		$template_path = KHA_PLUGIN_DIR . 'templates/';
	}

	$template_file = $template_path . $template_name;

	if ( file_exists( $template_file ) ) {
		include $template_file;
	}
}

/**
 * Format price with currency symbol.
 *
 * @param float $price Price to format.
 * @return string
 */
function kha_solar_format_price( $price ) {
	$currency_symbol = get_option( 'kha_solar_currency_symbol', '₫' );
	$currency_position = get_option( 'kha_solar_currency_position', 'right' );
	$decimal_separator = get_option( 'kha_solar_decimal_separator', ',' );
	$thousand_separator = get_option( 'kha_solar_thousand_separator', '.' );
	$decimals = get_option( 'kha_solar_decimals', 0 );

	$formatted_price = number_format( $price, $decimals, $decimal_separator, $thousand_separator );

	if ( 'left' === $currency_position ) {
		return $currency_symbol . $formatted_price;
	}

	return $formatted_price . ' ' . $currency_symbol;
}

/**
 * Load custom search template for product searches.
 *
 * @param string $template The path to the template.
 * @return string
 */
function kha_solar_search_template( $template ) {
	global $wp_query;

	// Check if this is a search for products
	if ( is_search() && isset( $_GET['post_type'] ) && 'kha_product' === $_GET['post_type'] ) {
		$custom_template = KHA_PLUGIN_DIR . 'templates/search-results.php';
		
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'kha_solar_search_template' );

/**
 * Enqueue product filtering scripts.
 */
function kha_solar_enqueue_filter_scripts() {
	if ( ! is_admin() && ( is_post_type_archive( 'kha_product' ) || is_tax( array( 'kha_product_cat', 'kha_brand' ) ) || has_shortcode( get_post()->post_content, 'kha_products_grid' ) ) ) {
		wp_enqueue_script(
			'kha-filter-products',
			KHA_PLUGIN_URL . 'public/js/filter-products.js',
			array( 'jquery' ),
			KHA_VERSION,
			true
		);

		wp_localize_script(
			'kha-filter-products',
			'khaFilterConfig',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'kha_solar_nonce' ),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'kha_solar_enqueue_filter_scripts' );

/**
 * Products grid shortcode.
 *
 * Usage: [kha_products_grid filters="true" columns="4" per_page="12"]
 *
 * @param array $atts Shortcode attributes.
 * @return string Shortcode output.
 */
function kha_solar_products_grid_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'filters'  => 'true',
			'columns'  => '4',
			'per_page' => '12',
		),
		$atts,
		'kha_products_grid'
	);

	ob_start();

	if ( 'true' === $atts['filters'] ) {
		// Include full archive template with filters
		kha_solar_get_template( 'archive-products.php' );
	} else {
		// Just the products grid
		$args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => absint( $atts['per_page'] ),
			'post_status'    => 'publish',
		);

		$products_query = new WP_Query( $args );

		echo '<div class="kha-products-grid kha-grid-cols-' . esc_attr( $atts['columns'] ) . '">';

		if ( $products_query->have_posts() ) {
			while ( $products_query->have_posts() ) {
				$products_query->the_post();
				kha_solar_get_template( 'partials/product-card.php', array( 'product_id' => get_the_ID() ) );
			}
			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__( 'Không có sản phẩm nào.', 'kha-solar' ) . '</p>';
		}

		echo '</div>';
	}

	return ob_get_clean();
}
add_shortcode( 'kha_products_grid', 'kha_solar_products_grid_shortcode' );

/**
 * Load custom archive template for products.
 *
 * @param string $template The path to the template.
 * @return string
 */
function kha_solar_archive_template( $template ) {
	if ( is_post_type_archive( 'kha_product' ) || is_tax( array( 'kha_product_cat', 'kha_brand' ) ) ) {
		$custom_template = KHA_PLUGIN_DIR . 'templates/archive-products.php';
		
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'kha_solar_archive_template', 99 );

/**
 * Display mini cart.
 *
 * Template function for theme integration.
 *
 * @since 1.0.0
 */
function kha_mini_cart() {
	kha_solar_get_template( 'partials/mini-cart.php' );
}

/**
 * Get cart count.
 *
 * Helper function to get current cart item count.
 *
 * @since 1.0.0
 * @return int Cart count.
 */
function kha_get_cart_count() {
	$cart = new KhaSolar\Cart();
	return $cart->get_cart_count();
}
