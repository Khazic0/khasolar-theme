<?php
/**
 * Product Custom Post Type.
 *
 * Comprehensive product custom post type for solar equipment with
 * detailed meta fields, taxonomies, and helper functions.
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
 * Product_Post_Type class.
 *
 * Handles product custom post type, taxonomies, and meta fields registration.
 */
class Product_Post_Type {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_meta_fields' ) );
		add_filter( 'template_include', array( $this, 'load_templates' ) );
		add_action( 'save_post_kha_product', array( $this, 'calculate_price_field' ), 10, 2 );
	}

	/**
	 * Register product post type.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Sản Phẩm', 'Post Type General Name', 'kha-solar' ),
			'singular_name'         => _x( 'Sản Phẩm', 'Post Type Singular Name', 'kha-solar' ),
			'menu_name'             => __( 'Sản Phẩm Solar', 'kha-solar' ),
			'name_admin_bar'        => __( 'Sản Phẩm', 'kha-solar' ),
			'archives'              => __( 'Kho Sản Phẩm', 'kha-solar' ),
			'attributes'            => __( 'Thuộc Tính Sản Phẩm', 'kha-solar' ),
			'parent_item_colon'     => __( 'Sản Phẩm Cha:', 'kha-solar' ),
			'all_items'             => __( 'Tất Cả Sản Phẩm', 'kha-solar' ),
			'add_new_item'          => __( 'Thêm Sản Phẩm Mới', 'kha-solar' ),
			'add_new'               => __( 'Thêm Mới', 'kha-solar' ),
			'new_item'              => __( 'Sản Phẩm Mới', 'kha-solar' ),
			'edit_item'             => __( 'Chỉnh Sửa Sản Phẩm', 'kha-solar' ),
			'update_item'           => __( 'Cập Nhật Sản Phẩm', 'kha-solar' ),
			'view_item'             => __( 'Xem Sản Phẩm', 'kha-solar' ),
			'view_items'            => __( 'Xem Sản Phẩm', 'kha-solar' ),
			'search_items'          => __( 'Tìm Kiếm Sản Phẩm', 'kha-solar' ),
			'not_found'             => __( 'Không Tìm Thấy', 'kha-solar' ),
			'not_found_in_trash'    => __( 'Không Tìm Thấy Trong Thùng Rác', 'kha-solar' ),
			'featured_image'        => __( 'Hình Ảnh Sản Phẩm', 'kha-solar' ),
			'set_featured_image'    => __( 'Đặt Hình Ảnh Sản Phẩm', 'kha-solar' ),
			'remove_featured_image' => __( 'Xóa Hình Ảnh Sản Phẩm', 'kha-solar' ),
			'use_featured_image'    => __( 'Sử Dụng Làm Hình Ảnh Sản Phẩm', 'kha-solar' ),
			'insert_into_item'      => __( 'Chèn Vào Sản Phẩm', 'kha-solar' ),
			'uploaded_to_this_item' => __( 'Tải Lên Sản Phẩm Này', 'kha-solar' ),
			'items_list'            => __( 'Danh Sách Sản Phẩm', 'kha-solar' ),
			'items_list_navigation' => __( 'Điều Hướng Danh Sách', 'kha-solar' ),
			'filter_items_list'     => __( 'Lọc Danh Sách Sản Phẩm', 'kha-solar' ),
		);

		$args = array(
			'label'               => __( 'Sản Phẩm', 'kha-solar' ),
			'description'         => __( 'Sản phẩm thiết bị năng lượng mặt trời', 'kha-solar' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'taxonomies'          => array( 'kha_product_cat', 'kha_product_tag', 'kha_brand' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-archive',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rewrite'             => array(
				'slug'       => 'san-pham',
				'with_front' => false,
			),
		);

		register_post_type( 'kha_product', $args );
	}

	/**
	 * Register product taxonomies.
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomies() {
		// Product Categories.
		$cat_labels = array(
			'name'                       => _x( 'Danh Mục Sản Phẩm', 'Taxonomy General Name', 'kha-solar' ),
			'singular_name'              => _x( 'Danh Mục', 'Taxonomy Singular Name', 'kha-solar' ),
			'menu_name'                  => __( 'Danh Mục', 'kha-solar' ),
			'all_items'                  => __( 'Tất Cả Danh Mục', 'kha-solar' ),
			'parent_item'                => __( 'Danh Mục Cha', 'kha-solar' ),
			'parent_item_colon'          => __( 'Danh Mục Cha:', 'kha-solar' ),
			'new_item_name'              => __( 'Tên Danh Mục Mới', 'kha-solar' ),
			'add_new_item'               => __( 'Thêm Danh Mục Mới', 'kha-solar' ),
			'edit_item'                  => __( 'Chỉnh Sửa Danh Mục', 'kha-solar' ),
			'update_item'                => __( 'Cập Nhật Danh Mục', 'kha-solar' ),
			'view_item'                  => __( 'Xem Danh Mục', 'kha-solar' ),
			'separate_items_with_commas' => __( 'Phân Cách Danh Mục Bằng Dấu Phẩy', 'kha-solar' ),
			'add_or_remove_items'        => __( 'Thêm Hoặc Xóa Danh Mục', 'kha-solar' ),
			'choose_from_most_used'      => __( 'Chọn Từ Danh Mục Phổ Biến', 'kha-solar' ),
			'popular_items'              => __( 'Danh Mục Phổ Biến', 'kha-solar' ),
			'search_items'               => __( 'Tìm Kiếm Danh Mục', 'kha-solar' ),
			'not_found'                  => __( 'Không Tìm Thấy', 'kha-solar' ),
		);

		$cat_args = array(
			'labels'            => $cat_labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'danh-muc' ),
		);

		register_taxonomy( 'kha_product_cat', array( 'kha_product' ), $cat_args );

		// Product Tags.
		$tag_labels = array(
			'name'                       => _x( 'Từ Khóa Sản Phẩm', 'Taxonomy General Name', 'kha-solar' ),
			'singular_name'              => _x( 'Từ Khóa', 'Taxonomy Singular Name', 'kha-solar' ),
			'menu_name'                  => __( 'Từ Khóa', 'kha-solar' ),
			'all_items'                  => __( 'Tất Cả Từ Khóa', 'kha-solar' ),
			'parent_item'                => __( 'Từ Khóa Cha', 'kha-solar' ),
			'parent_item_colon'          => __( 'Từ Khóa Cha:', 'kha-solar' ),
			'new_item_name'              => __( 'Tên Từ Khóa Mới', 'kha-solar' ),
			'add_new_item'               => __( 'Thêm Từ Khóa Mới', 'kha-solar' ),
			'edit_item'                  => __( 'Chỉnh Sửa Từ Khóa', 'kha-solar' ),
			'update_item'                => __( 'Cập Nhật Từ Khóa', 'kha-solar' ),
			'view_item'                  => __( 'Xem Từ Khóa', 'kha-solar' ),
			'separate_items_with_commas' => __( 'Phân Cách Từ Khóa Bằng Dấu Phẩy', 'kha-solar' ),
			'add_or_remove_items'        => __( 'Thêm Hoặc Xóa Từ Khóa', 'kha-solar' ),
			'choose_from_most_used'      => __( 'Chọn Từ Từ Khóa Phổ Biến', 'kha-solar' ),
			'popular_items'              => __( 'Từ Khóa Phổ Biến', 'kha-solar' ),
			'search_items'               => __( 'Tìm Kiếm Từ Khóa', 'kha-solar' ),
			'not_found'                  => __( 'Không Tìm Thấy', 'kha-solar' ),
		);

		$tag_args = array(
			'labels'            => $tag_labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'tu-khoa' ),
		);

		register_taxonomy( 'kha_product_tag', array( 'kha_product' ), $tag_args );

		// Brand Taxonomy.
		$brand_labels = array(
			'name'                       => _x( 'Thương Hiệu', 'Taxonomy General Name', 'kha-solar' ),
			'singular_name'              => _x( 'Thương Hiệu', 'Taxonomy Singular Name', 'kha-solar' ),
			'menu_name'                  => __( 'Thương Hiệu', 'kha-solar' ),
			'all_items'                  => __( 'Tất Cả Thương Hiệu', 'kha-solar' ),
			'parent_item'                => __( 'Thương Hiệu Cha', 'kha-solar' ),
			'parent_item_colon'          => __( 'Thương Hiệu Cha:', 'kha-solar' ),
			'new_item_name'              => __( 'Tên Thương Hiệu Mới', 'kha-solar' ),
			'add_new_item'               => __( 'Thêm Thương Hiệu Mới', 'kha-solar' ),
			'edit_item'                  => __( 'Chỉnh Sửa Thương Hiệu', 'kha-solar' ),
			'update_item'                => __( 'Cập Nhật Thương Hiệu', 'kha-solar' ),
			'view_item'                  => __( 'Xem Thương Hiệu', 'kha-solar' ),
			'separate_items_with_commas' => __( 'Phân Cách Thương Hiệu Bằng Dấu Phẩy', 'kha-solar' ),
			'add_or_remove_items'        => __( 'Thêm Hoặc Xóa Thương Hiệu', 'kha-solar' ),
			'choose_from_most_used'      => __( 'Chọn Từ Thương Hiệu Phổ Biến', 'kha-solar' ),
			'popular_items'              => __( 'Thương Hiệu Phổ Biến', 'kha-solar' ),
			'search_items'               => __( 'Tìm Kiếm Thương Hiệu', 'kha-solar' ),
			'not_found'                  => __( 'Không Tìm Thấy', 'kha-solar' ),
		);

		$brand_args = array(
			'labels'            => $brand_labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'thuong-hieu' ),
		);

		register_taxonomy( 'kha_brand', array( 'kha_product' ), $brand_args );
	}

	/**
	 * Register product meta fields.
	 *
	 * Registers all product meta fields with REST API support.
	 *
	 * @since 1.0.0
	 */
	public function register_meta_fields() {
		// Pricing fields.
		register_post_meta(
			'kha_product',
			'_regular_price',
			array(
				'type'         => 'number',
				'description'  => __( 'Regular price', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_sale_price',
			array(
				'type'         => 'number',
				'description'  => __( 'Sale price', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_price',
			array(
				'type'         => 'number',
				'description'  => __( 'Active price (calculated)', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		// Inventory fields.
		register_post_meta(
			'kha_product',
			'_sku',
			array(
				'type'         => 'string',
				'description'  => __( 'Product SKU (Stock Keeping Unit)', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_stock_quantity',
			array(
				'type'         => 'number',
				'description'  => __( 'Stock quantity', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_stock_status',
			array(
				'type'         => 'string',
				'description'  => __( 'Stock status', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
				'default'      => 'instock',
			)
		);

		register_post_meta(
			'kha_product',
			'_manage_stock',
			array(
				'type'         => 'boolean',
				'description'  => __( 'Manage stock', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
				'default'      => false,
			)
		);

		register_post_meta(
			'kha_product',
			'_low_stock_threshold',
			array(
				'type'         => 'number',
				'description'  => __( 'Low stock threshold', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
				'default'      => 5,
			)
		);

		// Technical specifications.
		register_post_meta(
			'kha_product',
			'_power_output',
			array(
				'type'         => 'number',
				'description'  => __( 'Power output in watts', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_voltage',
			array(
				'type'         => 'string',
				'description'  => __( 'Voltage specification', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_warranty_years',
			array(
				'type'         => 'number',
				'description'  => __( 'Warranty in years', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_origin_country',
			array(
				'type'         => 'string',
				'description'  => __( 'Country of origin', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_efficiency',
			array(
				'type'         => 'number',
				'description'  => __( 'Efficiency percentage', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_dimensions',
			array(
				'type'         => 'string',
				'description'  => __( 'Product dimensions', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_weight',
			array(
				'type'         => 'number',
				'description'  => __( 'Product weight in kg', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		// Additional fields.
		register_post_meta(
			'kha_product',
			'_product_type',
			array(
				'type'         => 'string',
				'description'  => __( 'Product type (simple or bundle)', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
				'default'      => 'simple',
			)
		);

		register_post_meta(
			'kha_product',
			'_bundle_products',
			array(
				'type'         => 'array',
				'description'  => __( 'Bundle product IDs', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => array(
					'schema' => array(
						'type'  => 'array',
						'items' => array(
							'type' => 'integer',
						),
					),
				),
			)
		);

		register_post_meta(
			'kha_product',
			'_bundle_discount',
			array(
				'type'         => 'number',
				'description'  => __( 'Bundle discount percentage', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
			)
		);

		register_post_meta(
			'kha_product',
			'_featured_product',
			array(
				'type'         => 'boolean',
				'description'  => __( 'Featured product', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => true,
				'default'      => false,
			)
		);

		// Gallery images.
		register_post_meta(
			'kha_product',
			'_product_gallery',
			array(
				'type'         => 'array',
				'description'  => __( 'Product gallery image IDs', 'kha-solar' ),
				'single'       => true,
				'show_in_rest' => array(
					'schema' => array(
						'type'  => 'array',
						'items' => array(
							'type' => 'integer',
						),
					),
				),
			)
		);
	}

	/**
	 * Calculate and save the _price field.
	 *
	 * The _price field is the active price (sale price if available, otherwise regular price).
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 * @since 1.0.0
	 */
	public function calculate_price_field( $post_id, $post ) {
		// Skip autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$regular_price = get_post_meta( $post_id, '_regular_price', true );
		$sale_price    = get_post_meta( $post_id, '_sale_price', true );

		// Calculate active price.
		$price = $sale_price && $sale_price > 0 ? $sale_price : $regular_price;

		// Update _price field.
		update_post_meta( $post_id, '_price', $price );
	}

	/**
	 * Load custom templates for products.
	 *
	 * @param string $template Template path.
	 * @return string
	 * @since 1.0.0
	 */
	public function load_templates( $template ) {
		if ( is_singular( 'kha_product' ) ) {
			$custom_template = KHA_PLUGIN_DIR . 'templates/single-product.php';
			if ( file_exists( $custom_template ) ) {
				return $custom_template;
			}
		}

		if ( is_post_type_archive( 'kha_product' ) || is_tax( array( 'kha_product_cat', 'kha_product_tag', 'kha_brand' ) ) ) {
			$custom_template = KHA_PLUGIN_DIR . 'templates/archive-products.php';
			if ( file_exists( $custom_template ) ) {
				return $custom_template;
			}
		}

		return $template;
	}
}
