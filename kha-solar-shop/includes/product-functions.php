<?php
/**
 * Product Helper Functions.
 *
 * Global helper functions for product data manipulation and retrieval.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Get product price with formatting.
 *
 * Returns the active price (sale price if available, otherwise regular price)
 * with optional HTML formatting for sale badge.
 *
 * @param int  $product_id Product ID.
 * @param bool $formatted  Whether to return formatted HTML (default: true).
 * @return string|float Formatted price HTML or raw price value.
 * @since 1.0.0
 */
function kha_get_product_price( $product_id, $formatted = true ) {
	$regular_price = get_post_meta( $product_id, '_regular_price', true );
	$sale_price    = get_post_meta( $product_id, '_sale_price', true );
	$price         = get_post_meta( $product_id, '_price', true );

	// Return raw price if not formatted.
	if ( ! $formatted ) {
		return floatval( $price );
	}

	// Build formatted HTML.
	$html = '<div class="kha-product-price">';

	if ( $sale_price && $sale_price > 0 && $sale_price < $regular_price ) {
		// Sale price with original price strikethrough.
		$html .= '<span class="kha-price-current kha-sale-price">' . kha_solar_format_price( $sale_price ) . '</span>';
		$html .= '<span class="kha-price-regular">' . kha_solar_format_price( $regular_price ) . '</span>';
		$html .= '<span class="kha-sale-badge">' . __( 'Sale', 'kha-solar' ) . '</span>';
	} elseif ( $regular_price ) {
		// Regular price only.
		$html .= '<span class="kha-price-current">' . kha_solar_format_price( $regular_price ) . '</span>';
	} else {
		// Contact for price.
		$html .= '<span class="kha-price-contact">' . __( 'Liên Hệ', 'kha-solar' ) . '</span>';
	}

	$html .= '</div>';

	return $html;
}

/**
 * Get product stock status HTML badge.
 *
 * Returns a formatted HTML badge indicating stock availability.
 *
 * @param int $product_id Product ID.
 * @return string HTML badge for stock status.
 * @since 1.0.0
 */
function kha_get_product_stock_status( $product_id ) {
	$stock_status = get_post_meta( $product_id, '_stock_status', true );
	$manage_stock = get_post_meta( $product_id, '_manage_stock', true );

	if ( ! $stock_status ) {
		$stock_status = 'instock';
	}

	$html = '<span class="kha-stock-badge kha-stock-' . esc_attr( $stock_status ) . '">';

	switch ( $stock_status ) {
		case 'instock':
			$html .= '<span class="kha-stock-icon">✓</span> ';
			$html .= __( 'Còn Hàng', 'kha-solar' );

			// Show quantity if stock management is enabled.
			if ( $manage_stock ) {
				$stock_quantity = get_post_meta( $product_id, '_stock_quantity', true );
				if ( $stock_quantity ) {
					$low_threshold = get_post_meta( $product_id, '_low_stock_threshold', true ) ?: 5;
					if ( $stock_quantity <= $low_threshold ) {
						$html .= ' <span class="kha-low-stock">(' . sprintf( __( 'Chỉ còn %d', 'kha-solar' ), $stock_quantity ) . ')</span>';
					}
				}
			}
			break;

		case 'outofstock':
			$html .= '<span class="kha-stock-icon">✗</span> ';
			$html .= __( 'Hết Hàng', 'kha-solar' );
			break;

		case 'onbackorder':
			$html .= '<span class="kha-stock-icon">⏳</span> ';
			$html .= __( 'Đặt Trước', 'kha-solar' );
			break;

		default:
			$html .= __( 'Liên Hệ', 'kha-solar' );
			break;
	}

	$html .= '</span>';

	return $html;
}

/**
 * Check if product is on sale.
 *
 * @param int $product_id Product ID.
 * @return bool True if product has an active sale price.
 * @since 1.0.0
 */
function kha_is_product_on_sale( $product_id ) {
	$regular_price = get_post_meta( $product_id, '_regular_price', true );
	$sale_price    = get_post_meta( $product_id, '_sale_price', true );

	return ! empty( $sale_price ) && $sale_price > 0 && $sale_price < $regular_price;
}

/**
 * Get product technical specifications.
 *
 * Returns an array of all technical specifications for a product.
 *
 * @param int $product_id Product ID.
 * @return array Array of specifications with labels and values.
 * @since 1.0.0
 */
function kha_get_product_specs( $product_id ) {
	$specs = array();

	// Define all possible specifications.
	$spec_fields = array(
		'_power_output'    => __( 'Công Suất', 'kha-solar' ),
		'_voltage'         => __( 'Điện Áp', 'kha-solar' ),
		'_efficiency'      => __( 'Hiệu Suất', 'kha-solar' ),
		'_warranty_years'  => __( 'Bảo Hành', 'kha-solar' ),
		'_dimensions'      => __( 'Kích Thước', 'kha-solar' ),
		'_weight'          => __( 'Trọng Lượng', 'kha-solar' ),
		'_origin_country'  => __( 'Xuất Xứ', 'kha-solar' ),
	);

	foreach ( $spec_fields as $meta_key => $label ) {
		$value = get_post_meta( $product_id, $meta_key, true );

		if ( ! empty( $value ) ) {
			// Format value based on field type.
			switch ( $meta_key ) {
				case '_power_output':
					// Convert watts to kW if >= 1000.
					if ( $value >= 1000 ) {
						$formatted_value = ( $value / 1000 ) . ' kW';
					} else {
						$formatted_value = $value . ' W';
					}
					break;

				case '_efficiency':
					$formatted_value = $value . '%';
					break;

				case '_warranty_years':
					$formatted_value = sprintf( _n( '%d năm', '%d năm', $value, 'kha-solar' ), $value );
					break;

				case '_weight':
					$formatted_value = $value . ' kg';
					break;

				default:
					$formatted_value = $value;
					break;
			}

			$specs[] = array(
				'label' => $label,
				'value' => $formatted_value,
				'raw'   => $value,
				'key'   => $meta_key,
			);
		}
	}

	return $specs;
}

