<?php
/**
 * Chat bubble widget (Zalo/Messenger).
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
 * Chat_Bubble class.
 *
 * Displays floating chat buttons for Zalo and Facebook Messenger.
 */
class Chat_Bubble {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$enable_chat = get_option( 'kha_solar_enable_chat', 'yes' );

		if ( 'yes' === $enable_chat ) {
			add_action( 'wp_footer', array( $this, 'render_chat_bubble' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_chat_styles' ) );
		}
	}

	/**
	 * Enqueue chat bubble styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_chat_styles() {
		wp_add_inline_style(
			'kha-solar-shop',
			'
			.kha-chat-bubble {
				position: fixed;
				bottom: 20px;
				right: 20px;
				z-index: 9999;
				display: flex;
				flex-direction: column;
				gap: 10px;
			}
			.kha-chat-button {
				width: 60px;
				height: 60px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				box-shadow: 0 4px 12px rgba(0,0,0,0.15);
				cursor: pointer;
				transition: transform 0.2s, box-shadow 0.2s;
				text-decoration: none;
			}
			.kha-chat-button:hover {
				transform: scale(1.1);
				box-shadow: 0 6px 16px rgba(0,0,0,0.2);
			}
			.kha-chat-button.zalo {
				background: linear-gradient(135deg, #0068FF, #0084FF);
			}
			.kha-chat-button.messenger {
				background: linear-gradient(135deg, #00B2FF, #006AFF);
			}
			.kha-chat-button svg {
				width: 32px;
				height: 32px;
				fill: white;
			}
			@media (max-width: 768px) {
				.kha-chat-bubble {
					bottom: 15px;
					right: 15px;
				}
				.kha-chat-button {
					width: 50px;
					height: 50px;
				}
				.kha-chat-button svg {
					width: 28px;
					height: 28px;
				}
			}
			'
		);
	}

	/**
	 * Render chat bubble.
	 *
	 * @since 1.0.0
	 */
	public function render_chat_bubble() {
		$zalo_phone   = get_option( 'kha_solar_zalo_phone' );
		$messenger_id = get_option( 'kha_solar_messenger_id' );

		if ( ! $zalo_phone && ! $messenger_id ) {
			return;
		}

		?>
		<div class="kha-chat-bubble">
			<?php if ( $zalo_phone ) : ?>
				<a href="https://zalo.me/<?php echo esc_attr( $zalo_phone ); ?>"
				   target="_blank"
				   rel="noopener noreferrer"
				   class="kha-chat-button zalo"
				   aria-label="<?php esc_attr_e( 'Chat on Zalo', 'kha-solar' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
						<path d="M24 4C13 4 4 11.6 4 21c0 5.4 3.1 10.2 8 13.4V44l9.2-5.1c.9.1 1.8.1 2.8.1 11 0 20-7.6 20-17S35 4 24 4z"/>
					</svg>
				</a>
			<?php endif; ?>

			<?php if ( $messenger_id ) : ?>
				<a href="https://m.me/<?php echo esc_attr( $messenger_id ); ?>"
				   target="_blank"
				   rel="noopener noreferrer"
				   class="kha-chat-button messenger"
				   aria-label="<?php esc_attr_e( 'Chat on Messenger', 'kha-solar' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
						<path d="M24 4C13.5 4 5 11.9 5 21.7c0 5.3 2.6 10 6.8 13.2v7.6l7.2-4c1.9.5 3.9.8 6 .8 10.5 0 19-7.9 19-17.7S34.5 4 24 4zm1.9 23.8l-4.8-5.2-9.4 5.2 10.4-11 4.9 5.2 9.3-5.2-10.4 11z"/>
					</svg>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
