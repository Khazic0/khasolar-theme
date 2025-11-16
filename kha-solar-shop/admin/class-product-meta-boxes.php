<?php
/**
 * Product meta boxes for admin.
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
 * Handles product meta boxes in admin.
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
	}

	/**
	 * Add meta boxes.
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'kha_product_details',
			__( 'Product Details', 'kha-solar' ),
			array( $this, 'product_details_callback' ),
			'kha_product',
			'normal',
			'high'
		);

		add_meta_box(
			'kha_product_specs',
			__( 'Technical Specifications', 'kha-solar' ),
			array( $this, 'product_specs_callback' ),
			'kha_product',
			'normal',
			'high'
		);

		add_meta_box(
			'kha_product_gallery',
			__( 'Product Gallery', 'kha-solar' ),
			array( $this, 'product_gallery_callback' ),
			'kha_product',
			'side',
			'default'
		);
	}

	/**
	 * Product details meta box callback.
	 *
	 * @param \WP_Post $post Current post object.
	 * @since 1.0.0
	 */
	public function product_details_callback( $post ) {
		wp_nonce_field( 'kha_product_details_nonce', 'kha_product_details_nonce' );

		$price       = get_post_meta( $post->ID, '_kha_product_price', true );
		$sale_price  = get_post_meta( $post->ID, '_kha_product_sale_price', true );
		$sku         = get_post_meta( $post->ID, '_kha_product_sku', true );
		$stock       = get_post_meta( $post->ID, '_kha_product_stock', true );
		$stock_status = get_post_meta( $post->ID, '_kha_product_stock_status', true );
		$warranty    = get_post_meta( $post->ID, '_kha_product_warranty', true );
		?>
		<table class="form-table">
			<tr>
				<th><label for="kha_product_sku"><?php esc_html_e( 'SKU', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_sku" name="kha_product_sku" value="<?php echo esc_attr( $sku ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_price"><?php esc_html_e( 'Regular Price (₫)', 'kha-solar' ); ?></label></th>
				<td>
					<input type="number" id="kha_product_price" name="kha_product_price" value="<?php echo esc_attr( $price ); ?>" step="1000" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_sale_price"><?php esc_html_e( 'Sale Price (₫)', 'kha-solar' ); ?></label></th>
				<td>
					<input type="number" id="kha_product_sale_price" name="kha_product_sale_price" value="<?php echo esc_attr( $sale_price ); ?>" step="1000" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_stock"><?php esc_html_e( 'Stock Quantity', 'kha-solar' ); ?></label></th>
				<td>
					<input type="number" id="kha_product_stock" name="kha_product_stock" value="<?php echo esc_attr( $stock ); ?>" class="small-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_stock_status"><?php esc_html_e( 'Stock Status', 'kha-solar' ); ?></label></th>
				<td>
					<select id="kha_product_stock_status" name="kha_product_stock_status">
						<option value="instock" <?php selected( $stock_status, 'instock' ); ?>><?php esc_html_e( 'In Stock', 'kha-solar' ); ?></option>
						<option value="outofstock" <?php selected( $stock_status, 'outofstock' ); ?>><?php esc_html_e( 'Out of Stock', 'kha-solar' ); ?></option>
						<option value="preorder" <?php selected( $stock_status, 'preorder' ); ?>><?php esc_html_e( 'Pre-order', 'kha-solar' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_warranty"><?php esc_html_e( 'Warranty Period', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_warranty" name="kha_product_warranty" value="<?php echo esc_attr( $warranty ); ?>" placeholder="<?php esc_attr_e( 'e.g., 10 years', 'kha-solar' ); ?>" class="regular-text">
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Product specifications meta box callback.
	 *
	 * @param \WP_Post $post Current post object.
	 * @since 1.0.0
	 */
	public function product_specs_callback( $post ) {
		wp_nonce_field( 'kha_product_specs_nonce', 'kha_product_specs_nonce' );

		$power       = get_post_meta( $post->ID, '_kha_product_power', true );
		$voltage     = get_post_meta( $post->ID, '_kha_product_voltage', true );
		$dimensions  = get_post_meta( $post->ID, '_kha_product_dimensions', true );
		$weight      = get_post_meta( $post->ID, '_kha_product_weight', true );
		$efficiency  = get_post_meta( $post->ID, '_kha_product_efficiency', true );
		$material    = get_post_meta( $post->ID, '_kha_product_material', true );
		$origin      = get_post_meta( $post->ID, '_kha_product_origin', true );
		?>
		<table class="form-table">
			<tr>
				<th><label for="kha_product_power"><?php esc_html_e( 'Power Capacity (W/kW)', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_power" name="kha_product_power" value="<?php echo esc_attr( $power ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_voltage"><?php esc_html_e( 'Voltage (V)', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_voltage" name="kha_product_voltage" value="<?php echo esc_attr( $voltage ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_efficiency"><?php esc_html_e( 'Efficiency (%)', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_efficiency" name="kha_product_efficiency" value="<?php echo esc_attr( $efficiency ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_dimensions"><?php esc_html_e( 'Dimensions (L x W x H)', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_dimensions" name="kha_product_dimensions" value="<?php echo esc_attr( $dimensions ); ?>" placeholder="<?php esc_attr_e( 'e.g., 1956 x 992 x 40mm', 'kha-solar' ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_weight"><?php esc_html_e( 'Weight (kg)', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_weight" name="kha_product_weight" value="<?php echo esc_attr( $weight ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_material"><?php esc_html_e( 'Material', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_material" name="kha_product_material" value="<?php echo esc_attr( $material ); ?>" class="regular-text">
				</td>
			</tr>
			<tr>
				<th><label for="kha_product_origin"><?php esc_html_e( 'Origin Country', 'kha-solar' ); ?></label></th>
				<td>
					<input type="text" id="kha_product_origin" name="kha_product_origin" value="<?php echo esc_attr( $origin ); ?>" class="regular-text">
				</td>
			</tr>
		</table>
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

		$gallery = get_post_meta( $post->ID, '_kha_product_gallery', true );
		?>
		<div id="kha_product_gallery_container">
			<input type="hidden" id="kha_product_gallery" name="kha_product_gallery" value="<?php echo esc_attr( $gallery ); ?>">
			<div id="kha_product_gallery_images">
				<?php
				if ( $gallery ) {
					$image_ids = explode( ',', $gallery );
					foreach ( $image_ids as $image_id ) {
						echo '<div class="kha-gallery-image">';
						echo wp_get_attachment_image( $image_id, 'thumbnail' );
						echo '<button type="button" class="remove-gallery-image">×</button>';
						echo '</div>';
					}
				}
				?>
			</div>
			<button type="button" id="kha_add_gallery_images" class="button"><?php esc_html_e( 'Add Images', 'kha-solar' ); ?></button>
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

		// Save product details.
		if ( isset( $_POST['kha_product_details_nonce'] ) && wp_verify_nonce( $_POST['kha_product_details_nonce'], 'kha_product_details_nonce' ) ) {
			$fields = array( 'sku', 'price', 'sale_price', 'stock', 'stock_status', 'warranty' );

			foreach ( $fields as $field ) {
				if ( isset( $_POST[ 'kha_product_' . $field ] ) ) {
					update_post_meta( $post_id, '_kha_product_' . $field, sanitize_text_field( $_POST[ 'kha_product_' . $field ] ) );
				}
			}
		}

		// Save product specifications.
		if ( isset( $_POST['kha_product_specs_nonce'] ) && wp_verify_nonce( $_POST['kha_product_specs_nonce'], 'kha_product_specs_nonce' ) ) {
			$fields = array( 'power', 'voltage', 'dimensions', 'weight', 'efficiency', 'material', 'origin' );

			foreach ( $fields as $field ) {
				if ( isset( $_POST[ 'kha_product_' . $field ] ) ) {
					update_post_meta( $post_id, '_kha_product_' . $field, sanitize_text_field( $_POST[ 'kha_product_' . $field ] ) );
				}
			}
		}

		// Save product gallery.
		if ( isset( $_POST['kha_product_gallery_nonce'] ) && wp_verify_nonce( $_POST['kha_product_gallery_nonce'], 'kha_product_gallery_nonce' ) ) {
			if ( isset( $_POST['kha_product_gallery'] ) ) {
				update_post_meta( $post_id, '_kha_product_gallery', sanitize_text_field( $_POST['kha_product_gallery'] ) );
			}
		}
	}
}