/**
 * Get related products.
 *
 * Returns related products based on shared categories and tags.
 *
 * @param int $product_id Product ID.
 * @param int $limit      Maximum number of related products (default: 4).
 * @return array Array of related product objects.
 * @since 1.0.0
 */
function kha_get_related_products( $product_id, $limit = 4 ) {
	$product = get_post( $product_id );

	if ( ! $product || 'kha_product' !== $product->post_type ) {
		return array();
	}

	// Get product categories.
	$categories = wp_get_object_terms( $product_id, 'kha_product_cat', array( 'fields' => 'ids' ) );

	// Get product tags.
	$tags = wp_get_object_terms( $product_id, 'kha_product_tag', array( 'fields' => 'ids' ) );

	// Build tax query.
	$tax_query = array( 'relation' => 'OR' );

	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
		$tax_query[] = array(
			'taxonomy' => 'kha_product_cat',
			'field'    => 'term_id',
			'terms'    => $categories,
		);
	}

	if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
		$tax_query[] = array(
			'taxonomy' => 'kha_product_tag',
			'field'    => 'term_id',
			'terms'    => $tags,
		);
	}

	// Query related products.
	$args = array(
		'post_type'      => 'kha_product',
		'posts_per_page' => $limit,
		'post__not_in'   => array( $product_id ),
		'orderby'        => 'rand',
		'tax_query'      => $tax_query,
	);

	$query = new WP_Query( $args );

	return $query->posts;
}

/**
 * Get featured products.
 *
 * Returns products marked as featured.
 *
 * @param int $limit Maximum number of products (default: 8).
 * @return array Array of featured product objects.
 * @since 1.0.0
 */
function kha_get_featured_products( $limit = 8 ) {
	$args = array(
		'post_type'      => 'kha_product',
		'posts_per_page' => $limit,
		'meta_query'     => array(
			array(
				'key'   => '_featured_product',
				'value' => '1',
			),
		),
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$query = new WP_Query( $args );

	return $query->posts;
}

/**
 * Get product SKU.
 *
 * @param int $product_id Product ID.
 * @return string Product SKU or empty string.
 * @since 1.0.0
 */
function kha_get_product_sku( $product_id ) {
	$sku = get_post_meta( $product_id, '_sku', true );
	return $sku ? $sku : '';
}

/**
 * Get product brand.
 *
 * @param int $product_id Product ID.
 * @return object|false Brand term object or false.
 * @since 1.0.0
 */
function kha_get_product_brand( $product_id ) {
	$brands = wp_get_object_terms( $product_id, 'kha_brand' );

	if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
		return $brands[0];
	}

	return false;
}

/**
 * Check if product is in stock.
 *
 * @param int $product_id Product ID.
 * @return bool True if product is in stock.
 * @since 1.0.0
 */
function kha_is_product_in_stock( $product_id ) {
	$stock_status = get_post_meta( $product_id, '_stock_status', true );
	return 'instock' === $stock_status || 'onbackorder' === $stock_status;
}

/**
 * Get product gallery images.
 *
 * @param int $product_id Product ID.
 * @return array Array of attachment IDs.
 * @since 1.0.0
 */
function kha_get_product_gallery( $product_id ) {
	$gallery = get_post_meta( $product_id, '_product_gallery', true );

	if ( is_array( $gallery ) ) {
		return $gallery;
	}

	// Handle comma-separated string (legacy support).
	if ( is_string( $gallery ) && ! empty( $gallery ) ) {
		return array_map( 'intval', explode( ',', $gallery ) );
	}

	return array();
}

/**
 * Calculate discount percentage.
 *
 * @param int $product_id Product ID.
 * @return int Discount percentage or 0.
 * @since 1.0.0
 */
function kha_get_discount_percentage( $product_id ) {
	$regular_price = get_post_meta( $product_id, '_regular_price', true );
	$sale_price    = get_post_meta( $product_id, '_sale_price', true );

	if ( empty( $regular_price ) || empty( $sale_price ) || $sale_price >= $regular_price ) {
		return 0;
	}

	$discount = ( ( $regular_price - $sale_price ) / $regular_price ) * 100;

	return round( $discount );
}

/**
 * Get bundle products.
 *
 * If product is a bundle, returns array of bundled product IDs.
 *
 * @param int $product_id Product ID.
 * @return array Array of product IDs or empty array.
 * @since 1.0.0
 */
function kha_get_bundle_products( $product_id ) {
	$product_type = get_post_meta( $product_id, '_product_type', true );

	if ( 'bundle' !== $product_type ) {
		return array();
	}

	$bundle_products = get_post_meta( $product_id, '_bundle_products', true );

	return is_array( $bundle_products ) ? $bundle_products : array();
}

/**
 * Get bundle discount.
 *
 * @param int $product_id Product ID.
 * @return int Bundle discount percentage or 0.
 * @since 1.0.0
 */
function kha_get_bundle_discount( $product_id ) {
	$product_type = get_post_meta( $product_id, '_product_type', true );

	if ( 'bundle' !== $product_type ) {
		return 0;
	}

	$discount = get_post_meta( $product_id, '_bundle_discount', true );

	return $discount ? absint( $discount ) : 0;
}
