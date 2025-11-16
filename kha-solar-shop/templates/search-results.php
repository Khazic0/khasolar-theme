<?php
/**
 * Search Results Template
 *
 * Displays full search results with filters
 *
 * @package KhaSolar
 * @since   1.0.0
 */

// Get search query
$search_query = get_search_query();

if ( empty( $search_query ) ) {
	wp_redirect( home_url() );
	exit;
}

// Initialize search
$search = new \KhaSolar\Search();

// Get filter parameters
$category     = isset( $_GET['category'] ) ? absint( $_GET['category'] ) : '';
$brand        = isset( $_GET['brand'] ) ? absint( $_GET['brand'] ) : '';
$price_min    = isset( $_GET['price_min'] ) ? absint( $_GET['price_min'] ) : 0;
$price_max    = isset( $_GET['price_max'] ) ? absint( $_GET['price_max'] ) : 0;
$stock_status = isset( $_GET['stock_status'] ) ? sanitize_text_field( $_GET['stock_status'] ) : '';
$orderby      = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'relevance';

// Pagination
$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
$limit = 20;
$offset = ( $paged - 1 ) * $limit;

// Perform search
$results = $search->search_products(
	$search_query,
	array(
		'category'     => $category,
		'brand'        => $brand,
		'price_min'    => $price_min,
		'price_max'    => $price_max,
		'stock_status' => $stock_status,
		'orderby'      => $orderby,
		'limit'        => $limit,
		'offset'       => $offset,
	)
);

get_header();
?>

