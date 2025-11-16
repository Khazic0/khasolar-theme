<?php
/**
 * Order management in admin.
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
 * Order_Management class.
 *
 * Handles order management in WordPress admin.
 */
class Order_Management {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Add admin menu items.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Orders', 'kha-solar' ),
			__( 'Orders', 'kha-solar' ),
			'manage_options',
			'kha-solar-orders',
			array( $this, 'orders_page' ),
			'dashicons-clipboard',
			6
		);

		add_submenu_page(
			'kha-solar-orders',
			__( 'All Orders', 'kha-solar' ),
			__( 'All Orders', 'kha-solar' ),
			'manage_options',
			'kha-solar-orders',
			array( $this, 'orders_page' )
		);

		add_submenu_page(
			'kha-solar-orders',
			__( 'Settings', 'kha-solar' ),
			__( 'Settings', 'kha-solar' ),
			'manage_options',
			'kha-solar-settings',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Orders page display.
	 *
	 * @since 1.0.0
	 */
	public function orders_page() {
		global $wpdb;

		$orders_table = $wpdb->prefix . KHA_DB_PREFIX . 'orders';

		// Handle order status updates.
		if ( isset( $_POST['update_order_status'] ) && isset( $_POST['order_id'] ) && isset( $_POST['new_status'] ) ) {
			check_admin_referer( 'update_order_status' );

			$order_id   = absint( $_POST['order_id'] );
			$new_status = sanitize_key( $_POST['new_status'] );

			$order = new Order();
			$order->update_status( $order_id, $new_status );

			echo '<div class="notice notice-success"><p>' . esc_html__( 'Order status updated!', 'kha-solar' ) . '</p></div>';
		}

		// Get all orders.
		$orders = $wpdb->get_results(
			"SELECT * FROM {$orders_table} ORDER BY created_at DESC"
		);

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Orders', 'kha-solar' ); ?></h1>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Order #', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Customer', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Email', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Phone', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Total', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Status', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Date', 'kha-solar' ); ?></th>
						<th><?php esc_html_e( 'Actions', 'kha-solar' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( $orders ) : ?>
						<?php foreach ( $orders as $order ) : ?>
							<tr>
								<td><strong><?php echo esc_html( $order->order_number ); ?></strong></td>
								<td><?php echo esc_html( $order->customer_name ); ?></td>
								<td><?php echo esc_html( $order->customer_email ); ?></td>
								<td><?php echo esc_html( $order->customer_phone ); ?></td>
								<td><?php echo kha_solar_format_price( $order->total ); ?></td>
								<td>
									<form method="post" style="display: inline;">
										<?php wp_nonce_field( 'update_order_status' ); ?>
										<input type="hidden" name="order_id" value="<?php echo esc_attr( $order->id ); ?>">
										<select name="new_status" onchange="this.form.submit()">
											<option value="pending" <?php selected( $order->status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'kha-solar' ); ?></option>
											<option value="processing" <?php selected( $order->status, 'processing' ); ?>><?php esc_html_e( 'Processing', 'kha-solar' ); ?></option>
											<option value="completed" <?php selected( $order->status, 'completed' ); ?>><?php esc_html_e( 'Completed', 'kha-solar' ); ?></option>
											<option value="cancelled" <?php selected( $order->status, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'kha-solar' ); ?></option>
										</select>
										<input type="hidden" name="update_order_status" value="1">
									</form>
								</td>
								<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $order->created_at ) ) ); ?></td>
								<td>
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=kha-solar-orders&view=order&id=' . $order->id ) ); ?>" class="button button-small">
										<?php esc_html_e( 'View', 'kha-solar' ); ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="8"><?php esc_html_e( 'No orders found.', 'kha-solar' ); ?></td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Settings page display.
	 *
	 * @since 1.0.0
	 */
	public function settings_page() {
		// Handle settings save.
		if ( isset( $_POST['save_kha_settings'] ) ) {
			check_admin_referer( 'kha_solar_settings' );

			$settings = array(
				'kha_solar_currency_symbol',
				'kha_solar_currency_position',
				'kha_solar_thousand_separator',
				'kha_solar_decimal_separator',
				'kha_solar_decimals',
				'kha_solar_products_per_page',
				'kha_solar_admin_email',
				'kha_solar_enable_order_emails',
				'kha_solar_zalo_phone',
				'kha_solar_messenger_id',
				'kha_solar_enable_chat',
			);

			foreach ( $settings as $setting ) {
				if ( isset( $_POST[ $setting ] ) ) {
					update_option( $setting, sanitize_text_field( $_POST[ $setting ] ) );
				}
			}

			echo '<div class="notice notice-success"><p>' . esc_html__( 'Settings saved!', 'kha-solar' ) . '</p></div>';
		}

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Kha Solar Settings', 'kha-solar' ); ?></h1>

			<form method="post">
				<?php wp_nonce_field( 'kha_solar_settings' ); ?>

				<h2><?php esc_html_e( 'Currency Settings', 'kha-solar' ); ?></h2>
				<table class="form-table">
					<tr>
						<th><label for="kha_solar_currency_symbol"><?php esc_html_e( 'Currency Symbol', 'kha-solar' ); ?></label></th>
						<td>
							<input type="text" id="kha_solar_currency_symbol" name="kha_solar_currency_symbol" value="<?php echo esc_attr( get_option( 'kha_solar_currency_symbol', '₫' ) ); ?>" class="regular-text">
						</td>
					</tr>
					<tr>
						<th><label for="kha_solar_currency_position"><?php esc_html_e( 'Currency Position', 'kha-solar' ); ?></label></th>
						<td>
							<select id="kha_solar_currency_position" name="kha_solar_currency_position">
								<option value="left" <?php selected( get_option( 'kha_solar_currency_position' ), 'left' ); ?>><?php esc_html_e( 'Left', 'kha-solar' ); ?></option>
								<option value="right" <?php selected( get_option( 'kha_solar_currency_position' ), 'right' ); ?>><?php esc_html_e( 'Right', 'kha-solar' ); ?></option>
							</select>
						</td>
					</tr>
				</table>

				<h2><?php esc_html_e( 'Email Settings', 'kha-solar' ); ?></h2>
				<table class="form-table">
					<tr>
						<th><label for="kha_solar_admin_email"><?php esc_html_e( 'Admin Email', 'kha-solar' ); ?></label></th>
						<td>
							<input type="email" id="kha_solar_admin_email" name="kha_solar_admin_email" value="<?php echo esc_attr( get_option( 'kha_solar_admin_email' ) ); ?>" class="regular-text">
						</td>
					</tr>
					<tr>
						<th><label for="kha_solar_enable_order_emails"><?php esc_html_e( 'Enable Order Emails', 'kha-solar' ); ?></label></th>
						<td>
							<select id="kha_solar_enable_order_emails" name="kha_solar_enable_order_emails">
								<option value="yes" <?php selected( get_option( 'kha_solar_enable_order_emails' ), 'yes' ); ?>><?php esc_html_e( 'Yes', 'kha-solar' ); ?></option>
								<option value="no" <?php selected( get_option( 'kha_solar_enable_order_emails' ), 'no' ); ?>><?php esc_html_e( 'No', 'kha-solar' ); ?></option>
							</select>
						</td>
					</tr>
				</table>

				<h2><?php esc_html_e( 'Chat Settings', 'kha-solar' ); ?></h2>
				<table class="form-table">
					<tr>
						<th><label for="kha_solar_enable_chat"><?php esc_html_e( 'Enable Chat Bubble', 'kha-solar' ); ?></label></th>
						<td>
							<select id="kha_solar_enable_chat" name="kha_solar_enable_chat">
								<option value="yes" <?php selected( get_option( 'kha_solar_enable_chat' ), 'yes' ); ?>><?php esc_html_e( 'Yes', 'kha-solar' ); ?></option>
								<option value="no" <?php selected( get_option( 'kha_solar_enable_chat' ), 'no' ); ?>><?php esc_html_e( 'No', 'kha-solar' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="kha_solar_zalo_phone"><?php esc_html_e( 'Zalo Phone Number', 'kha-solar' ); ?></label></th>
						<td>
							<input type="text" id="kha_solar_zalo_phone" name="kha_solar_zalo_phone" value="<?php echo esc_attr( get_option( 'kha_solar_zalo_phone' ) ); ?>" class="regular-text">
						</td>
					</tr>
					<tr>
						<th><label for="kha_solar_messenger_id"><?php esc_html_e( 'Facebook Messenger ID', 'kha-solar' ); ?></label></th>
						<td>
							<input type="text" id="kha_solar_messenger_id" name="kha_solar_messenger_id" value="<?php echo esc_attr( get_option( 'kha_solar_messenger_id' ) ); ?>" class="regular-text">
						</td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" name="save_kha_settings" class="button button-primary" value="<?php esc_attr_e( 'Save Settings', 'kha-solar' ); ?>">
				</p>
			</form>
		</div>
		<?php
	}
}
