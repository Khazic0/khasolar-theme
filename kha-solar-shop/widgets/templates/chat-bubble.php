<?php
/**
 * Chat Bubble Template
 *
 * Frontend template for floating chat buttons.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$position = $settings['position'];
$zalo     = $settings['zalo_phone'];
$facebook = $settings['facebook_page_id'];
$phone    = $settings['phone_hotline'];
$greeting = $settings['greeting_message'];
$animation = $settings['animation'];

// Build Zalo URL based on device.
$zalo_url = '';
if ( ! empty( $zalo ) ) {
	$zalo_url = wp_is_mobile() ? 'zalo://conversation?phone=' . $zalo : 'https://zalo.me/' . $zalo;
}

// Build Messenger URL.
$messenger_url = ! empty( $facebook ) ? 'https://m.me/' . $facebook : '';

// Build phone URL.
$phone_url = ! empty( $phone ) ? 'tel:' . $phone : '';
?>

<!-- Chat Bubble Widget -->
<div class="kha-chat-bubble kha-position-<?php echo esc_attr( $position ); ?>" data-animation="<?php echo esc_attr( $animation ); ?>">

	<!-- Greeting Bubble (shown on first visit) -->
	<?php if ( ! empty( $greeting ) ) : ?>
		<div class="kha-greeting-bubble" id="kha-greeting-bubble" style="display: none;">
			<button type="button" class="kha-greeting-close" aria-label="<?php esc_attr_e( 'Close', 'kha-solar' ); ?>">
				&times;
			</button>
			<div class="kha-greeting-content">
				<span class="kha-greeting-emoji">=K</span>
				<p><?php echo esc_html( $greeting ); ?></p>
			</div>
		</div>
	<?php endif; ?>

	<!-- Chat Buttons Container -->
	<div class="kha-chat-buttons">

		<!-- Zalo Button -->
		<?php if ( ! empty( $zalo ) ) : ?>
			<a
				href="<?php echo esc_url( $zalo_url ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				class="kha-chat-button kha-chat-zalo"
				data-channel="zalo"
				aria-label="<?php esc_attr_e( 'Chat via Zalo', 'kha-solar' ); ?>"
				title="<?php esc_attr_e( 'Chat via Zalo', 'kha-solar' ); ?>"
			>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="kha-chat-icon">
					<path fill="#fff" d="M24 4C13 4 4 11.6 4 21c0 5.4 3.1 10.2 8 13.4V44l9.2-5.1c.9.1 1.8.1 2.8.1 11 0 20-7.6 20-17S35 4 24 4z"/>
				</svg>
			</a>
		<?php endif; ?>

		<!-- Facebook Messenger Button -->
		<?php if ( ! empty( $facebook ) ) : ?>
			<a
				href="<?php echo esc_url( $messenger_url ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				class="kha-chat-button kha-chat-messenger"
				data-channel="messenger"
				aria-label="<?php esc_attr_e( 'Chat via Messenger', 'kha-solar' ); ?>"
				title="<?php esc_attr_e( 'Chat via Messenger', 'kha-solar' ); ?>"
			>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="kha-chat-icon">
					<path fill="#fff" d="M24 4C13.5 4 5 11.9 5 21.7c0 5.3 2.6 10 6.8 13.2v7.6l7.2-4c1.9.5 3.9.8 6 .8 10.5 0 19-7.9 19-17.7S34.5 4 24 4zm1.9 23.8l-4.8-5.2-9.4 5.2 10.4-11 4.9 5.2 9.3-5.2-10.4 11z"/>
				</svg>
			</a>
		<?php endif; ?>

		<!-- Phone Call Button -->
		<?php if ( ! empty( $phone ) ) : ?>
			<?php if ( wp_is_mobile() ) : ?>
				<a
					href="<?php echo esc_url( $phone_url ); ?>"
					class="kha-chat-button kha-chat-phone"
					data-channel="phone"
					aria-label="<?php esc_attr_e( 'Call hotline', 'kha-solar' ); ?>"
					title="<?php esc_attr_e( 'Call hotline', 'kha-solar' ); ?>"
				>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="kha-chat-icon">
						<path fill="#fff" d="M13.25 21.59c2.88 5.66 7.51 10.29 13.18 13.17l4.4-4.41c.55-.55 1.34-.71 2.03-.49C35.1 30.6 37.51 31 40 31c1.11 0 2 .89 2 2v7c0 1.11-.89 2-2 2C21.22 42 6 26.78 6 8c0-1.11.9-2 2-2h7c1.11 0 2 .89 2 2 0 2.49.4 4.9 1.14 7.14.22.69.06 1.48-.49 2.03l-4.4 4.42z"/>
					</svg>
				</a>
			<?php else : ?>
				<button
					type="button"
					class="kha-chat-button kha-chat-phone"
					data-channel="phone"
					data-phone="<?php echo esc_attr( $phone ); ?>"
					id="kha-phone-button"
					aria-label="<?php esc_attr_e( 'Show phone number', 'kha-solar' ); ?>"
					title="<?php esc_attr_e( 'Show phone number', 'kha-solar' ); ?>"
				>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="kha-chat-icon">
						<path fill="#fff" d="M13.25 21.59c2.88 5.66 7.51 10.29 13.18 13.17l4.4-4.41c.55-.55 1.34-.71 2.03-.49C35.1 30.6 37.51 31 40 31c1.11 0 2 .89 2 2v7c0 1.11-.89 2-2 2C21.22 42 6 26.78 6 8c0-1.11.9-2 2-2h7c1.11 0 2 .89 2 2 0 2.49.4 4.9 1.14 7.14.22.69.06 1.48-.49 2.03l-4.4 4.42z"/>
					</svg>
				</button>
			<?php endif; ?>
		<?php endif; ?>

	</div>
</div>

<!-- Phone Number Modal (desktop only) -->
<?php if ( ! empty( $phone ) && ! wp_is_mobile() ) : ?>
	<div class="kha-phone-modal" id="kha-phone-modal" style="display: none;">
		<div class="kha-phone-modal-overlay"></div>
		<div class="kha-phone-modal-content">
			<button type="button" class="kha-phone-modal-close" id="kha-phone-modal-close" aria-label="<?php esc_attr_e( 'Close', 'kha-solar' ); ?>">
				&times;
			</button>
			<div class="kha-phone-modal-body">
				<div class="kha-phone-icon">=Þ</div>
				<h3><?php esc_html_e( 'GÍi ngay Ã °ãc t° v¥n', 'kha-solar' ); ?></h3>
				<div class="kha-phone-number"><?php echo esc_html( $phone ); ?></div>
				<p><?php esc_html_e( 'Vui lòng gÍi të iÇn tho¡i di Ùng', 'kha-solar' ); ?></p>
			</div>
		</div>
	</div>
<?php endif; ?>