<div class="kha-search-results-page">
	<div class="container">
		<div class="kha-search-header">
			<h1 class="kha-search-title">
				<?php
				printf(
					__( 'Kết quả tìm kiếm: "%s"', 'kha-solar' ),
					'<span class="kha-search-query">' . esc_html( $search_query ) . '</span>'
				);
				?>
			</h1>
			<p class="kha-search-count">
				<?php
				printf(
					_n( 'Tìm thấy %d sản phẩm', 'Tìm thấy %d sản phẩm', $results['total'], 'kha-solar' ),
					$results['total']
				);
				?>
			</p>
		</div>

		<div class="kha-search-content">
			<!-- Sidebar Filters -->
			<aside class="kha-search-sidebar">
				<div class="kha-filter-box">
					<h3><?php esc_html_e( 'Bộ Lọc', 'kha-solar' ); ?></h3>

					<form method="get" class="kha-filter-form">
						<input type="hidden" name="s" value="<?php echo esc_attr( $search_query ); ?>">
						<input type="hidden" name="post_type" value="kha_product">

						<!-- Category Filter -->
						<div class="kha-filter-group">
							<label><?php esc_html_e( 'Danh Mục', 'kha-solar' ); ?></label>
							<select name="category">
								<option value=""><?php esc_html_e( 'Tất cả', 'kha-solar' ); ?></option>
								<?php
								$categories = get_terms(
									array(
										'taxonomy'   => 'kha_product_cat',
										'hide_empty' => true,
									)
								);
								foreach ( $categories as $cat ) :
									?>
									<option value="<?php echo esc_attr( $cat->term_id ); ?>" 
										<?php selected( $category, $cat->term_id ); ?>>
										<?php echo esc_html( $cat->name ); ?> (<?php echo $cat->count; ?>)
									</option>
								<?php endforeach; ?>
							</select>
						</div>

						<!-- Brand Filter -->
						<div class="kha-filter-group">
							<label><?php esc_html_e( 'Thương Hiệu', 'kha-solar' ); ?></label>
							<select name="brand">
								<option value=""><?php esc_html_e( 'Tất cả', 'kha-solar' ); ?></option>
								<?php
								$brands = get_terms(
									array(
										'taxonomy'   => 'kha_brand',
										'hide_empty' => true,
									)
								);
								foreach ( $brands as $b ) :
									?>
									<option value="<?php echo esc_attr( $b->term_id ); ?>" 
										<?php selected( $brand, $b->term_id ); ?>>
										<?php echo esc_html( $b->name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>

						<!-- Price Range Filter -->
						<div class="kha-filter-group">
							<label><?php esc_html_e( 'Khoảng Giá', 'kha-solar' ); ?></label>
							<div class="kha-price-inputs">
								<input type="number" 
									name="price_min" 
									placeholder="<?php esc_attr_e( 'Từ', 'kha-solar' ); ?>"
									value="<?php echo esc_attr( $price_min ); ?>">
								<span>-</span>
								<input type="number" 
									name="price_max" 
									placeholder="<?php esc_attr_e( 'Đến', 'kha-solar' ); ?>"
									value="<?php echo esc_attr( $price_max ); ?>">
							</div>
						</div>

						<!-- Stock Status Filter -->
						<div class="kha-filter-group">
							<label><?php esc_html_e( 'Tình Trạng', 'kha-solar' ); ?></label>
							<select name="stock_status">
								<option value=""><?php esc_html_e( 'Tất cả', 'kha-solar' ); ?></option>
								<option value="instock" <?php selected( $stock_status, 'instock' ); ?>>
									<?php esc_html_e( 'Còn hàng', 'kha-solar' ); ?>
								</option>
								<option value="outofstock" <?php selected( $stock_status, 'outofstock' ); ?>>
									<?php esc_html_e( 'Hết hàng', 'kha-solar' ); ?>
								</option>
								<option value="onbackorder" <?php selected( $stock_status, 'onbackorder' ); ?>>
									<?php esc_html_e( 'Đặt trước', 'kha-solar' ); ?>
								</option>
							</select>
						</div>

						<button type="submit" class="kha-filter-submit">
							<?php esc_html_e( 'Áp Dụng', 'kha-solar' ); ?>
						</button>
					</form>
				</div>
			</aside>

			<!-- Main Results -->
			<main class="kha-search-main">
				<!-- Sort Options -->
				<div class="kha-search-toolbar">
					<div class="kha-sort-options">
						<label><?php esc_html_e( 'Sắp xếp:', 'kha-solar' ); ?></label>
						<select id="kha-sort-select" onchange="this.form.submit()">
							<option value="relevance" <?php selected( $orderby, 'relevance' ); ?>>
								<?php esc_html_e( 'Liên quan nhất', 'kha-solar' ); ?>
							</option>
							<option value="popularity" <?php selected( $orderby, 'popularity' ); ?>>
								<?php esc_html_e( 'Phổ biến nhất', 'kha-solar' ); ?>
							</option>
							<option value="price" <?php selected( $orderby, 'price' ); ?>>
								<?php esc_html_e( 'Giá: Thấp đến cao', 'kha-solar' ); ?>
							</option>
							<option value="price-desc" <?php selected( $orderby, 'price-desc' ); ?>>
								<?php esc_html_e( 'Giá: Cao đến thấp', 'kha-solar' ); ?>
							</option>
						</select>
					</div>
				</div>

				<!-- Products Grid -->
				<?php if ( ! empty( $results['products'] ) ) : ?>
					<div class="kha-products-grid">
						<?php foreach ( $results['products'] as $product ) : ?>
							<article class="kha-product-card">
								<a href="<?php echo esc_url( $product['url'] ); ?>" class="kha-product-link">
									<div class="kha-product-image">
										<?php if ( $product['image'] ) : ?>
											<img src="<?php echo esc_url( $product['image'] ); ?>" 
												alt="<?php echo esc_attr( $product['title_plain'] ); ?>"
												loading="lazy">
										<?php else : ?>
											<div class="kha-no-image">
												<span class="dashicons dashicons-camera"></span>
											</div>
										<?php endif; ?>
										<?php if ( $product['on_sale'] ) : ?>
											<span class="kha-sale-badge"><?php esc_html_e( 'Sale', 'kha-solar' ); ?></span>
										<?php endif; ?>
									</div>

									<div class="kha-product-info">
										<h3 class="kha-product-title"><?php echo wp_kses_post( $product['title'] ); ?></h3>
										
										<div class="kha-product-price">
											<?php echo wp_kses_post( $product['price'] ); ?>
										</div>

										<?php echo wp_kses_post( $product['stock_status'] ); ?>

										<?php if ( $product['sku'] ) : ?>
											<div class="kha-product-sku">
												<?php echo esc_html( $product['sku'] ); ?>
											</div>
										<?php endif; ?>
									</div>
								</a>
							</article>
						<?php endforeach; ?>
					</div>

					<!-- Pagination -->
					<?php if ( $results['total_pages'] > 1 ) : ?>
						<div class="kha-pagination">
							<?php
							echo paginate_links(
								array(
									'base'      => add_query_arg( 'paged', '%#%' ),
									'format'    => '?paged=%#%',
									'current'   => $paged,
									'total'     => $results['total_pages'],
									'prev_text' => '&laquo; ' . __( 'Trước', 'kha-solar' ),
									'next_text' => __( 'Sau', 'kha-solar' ) . ' &raquo;',
								)
							);
							?>
						</div>
					<?php endif; ?>

				<?php else : ?>
					<!-- No Results -->
					<div class="kha-no-results-full">
						<div class="kha-no-results-icon">🔍</div>
						<h2><?php esc_html_e( 'Không tìm thấy sản phẩm nào', 'kha-solar' ); ?></h2>
						<p><?php esc_html_e( 'Vui lòng thử tìm kiếm với từ khóa khác hoặc điều chỉnh bộ lọc.', 'kha-solar' ); ?></p>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="kha-back-home-btn">
							<?php esc_html_e( 'Về trang chủ', 'kha-solar' ); ?>
						</a>
					</div>
				<?php endif; ?>
			</main>
		</div>
	</div>
</div>

<?php get_footer(); ?>
