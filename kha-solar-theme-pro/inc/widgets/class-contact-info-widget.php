<?php
/**
 * Contact Info Widget
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

class Khasolar_Pro_Contact_Info_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'khasolar_pro_contact_info',
			__( 'Kha Solar: Contact Info', 'kha-solar-theme-pro' ),
			array( 'description' => __( 'Display contact information', 'kha-solar-theme-pro' ) )
		);
	}

	public function widget( $args, $instance ) {
		$title   = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Contact Us', 'kha-solar-theme-pro' );
		$phone   = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
		$email   = ! empty( $instance['email'] ) ? $instance['email'] : '';
		$address = ! empty( $instance['address'] ) ? $instance['address'] : '';

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
		<ul class="contact-info-widget">
			<?php if ( ! empty( $phone ) ) : ?>
				<li class="contact-item">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
					</svg>
					<a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
				</li>
			<?php endif; ?>

			<?php if ( ! empty( $email ) ) : ?>
				<li class="contact-item">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
						<polyline points="22,6 12,13 2,6"></polyline>
					</svg>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				</li>
			<?php endif; ?>

			<?php if ( ! empty( $address ) ) : ?>
				<li class="contact-item">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
						<circle cx="12" cy="10" r="3"></circle>
					</svg>
					<span><?php echo wp_kses_post( $address ); ?></span>
				</li>
			<?php endif; ?>
		</ul>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function form( $instance ) {
		$title   = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$phone   = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
		$email   = ! empty( $instance['email'] ) ? $instance['email'] : '';
		$address = ! empty( $instance['address'] ) ? $instance['address'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="email" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address:', 'kha-solar-theme-pro' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" rows="3"><?php echo esc_textarea( $address ); ?></textarea>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance            = array();
		$instance['title']   = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['phone']   = ! empty( $new_instance['phone'] ) ? sanitize_text_field( $new_instance['phone'] ) : '';
		$instance['email']   = ! empty( $new_instance['email'] ) ? sanitize_email( $new_instance['email'] ) : '';
		$instance['address'] = ! empty( $new_instance['address'] ) ? wp_kses_post( $new_instance['address'] ) : '';
		return $instance;
	}
}

function khasolar_pro_register_contact_info_widget() {
	register_widget( 'Khasolar_Pro_Contact_Info_Widget' );
}
add_action( 'widgets_init', 'khasolar_pro_register_contact_info_widget' );
