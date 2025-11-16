<?php
/**
 * Single Product Template
 *
 * @package KhaSolar
 * @since   1.0.0
 */

get_header();

while ( have_posts() ) :
	the_post();

	$product_id    = get_the_ID();
	$price         = get_post_meta( $product_id, '_kha_product_price', true );
	$sale_price    = get_post_meta( $product_id, '_kha_product_sale_price', true );
	$sku           = get_post_meta( $product_id, '_kha_product_sku', true );
	$stock_status  = get_post_meta( $product_id, '_kha_product_stock_status', true );
	$warranty      = get_post_meta( $product_id, '_kha_product_warranty', true );
	$power         = get_post_meta( $product_id, '_kha_product_power', true );
	$voltage       = get_post_meta( $product_id, '_kha_product_voltage', true );
	$efficiency    = get_post_meta( $product_id, '_kha_product_efficiency', true );
	$dimensions    = get_post_meta( $product_id, '_kha_product_dimensions', true );
	$weight        = get_post_meta( $product_id, '_kha_product_weight', true );
	$material      = get_post_meta( $product_id, '_kha_product_material', true );
	$origin        = get_post_meta( $product_id, '_kha_product_origin', true );
	$gallery       = get_post_meta( $product_id, '_kha_product_gallery', true );

	$display_price = $sale_price ? $sale_price : $price;
	?>

	<div class="kha-single-product" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">

		<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; background: white; padding: 40px; border-radius: 12px;">

			<!-- Product Images -->
			<div class="kha-product-images">
				<div class="kha-product-main-image" style="aspect-ratio: 1; background: #f5f5f5; border-radius: 8px; overflow: hidden; margin-bottom: 20px;">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'kha-product-large', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
					<?php else : ?>
						<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #999;">
							<?php esc_html_e( 'No image available', 'kha-solar' ); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $gallery ) : ?>
					<div class="kha-product-gallery" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
						<?php
						$image_ids = explode( ',', $gallery );
						foreach ( $image_ids as $image_id ) :
							?>
							<div style="aspect-ratio: 1; background: #f5f5f5; border-radius: 6px; overflow: hidden;">
								<?php echo wp_get_attachment_image( $image_id, 'kha-product-gallery-thumb', false, array( 'style' => 'width: 100%; height: 100%; object-fit: cover; cursor: pointer;' ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Product Info -->
			<div class="kha-product-details">
				<h1 style="margin-top: 0; margin-bottom: 15px; font-size: 28px;"><?php the_title(); ?></h1>

				<?php if ( $sku ) : ?>
					<div style="color: #666; margin-bottom: 15px;">
						<strong><?php esc_html_e( 'SKU:', 'kha-solar' ); ?></strong> <?php echo esc_html( $sku ); ?>
					</div>
				<?php endif; ?>

				<div style="display: flex; align-items: baseline; gap: 15px; margin-bottom: 20px;">
					<?php if ( $display_price ) : ?>
						<div style="font-size: 36px; font-weight: 700; color: #FF9800;">
							<?php echo kha_solar_format_price( $display_price ); ?>
						</div>
						<?php if ( $sale_price && $price ) : ?>
							<div style="font-size: 24px; color: #999; text-decoration: line-through;">
								<?php echo kha_solar_format_price( $price ); ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<div style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border-radius: 8px;">
					<div style="display: flex; align-items: center; gap: 8px;">
						<span style="font-weight: 600;"><?php esc_html_e( 'Availability:', 'kha-solar' ); ?></span>
						<?php if ( 'instock' === $stock_status ) : ?>
							<span style="color: #4CAF50; font-weight: 600;">✓ <?php esc_html_e( 'In Stock', 'kha-solar' ); ?></span>
						<?php else : ?>
							<span style="color: #dc3545; font-weight: 600;">✗ <?php esc_html_e( 'Out of Stock', 'kha-solar' ); ?></span>
						<?php endif; ?>
					</div>
				</div>

				<div class="kha-product-excerpt" style="margin-bottom: 25px; line-height: 1.6; color: #666;">
					<?php the_excerpt(); ?>
				</div>

				<?php if ( 'instock' === $stock_status ) : ?>
					<div style="display: flex; gap: 15px; margin-bottom: 30px;">
						<div style="display: flex; align-items: center; gap: 10px;">
							<button type="button" class="kha-qty-btn kha-qty-decrease" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer;">−</button>
							<input type="number" class="kha-quantity-input" value="1" min="1" style="width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 6px; padding: 10px;">
							<button type="button" class="kha-qty-btn kha-qty-increase" style="width: 40px; height: 40px; border: 1px solid #ddd; background: white; border-radius: 6px; cursor: pointer;">+</button>
						</div>
						<button class="kha-add-to-cart kha-btn kha-btn-primary" data-product-id="<?php echo esc_attr( $product_id ); ?>" style="flex: 1; padding: 12px 30px; background: #FF9800; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer;">
							<?php esc_html_e( 'Add to Cart', 'kha-solar' ); ?>
						</button>
					</div>
				<?php endif; ?>

				<?php if ( $warranty ) : ?>
					<div style="padding: 15px; background: #e3f2fd; border-left: 4px solid #2196F3; border-radius: 4px; margin-bottom: 20px;">
						<strong>🛡️ <?php esc_html_e( 'Warranty:', 'kha-solar' ); ?></strong> <?php echo esc_html( $warranty ); ?>
					</div>
				<?php endif; ?>

				<div style="border-top: 1px solid #e0e0e0; padding-top: 20px;">
					<?php
					$terms = get_the_terms( $product_id, 'kha_product_cat' );
					if ( $terms && ! is_wp_error( $terms ) ) :
						?>
						<div style="margin-bottom: 10px;">
							<strong><?php esc_html_e( 'Categories:', 'kha-solar' ); ?></strong>
							<?php
							$term_links = array();
							foreach ( $terms as $term ) {
								$term_links[] = '<a href="' . esc_url( get_term_link( $term ) ) . '" style="color: #FF9800; text-decoration: none;">' . esc_html( $term->name ) . '</a>';
							}
							echo implode( ', ', $term_links );
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>

		<!-- Technical Specifications -->
		<?php if ( $power || $voltage || $efficiency || $dimensions || $weight || $material || $origin ) : ?>
			<div style="background: white; padding: 40px; border-radius: 12px; margin-top: 30px;">
				<h2 style="margin-top: 0; margin-bottom: 25px; font-size: 24px;"><?php esc_html_e( 'Technical Specifications', 'kha-solar' ); ?></h2>

				<table style="width: 100%; border-collapse: collapse;">
					<?php if ( $power ) : ?>
						<tr style="border-bottom: 1px solid #f0f0f0;">
							<td style="padding: 15px 20px; font-weight: 600; width: 250px;"><?php esc_html_e( 'Power Capacity', 'kha-solar' ); ?></td>
							<td style="padding: 15px 20px; color: #666;"><?php echo esc_html( $power ); ?></td>
						</tr>
					<?php endif; ?>
					<?php if ( $voltage ) : ?>
						<tr style="border-bottom: 1px solid #f0f0f0;">
							<td style="padding: 15px 20px; font-weight: 600;"><?php esc_html_e( 'Voltage', 'kha-solar' ); ?></td>
							<td style="padding: 15px 20px; color: #666;"><?php echo esc_html( $voltage ); ?></td>
						</tr>
					<?php endif; ?>
					<?php if ( $efficiency ) : ?>
						<tr style="border-bottom: 1px solid #f0f0f0;">
							<td style="padding: 15px 20px; font-weight: 600;"><?php esc_html_e( 'Efficiency', 'kha-solar' ); ?></td>
							<td style="padding: 15px 20px; color: #666;"><?php echo esc_html( $efficiency ); ?>%</td>
						</tr>
					<?php endif; ?>
					<?php if ( $dimensions ) : ?>
						<tr style="border-bottom: 1px solid #f0f0f0;">
							<td style="padding: 15px 20px; font-weight: 600;"><?php esc_html_e( 'Dimensions', 'kha-solar' ); ?></td>
							<td style="padding: 15px 20px; color: #666;"><?php echo esc_html( $dimensions ); ?></td>
						</tr>
					<?php endif; ?>
					<?php if ( $weight ) : ?>
						<tr style="border-bottom: 1px solid #f0f0f0;">
							<td style="padding: 15px 20px; font-weight: 600;"><?php esc_html_e( 'Weight', 'kha-solar' ); ?></td>
							<td style="padding: 15px 20px; color: #666;"><?php echo esc_html( $weight ); ?> kg</td>
						</tr>
					<?php endif; ?>
					<?php if ( $material ) : ?>
						<tr style="border-bottom: 1px solid #f0f0f0;">
							<td style="padding: 15px 20px; font-weight: 600;"><?php esc_html_e( 'Material', 'kha-solar' ); ?></td>
							<td style="padding: 15px 20px; color: #666;"><?php echo esc_html( $material ); ?></td>
						</tr>
					<?php endif; ?>
					<?php if ( $origin ) : ?>
						<tr>
							<td style="padding: 15px 20px; font-weight: 600;"><?php esc_html_e( 'Origin', 'kha-solar' ); ?></td>
							<td style="padding: 15px 20px; color: #666;"><?php echo esc_html( $origin ); ?></td>
						</tr>
					<?php endif; ?>
				</table>
			</div>
		<?php endif; ?>

		<!-- Product Description -->
		<div style="background: white; padding: 40px; border-radius: 12px; margin-top: 30px;">
			<h2 style="margin-top: 0; margin-bottom: 25px; font-size: 24px;"><?php esc_html_e( 'Product Description', 'kha-solar' ); ?></h2>
			<div style="line-height: 1.8; color: #555;">
				<?php the_content(); ?>
			</div>
		</div>

	</div>

	<script>
	jQuery(document).ready(function($) {
		// Quantity controls
		$('.kha-qty-decrease').on('click', function() {
			var $input = $('.kha-quantity-input');
			var val = parseInt($input.val()) || 1;
			if (val > 1) {
				$input.val(val - 1);
			}
		});

		$('.kha-qty-increase').on('click', function() {
			var $input = $('.kha-quantity-input');
			var val = parseInt($input.val()) || 1;
			$input.val(val + 1);
		});

		// Track product view
		$.post(khaSolar.ajaxUrl, {
			action: 'kha_track_view',
			product_id: <?php echo absint( $product_id ); ?>
		});
	});
	</script>

	<?php
endwhile;

get_footer();
