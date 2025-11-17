<?php
/**
 * Chat Bubble Widget
 *
 * Floating chat buttons for Zalo, Facebook Messenger, and phone support.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

namespace KhaSolar;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Chat_Bubble class.
 */
class Chat_Bubble {

	/**
	 * Default settings.
	 *
	 * @var array
	 */
	private $defaults = array(
		'enabled'          => true,
		'zalo_phone'       => '',
		'facebook_page_id' => '',
		'phone_hotline'    => '',
		'position'         => 'bottom-right',
		'show_on'          => 'all',
		'exclude_pages'    => array(),
		'greeting_message' => 'Xin chào! Cần hỗ trợ về thiết bị solar?',
		'animation'        => 'pulse',
	);

	/**
	 * Initialize hooks.
	 */
	public function init() {
		// Add settings page.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Frontend display.
		add_action( 'wp_footer', array( $this, 'render_chat_bubble' ) );

		// Enqueue assets.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Add settings page to admin menu.
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'Kha Solar Chat Settings', 'kha-solar' ),
			__( 'Kha Solar Chat', 'kha-solar' ),
			'manage_options',
			'kha-solar-chat',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting(
			'kha_solar_chat_settings',
			'kha_solar_chat_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);

		// General section.
		add_settings_section(
			'kha_chat_general',
			__( 'General Settings', 'kha-solar' ),
			null,
			'kha-solar-chat'
		);

		// Enable/disable field.
		add_settings_field(
			'enabled',
			__( 'Enable Chat Bubbles', 'kha-solar' ),
			array( $this, 'render_checkbox_field' ),
			'kha-solar-chat',
			'kha_chat_general',
			array(
				'label_for' => 'enabled',
				'field'     => 'enabled',
			)
		);

		// Contact section.
		add_settings_section(
			'kha_chat_contact',
			__( 'Contact Information', 'kha-solar' ),
			array( $this, 'render_contact_section' ),
			'kha-solar-chat'
		);

		// Zalo phone.
		add_settings_field(
			'zalo_phone',
			__( 'Zalo Phone Number', 'kha-solar' ),
			array( $this, 'render_text_field' ),
			'kha-solar-chat',
			'kha_chat_contact',
			array(
				'label_for'   => 'zalo_phone',
				'field'       => 'zalo_phone',
				'placeholder' => '0901234567',
				'description' => __( 'Enter phone number for Zalo (10-11 digits)', 'kha-solar' ),
			)
		);

		// Facebook Page ID.
		add_settings_field(
			'facebook_page_id',
			__( 'Facebook Page ID', 'kha-solar' ),
			array( $this, 'render_text_field' ),
			'kha-solar-chat',
			'kha_chat_contact',
			array(
				'label_for'   => 'facebook_page_id',
				'field'       => 'facebook_page_id',
				'placeholder' => 'khasolar',
				'description' => __( 'Enter Facebook Page username or ID', 'kha-solar' ),
			)
		);

		// Phone hotline.
		add_settings_field(
			'phone_hotline',
			__( 'Phone Hotline', 'kha-solar' ),
			array( $this, 'render_text_field' ),
			'kha-solar-chat',
			'kha_chat_contact',
			array(
				'label_for'   => 'phone_hotline',
				'field'       => 'phone_hotline',
				'placeholder' => '1900-xxxx',
				'description' => __( 'Enter hotline number for phone calls', 'kha-solar' ),
			)
		);

		// Display section.
		add_settings_section(
			'kha_chat_display',
			__( 'Display Settings', 'kha-solar' ),
			null,
			'kha-solar-chat'
		);

		// Position.
		add_settings_field(
			'position',
			__( 'Position', 'kha-solar' ),
			array( $this, 'render_radio_field' ),
			'kha-solar-chat',
			'kha_chat_display',
			array(
				'label_for' => 'position',
				'field'     => 'position',
				'options'   => array(
					'bottom-right' => __( 'Bottom Right', 'kha-solar' ),
					'bottom-left'  => __( 'Bottom Left', 'kha-solar' ),
				),
			)
		);

		// Show on.
		add_settings_field(
			'show_on',
			__( 'Show On Pages', 'kha-solar' ),
			array( $this, 'render_radio_field' ),
			'kha-solar-chat',
			'kha_chat_display',
			array(
				'label_for' => 'show_on',
				'field'     => 'show_on',
				'options'   => array(
					'all'          => __( 'All pages', 'kha-solar' ),
					'shop'         => __( 'Shop pages only', 'kha-solar' ),
					'exclude_cart' => __( 'Exclude cart/checkout', 'kha-solar' ),
				),
			)
		);

		// Greeting message.
		add_settings_field(
			'greeting_message',
			__( 'Greeting Message', 'kha-solar' ),
			array( $this, 'render_textarea_field' ),
			'kha-solar-chat',
			'kha_chat_display',
			array(
				'label_for'   => 'greeting_message',
				'field'       => 'greeting_message',
				'description' => __( 'Message shown in greeting bubble (first visit)', 'kha-solar' ),
			)
		);

		// Animation.
		add_settings_field(
			'animation',
			__( 'Animation', 'kha-solar' ),
			array( $this, 'render_select_field' ),
			'kha-solar-chat',
			'kha_chat_display',
			array(
				'label_for' => 'animation',
				'field'     => 'animation',
				'options'   => array(
					'pulse'  => __( 'Pulse', 'kha-solar' ),
					'bounce' => __( 'Bounce', 'kha-solar' ),
					'none'   => __( 'None', 'kha-solar' ),
				),
			)
		);
	}

