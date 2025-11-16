<?php
/**
 * Product search functionality.
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
 * Search class.
 *
 * Handles product search and autocomplete functionality.
 */
class Search {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Add search functionality hooks if needed.
	}

	/**
	 * Search products.
	 *
	 * @param string $search_term Search term.
	 * @param int    $limit       Results limit.
	 * @return array
	 * @since 1.0.0
	 */
	public function search( $search_term, $limit = 10 ) {
		$args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => absint( $limit ),
			's'              => sanitize_text_field( $search_term ),
			'post_status'    => 'publish',
			'orderby'        => 'relevance',
		);

		$query = new \WP_Query( $args );

		$results = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$product_id = get_the_ID();
				$price      = get_post_meta( $product_id, '_kha_product_price', true );
				$sku        = get_post_meta( $product_id, '_kha_product_sku', true );

				$results[] = array(
					'id'        => $product_id,
					'title'     => get_the_title(),
					'url'       => get_permalink(),
					'image'     => get_the_post_thumbnail_url( $product_id, 'kha-product-thumbnail' ),
					'price'     => $price ? kha_solar_format_price( $price ) : '',
					'sku'       => $sku,
					'excerpt'   => get_the_excerpt(),
				);
			}
			wp_reset_postdata();
		}

		return $results;
	}

	/**
	 * Search products by category.
	 *
	 * @param string $search_term Search term.
	 * @param int    $category_id Category ID.
	 * @param int    $limit       Results limit.
	 * @return array
	 * @since 1.0.0
	 */
	public function search_by_category( $search_term, $category_id, $limit = 10 ) {
		$args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => absint( $limit ),
			's'              => sanitize_text_field( $search_term ),
			'post_status'    => 'publish',
			'orderby'        => 'relevance',
			'tax_query'      => array(
				array(
					'taxonomy' => 'kha_product_cat',
					'field'    => 'term_id',
					'terms'    => absint( $category_id ),
				),
			),
		);

		$query = new \WP_Query( $args );

		$results = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$product_id = get_the_ID();
				$price      = get_post_meta( $product_id, '_kha_product_price', true );

				$results[] = array(
					'id'     => $product_id,
					'title'  => get_the_title(),
					'url'    => get_permalink(),
					'image'  => get_the_post_thumbnail_url( $product_id, 'kha-product-thumbnail' ),
					'price'  => $price ? kha_solar_format_price( $price ) : '',
				);
			}
			wp_reset_postdata();
		}

		return $results;
	}

	/**
	 * Get popular search terms.
	 *
	 * @param int $limit Results limit.
	 * @return array
	 * @since 1.0.0
	 */
	public function get_popular_searches( $limit = 5 ) {
		// This could be enhanced with actual search tracking.
		// For now, return popular product categories.
		$terms = get_terms(
			array(
				'taxonomy'   => 'kha_product_cat',
				'hide_empty' => true,
				'number'     => absint( $limit ),
				'orderby'    => 'count',
				'order'      => 'DESC',
			)
		);

		$results = array();

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$results[] = array(
					'term' => $term->name,
					'url'  => get_term_link( $term ),
				);
			}
		}

		return $results;
	}
}
