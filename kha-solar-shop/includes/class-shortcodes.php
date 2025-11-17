<?php
/**
 * Shortcodes Class
 *
 * Registers and handles all plugin shortcodes.
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
 * Shortcodes class.
 */
class Shortcodes {

	/**
	 * Initialize shortcodes.
	 */
	public function init() {
		add_shortcode( 'kha_products', array( $this, 'products_shortcode' ) );
		add_shortcode( 'kha_product_categories', array( $this, 'product_categories_shortcode' ) );
		add_shortcode( 'kha_cart', array( $this, 'cart_shortcode' ) );
		add_shortcode( 'kha_checkout', array( $this, 'checkout_shortcode' ) );
		add_shortcode( 'kha_calculator', array( $this, 'calculator_shortcode' ) );
		add_shortcode( 'kha_comparison', array( $this, 'comparison_shortcode' ) );
		add_shortcode( 'kha_search_box', array( $this, 'search_box_shortcode' ) );
		add_shortcode( 'kha_featured_products', array( $this, 'featured_products_shortcode' ) );
	}

	/**
	 * Products grid shortcode.
	 *
	 * Usage: [kha_products category="inverter" limit="8" columns="4" orderby="price" order="ASC"]
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function products_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'category' => '',
				'limit'    => 12,
				'columns'  => 4,
				'orderby'  => 'date',
				'order'    => 'DESC',
			),
			$atts,
			'kha_products'
		);

		$args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => absint( $atts['limit'] ),
			'post_status'    => 'publish',
			'orderby'        => sanitize_text_field( $atts['orderby'] ),
			'order'          => sanitize_text_field( $atts['order'] ),
		);

		// Add category filter if specified.
		if ( ! empty( $atts['category'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'kha_product_cat',
					'field'    => 'slug',
					'terms'    => sanitize_text_field( $atts['category'] ),
				),
			);
		}

		// Handle meta key orderby for price.
		if ( 'price' === $atts['orderby'] ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_price';
		}

		$query = new \WP_Query( $args );

		ob_start();

		if ( $query->have_posts() ) {
			echo '<div class="kha-products-grid kha-grid-cols-' . esc_attr( $atts['columns'] ) . '">';

			while ( $query->have_posts() ) {
				$query->the_post();
				kha_solar_get_template( 'partials/product-card.php', array( 'product_id' => get_the_ID() ) );
			}

			echo '</div>';

			wp_reset_postdata();
		} else {
			echo '<p class="kha-no-products">' . esc_html__( 'Không có sản phẩm nào.', 'kha-solar' ) . '</p>';
		}

		return ob_get_clean();
	}

	/**
	 * Product categories shortcode.
	 *
	 * Usage: [kha_product_categories columns="3" show_count="yes"]
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function product_categories_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'columns'    => 3,
				'show_count' => 'yes',
			),
			$atts,
			'kha_product_categories'
		);

		$terms = get_terms(
			array(
				'taxonomy'   => 'kha_product_cat',
				'hide_empty' => true,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return '<p>' . esc_html__( 'Không có danh mục nào.', 'kha-solar' ) . '</p>';
		}

		ob_start();

		echo '<div class="kha-categories-grid kha-grid-cols-' . esc_attr( $atts['columns'] ) . '">';

		foreach ( $terms as $term ) {
			$term_link = get_term_link( $term );
			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			$thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : KHA_PLUGIN_URL . 'assets/images/placeholder.png';

			echo '<div class="kha-category-card">';
			echo '<a href="' . esc_url( $term_link ) . '">';
			echo '<div class="kha-category-image">';
			echo '<img src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( $term->name ) . '" loading="lazy">';
			echo '</div>';
			echo '<div class="kha-category-info">';
			echo '<h3 class="kha-category-title">' . esc_html( $term->name ) . '</h3>';

			if ( 'yes' === $atts['show_count'] ) {
				echo '<span class="kha-category-count">' . sprintf( _n( '%s sản phẩm', '%s sản phẩm', $term->count, 'kha-solar' ), number_format_i18n( $term->count ) ) . '</span>';
			}

			echo '</div>';
			echo '</a>';
			echo '</div>';
		}

		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Cart shortcode.
	 *
	 * Usage: [kha_cart]
	 *
	 * @return string Shortcode output.
	 */
	public function cart_shortcode() {
		ob_start();
		kha_solar_get_template( 'cart.php' );
		return ob_get_clean();
	}

	/**
	 * Checkout shortcode.
	 *
	 * Usage: [kha_checkout]
	 *
	 * @return string Shortcode output.
	 */
	public function checkout_shortcode() {
		ob_start();
		kha_solar_get_template( 'checkout.php' );
		return ob_get_clean();
	}

	/**
	 * Calculator shortcode.
	 *
	 * Usage: [kha_calculator]
	 *
	 * @return string Shortcode output.
	 */
	public function calculator_shortcode() {
		ob_start();
		kha_solar_get_template( 'calculator.php' );
		return ob_get_clean();
	}

	/**
	 * Comparison shortcode.
	 *
	 * Usage: [kha_comparison]
	 *
	 * @return string Shortcode output.
	 */
	public function comparison_shortcode() {
		ob_start();
		kha_solar_get_template( 'comparison.php' );
		return ob_get_clean();
	}

	/**
	 * Search box shortcode.
	 *
	 * Usage: [kha_search_box]
	 *
	 * @return string Shortcode output.
	 */
	public function search_box_shortcode() {
		ob_start();
		kha_solar_get_template( 'partials/search-box.php' );
		return ob_get_clean();
	}

	/**
	 * Featured products shortcode.
	 *
	 * Usage: [kha_featured_products limit="8" columns="4"]
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function featured_products_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'limit'   => 8,
				'columns' => 4,
			),
			$atts,
			'kha_featured_products'
		);

		$args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => absint( $atts['limit'] ),
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'   => '_featured',
					'value' => '1',
				),
			),
		);

		$query = new \WP_Query( $args );

		ob_start();

		if ( $query->have_posts() ) {
			echo '<div class="kha-products-grid kha-featured-products kha-grid-cols-' . esc_attr( $atts['columns'] ) . '">';

			while ( $query->have_posts() ) {
				$query->the_post();
				kha_solar_get_template( 'partials/product-card.php', array( 'product_id' => get_the_ID() ) );
			}

			echo '</div>';

			wp_reset_postdata();
		} else {
			echo '<p class="kha-no-products">' . esc_html__( 'Không có sản phẩm nổi bật.', 'kha-solar' ) . '</p>';
		}

		return ob_get_clean();
	}
}