	/**
	 * Render contact section description.
	 */
	public function render_contact_section() {
		echo '<p>' . esc_html__( 'Configure your contact channels for the chat bubbles.', 'kha-solar' ) . '</p>';
	}

	/**
	 * Render checkbox field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_checkbox_field( $args ) {
		$settings = $this->get_settings();
		$value    = isset( $settings[ $args['field'] ] ) ? $settings[ $args['field'] ] : false;
		?>
		<label>
			<input
				type="checkbox"
				id="<?php echo esc_attr( $args['label_for'] ); ?>"
				name="kha_solar_chat_settings[<?php echo esc_attr( $args['field'] ); ?>]"
				value="1"
				<?php checked( $value, true ); ?>
			>
			<?php echo isset( $args['description'] ) ? esc_html( $args['description'] ) : ''; ?>
		</label>
		<?php
	}

	/**
	 * Render text field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_text_field( $args ) {
		$settings = $this->get_settings();
		$value    = isset( $settings[ $args['field'] ] ) ? $settings[ $args['field'] ] : '';
		?>
		<input
			type="text"
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="kha_solar_chat_settings[<?php echo esc_attr( $args['field'] ); ?>]"
			value="<?php echo esc_attr( $value ); ?>"
			placeholder="<?php echo esc_attr( $args['placeholder'] ?? '' ); ?>"
			class="regular-text"
		>
		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render textarea field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_textarea_field( $args ) {
		$settings = $this->get_settings();
		$value    = isset( $settings[ $args['field'] ] ) ? $settings[ $args['field'] ] : '';
		?>
		<textarea
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="kha_solar_chat_settings[<?php echo esc_attr( $args['field'] ); ?>]"
			rows="3"
			class="large-text"
		><?php echo esc_textarea( $value ); ?></textarea>
		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render radio field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_radio_field( $args ) {
		$settings = $this->get_settings();
		$value    = isset( $settings[ $args['field'] ] ) ? $settings[ $args['field'] ] : '';

		foreach ( $args['options'] as $option_value => $option_label ) :
			?>
			<label style="margin-right: 20px;">
				<input
					type="radio"
					name="kha_solar_chat_settings[<?php echo esc_attr( $args['field'] ); ?>]"
					value="<?php echo esc_attr( $option_value ); ?>"
					<?php checked( $value, $option_value ); ?>
				>
				<?php echo esc_html( $option_label ); ?>
			</label>
			<?php
		endforeach;
	}

	/**
	 * Render select field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_select_field( $args ) {
		$settings = $this->get_settings();
		$value    = isset( $settings[ $args['field'] ] ) ? $settings[ $args['field'] ] : '';
		?>
		<select
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="kha_solar_chat_settings[<?php echo esc_attr( $args['field'] ); ?>]"
		>
			<?php foreach ( $args['options'] as $option_value => $option_label ) : ?>
				<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $value, $option_value ); ?>>
					<?php echo esc_html( $option_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Save success message.
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				'kha_solar_chat_messages',
				'kha_solar_chat_message',
				__( 'Settings saved successfully.', 'kha-solar' ),
				'success'
			);
		}

		settings_errors( 'kha_solar_chat_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'kha_solar_chat_settings' );
				do_settings_sections( 'kha-solar-chat' );
				submit_button( __( 'Save Changes', 'kha-solar' ) );
				?>
			</form>

			<div class="kha-chat-preview" style="margin-top: 30px; padding: 20px; background: #f5f5f5; border-radius: 8px;">
				<h2><?php esc_html_e( 'Preview', 'kha-solar' ); ?></h2>
				<p><?php esc_html_e( 'Chat bubbles will appear on the frontend based on your settings.', 'kha-solar' ); ?></p>
				<?php $this->render_preview(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render preview in admin.
	 */
	private function render_preview() {
		$settings = $this->get_settings();
		?>
		<div style="position: relative; height: 300px; background: #fff; border: 1px solid #ddd; border-radius: 8px;">
			<div class="kha-chat-preview-buttons" style="position: absolute; <?php echo $settings['position'] === 'bottom-left' ? 'left' : 'right'; ?>: 20px; bottom: 20px;">
				<?php if ( ! empty( $settings['zalo_phone'] ) ) : ?>
					<div style="width: 56px; height: 56px; background: #0068FF; border-radius: 50%; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px;">
						📱
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $settings['facebook_page_id'] ) ) : ?>
					<div style="width: 56px; height: 56px; background: linear-gradient(135deg, #0084FF, #0066CC); border-radius: 50%; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px;">
						💬
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $settings['phone_hotline'] ) ) : ?>
					<div style="width: 56px; height: 56px; background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px;">
						📞
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Settings input.
	 * @return array Sanitized settings.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = array();

		$sanitized['enabled']          = isset( $input['enabled'] ) ? true : false;
		$sanitized['zalo_phone']       = isset( $input['zalo_phone'] ) ? sanitize_text_field( $input['zalo_phone'] ) : '';
		$sanitized['facebook_page_id'] = isset( $input['facebook_page_id'] ) ? sanitize_text_field( $input['facebook_page_id'] ) : '';
		$sanitized['phone_hotline']    = isset( $input['phone_hotline'] ) ? sanitize_text_field( $input['phone_hotline'] ) : '';
		$sanitized['position']         = isset( $input['position'] ) ? sanitize_text_field( $input['position'] ) : 'bottom-right';
		$sanitized['show_on']          = isset( $input['show_on'] ) ? sanitize_text_field( $input['show_on'] ) : 'all';
		$sanitized['greeting_message'] = isset( $input['greeting_message'] ) ? sanitize_textarea_field( $input['greeting_message'] ) : '';
		$sanitized['animation']        = isset( $input['animation'] ) ? sanitize_text_field( $input['animation'] ) : 'pulse';

		return $sanitized;
	}

	/**
	 * Get settings.
	 *
	 * @return array Settings.
	 */
	public function get_settings() {
		$settings = get_option( 'kha_solar_chat_settings', array() );
		return wp_parse_args( $settings, $this->defaults );
	}

	/**
	 * Check if chat should be displayed on current page.
	 *
	 * @return bool True if should display.
	 */
	private function should_display() {
		$settings = $this->get_settings();

		if ( ! $settings['enabled'] ) {
			return false;
		}

		// Check if we have at least one contact method.
		if ( empty( $settings['zalo_phone'] ) && empty( $settings['facebook_page_id'] ) && empty( $settings['phone_hotline'] ) ) {
			return false;
		}

		// Check page visibility.
		if ( $settings['show_on'] === 'shop' ) {
			if ( ! ( is_singular( 'kha_product' ) || is_post_type_archive( 'kha_product' ) || kha_solar_is_shop_page() ) ) {
				return false;
			}
		}

		if ( $settings['show_on'] === 'exclude_cart' ) {
			$cart_page_id     = get_option( 'kha_solar_cart_page_id' );
			$checkout_page_id = get_option( 'kha_solar_checkout_page_id' );
			$current_page_id  = get_the_ID();

			if ( in_array( $current_page_id, array( $cart_page_id, $checkout_page_id ), true ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Enqueue assets.
	 */
	public function enqueue_assets() {
		if ( ! $this->should_display() ) {
			return;
		}

		wp_enqueue_style(
			'kha-solar-chat-bubble',
			KHA_PLUGIN_URL . 'public/css/chat-bubble.css',
			array(),
			KHA_VERSION
		);

		wp_enqueue_script(
			'kha-solar-chat-bubble',
			KHA_PLUGIN_URL . 'public/js/chat-bubble.js',
			array( 'jquery' ),
			KHA_VERSION,
			true
		);

		$settings = $this->get_settings();

		wp_localize_script(
			'kha-solar-chat-bubble',
			'khaChatConfig',
			array(
				'greetingMessage' => $settings['greeting_message'],
				'animation'       => $settings['animation'],
			)
		);
	}

	/**
	 * Render chat bubble on frontend.
	 */
	public function render_chat_bubble() {
		if ( ! $this->should_display() ) {
			return;
		}

		$settings = $this->get_settings();
		include KHA_PLUGIN_DIR . 'widgets/templates/chat-bubble.php';
	}
}
