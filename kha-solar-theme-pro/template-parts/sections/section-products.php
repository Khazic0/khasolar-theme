<?php
/**
 * Homepage Section - Products Showcase
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

// Query featured products
$products_query = new WP_Query(
	array(
		'post_type'      => 'kha_product',
		'posts_per_page' => 8,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
	)
);

if ( ! $products_query->have_posts() ) {
	return;
}
?>

<section class="products-section section-padding bg-light">
	<div class="container">
		<div class="section-header text-center">
			<h2 class="section-title"><?php echo esc_html( khasolar_pro_get_option( 'products_title', __( 'Featured Products', 'kha-solar-theme-pro' ) ) ); ?></h2>
			<p class="section-subtitle"><?php echo esc_html( khasolar_pro_get_option( 'products_subtitle', __( 'Premium solar panels and equipment', 'kha-solar-theme-pro' ) ) ); ?></p>
		</div>

		<!-- Product Tabs -->
		<div class="product-tabs">
			<ul class="tabs-nav">
				<li class="active"><a href="#all-products" data-toggle="tab"><?php esc_html_e( 'All Products', 'kha-solar-theme-pro' ); ?></a></li>
				<li><a href="#solar-panels" data-toggle="tab"><?php esc_html_e( 'Solar Panels', 'kha-solar-theme-pro' ); ?></a></li>
				<li><a href="#inverters" data-toggle="tab"><?php esc_html_e( 'Inverters', 'kha-solar-theme-pro' ); ?></a></li>
				<li><a href="#batteries" data-toggle="tab"><?php esc_html_e( 'Batteries', 'kha-solar-theme-pro' ); ?></a></li>
			</ul>

			<div class="tabs-content">
				<div id="all-products" class="tab-pane active">
					<div class="products-grid row">
						<?php
						while ( $products_query->have_posts() ) :
							$products_query->the_post();

							$price = get_post_meta( get_the_ID(), '_kha_product_price', true );
							$power = get_post_meta( get_the_ID(), '_kha_product_power', true );
							?>
							<div class="col-lg-3 col-md-4 col-sm-6">
								<div class="product-card">
									<div class="product-image">
										<a href="<?php the_permalink(); ?>">
											<?php
											if ( has_post_thumbnail() ) {
												the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) );
											} else {
												echo '<img src="' . esc_url( KHASOLAR_PRO_URI . '/assets/images/placeholder-product.jpg' ) . '" alt="' . esc_attr( get_the_title() ) . '">';
											}
											?>
										</a>
										<div class="product-badges">
											<?php if ( get_post_meta( get_the_ID(), '_kha_product_featured', true ) ) : ?>
												<span class="badge badge-featured"><?php esc_html_e( 'Featured', 'kha-solar-theme-pro' ); ?></span>
											<?php endif; ?>
										</div>
										<div class="product-actions">
											<button class="btn-wishlist" data-product-id="<?php the_ID(); ?>" title="<?php esc_attr_e( 'Add to Wishlist', 'kha-solar-theme-pro' ); ?>">
												<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
													<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
												</svg>
											</button>
											<button class="btn-compare" data-product-id="<?php the_ID(); ?>" title="<?php esc_attr_e( 'Add to Compare', 'kha-solar-theme-pro' ); ?>">
												<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
													<polyline points="16 3 21 3 21 8"></polyline>
													<line x1="4" y1="20" x2="21" y2="3"></line>
													<polyline points="21 16 21 21 16 21"></polyline>
													<line x1="15" y1="15" x2="21" y2="21"></line>
													<line x1="4" y1="4" x2="9" y2="9"></line>
												</svg>
											</button>
										</div>
									</div>
									<div class="product-info">
										<?php if ( ! empty( $power ) ) : ?>
											<span class="product-power"><?php echo esc_html( $power ); ?>W</span>
										<?php endif; ?>
										<h3 class="product-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<?php if ( ! empty( $price ) ) : ?>
											<div class="product-price">
												<span class="price-amount"><?php echo esc_html( number_format( $price, 0, ',', '.' ) ); ?> đ</span>
											</div>
										<?php endif; ?>
										<button class="btn btn-primary btn-block btn-add-to-cart" data-product-id="<?php the_ID(); ?>">
											<?php esc_html_e( 'Add to Cart', 'kha-solar-theme-pro' ); ?>
										</button>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="section-footer text-center">
			<a href="<?php echo esc_url( home_url( '/san-pham' ) ); ?>" class="btn btn-lg btn-primary">
				<?php esc_html_e( 'View All Products', 'kha-solar-theme-pro' ); ?>
			</a>
		</div>
	</div>
</section>

<?php wp_reset_postdata(); ?>
