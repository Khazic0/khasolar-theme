<?php
/**
 * Product Custom Post Type.
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
 * Handles product custom post type and taxonomies registration.
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
		add_filter( 'template_include', array( $this, 'load_templates' ) );
	}

	/**
	 * Register product post type.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Products', 'Post Type General Name', 'kha-solar' ),
			'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'kha-solar' ),
			'menu_name'             => __( 'Solar Products', 'kha-solar' ),
			'name_admin_bar'        => __( 'Product', 'kha-solar' ),
			'archives'              => __( 'Product Archives', 'kha-solar' ),
			'attributes'            => __( 'Product Attributes', 'kha-solar' ),
			'parent_item_colon'     => __( 'Parent Product:', 'kha-solar' ),
			'all_items'             => __( 'All Products', 'kha-solar' ),
			'add_new_item'          => __( 'Add New Product', 'kha-solar' ),
			'add_new'               => __( 'Add New', 'kha-solar' ),
			'new_item'              => __( 'New Product', 'kha-solar' ),
			'edit_item'             => __( 'Edit Product', 'kha-solar' ),
			'update_item'           => __( 'Update Product', 'kha-solar' ),
			'view_item'             => __( 'View Product', 'kha-solar' ),
			'view_items'            => __( 'View Products', 'kha-solar' ),
			'search_items'          => __( 'Search Product', 'kha-solar' ),
			'not_found'             => __( 'Not found', 'kha-solar' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'kha-solar' ),
			'featured_image'        => __( 'Product Image', 'kha-solar' ),
			'set_featured_image'    => __( 'Set product image', 'kha-solar' ),
			'remove_featured_image' => __( 'Remove product image', 'kha-solar' ),
			'use_featured_image'    => __( 'Use as product image', 'kha-solar' ),
			'insert_into_item'      => __( 'Insert into product', 'kha-solar' ),
			'uploaded_to_this_item' => __( 'Uploaded to this product', 'kha-solar' ),
			'items_list'            => __( 'Products list', 'kha-solar' ),
			'items_list_navigation' => __( 'Products list navigation', 'kha-solar' ),
			'filter_items_list'     => __( 'Filter products list', 'kha-solar' ),
		);

		$args = array(
			'label'               => __( 'Product', 'kha-solar' ),
			'description'         => __( 'Solar equipment products', 'kha-solar' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
			'taxonomies'          => array( 'kha_product_cat', 'kha_product_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-cart',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rewrite'             => array(
				'slug'       => 'products',
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
			'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'kha-solar' ),
			'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'kha-solar' ),
			'menu_name'                  => __( 'Categories', 'kha-solar' ),
			'all_items'                  => __( 'All Categories', 'kha-solar' ),
			'parent_item'                => __( 'Parent Category', 'kha-solar' ),
			'parent_item_colon'          => __( 'Parent Category:', 'kha-solar' ),
			'new_item_name'              => __( 'New Category Name', 'kha-solar' ),
			'add_new_item'               => __( 'Add New Category', 'kha-solar' ),
			'edit_item'                  => __( 'Edit Category', 'kha-solar' ),
			'update_item'                => __( 'Update Category', 'kha-solar' ),
			'view_item'                  => __( 'View Category', 'kha-solar' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'kha-solar' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'kha-solar' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'kha-solar' ),
			'popular_items'              => __( 'Popular Categories', 'kha-solar' ),
			'search_items'               => __( 'Search Categories', 'kha-solar' ),
			'not_found'                  => __( 'Not Found', 'kha-solar' ),
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
			'rewrite'           => array( 'slug' => 'product-category' ),
		);

		register_taxonomy( 'kha_product_cat', array( 'kha_product' ), $cat_args );

		// Product Tags.
		$tag_labels = array(
			'name'                       => _x( 'Product Tags', 'Taxonomy General Name', 'kha-solar' ),
			'singular_name'              => _x( 'Product Tag', 'Taxonomy Singular Name', 'kha-solar' ),
			'menu_name'                  => __( 'Tags', 'kha-solar' ),
			'all_items'                  => __( 'All Tags', 'kha-solar' ),
			'parent_item'                => __( 'Parent Tag', 'kha-solar' ),
			'parent_item_colon'          => __( 'Parent Tag:', 'kha-solar' ),
			'new_item_name'              => __( 'New Tag Name', 'kha-solar' ),
			'add_new_item'               => __( 'Add New Tag', 'kha-solar' ),
			'edit_item'                  => __( 'Edit Tag', 'kha-solar' ),
			'update_item'                => __( 'Update Tag', 'kha-solar' ),
			'view_item'                  => __( 'View Tag', 'kha-solar' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'kha-solar' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'kha-solar' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'kha-solar' ),
			'popular_items'              => __( 'Popular Tags', 'kha-solar' ),
			'search_items'               => __( 'Search Tags', 'kha-solar' ),
			'not_found'                  => __( 'Not Found', 'kha-solar' ),
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
			'rewrite'           => array( 'slug' => 'product-tag' ),
		);

		register_taxonomy( 'kha_product_tag', array( 'kha_product' ), $tag_args );
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

		if ( is_post_type_archive( 'kha_product' ) || is_tax( array( 'kha_product_cat', 'kha_product_tag' ) ) ) {
			$custom_template = KHA_PLUGIN_DIR . 'templates/archive-products.php';
			if ( file_exists( $custom_template ) ) {
				return $custom_template;
			}
		}

		return $template;
	}
}
