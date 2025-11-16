<?php
/**
 * Product Comparison Class
 *
 * Handles product comparison functionality
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
 * Comparison class.
 */
class Comparison {

	/**
	 * Maximum number of products to compare.
	 *
	 * @var int
	 */
	private $max_products = 3;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'render_floating_bar' ) );
		add_shortcode( 'kha_comparison', array( $this, 'comparison_shortcode' ) );
	}

	/**
	 * Enqueue comparison scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		if ( ! is_admin() ) {
			// Enqueue CSS
			wp_enqueue_style(
				'kha-comparison',
				KHA_PLUGIN_URL . 'public/css/comparison.css',
				array(),
				KHA_VERSION
			);

			// Enqueue JS
			wp_enqueue_script(
				'kha-comparison',
				KHA_PLUGIN_URL . 'public/js/comparison.js',
				array( 'jquery' ),
				KHA_VERSION,
				true
			);

			wp_localize_script(
				'kha-comparison',
				'khaComparisonConfig',
				array(
					'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
					'nonce'       => wp_create_nonce( 'kha_solar_nonce' ),
					'maxProducts' => $this->max_products,
					'pageUrl'     => $this->get_comparison_page_url(),
					'strings'     => array(
						'added'       => __( 'Đã thêm vào so sánh', 'kha-solar' ),
						'removed'     => __( 'Đã xóa khỏi so sánh', 'kha-solar' ),
						'maxReached'  => sprintf( __( 'Bạn chỉ có thể so sánh tối đa %d sản phẩm', 'kha-solar' ), $this->max_products ),
						'compareNow'  => __( 'So Sánh Ngay', 'kha-solar' ),
						'clearAll'    => __( 'Xóa Tất Cả', 'kha-solar' ),
						'addMore'     => __( 'Thêm Sản Phẩm', 'kha-solar' ),
					),
				)
			);
		}
	}

	/**
	 * Render floating comparison bar.
	 *
	 * @since 1.0.0
	 */
	public function render_floating_bar() {
		if ( is_admin() ) {
			return;
		}
		?>
		<div id="kha-comparison-bar" class="kha-comparison-bar" style="display: none;">
			<div class="kha-comparison-bar-inner">
				<div class="kha-comparison-bar-left">
					<span class="kha-comparison-icon">📊</span>
					<span class="kha-comparison-title">
						<?php esc_html_e( 'So sánh', 'kha-solar' ); ?>
						<span class="kha-comparison-count">(0/<?php echo absint( $this->max_products ); ?>)</span>
					</span>
				</div>

				<div class="kha-comparison-bar-center">
					<div class="kha-comparison-products"></div>
				</div>

				<div class="kha-comparison-bar-right">
					<button class="kha-comparison-clear-btn" title="<?php esc_attr_e( 'Xóa tất cả', 'kha-solar' ); ?>">
						<span class="dashicons dashicons-trash"></span>
					</button>
					<a href="<?php echo esc_url( $this->get_comparison_page_url() ); ?>" class="kha-comparison-view-btn">
						<?php esc_html_e( 'So Sánh Ngay', 'kha-solar' ); ?> →
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get comparison page URL.
	 *
	 * @return string
	 */
	private function get_comparison_page_url() {
		$page_id = get_option( 'kha_solar_comparison_page_id' );
		
		if ( $page_id ) {
			return get_permalink( $page_id );
		}

		return add_query_arg( 'kha_comparison', '1', home_url( '/' ) );
	}

	/**
	 * Get comparison data for products.
	 *
	 * @param array $product_ids Array of product IDs.
	 * @return array Comparison data.
	 */
	public function get_comparison_data( $product_ids ) {
		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			return array();
		}

		$product_ids = array_slice( array_map( 'absint', $product_ids ), 0, $this->max_products );
		$products    = array();

		foreach ( $product_ids as $product_id ) {
			$product = get_post( $product_id );

			if ( ! $product || 'kha_product' !== $product->post_type ) {
				continue;
			}

			$products[] = $this->get_product_comparison_data( $product_id );
		}

		return $products;
	}

	/**
	 * Get single product comparison data.
	 *
	 * @param int $product_id Product ID.
	 * @return array Product data.
	 */
	private function get_product_comparison_data( $product_id ) {
		$thumbnail      = get_the_post_thumbnail_url( $product_id, 'medium' );
		$price          = get_post_meta( $product_id, '_price', true );
		$regular_price  = get_post_meta( $product_id, '_regular_price', true );
		$sale_price     = get_post_meta( $product_id, '_sale_price', true );
		$sku            = get_post_meta( $product_id, '_sku', true );
		$stock_status   = get_post_meta( $product_id, '_stock_status', true );

		// Technical specs
		$power_output   = get_post_meta( $product_id, '_power_output', true );
		$voltage        = get_post_meta( $product_id, '_voltage', true );
		$efficiency     = get_post_meta( $product_id, '_efficiency', true );
		$warranty_years = get_post_meta( $product_id, '_warranty_years', true );
		$dimensions     = get_post_meta( $product_id, '_dimensions', true );
		$weight         = get_post_meta( $product_id, '_weight', true );
		$origin_country = get_post_meta( $product_id, '_origin_country', true );

		// Taxonomies
		$categories = wp_get_object_terms( $product_id, 'kha_product_cat', array( 'fields' => 'names' ) );
		$brand      = kha_get_product_brand( $product_id );

		return array(
			'id'             => $product_id,
			'title'          => get_the_title( $product_id ),
			'url'            => get_permalink( $product_id ),
			'image'          => $thumbnail ? $thumbnail : '',
			'price'          => $price ? kha_solar_format_price( $price ) : __( 'Liên Hệ', 'kha-solar' ),
			'price_raw'      => $price ? floatval( $price ) : 0,
			'regular_price'  => $regular_price ? kha_solar_format_price( $regular_price ) : '',
			'sale_price'     => $sale_price ? kha_solar_format_price( $sale_price ) : '',
			'on_sale'        => kha_is_product_on_sale( $product_id ),
			'sku'            => $sku ? $sku : '',
			'stock_status'   => $stock_status ? $stock_status : 'instock',
			'stock_label'    => kha_get_product_stock_status( $product_id ),
			'categories'     => ! is_wp_error( $categories ) ? implode( ', ', $categories ) : '',
			'brand'          => $brand ? $brand->name : '',
			'power_output'   => $power_output ? ( $power_output >= 1000 ? ( $power_output / 1000 ) . ' kW' : $power_output . ' W' ) : '',
			'voltage'        => $voltage ? $voltage . ' V' : '',
			'efficiency'     => $efficiency ? $efficiency . '%' : '',
			'warranty_years' => $warranty_years ? sprintf( _n( '%d năm', '%d năm', $warranty_years, 'kha-solar' ), $warranty_years ) : '',
			'dimensions'     => $dimensions ? $dimensions : '',
			'weight'         => $weight ? $weight . ' kg' : '',
			'origin_country' => $origin_country ? $origin_country : '',
			'excerpt'        => wp_trim_words( get_the_excerpt( $product_id ), 20 ),
		);
	}

	/**
	 * Comparison shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function comparison_shortcode( $atts ) {
		ob_start();
		include KHA_PLUGIN_DIR . 'templates/comparison.php';
		return ob_get_clean();
	}
}
