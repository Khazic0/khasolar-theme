<?php
/**
 * Product meta boxes for admin.
 *
 * Professional product management interface with tabbed layout,
 * similar to WooCommerce but lighter and optimized for solar products.
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
 * Product_Meta_Boxes class.
 *
 * Handles product meta boxes in admin with tabbed interface.
 */
class Product_Meta_Boxes {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_kha_product', array( $this, 'save_meta_boxes' ), 10, 2 );
		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_fields' ), 10, 2 );
		add_action( 'manage_kha_product_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
		add_filter( 'manage_kha_product_posts_columns', array( $this, 'custom_columns' ) );
		add_filter( 'bulk_actions-edit-kha_product', array( $this, 'register_bulk_actions' ) );
		add_filter( 'handle_bulk_actions-edit-kha_product', array( $this, 'handle_bulk_actions' ), 10, 3 );
	}

	/**
	 * Add meta boxes.
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		// Product Data meta box (tabbed interface).
		add_meta_box(
			'kha_product_data',
			'<span class="dashicons dashicons-archive"></span> ' . __( 'Product Data', 'kha-solar' ),
			array( $this, 'product_data_callback' ),
			'kha_product',
			'normal',
			'high'
		);

		// Product Gallery meta box.
		add_meta_box(
			'kha_product_gallery',
			'<span class="dashicons dashicons-format-gallery"></span> ' . __( 'Product Gallery', 'kha-solar' ),
			array( $this, 'product_gallery_callback' ),
			'kha_product',
			'side',
			'low'
		);
	}

	/**
	 * Product data meta box callback (tabbed interface).
	 *
	 * @param \WP_Post $post Current post object.
	 * @since 1.0.0
	 */
	public function product_data_callback( $post ) {
		wp_nonce_field( 'kha_product_data_nonce', 'kha_product_data_nonce' );

		// Get all meta values.
		$sku                = get_post_meta( $post->ID, '_sku', true );
		$regular_price      = get_post_meta( $post->ID, '_regular_price', true );
		$sale_price         = get_post_meta( $post->ID, '_sale_price', true );
		$featured           = get_post_meta( $post->ID, '_featured_product', true );
		$manage_stock       = get_post_meta( $post->ID, '_manage_stock', true );
		$stock_quantity     = get_post_meta( $post->ID, '_stock_quantity', true );
		$stock_status       = get_post_meta( $post->ID, '_stock_status', true ) ?: 'instock';
		$low_stock_threshold = get_post_meta( $post->ID, '_low_stock_threshold', true ) ?: 5;
		$power_output       = get_post_meta( $post->ID, '_power_output', true );
		$voltage            = get_post_meta( $post->ID, '_voltage', true );
		$warranty_years     = get_post_meta( $post->ID, '_warranty_years', true );
		$origin_country     = get_post_meta( $post->ID, '_origin_country', true );
		$efficiency         = get_post_meta( $post->ID, '_efficiency', true );
		$dimensions         = get_post_meta( $post->ID, '_dimensions', true );
		$weight             = get_post_meta( $post->ID, '_weight', true );
		$product_type       = get_post_meta( $post->ID, '_product_type', true ) ?: 'simple';
		$bundle_products    = get_post_meta( $post->ID, '_bundle_products', true );
		$bundle_discount    = get_post_meta( $post->ID, '_bundle_discount', true );

		?>
		<div class="kha-product-data-panel">
			<!-- Tab Navigation -->
			<ul class="kha-tabs">
				<li class="active">
					<a href="#kha-tab-general" data-tab="general">
						<span class="dashicons dashicons-admin-generic"></span>
						<?php esc_html_e( 'General', 'kha-solar' ); ?>
					</a>
				</li>
				<li>
					<a href="#kha-tab-inventory" data-tab="inventory">
						<span class="dashicons dashicons-chart-bar"></span>
						<?php esc_html_e( 'Inventory', 'kha-solar' ); ?>
					</a>
				</li>
				<li>
					<a href="#kha-tab-specs" data-tab="specs">
						<span class="dashicons dashicons-list-view"></span>
						<?php esc_html_e( 'Specs', 'kha-solar' ); ?>
					</a>
				</li>
				<li>
					<a href="#kha-tab-bundle" data-tab="bundle">
						<span class="dashicons dashicons-networking"></span>
						<?php esc_html_e( 'Bundle', 'kha-solar' ); ?>
					</a>
				</li>
			</ul>

			<!-- Tab Content -->
			<div class="kha-tab-content">

				<!-- GENERAL TAB -->
				<div id="kha-tab-general" class="kha-tab-panel active">
					<div class="kha-field-group">
						<div class="kha-field">
							<label for="kha_sku">
								<strong><?php esc_html_e( 'SKU', 'kha-solar' ); ?></strong>
								<span class="kha-tooltip" title="<?php esc_attr_e( 'Stock Keeping Unit - Unique identifier', 'kha-solar' ); ?>">ⓘ</span>
							</label>
							<div class="kha-input-group">
								<input type="text" id="kha_sku" name="kha_sku" value="<?php echo esc_attr( $sku ); ?>" placeholder="<?php esc_attr_e( 'e.g., KS-INV-001', 'kha-solar' ); ?>" class="regular-text">
								<button type="button" class="button kha-generate-sku" title="<?php esc_attr_e( 'Auto-generate SKU', 'kha-solar' ); ?>">
									<span class="dashicons dashicons-update"></span>
								</button>
							</div>
						</div>

						<div class="kha-field-row">
							<div class="kha-field kha-field-half">
								<label for="kha_regular_price">
									<strong><?php esc_html_e( 'Regular Price (₫)', 'kha-solar' ); ?></strong>
									<span class="kha-required">*</span>
								</label>
								<input type="text" id="kha_regular_price" name="kha_regular_price" value="<?php echo esc_attr( $regular_price ); ?>" placeholder="15000000" class="regular-text kha-price-input">
								<p class="description"><?php esc_html_e( 'Enter price without formatting', 'kha-solar' ); ?></p>
							</div>

							<div class="kha-field kha-field-half">
								<label for="kha_sale_price">
									<strong><?php esc_html_e( 'Sale Price (₫)', 'kha-solar' ); ?></strong>
								</label>
								<input type="text" id="kha_sale_price" name="kha_sale_price" value="<?php echo esc_attr( $sale_price ); ?>" placeholder="12000000" class="regular-text kha-price-input">
								<p class="description"><?php esc_html_e( 'Leave empty for no sale', 'kha-solar' ); ?></p>
							</div>
						</div>

						<div class="kha-field">
							<div class="kha-status-badges">
								<?php if ( $featured ) : ?>
									<span class="kha-badge kha-badge-featured">
										<span class="dashicons dashicons-star-filled"></span>
										<?php esc_html_e( 'Featured', 'kha-solar' ); ?>
									</span>
								<?php endif; ?>

								<?php if ( $sale_price && $sale_price < $regular_price ) : ?>
									<span class="kha-badge kha-badge-sale">
										<span class="dashicons dashicons-tag"></span>
										<?php esc_html_e( 'On Sale', 'kha-solar' ); ?>
										<?php
										$discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
										echo ' -' . $discount . '%';
										?>
									</span>
								<?php endif; ?>

								<?php
								$post_age = ( time() - strtotime( $post->post_date ) ) / DAY_IN_SECONDS;
								if ( $post_age <= 30 ) :
									?>
									<span class="kha-badge kha-badge-new">
										<span class="dashicons dashicons-megaphone"></span>
										<?php esc_html_e( 'New', 'kha-solar' ); ?>
									</span>
								<?php endif; ?>
							</div>
						</div>

						<div class="kha-field">
							<label>
								<input type="checkbox" name="kha_featured_product" value="1" <?php checked( $featured, 1 ); ?>>
								<strong><?php esc_html_e( 'This is a featured product', 'kha-solar' ); ?></strong>
							</label>
							<p class="description"><?php esc_html_e( 'Featured products will be displayed prominently on your site', 'kha-solar' ); ?></p>
						</div>
					</div>
				</div>

				<!-- INVENTORY TAB -->
				<div id="kha-tab-inventory" class="kha-tab-panel">
					<div class="kha-field-group">
						<div class="kha-field">
							<label class="kha-toggle-label">
								<input type="checkbox" name="kha_manage_stock" id="kha_manage_stock" value="1" <?php checked( $manage_stock, 1 ); ?> class="kha-toggle-input">
								<span class="kha-toggle-slider"></span>
								<strong><?php esc_html_e( 'Enable stock management', 'kha-solar' ); ?></strong>
							</label>
							<p class="description"><?php esc_html_e( 'Enable to track inventory for this product', 'kha-solar' ); ?></p>
						</div>

						<div class="kha-stock-fields" style="<?php echo $manage_stock ? '' : 'display:none;'; ?>">
							<div class="kha-field">
								<label for="kha_stock_quantity">
									<strong><?php esc_html_e( 'Stock Quantity', 'kha-solar' ); ?></strong>
								</label>
								<div class="kha-quantity-control">
									<button type="button" class="kha-qty-btn kha-qty-minus">
										<span class="dashicons dashicons-minus"></span>
									</button>
									<input type="number" id="kha_stock_quantity" name="kha_stock_quantity" value="<?php echo esc_attr( $stock_quantity ); ?>" min="0" class="small-text">
									<button type="button" class="kha-qty-btn kha-qty-plus">
										<span class="dashicons dashicons-plus"></span>
									</button>
								</div>
								<?php if ( $stock_quantity ) : ?>
									<p class="kha-stock-display">
										<?php
										echo sprintf(
											/* translators: %d: stock quantity */
											esc_html__( 'Current: %d units', 'kha-solar' ),
											absint( $stock_quantity )
										);
										?>
									</p>
								<?php endif; ?>
							</div>

							<div class="kha-field">
								<label for="kha_low_stock_threshold">
									<strong><?php esc_html_e( 'Low Stock Threshold', 'kha-solar' ); ?></strong>
								</label>
								<input type="number" id="kha_low_stock_threshold" name="kha_low_stock_threshold" value="<?php echo esc_attr( $low_stock_threshold ); ?>" min="0" class="small-text">
								<p class="description"><?php esc_html_e( 'Show low stock warning below this quantity', 'kha-solar' ); ?></p>
							</div>
						</div>

						<div class="kha-field">
							<label><strong><?php esc_html_e( 'Stock Status', 'kha-solar' ); ?></strong></label>
							<div class="kha-radio-group">
								<label class="kha-radio-label">
									<input type="radio" name="kha_stock_status" value="instock" <?php checked( $stock_status, 'instock' ); ?>>
									<span class="kha-radio-text">
										<span class="dashicons dashicons-yes-alt"></span>
										<?php esc_html_e( 'In Stock', 'kha-solar' ); ?>
									</span>
								</label>
								<label class="kha-radio-label">
									<input type="radio" name="kha_stock_status" value="outofstock" <?php checked( $stock_status, 'outofstock' ); ?>>
									<span class="kha-radio-text">
										<span class="dashicons dashicons-dismiss"></span>
										<?php esc_html_e( 'Out of Stock', 'kha-solar' ); ?>
									</span>
								</label>
								<label class="kha-radio-label">
									<input type="radio" name="kha_stock_status" value="onbackorder" <?php checked( $stock_status, 'onbackorder' ); ?>>
									<span class="kha-radio-text">
										<span class="dashicons dashicons-backup"></span>
										<?php esc_html_e( 'On Backorder', 'kha-solar' ); ?>
									</span>
								</label>
							</div>
						</div>
					</div>
				</div>

				<!-- SPECS TAB -->
				<div id="kha-tab-specs" class="kha-tab-panel">
					<div class="kha-field-group">
						<div class="kha-quick-fill">
							<p class="description"><?php esc_html_e( 'Quick fill common specifications:', 'kha-solar' ); ?></p>
							<div class="kha-quick-fill-buttons">
								<button type="button" class="button kha-quick-fill-btn" data-field="power"><?php esc_html_e( 'Power', 'kha-solar' ); ?></button>
								<button type="button" class="button kha-quick-fill-btn" data-field="voltage"><?php esc_html_e( 'Voltage', 'kha-solar' ); ?></button>
								<button type="button" class="button kha-quick-fill-btn" data-field="warranty"><?php esc_html_e( 'Warranty', 'kha-solar' ); ?></button>
								<button type="button" class="button kha-quick-fill-btn" data-field="all"><?php esc_html_e( 'All Specs', 'kha-solar' ); ?></button>
							</div>
						</div>

						<div class="kha-field-row kha-spec-field" data-spec="power">
							<div class="kha-field kha-field-half">
								<label for="kha_power_output">
									<strong><?php esc_html_e( 'Power Output (W)', 'kha-solar' ); ?></strong>
								</label>
								<input type="number" id="kha_power_output" name="kha_power_output" value="<?php echo esc_attr( $power_output ); ?>" placeholder="3000" class="regular-text">
							</div>

							<div class="kha-field kha-field-half" data-spec="voltage">
								<label for="kha_voltage">
									<strong><?php esc_html_e( 'Voltage (V)', 'kha-solar' ); ?></strong>
								</label>
								<input type="text" id="kha_voltage" name="kha_voltage" value="<?php echo esc_attr( $voltage ); ?>" placeholder="220V" class="regular-text">
							</div>
						</div>

						<div class="kha-field-row">
							<div class="kha-field kha-field-half" data-spec="warranty">
								<label for="kha_warranty_years">
									<strong><?php esc_html_e( 'Warranty (years)', 'kha-solar' ); ?></strong>
								</label>
								<input type="number" id="kha_warranty_years" name="kha_warranty_years" value="<?php echo esc_attr( $warranty_years ); ?>" placeholder="10" class="regular-text">
							</div>

							<div class="kha-field kha-field-half" data-spec="efficiency">
								<label for="kha_efficiency">
									<strong><?php esc_html_e( 'Efficiency (%)', 'kha-solar' ); ?></strong>
								</label>
								<input type="number" id="kha_efficiency" name="kha_efficiency" value="<?php echo esc_attr( $efficiency ); ?>" placeholder="97.5" step="0.1" class="regular-text">
							</div>
						</div>

						<div class="kha-field-row">
							<div class="kha-field kha-field-half" data-spec="origin">
								<label for="kha_origin_country">
									<strong><?php esc_html_e( 'Origin Country', 'kha-solar' ); ?></strong>
								</label>
								<input type="text" id="kha_origin_country" name="kha_origin_country" value="<?php echo esc_attr( $origin_country ); ?>" placeholder="<?php esc_attr_e( 'Vietnam', 'kha-solar' ); ?>" class="regular-text">
							</div>

							<div class="kha-field kha-field-half" data-spec="weight">
								<label for="kha_weight">
									<strong><?php esc_html_e( 'Weight (kg)', 'kha-solar' ); ?></strong>
								</label>
								<input type="number" id="kha_weight" name="kha_weight" value="<?php echo esc_attr( $weight ); ?>" placeholder="12.5" step="0.1" class="regular-text">
							</div>
						</div>

						<div class="kha-field" data-spec="dimensions">
							<label for="kha_dimensions">
								<strong><?php esc_html_e( 'Dimensions (L x W x H)', 'kha-solar' ); ?></strong>
							</label>
							<input type="text" id="kha_dimensions" name="kha_dimensions" value="<?php echo esc_attr( $dimensions ); ?>" placeholder="<?php esc_attr_e( 'e.g., 1956 x 992 x 40mm', 'kha-solar' ); ?>" class="regular-text">
						</div>
					</div>
				</div>

				<!-- BUNDLE TAB -->
				<div id="kha-tab-bundle" class="kha-tab-panel">
					<div class="kha-field-group">
						<div class="kha-field">
							<label><strong><?php esc_html_e( 'Product Type', 'kha-solar' ); ?></strong></label>
							<div class="kha-radio-group">
								<label class="kha-radio-label">
									<input type="radio" name="kha_product_type" value="simple" <?php checked( $product_type, 'simple' ); ?> class="kha-product-type-radio">
									<span class="kha-radio-text">
										<span class="dashicons dashicons-admin-page"></span>
										<?php esc_html_e( 'Simple Product', 'kha-solar' ); ?>
									</span>
								</label>
								<label class="kha-radio-label">
									<input type="radio" name="kha_product_type" value="bundle" <?php checked( $product_type, 'bundle' ); ?> class="kha-product-type-radio">
									<span class="kha-radio-text">
										<span class="dashicons dashicons-networking"></span>
										<?php esc_html_e( 'Bundle Product', 'kha-solar' ); ?>
									</span>
								</label>
							</div>
						</div>

						<div class="kha-bundle-fields" style="<?php echo 'bundle' === $product_type ? '' : 'display:none;'; ?>">
							<div class="kha-field">
								<label for="kha_bundle_search">
									<strong><?php esc_html_e( 'Add Products to Bundle', 'kha-solar' ); ?></strong>
								</label>
								<input type="text" id="kha_bundle_search" placeholder="<?php esc_attr_e( 'Search products...', 'kha-solar' ); ?>" class="regular-text">
								<div id="kha_bundle_search_results" class="kha-search-results"></div>
							</div>

							<div class="kha-field">
								<label><strong><?php esc_html_e( 'Bundled Products', 'kha-solar' ); ?></strong></label>
								<div id="kha_bundle_products_list" class="kha-bundle-list">
									<?php
									if ( is_array( $bundle_products ) && ! empty( $bundle_products ) ) {
										foreach ( $bundle_products as $bundle_product_id ) {
											$bundle_product = get_post( $bundle_product_id );
											if ( $bundle_product ) {
												echo '<div class="kha-bundle-item" data-product-id="' . esc_attr( $bundle_product_id ) . '">';
												echo '<span class="kha-bundle-product-title">' . esc_html( $bundle_product->post_title ) . '</span>';
												echo '<button type="button" class="button-link kha-remove-bundle-product"><span class="dashicons dashicons-no"></span></button>';
												echo '<input type="hidden" name="kha_bundle_products[]" value="' . esc_attr( $bundle_product_id ) . '">';
												echo '</div>';
											}
										}
									}
									?>
								</div>
							</div>

							<div class="kha-field">
								<label for="kha_bundle_discount">
									<strong><?php esc_html_e( 'Bundle Discount (%)', 'kha-solar' ); ?></strong>
								</label>
								<input type="number" id="kha_bundle_discount" name="kha_bundle_discount" value="<?php echo esc_attr( $bundle_discount ); ?>" min="0" max="100" class="small-text">
								<p class="description"><?php esc_html_e( 'Percentage discount when buying as bundle', 'kha-solar' ); ?></p>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * Product gallery meta box callback.
	 *
	 * @param \WP_Post $post Current post object.
	 * @since 1.0.0
	 */
	public function product_gallery_callback( $post ) {
		wp_nonce_field( 'kha_product_gallery_nonce', 'kha_product_gallery_nonce' );

		$gallery = get_post_meta( $post->ID, '_product_gallery', true );
		$gallery_ids = is_array( $gallery ) ? $gallery : ( $gallery ? explode( ',', $gallery ) : array() );
		?>
		<div class="kha-gallery-container">
			<div id="kha_product_gallery_images" class="kha-gallery-grid">
				<?php
				if ( ! empty( $gallery_ids ) ) {
					foreach ( $gallery_ids as $image_id ) {
						if ( $image_id ) {
							echo '<div class="kha-gallery-image" data-id="' . esc_attr( $image_id ) . '">';
							echo wp_get_attachment_image( $image_id, 'thumbnail' );
							echo '<button type="button" class="kha-remove-gallery-image" title="' . esc_attr__( 'Remove', 'kha-solar' ) . '">';
							echo '<span class="dashicons dashicons-no"></span>';
							echo '</button>';
							echo '<input type="hidden" name="kha_product_gallery[]" value="' . esc_attr( $image_id ) . '">';
							echo '</div>';
						}
					}
				}
				?>
			</div>

			<div class="kha-gallery-actions">
				<button type="button" id="kha_add_gallery_images" class="button button-primary button-large">
					<span class="dashicons dashicons-plus-alt"></span>
					<?php esc_html_e( 'Add Gallery Images', 'kha-solar' ); ?>
				</button>
				<p class="description"><?php esc_html_e( 'Max 8 images recommended. Drag to reorder.', 'kha-solar' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Save meta boxes data.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 * @since 1.0.0
	 */
	public function save_meta_boxes( $post_id, $post ) {
		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save product data.
		if ( isset( $_POST['kha_product_data_nonce'] ) && wp_verify_nonce( $_POST['kha_product_data_nonce'], 'kha_product_data_nonce' ) ) {
			// General fields.
			$fields = array(
				'sku'              => 'sanitize_text_field',
				'regular_price'    => 'floatval',
				'sale_price'       => 'floatval',
				'manage_stock'     => 'rest_sanitize_boolean',
				'stock_quantity'   => 'absint',
				'stock_status'     => 'sanitize_key',
				'low_stock_threshold' => 'absint',
				'power_output'     => 'floatval',
				'voltage'          => 'sanitize_text_field',
				'warranty_years'   => 'absint',
				'origin_country'   => 'sanitize_text_field',
				'efficiency'       => 'floatval',
				'dimensions'       => 'sanitize_text_field',
				'weight'           => 'floatval',
				'product_type'     => 'sanitize_key',
				'bundle_discount'  => 'absint',
			);

			foreach ( $fields as $field => $sanitize_callback ) {
				if ( isset( $_POST[ 'kha_' . $field ] ) ) {
					$value = call_user_func( $sanitize_callback, $_POST[ 'kha_' . $field ] );
					update_post_meta( $post_id, '_' . $field, $value );
				}
			}

			// Featured product checkbox.
			$featured = isset( $_POST['kha_featured_product'] ) ? 1 : 0;
			update_post_meta( $post_id, '_featured_product', $featured );

			// Bundle products.
			if ( isset( $_POST['kha_bundle_products'] ) && is_array( $_POST['kha_bundle_products'] ) ) {
				$bundle_products = array_map( 'absint', $_POST['kha_bundle_products'] );
				update_post_meta( $post_id, '_bundle_products', $bundle_products );
			} else {
				delete_post_meta( $post_id, '_bundle_products' );
			}
		}

		// Save product gallery.
		if ( isset( $_POST['kha_product_gallery_nonce'] ) && wp_verify_nonce( $_POST['kha_product_gallery_nonce'], 'kha_product_gallery_nonce' ) ) {
			if ( isset( $_POST['kha_product_gallery'] ) && is_array( $_POST['kha_product_gallery'] ) ) {
				$gallery = array_map( 'absint', $_POST['kha_product_gallery'] );
				update_post_meta( $post_id, '_product_gallery', $gallery );
			} else {
				delete_post_meta( $post_id, '_product_gallery' );
			}
		}
	}

	/**
	 * Add custom columns to products list.
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 * @since 1.0.0
	 */
	public function custom_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;

			if ( 'title' === $key ) {
				$new_columns['image']  = __( 'Image', 'kha-solar' );
				$new_columns['sku']    = __( 'SKU', 'kha-solar' );
				$new_columns['price']  = __( 'Price', 'kha-solar' );
				$new_columns['stock']  = __( 'Stock', 'kha-solar' );
			}
		}

		return $new_columns;
	}

	/**
	 * Custom columns content.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 * @since 1.0.0
	 */
	public function custom_columns_content( $column, $post_id ) {
		switch ( $column ) {
			case 'image':
				if ( has_post_thumbnail( $post_id ) ) {
					echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
				} else {
					echo '<span class="dashicons dashicons-format-image" style="font-size: 50px; color: #ddd;"></span>';
				}
				break;

			case 'sku':
				$sku = get_post_meta( $post_id, '_sku', true );
				echo $sku ? esc_html( $sku ) : '—';
				break;

			case 'price':
				echo kha_get_product_price( $post_id );
				break;

			case 'stock':
				echo kha_get_product_stock_status( $post_id );
				break;
		}
	}

	/**
	 * Quick edit fields.
	 *
	 * @param string $column_name Column name.
	 * @param string $post_type   Post type.
	 * @since 1.0.0
	 */
	public function quick_edit_fields( $column_name, $post_type ) {
		if ( 'kha_product' !== $post_type || 'price' !== $column_name ) {
			return;
		}

		?>
		<fieldset class="inline-edit-col-right inline-edit-kha-product">
			<div class="inline-edit-col column-<?php echo esc_attr( $column_name ); ?>">
				<label class="inline-edit-group">
					<span class="title"><?php esc_html_e( 'Regular Price', 'kha-solar' ); ?></span>
					<input type="number" name="kha_quick_regular_price" class="text" value="" step="any">
				</label>
				<label class="inline-edit-group">
					<span class="title"><?php esc_html_e( 'Sale Price', 'kha-solar' ); ?></span>
					<input type="number" name="kha_quick_sale_price" class="text" value="" step="any">
				</label>
				<label class="inline-edit-group">
					<span class="title"><?php esc_html_e( 'Stock', 'kha-solar' ); ?></span>
					<input type="number" name="kha_quick_stock" class="text" value="" step="1">
				</label>
				<label class="inline-edit-group">
					<span class="title"><?php esc_html_e( 'Stock Status', 'kha-solar' ); ?></span>
					<select name="kha_quick_stock_status">
						<option value="instock"><?php esc_html_e( 'In Stock', 'kha-solar' ); ?></option>
						<option value="outofstock"><?php esc_html_e( 'Out of Stock', 'kha-solar' ); ?></option>
						<option value="onbackorder"><?php esc_html_e( 'On Backorder', 'kha-solar' ); ?></option>
					</select>
				</label>
				<label class="alignleft">
					<input type="checkbox" name="kha_quick_featured" value="1">
					<span class="checkbox-title"><?php esc_html_e( 'Featured', 'kha-solar' ); ?></span>
				</label>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Register bulk actions.
	 *
	 * @param array $bulk_actions Existing bulk actions.
	 * @return array Modified bulk actions.
	 * @since 1.0.0
	 */
	public function register_bulk_actions( $bulk_actions ) {
		$bulk_actions['kha_mark_featured']   = __( 'Mark as Featured', 'kha-solar' );
		$bulk_actions['kha_unmark_featured'] = __( 'Remove Featured', 'kha-solar' );
		$bulk_actions['kha_stock_instock']   = __( 'Change stock status to In Stock', 'kha-solar' );
		$bulk_actions['kha_stock_outofstock'] = __( 'Change stock status to Out of Stock', 'kha-solar' );

		return $bulk_actions;
	}

	/**
	 * Handle bulk actions.
	 *
	 * @param string $redirect_to Redirect URL.
	 * @param string $doaction    Action name.
	 * @param array  $post_ids    Selected post IDs.
	 * @return string Modified redirect URL.
	 * @since 1.0.0
	 */
	public function handle_bulk_actions( $redirect_to, $doaction, $post_ids ) {
		if ( empty( $post_ids ) ) {
			return $redirect_to;
		}

		$count = 0;

		switch ( $doaction ) {
			case 'kha_mark_featured':
				foreach ( $post_ids as $post_id ) {
					update_post_meta( $post_id, '_featured_product', 1 );
					$count++;
				}
				$redirect_to = add_query_arg( 'kha_marked_featured', $count, $redirect_to );
				break;

			case 'kha_unmark_featured':
				foreach ( $post_ids as $post_id ) {
					update_post_meta( $post_id, '_featured_product', 0 );
					$count++;
				}
				$redirect_to = add_query_arg( 'kha_unmarked_featured', $count, $redirect_to );
				break;

			case 'kha_stock_instock':
				foreach ( $post_ids as $post_id ) {
					update_post_meta( $post_id, '_stock_status', 'instock' );
					$count++;
				}
				$redirect_to = add_query_arg( 'kha_stock_updated', $count, $redirect_to );
				break;

			case 'kha_stock_outofstock':
				foreach ( $post_ids as $post_id ) {
					update_post_meta( $post_id, '_stock_status', 'outofstock' );
					$count++;
				}
				$redirect_to = add_query_arg( 'kha_stock_updated', $count, $redirect_to );
				break;
		}

		return $redirect_to;
	}
}
