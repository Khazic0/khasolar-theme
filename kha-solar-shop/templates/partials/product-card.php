<?php
/**
 * Product Card Partial
 *
 * @package KhaSolar
 * @since   1.0.0
 *
 * @var int $product_id Product ID.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$thumbnail     = get_the_post_thumbnail_url( $product_id, 'medium' );
$price         = get_post_meta( $product_id, '_price', true );
$regular_price = get_post_meta( $product_id, '_regular_price', true );
$sale_price    = get_post_meta( $product_id, '_sale_price', true );
$on_sale       = kha_is_product_on_sale( $product_id );
$power         = get_post_meta( $product_id, '_power_output', true );
$brand         = kha_get_product_brand( $product_id );
?>

<article class="kha-product-card">
	<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="kha-product-link">
		<!-- Image -->
		<div class="kha-product-image">
			<?php if ( $thumbnail ) : ?>
				<img src="<?php echo esc_url( $thumbnail ); ?>" 
					alt="<?php echo esc_attr( get_the_title( $product_id ) ); ?>" 
					loading="lazy">
			<?php else : ?>
				<div class="kha-no-image">
					<span class="dashicons dashicons-camera"></span>
				</div>
			<?php endif; ?>

			<?php if ( $on_sale && $regular_price && $sale_price ) : ?>
				<?php $discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 ); ?>
				<span class="kha-sale-badge">-<?php echo absint( $discount ); ?>%</span>
			<?php endif; ?>
		</div>

		<!-- Info -->
		<div class="kha-product-info">
			<h3 class="kha-product-title"><?php echo get_the_title( $product_id ); ?></h3>

			<!-- Price -->
			<div class="kha-product-price">
				<?php if ( $on_sale && $sale_price ) : ?>
					<span class="kha-price-sale"><?php echo kha_solar_format_price( $sale_price ); ?></span>
					<span class="kha-price-regular"><?php echo kha_solar_format_price( $regular_price ); ?></span>
				<?php elseif ( $price ) : ?>
					<span class="kha-price-current"><?php echo kha_solar_format_price( $price ); ?></span>
				<?php else : ?>
					<span class="kha-price-contact"><?php esc_html_e( 'Liên Hệ', 'kha-solar' ); ?></span>
				<?php endif; ?>
			</div>

			<!-- Meta -->
			<div class="kha-product-meta">
				<?php if ( $power ) : ?>
					<?php $power_formatted = $power >= 1000 ? ( $power / 1000 ) . ' kW' : $power . ' W'; ?>
					<span class="kha-meta-item">
						<span class="dashicons dashicons-admin-plugins"></span> 
						<?php echo esc_html( $power_formatted ); ?>
					</span>
				<?php endif; ?>

				<?php if ( $brand ) : ?>
					<span class="kha-meta-item">
						<span class="dashicons dashicons-admin-home"></span> 
						<?php echo esc_html( $brand->name ); ?>
					</span>
				<?php endif; ?>
			</div>

			<!-- Stock Status -->
			<div class="kha-product-stock">
				<?php echo kha_get_product_stock_status( $product_id ); ?>
			</div>
		</div>
	</a>
</article>
