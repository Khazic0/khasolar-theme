<?php
/**
 * Archive Products Template
 *
 * @package KhaSolar
 * @since   1.0.0
 */

get_header();
?>

<div class="kha-shop-wrapper" style="max-width: 1400px; margin: 40px auto; padding: 0 20px;">

	<h1 style="margin-bottom: 30px; font-size: 32px; text-align: center;">
		<?php
		if ( is_tax() ) {
			single_term_title();
		} else {
			esc_html_e( 'All Products', 'kha-solar' );
		}
		?>
	</h1>

	<!-- Product Filters -->
	<div class="kha-product-filters">
		<div class="kha-filters-row">
			<div class="kha-search-bar">
				<input type="text" class="kha-search-input" placeholder="<?php esc_attr_e( 'Search products...', 'kha-solar' ); ?>">
				<button class="kha-search-button"><?php esc_html_e( 'Search', 'kha-solar' ); ?></button>
			</div>

			<div class="kha-filter-group">
				<label class="kha-filter-label"><?php esc_html_e( 'Category', 'kha-solar' ); ?></label>
				<select class="kha-filter-select kha-filter-category">
					<option value=""><?php esc_html_e( 'All Categories', 'kha-solar' ); ?></option>
					<?php
					$categories = get_terms( array( 'taxonomy' => 'kha_product_cat', 'hide_empty' => true ) );
					if ( $categories && ! is_wp_error( $categories ) ) :
						foreach ( $categories as $category ) :
							?>
							<option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>

			<div class="kha-filter-group">
				<label class="kha-filter-label"><?php esc_html_e( 'Sort By', 'kha-solar' ); ?></label>
				<select class="kha-filter-select kha-filter-sort">
					<option value=""><?php esc_html_e( 'Default', 'kha-solar' ); ?></option>
					<option value="price-asc"><?php esc_html_e( 'Price: Low to High', 'kha-solar' ); ?></option>
					<option value="price-desc"><?php esc_html_e( 'Price: High to Low', 'kha-solar' ); ?></option>
					<option value="name"><?php esc_html_e( 'Name: A to Z', 'kha-solar' ); ?></option>
					<option value="date"><?php esc_html_e( 'Latest', 'kha-solar' ); ?></option>
				</select>
			</div>
		</div>
	</div>

	<!-- Products Grid -->
	<?php if ( have_posts() ) : ?>
		<div class="kha-products-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				$product_id    = get_the_ID();
				$price         = get_post_meta( $product_id, '_kha_product_price', true );
				$sale_price    = get_post_meta( $product_id, '_kha_product_sale_price', true );
				$stock_status  = get_post_meta( $product_id, '_kha_product_stock_status', true );
				$power         = get_post_meta( $product_id, '_kha_product_power', true );

				$display_price = $sale_price ? $sale_price : $price;
				?>

				<div class="kha-product-card">
					<?php if ( $sale_price && $price ) : ?>
						<span class="kha-product-badge sale"><?php esc_html_e( 'Sale', 'kha-solar' ); ?></span>
					<?php elseif ( 'outofstock' === $stock_status ) : ?>
						<span class="kha-product-badge out-of-stock"><?php esc_html_e( 'Out of Stock', 'kha-solar' ); ?></span>
					<?php endif; ?>

					<div class="kha-product-image">
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'kha-product-medium' ); ?>
							<?php else : ?>
								<div style="width: 100%; height: 100%; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999;">
									<?php esc_html_e( 'No image', 'kha-solar' ); ?>
								</div>
							<?php endif; ?>
						</a>
						<input type="checkbox" class="kha-compare-checkbox" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php esc_attr_e( 'Add to comparison', 'kha-solar' ); ?>">
					</div>

					<div class="kha-product-info">
						<?php
						$categories = get_the_terms( $product_id, 'kha_product_cat' );
						if ( $categories && ! is_wp_error( $categories ) ) :
							?>
							<div class="kha-product-category"><?php echo esc_html( $categories[0]->name ); ?></div>
						<?php endif; ?>

						<h3 class="kha-product-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<?php if ( $power ) : ?>
							<div class="kha-product-specs">
								<span class="kha-product-spec">⚡ <?php echo esc_html( $power ); ?></span>
							</div>
						<?php endif; ?>

						<div class="kha-product-price">
							<?php if ( $display_price ) : ?>
								<span class="kha-price-current"><?php echo kha_solar_format_price( $display_price ); ?></span>
								<?php if ( $sale_price && $price ) : ?>
									<span class="kha-price-regular"><?php echo kha_solar_format_price( $price ); ?></span>
								<?php endif; ?>
							<?php endif; ?>
						</div>

						<div class="kha-product-actions">
							<?php if ( 'instock' === $stock_status ) : ?>
								<button class="kha-btn kha-btn-primary kha-add-to-cart" data-product-id="<?php echo esc_attr( $product_id ); ?>">
									<?php esc_html_e( 'Add to Cart', 'kha-solar' ); ?>
								</button>
							<?php else : ?>
								<button class="kha-btn kha-btn-secondary" disabled>
									<?php esc_html_e( 'Out of Stock', 'kha-solar' ); ?>
								</button>
							<?php endif; ?>
						</div>
					</div>
				</div>

			<?php endwhile; ?>
		</div>

		<!-- Pagination -->
		<div class="kha-pagination">
			<?php
			echo paginate_links(
				array(
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
				)
			);
			?>
		</div>

	<?php else : ?>
		<div style="text-align: center; padding: 60px 20px; background: white; border-radius: 8px;">
			<h2><?php esc_html_e( 'No products found', 'kha-solar' ); ?></h2>
			<p><?php esc_html_e( 'Try adjusting your filters or search terms', 'kha-solar' ); ?></p>
		</div>
	<?php endif; ?>

</div>

<!-- Comparison Counter -->
<div class="kha-comparison-counter">
	<span class="kha-comparison-count">0 Products</span>
	<button class="kha-view-comparison-btn"><?php esc_html_e( 'Compare', 'kha-solar' ); ?></button>
</div>

<?php get_footer(); ?>
