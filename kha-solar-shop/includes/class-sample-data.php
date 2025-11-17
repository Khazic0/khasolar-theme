<?php
/**
 * Sample Data Importer
 *
 * Imports demo products for testing and demonstration.
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
 * Sample_Data class.
 */
class Sample_Data {

	/**
	 * Initialize sample data importer.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_post_kha_import_sample_data', array( $this, 'handle_import' ) );
	}

	/**
	 * Add admin menu page.
	 */
	public function add_menu_page() {
		add_management_page(
			__( 'Import Sample Data', 'kha-solar' ),
			__( 'Import Sample Data', 'kha-solar' ),
			'manage_options',
			'kha-import-sample-data',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render admin page.
	 */
	public function render_page() {
		// Check for success message.
		if ( isset( $_GET['imported'] ) && 'success' === $_GET['imported'] ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Sample data imported successfully!', 'kha-solar' ) . '</p></div>';
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Import Sample Data', 'kha-solar' ); ?></h1>

			<div class="card" style="max-width: 800px;">
				<h2><?php echo esc_html__( 'Demo Products', 'kha-solar' ); ?></h2>
				<p><?php echo esc_html__( 'Click the button below to import 10 sample products with complete specifications, pricing, and images.', 'kha-solar' ); ?></p>

				<p><strong><?php echo esc_html__( 'This will create:', 'kha-solar' ); ?></strong></p>
				<ul style="list-style-type: disc; margin-left: 20px;">
					<li>3 Inverters (3kW, 5kW, 10kW)</li>
					<li>3 Solar Panels (Different wattages)</li>
					<li>2 Solar Batteries</li>
					<li>2 Accessories</li>
				</ul>

				<p class="description">
					<?php echo esc_html__( 'Note: This is safe to run multiple times. Duplicate products will not be created.', 'kha-solar' ); ?>
				</p>

				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'kha_import_sample_data', 'kha_import_nonce' ); ?>
					<input type="hidden" name="action" value="kha_import_sample_data">

					<p>
						<button type="submit" class="button button-primary button-large">
							<span class="dashicons dashicons-download" style="margin-top: 3px;"></span>
							<?php echo esc_html__( 'Import Demo Products', 'kha-solar' ); ?>
						</button>
					</p>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle import request.
	 */
	public function handle_import() {
		// Verify nonce.
		if ( ! isset( $_POST['kha_import_nonce'] ) || ! wp_verify_nonce( $_POST['kha_import_nonce'], 'kha_import_sample_data' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'kha-solar' ) );
		}

		// Check permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'kha-solar' ) );
		}

		// Import products.
		$this->import_products();

		// Redirect back with success message.
		wp_redirect( add_query_arg( 'imported', 'success', admin_url( 'tools.php?page=kha-import-sample-data' ) ) );
		exit;
	}

	/**
	 * Import sample products.
	 */
	private function import_products() {
		$products = array(
			// Inverters.
			array(
				'title'       => 'Inverter Growatt 3kW - MIN 3000TL-X',
				'description' => 'Inverter năng lượng mặt trời Growatt 3kW, hiệu suất cao, phù hợp cho hộ gia đình nhỏ. Công nghệ MPPT tiên tiến, bảo hành 10 năm.',
				'price'       => 12000000,
				'sale_price'  => 10500000,
				'sku'         => 'INV-GRW-3K',
				'stock'       => 15,
				'category'    => 'inverter',
				'brand'       => 'growatt',
				'specs'       => array(
					'power'              => 3000,
					'voltage'            => '220V',
					'efficiency'         => '97.6%',
					'mppt_trackers'      => 2,
					'max_input_voltage'  => '550V',
					'operating_temp'     => '-25°C đến 60°C',
					'protection_rating'  => 'IP65',
					'warranty'           => '10 năm',
				),
				'featured'    => true,
			),
			array(
				'title'       => 'Inverter Deye 5kW - SUN-5K-SG04LP3-EU',
				'description' => 'Inverter hybrid Deye 5kW, hỗ trợ lưu trữ pin, phù hợp cho hộ gia đình vừa và nhỏ. Thiết kế nhỏ gọn, dễ lắp đặt.',
				'price'       => 18500000,
				'sale_price'  => '',
				'sku'         => 'INV-DEY-5K',
				'stock'       => 12,
				'category'    => 'inverter',
				'brand'       => 'deye',
				'specs'       => array(
					'power'              => 5000,
					'voltage'            => '220V',
					'efficiency'         => '97.8%',
					'mppt_trackers'      => 2,
					'max_input_voltage'  => '600V',
					'operating_temp'     => '-25°C đến 60°C',
					'protection_rating'  => 'IP65',
					'warranty'           => '10 năm',
				),
				'featured'    => true,
			),
			array(
				'title'       => 'Inverter SMA 10kW - Sunny Tripower 10.0',
				'description' => 'Inverter thương mại SMA 10kW, hiệu suất vượt trội, phù hợp cho doanh nghiệp nhỏ và nhà xưởng. Công nghệ Đức, độ bền cao.',
				'price'       => 45000000,
				'sale_price'  => 42000000,
				'sku'         => 'INV-SMA-10K',
				'stock'       => 8,
				'category'    => 'inverter',
				'brand'       => 'sma',
				'specs'       => array(
					'power'              => 10000,
					'voltage'            => '3 pha 380V',
					'efficiency'         => '98.4%',
					'mppt_trackers'      => 2,
					'max_input_voltage'  => '1000V',
					'operating_temp'     => '-25°C đến 60°C',
					'protection_rating'  => 'IP65',
					'warranty'           => '10 năm',
				),
				'featured'    => false,
			),

			// Solar Panels.
			array(
				'title'       => 'Tấm Pin Jinko 450W - Tiger Neo',
				'description' => 'Tấm pin mono-crystalline Jinko 450W, công nghệ N-Type, hiệu suất cao, tuổi thọ lên đến 30 năm. Chống ăn mòn tốt trong môi trường nhiệt đới.',
				'price'       => 2800000,
				'sale_price'  => '',
				'sku'         => 'PANEL-JK-450',
				'stock'       => 100,
				'category'    => 'solar-panel',
				'brand'       => 'jinko',
				'specs'       => array(
					'power'              => 450,
					'efficiency'         => '21.5%',
					'voltage'            => '41.5V',
					'current'            => '10.85A',
					'dimensions'         => '1903 x 1134 x 30mm',
					'weight'             => '22.5kg',
					'warranty'           => '25 năm hiệu suất',
				),
				'featured'    => true,
			),
			array(
				'title'       => 'Tấm Pin Longi 550W - Hi-MO 5',
				'description' => 'Tấm pin Longi 550W, công nghệ PERC tiên tiến, hiệu suất vượt trội trong điều kiện ánh sáng yếu. Bảo hành 30 năm.',
				'price'       => 3200000,
				'sale_price'  => 2950000,
				'sku'         => 'PANEL-LG-550',
				'stock'       => 80,
				'category'    => 'solar-panel',
				'brand'       => 'longi',
				'specs'       => array(
					'power'              => 550,
					'efficiency'         => '22.3%',
					'voltage'            => '41.8V',
					'current'            => '13.16A',
					'dimensions'         => '2278 x 1134 x 35mm',
					'weight'             => '27.5kg',
					'warranty'           => '30 năm hiệu suất',
				),
				'featured'    => true,
			),
			array(
				'title'       => 'Tấm Pin Jinko 380W - Cheetah Plus',
				'description' => 'Tấm pin Jinko 380W, giá tốt, phù hợp cho ngân sách vừa phải. Hiệu suất ổn định, chống nước, bảo hành 25 năm.',
				'price'       => 2100000,
				'sale_price'  => '',
				'sku'         => 'PANEL-JK-380',
				'stock'       => 120,
				'category'    => 'solar-panel',
				'brand'       => 'jinko',
				'specs'       => array(
					'power'              => 380,
					'efficiency'         => '19.8%',
					'voltage'            => '40.2V',
					'current'            => '9.45A',
					'dimensions'         => '1765 x 1048 x 30mm',
					'weight'             => '19.5kg',
					'warranty'           => '25 năm hiệu suất',
				),
				'featured'    => false,
			),

			// Batteries.
			array(
				'title'       => 'Pin Lưu Trữ Pylontech 5.12kWh - US3000C',
				'description' => 'Pin lithium LiFePO4 5.12kWh, tuổi thọ 6000 chu kỳ, hỗ trợ mở rộng song song. An toàn cao, không cần bảo trì.',
				'price'       => 28000000,
				'sale_price'  => 25500000,
				'sku'         => 'BAT-PY-5K',
				'stock'       => 20,
				'category'    => 'solar-battery',
				'brand'       => 'growatt',
				'specs'       => array(
					'capacity'           => '5120Wh',
					'voltage'            => '48V',
					'usable_capacity'    => '4608Wh',
					'cycle_life'         => '6000 chu kỳ',
					'warranty'           => '10 năm',
					'weight'             => '52kg',
					'operating_temp'     => '0°C đến 50°C',
				),
				'featured'    => true,
			),
			array(
				'title'       => 'Pin Lưu Trữ Huawei 10kWh - LUNA2000',
				'description' => 'Hệ thống lưu trữ pin Huawei 10kWh, quản lý thông minh qua app, tuổi thọ cao. Thiết kế module linh hoạt, dễ mở rộng.',
				'price'       => 55000000,
				'sale_price'  => '',
				'sku'         => 'BAT-HW-10K',
				'stock'       => 10,
				'category'    => 'solar-battery',
				'brand'       => 'huawei',
				'specs'       => array(
					'capacity'           => '10000Wh',
					'voltage'            => '48V',
					'usable_capacity'    => '9000Wh',
					'cycle_life'         => '10000 chu kỳ',
					'warranty'           => '10 năm',
					'weight'             => '95kg',
					'operating_temp'     => '-10°C đến 45°C',
				),
				'featured'    => false,
			),

			// Accessories.
			array(
				'title'       => 'Bộ Giá Đỡ Tấm Pin Mái Ngói - Aluminum',
				'description' => 'Bộ giá đỡ hợp kim nhôm cho mái ngói, chịu được tải trọng gió lớn. Bộ đủ 10 tấm pin, kèm toàn bộ ốc vít.',
				'price'       => 4500000,
				'sale_price'  => 3800000,
				'sku'         => 'ACC-MOUNT-10',
				'stock'       => 50,
				'category'    => 'accessories',
				'brand'       => '',
				'specs'       => array(
					'material'           => 'Nhôm hợp kim 6005-T5',
					'max_panels'         => '10 tấm',
					'wind_load'          => '60m/s',
					'snow_load'          => '1.5kN/m²',
					'warranty'           => '15 năm',
					'angle_adjustment'   => '10-30 độ',
				),
				'featured'    => false,
			),
			array(
				'title'       => 'Cáp DC Solar 4mm² - 50m',
				'description' => 'Cáp DC chuyên dụng cho hệ thống solar, chịu UV tốt, chống cháy, chịu nhiệt độ cao. Đạt chuẩn TUV.',
				'price'       => 850000,
				'sale_price'  => '',
				'sku'         => 'ACC-CABLE-4MM',
				'stock'       => 200,
				'category'    => 'accessories',
				'brand'       => '',
				'specs'       => array(
					'cable_size'         => '4mm²',
					'length'             => '50m',
					'max_voltage'        => '1000V DC',
					'max_current'        => '32A',
					'temperature_range'  => '-40°C đến +90°C',
					'certification'      => 'TUV, CE',
				),
				'featured'    => false,
			),
		);

		foreach ( $products as $product_data ) {
			$this->create_product( $product_data );
		}
	}

	/**
	 * Create a single product.
	 *
	 * @param array $data Product data.
	 */
	private function create_product( $data ) {
		// Check if product already exists by SKU.
		$existing = $this->get_product_by_sku( $data['sku'] );

		if ( $existing ) {
			return; // Skip duplicate.
		}

		// Create product post.
		$post_id = wp_insert_post(
			array(
				'post_title'   => $data['title'],
				'post_content' => $data['description'],
				'post_status'  => 'publish',
				'post_type'    => 'kha_product',
			)
		);

		if ( is_wp_error( $post_id ) ) {
			return;
		}

		// Set product category.
		if ( ! empty( $data['category'] ) ) {
			wp_set_object_terms( $post_id, $data['category'], 'kha_product_cat' );
		}

		// Set brand.
		if ( ! empty( $data['brand'] ) ) {
			wp_set_object_terms( $post_id, $data['brand'], 'kha_brand' );
		}

		// Set meta data.
		update_post_meta( $post_id, '_price', $data['price'] );
		update_post_meta( $post_id, '_sku', $data['sku'] );
		update_post_meta( $post_id, '_stock_quantity', $data['stock'] );
		update_post_meta( $post_id, '_stock_status', 'instock' );

		if ( ! empty( $data['sale_price'] ) ) {
			update_post_meta( $post_id, '_sale_price', $data['sale_price'] );
		}

		if ( ! empty( $data['featured'] ) ) {
			update_post_meta( $post_id, '_featured', '1' );
		}

		// Set specifications.
		if ( ! empty( $data['specs'] ) ) {
			foreach ( $data['specs'] as $key => $value ) {
				update_post_meta( $post_id, '_' . $key, $value );
			}
		}
	}

	/**
	 * Get product by SKU.
	 *
	 * @param string $sku Product SKU.
	 * @return WP_Post|null Product post or null.
	 */
	private function get_product_by_sku( $sku ) {
		$args = array(
			'post_type'   => 'kha_product',
			'post_status' => 'any',
			'meta_query'  => array(
				array(
					'key'   => '_sku',
					'value' => $sku,
				),
			),
		);

		$query = new \WP_Query( $args );

		return $query->have_posts() ? $query->posts[0] : null;
	}
}
