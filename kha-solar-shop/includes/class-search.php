<?php
/**
 * Product search functionality with AJAX autocomplete.
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
 * Handles product search, autocomplete, and search analytics.
 */
class Search {

	/**
	 * Cache group for transients.
	 *
	 * @var string
	 */
	private $cache_group = 'kha_search';

	/**
	 * Cache expiration time (5 minutes).
	 *
	 * @var int
	 */
	private $cache_time = 300;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_header', array( $this, 'render_search_box' ) );
	}

	/**
	 * Enqueue search scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		if ( ! is_admin() ) {
			wp_enqueue_script(
				'kha-search-autocomplete',
				KHA_PLUGIN_URL . 'public/js/search-autocomplete.js',
				array( 'jquery' ),
				KHA_VERSION,
				true
			);

			wp_localize_script(
				'kha-search-autocomplete',
				'khaSearchConfig',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'kha_solar_nonce' ),
					'strings' => array(
						'searching'    => __( 'Đang tìm kiếm...', 'kha-solar' ),
						'noResults'    => __( 'Không tìm thấy sản phẩm', 'kha-solar' ),
						'viewAll'      => __( 'Xem tất cả kết quả', 'kha-solar' ),
						'products'     => __( 'Sản Phẩm', 'kha-solar' ),
						'categories'   => __( 'Danh Mục', 'kha-solar' ),
						'popularTitle' => __( 'Tìm kiếm phổ biến', 'kha-solar' ),
					),
				)
			);
		}
	}

	/**
	 * Render search box in header.
	 *
	 * @since 1.0.0
	 */
	public function render_search_box() {
		?>
		<div class="kha-search-box">
			<form role="search" method="get" class="kha-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="hidden" name="post_type" value="kha_product">
				<div class="kha-search-input-wrapper">
					<input type="search"
						id="kha-search-input"
						name="s"
						class="kha-search-input"
						placeholder="<?php esc_attr_e( 'Tìm inverter, pin mặt trời...', 'kha-solar' ); ?>"
						autocomplete="off"
						value="<?php echo get_search_query(); ?>">
					<button type="submit" class="kha-search-submit">
						<span class="dashicons dashicons-search"></span>
					</button>
				</div>
				<div id="kha-search-results" class="kha-autocomplete-dropdown" style="display: none;">
					<!-- AJAX results populated here -->
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Search products with advanced filters and relevance scoring.
	 *
	 * @param string $query Search query.
	 * @param array  $args  Additional arguments.
	 * @return array Array of product results.
	 * @since 1.0.0
	 */
	public function search_products( $query, $args = array() ) {
		global $wpdb;

		$defaults = array(
			'category'     => '',
			'brand'        => '',
			'price_min'    => 0,
			'price_max'    => 0,
			'stock_status' => '',
			'orderby'      => 'relevance',
			'order'        => 'DESC',
			'limit'        => 20,
			'offset'       => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		// Build WP_Query args.
		$query_args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => absint( $args['limit'] ),
			'offset'         => absint( $args['offset'] ),
			'post_status'    => 'publish',
		);

		// Search in title, content, and SKU.
		if ( ! empty( $query ) ) {
			$query_args['s'] = sanitize_text_field( $query );

			// Also search in meta fields (SKU, specs).
			$meta_query = array(
				'relation' => 'OR',
				array(
					'key'     => '_sku',
					'value'   => $query,
					'compare' => 'LIKE',
				),
				array(
					'key'     => '_power_output',
					'value'   => $query,
					'compare' => 'LIKE',
				),
			);

			$query_args['meta_query'] = $meta_query;
		}

		// Category filter.
		$tax_query = array();
		if ( ! empty( $args['category'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'kha_product_cat',
				'field'    => 'term_id',
				'terms'    => absint( $args['category'] ),
			);
		}

		// Brand filter.
		if ( ! empty( $args['brand'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'kha_brand',
				'field'    => 'term_id',
				'terms'    => absint( $args['brand'] ),
			);
		}

		if ( ! empty( $tax_query ) ) {
			$tax_query['relation']  = 'AND';
			$query_args['tax_query'] = $tax_query;
		}

		// Price range filter.
		if ( ! empty( $args['price_min'] ) || ! empty( $args['price_max'] ) ) {
			$price_meta_query = array( 'relation' => 'AND' );

			if ( ! empty( $args['price_min'] ) ) {
				$price_meta_query[] = array(
					'key'     => '_price',
					'value'   => floatval( $args['price_min'] ),
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);
			}

			if ( ! empty( $args['price_max'] ) ) {
				$price_meta_query[] = array(
					'key'     => '_price',
					'value'   => floatval( $args['price_max'] ),
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);
			}

			if ( isset( $query_args['meta_query'] ) ) {
				$query_args['meta_query'] = array(
					'relation' => 'AND',
					$query_args['meta_query'],
					$price_meta_query,
				);
			} else {
				$query_args['meta_query'] = $price_meta_query;
			}
		}

		// Stock status filter.
		if ( ! empty( $args['stock_status'] ) ) {
			$stock_meta = array(
				'key'   => '_stock_status',
				'value' => sanitize_text_field( $args['stock_status'] ),
			);

			if ( isset( $query_args['meta_query'] ) ) {
				$query_args['meta_query'][] = $stock_meta;
			} else {
				$query_args['meta_query'] = array( $stock_meta );
			}
		}

		// Order by relevance and popularity.
		if ( 'relevance' === $args['orderby'] ) {
			$query_args['orderby'] = 'relevance';
		} elseif ( 'popularity' === $args['orderby'] ) {
			$query_args['orderby']  = 'meta_value_num';
			$query_args['meta_key'] = '_view_count';
			$query_args['order']    = 'DESC';
		} elseif ( 'price' === $args['orderby'] ) {
			$query_args['orderby']  = 'meta_value_num';
			$query_args['meta_key'] = '_price';
			$query_args['order']    = $args['order'];
		} else {
			$query_args['orderby'] = $args['orderby'];
			$query_args['order']   = $args['order'];
		}

		// Execute query.
		$products_query = new \WP_Query( $query_args );

		$results = array(
			'products'    => array(),
			'total'       => $products_query->found_posts,
			'total_pages' => $products_query->max_num_pages,
		);

		if ( $products_query->have_posts() ) {
			while ( $products_query->have_posts() ) {
				$products_query->the_post();

				$product_id = get_the_ID();
				$price      = get_post_meta( $product_id, '_price', true );
				$sku        = get_post_meta( $product_id, '_sku', true );
				$thumbnail  = get_the_post_thumbnail_url( $product_id, 'medium' );

				// Highlight matched text.
				$title = get_the_title();
				if ( ! empty( $query ) ) {
					$title = $this->highlight_text( $title, $query );
				}

				$results['products'][] = array(
					'id'           => $product_id,
					'title'        => $title,
					'title_plain'  => get_the_title(),
					'url'          => get_permalink(),
					'image'        => $thumbnail ? $thumbnail : '',
					'price'        => $price ? kha_solar_format_price( $price ) : '',
					'price_raw'    => $price ? floatval( $price ) : 0,
					'sku'          => $sku,
					'excerpt'      => wp_trim_words( get_the_excerpt(), 20 ),
					'stock_status' => kha_get_product_stock_status( $product_id ),
					'on_sale'      => kha_is_product_on_sale( $product_id ),
				);
			}
			wp_reset_postdata();
		}

		return $results;
	}

	/**
	 * Get autocomplete suggestions for search.
	 *
	 * @param string $search_term Search term.
	 * @param int    $limit       Max number of results.
	 * @return array Autocomplete suggestions.
	 * @since 1.0.0
	 */
	public function get_autocomplete_suggestions( $search_term, $limit = 8 ) {
		// Check cache first.
		$cache_key = md5( 'autocomplete_' . $search_term . '_' . $limit );
		$cached    = get_transient( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$suggestions = array(
			'products'   => array(),
			'categories' => array(),
			'popular'    => array(),
		);

		// Get products.
		$product_args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => $limit,
			's'              => sanitize_text_field( $search_term ),
			'post_status'    => 'publish',
			'orderby'        => 'relevance',
		);

		$products_query = new \WP_Query( $product_args );

		if ( $products_query->have_posts() ) {
			while ( $products_query->have_posts() ) {
				$products_query->the_post();

				$product_id = get_the_ID();
				$price      = get_post_meta( $product_id, '_price', true );
				$thumbnail  = get_the_post_thumbnail_url( $product_id, 'thumbnail' );

				$suggestions['products'][] = array(
					'id'    => $product_id,
					'title' => $this->highlight_text( get_the_title(), $search_term ),
					'url'   => get_permalink(),
					'image' => $thumbnail ? $thumbnail : '',
					'price' => $price ? kha_solar_format_price( $price ) : '',
				);
			}
			wp_reset_postdata();
		}

		// Get matching categories.
		$categories = get_terms(
			array(
				'taxonomy'   => 'kha_product_cat',
				'hide_empty' => true,
				'search'     => $search_term,
				'number'     => 3,
			)
		);

		if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				$suggestions['categories'][] = array(
					'name'  => $this->highlight_text( $category->name, $search_term ),
					'url'   => get_term_link( $category ),
					'count' => $category->count,
				);
			}
		}

		// Get popular searches.
		$suggestions['popular'] = $this->get_popular_searches( 3 );

		// Cache for 5 minutes.
		set_transient( $cache_key, $suggestions, $this->cache_time );

		return $suggestions;
	}

	/**
	 * Track search queries for analytics.
	 *
	 * @param string $search_term  Search term.
	 * @param int    $result_count Number of results found.
	 * @since 1.0.0
	 */
	public function track_search( $search_term, $result_count ) {
		$search_term = sanitize_text_field( $search_term );

		if ( empty( $search_term ) || strlen( $search_term ) < 2 ) {
			return;
		}

		// Get existing search stats.
		$search_stats = get_option( 'kha_search_stats', array() );

		$search_key = md5( strtolower( $search_term ) );

		if ( isset( $search_stats[ $search_key ] ) ) {
			// Increment count.
			$search_stats[ $search_key ]['count']++;
			$search_stats[ $search_key ]['last_searched'] = current_time( 'mysql' );
		} else {
			// New search term.
			$search_stats[ $search_key ] = array(
				'term'          => $search_term,
				'count'         => 1,
				'result_count'  => $result_count,
				'first_searched' => current_time( 'mysql' ),
				'last_searched' => current_time( 'mysql' ),
			);
		}

		// Keep only top 100 searches to prevent database bloat.
		if ( count( $search_stats ) > 100 ) {
			// Sort by count.
			uasort(
				$search_stats,
				function( $a, $b ) {
					return $b['count'] - $a['count'];
				}
			);

			// Keep top 100.
			$search_stats = array_slice( $search_stats, 0, 100, true );
		}

		update_option( 'kha_search_stats', $search_stats );
	}

	/**
	 * Get popular search terms.
	 *
	 * @param int $limit Number of results.
	 * @return array Popular search terms.
	 * @since 1.0.0
	 */
	public function get_popular_searches( $limit = 5 ) {
		$search_stats = get_option( 'kha_search_stats', array() );

		if ( empty( $search_stats ) ) {
			// Return default popular searches.
			return array(
				array(
					'term' => 'Inverter 5kW',
					'url'  => add_query_arg( 's', 'Inverter 5kW', home_url( '/' ) ),
				),
				array(
					'term' => 'Pin năng lượng',
					'url'  => add_query_arg( 's', 'Pin năng lượng', home_url( '/' ) ),
				),
			);
		}

		// Sort by count.
		uasort(
			$search_stats,
			function( $a, $b ) {
				return $b['count'] - $a['count'];
			}
		);

		$popular = array();
		$count   = 0;

		foreach ( $search_stats as $stat ) {
			if ( $count >= $limit ) {
				break;
			}

			$popular[] = array(
				'term' => $stat['term'],
				'url'  => add_query_arg(
					array(
						's'         => $stat['term'],
						'post_type' => 'kha_product',
					),
					home_url( '/' )
				),
			);

			$count++;
		}

		return $popular;
	}

	/**
	 * Highlight search term in text.
	 *
	 * @param string $text        Text to highlight.
	 * @param string $search_term Search term.
	 * @return string Highlighted text.
	 * @since 1.0.0
	 */
	private function highlight_text( $text, $search_term ) {
		if ( empty( $search_term ) ) {
			return $text;
		}

		$search_term = preg_quote( $search_term, '/' );

		return preg_replace(
			'/(' . $search_term . ')/iu',
			'<mark class="kha-search-highlight">$1</mark>',
			$text
		);
	}

	/**
	 * Clear search cache.
	 *
	 * @since 1.0.0
	 */
	public function clear_cache() {
		global $wpdb;

		// Delete all transients with our cache key pattern.
		$wpdb->query(
			"DELETE FROM {$wpdb->options}
			WHERE option_name LIKE '_transient_%'
			AND option_name LIKE '%autocomplete_%'"
		);
	}
}
