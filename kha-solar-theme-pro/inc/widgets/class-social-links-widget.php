<?php
/**
 * Social Links Widget
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

class Khasolar_Pro_Social_Links_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'khasolar_pro_social_links',
			__( 'Kha Solar: Social Links', 'kha-solar-theme-pro' ),
			array( 'description' => __( 'Display social media links', 'kha-solar-theme-pro' ) )
		);
	}

	public function widget( $args, $instance ) {
		$title     = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Follow Us', 'kha-solar-theme-pro' );
		$facebook  = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
		$twitter   = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
		$instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
		$linkedin  = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
		$youtube   = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';

		if ( empty( $facebook ) && empty( $twitter ) && empty( $instagram ) && empty( $linkedin ) && empty( $youtube ) ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
		<div class="social-links-widget">
			<?php if ( ! empty( $facebook ) ) : ?>
				<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer" class="social-link facebook" aria-label="Facebook">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $twitter ) ) : ?>
				<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer" class="social-link twitter" aria-label="Twitter">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $instagram ) ) : ?>
				<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="social-link instagram" aria-label="Instagram">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $linkedin ) ) : ?>
				<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="social-link linkedin" aria-label="LinkedIn">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $youtube ) ) : ?>
				<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer" class="social-link youtube" aria-label="YouTube">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
				</a>
			<?php endif; ?>
		</div>
		<?php
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function form( $instance ) {
		$title     = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$facebook  = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
		$twitter   = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
		$instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
		$linkedin  = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
		$youtube   = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook URL:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="url" value="<?php echo esc_attr( $facebook ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter URL:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="url" value="<?php echo esc_attr( $twitter ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram URL:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="url" value="<?php echo esc_attr( $instagram ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn URL:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="url" value="<?php echo esc_attr( $linkedin ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube URL:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="url" value="<?php echo esc_attr( $youtube ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance['title']     = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['facebook']  = ! empty( $new_instance['facebook'] ) ? esc_url_raw( $new_instance['facebook'] ) : '';
		$instance['twitter']   = ! empty( $new_instance['twitter'] ) ? esc_url_raw( $new_instance['twitter'] ) : '';
		$instance['instagram'] = ! empty( $new_instance['instagram'] ) ? esc_url_raw( $new_instance['instagram'] ) : '';
		$instance['linkedin']  = ! empty( $new_instance['linkedin'] ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
		$instance['youtube']   = ! empty( $new_instance['youtube'] ) ? esc_url_raw( $new_instance['youtube'] ) : '';
		return $instance;
	}
}

function khasolar_pro_register_social_links_widget() {
	register_widget( 'Khasolar_Pro_Social_Links_Widget' );
}
add_action( 'widgets_init', 'khasolar_pro_register_social_links_widget' );
