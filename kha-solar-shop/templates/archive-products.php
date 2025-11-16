<?php
/**
 * Product Archive Template
 *
 * Displays products grid with filtering sidebar
 *
 * @package KhaSolar
 * @since   1.0.0
 */

get_header();

// Get max price for slider
global $wpdb;
$max_price = $wpdb->get_var(
	"SELECT MAX(CAST(meta_value AS DECIMAL(10,2))) 
	FROM {$wpdb->postmeta} 
	WHERE meta_key = '_price' 
	AND meta_value != ''"
);
$max_price = $max_price ? ceil( $max_price ) : 100000000;

// Get all categories
$categories = get_terms(
	array(
		'taxonomy'   => 'kha_product_cat',
		'hide_empty' => true,
	)
);

// Get all brands
$brands = get_terms(
	array(
		'taxonomy'   => 'kha_brand',
		'hide_empty' => true,
	)
);
?>

<div class="kha-shop-page">
	<div class="container">
		<!-- Mobile Filter Toggle -->
		<div class="kha-mobile-controls">
			<button class="kha-mobile-filter-toggle">
				<span class="dashicons dashicons-filter"></span>
				<?php esc_html_e( 'Bộ Lọc', 'kha-solar' ); ?>
			</button>
			<span class="kha-product-count"></span>
		</div>

		<div class="kha-shop-content">
			<!-- Filter Sidebar -->
			<aside class="kha-filter-sidebar">
				<div class="kha-filter-header">
					<h3><?php esc_html_e( 'Bộ Lọc', 'kha-solar' ); ?></h3>
					<button class="kha-filter-close">×</button>
				</div>

				<div class="kha-filter-inner">
					<!-- Quick Search -->
					<div class="kha-filter-group kha-filter-search">
						<label><?php esc_html_e( '🔍 Tìm Kiếm Nhanh', 'kha-solar' ); ?></label>
						<input type="search" 
							placeholder="<?php esc_attr_e( 'Nhập từ khóa...', 'kha-solar' ); ?>">
					</div>

					<!-- Price Range -->
					<div class="kha-filter-group kha-filter-price">
						<label><?php esc_html_e( '💰 Khoảng Giá', 'kha-solar' ); ?></label>
						<div class="kha-price-range-slider" 
							data-min="0" 
							data-max="<?php echo esc_attr( $max_price ); ?>">
						</div>
					</div>

					<!-- Categories -->
					<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
						<div class="kha-filter-group kha-category-filter">
							<label><?php esc_html_e( '📁 Danh Mục', 'kha-solar' ); ?></label>
							<?php foreach ( $categories as $category ) : ?>
								<label class="kha-filter-checkbox">
									<input type="checkbox" 
										value="<?php echo esc_attr( $category->term_id ); ?>">
									<span><?php echo esc_html( $category->name ); ?> (<?php echo $category->count; ?>)</span>
								</label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<!-- Power Output -->
					<div class="kha-filter-group kha-power-filter">
						<label><?php esc_html_e( '⚡ Công Suất', 'kha-solar' ); ?></label>
						<label class="kha-filter-checkbox">
							<input type="checkbox" value="0-3000">
							<span>&lt; 3kW</span>
						</label>
						<label class="kha-filter-checkbox">
							<input type="checkbox" value="3000-5000">
							<span>3-5kW</span>
						</label>
						<label class="kha-filter-checkbox">
							<input type="checkbox" value="5000-10000">
							<span>5-10kW</span>
						</label>
						<label class="kha-filter-checkbox">
							<input type="checkbox" value="10000+">
							<span>&gt; 10kW</span>
						</label>
					</div>

					<!-- Brands -->
					<?php if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) : ?>
						<div class="kha-filter-group kha-brand-filter">
							<label><?php esc_html_e( '🏭 Thương Hiệu', 'kha-solar' ); ?></label>
							<?php foreach ( $brands as $brand ) : ?>
								<label class="kha-filter-checkbox">
									<input type="checkbox" 
										value="<?php echo esc_attr( $brand->term_id ); ?>">
									<span><?php echo esc_html( $brand->name ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<!-- Stock Status -->
					<div class="kha-filter-group kha-stock-filter">
						<label><?php esc_html_e( '📦 Tình Trạng', 'kha-solar' ); ?></label>
						<label class="kha-filter-checkbox">
							<input type="checkbox" value="instock">
							<span><?php esc_html_e( 'Còn hàng', 'kha-solar' ); ?></span>
						</label>
						<label class="kha-filter-checkbox">
							<input type="checkbox" value="on_sale">
							<span><?php esc_html_e( 'Sale', 'kha-solar' ); ?></span>
						</label>
						<label class="kha-filter-checkbox">
							<input type="checkbox" value="is_new">
							<span><?php esc_html_e( 'Mới về', 'kha-solar' ); ?></span>
						</label>
					</div>

					<!-- Reset Button -->
					<button class="kha-reset-filters">
						<?php esc_html_e( 'Đặt Lại Bộ Lọc', 'kha-solar' ); ?>
					</button>
				</div>
			</aside>

			<!-- Main Products Area -->
			<main class="kha-shop-main">
				<!-- Active Filters -->
				<div class="kha-active-filters" style="display: none;"></div>

				<!-- Toolbar -->
				<div class="kha-shop-toolbar">
					<div class="kha-toolbar-left">
						<h1 class="kha-shop-title"><?php esc_html_e( 'Sản Phẩm', 'kha-solar' ); ?></h1>
						<span class="kha-product-count"></span>
					</div>

					<div class="kha-toolbar-right">
						<label><?php esc_html_e( 'Sắp xếp:', 'kha-solar' ); ?></label>
						<select class="kha-sort-dropdown">
							<option value="date"><?php esc_html_e( 'Mới nhất', 'kha-solar' ); ?></option>
							<option value="price"><?php esc_html_e( 'Giá: Thấp → Cao', 'kha-solar' ); ?></option>
							<option value="price-desc"><?php esc_html_e( 'Giá: Cao → Thấp', 'kha-solar' ); ?></option>
							<option value="popularity"><?php esc_html_e( 'Bán chạy nhất', 'kha-solar' ); ?></option>
							<option value="rating"><?php esc_html_e( 'Đánh giá cao nhất', 'kha-solar' ); ?></option>
						</select>
					</div>
				</div>

				<!-- Products Grid -->
				<div class="kha-products-grid">
					<?php
					$args = array(
						'post_type'      => 'kha_product',
						'posts_per_page' => 12,
						'post_status'    => 'publish',
					);

					$products_query = new WP_Query( $args );

					if ( $products_query->have_posts() ) :
						while ( $products_query->have_posts() ) :
							$products_query->the_post();
							kha_solar_get_template( 'partials/product-card.php', array( 'product_id' => get_the_ID() ) );
						endwhile;
						wp_reset_postdata();
					else :
						?>
						<div class="kha-no-products">
							<div class="kha-no-products-icon">📦</div>
							<h3><?php esc_html_e( 'Không có sản phẩm nào', 'kha-solar' ); ?></h3>
						</div>
					<?php endif; ?>
				</div>

				<!-- Pagination -->
				<div class="kha-pagination-wrapper">
					<?php
					if ( $products_query->max_num_pages > 1 ) {
						echo paginate_links(
							array(
								'total'     => $products_query->max_num_pages,
								'current'   => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
								'type'      => 'list',
								'prev_text' => '&laquo; ' . __( 'Trước', 'kha-solar' ),
								'next_text' => __( 'Sau', 'kha-solar' ) . ' &raquo;',
							)
						);
					}
					?>
				</div>
			</main>
		</div>
	</div>
</div>

<div class="kha-filter-overlay"></div>

<?php
get_footer();
?>
