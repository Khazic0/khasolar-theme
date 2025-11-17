<?php
/**
 * Product Categories Widget
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

class Khasolar_Pro_Product_Categories_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'khasolar_pro_product_categories',
			__( 'Kha Solar: Product Categories', 'kha-solar-theme-pro' ),
			array( 'description' => __( 'Display product categories', 'kha-solar-theme-pro' ) )
		);
	}

	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Product Categories', 'kha-solar-theme-pro' );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'kha_product_category',
				'hide_empty' => false,
			)
		);

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
			?>
			<ul class="product-categories-widget">
				<?php foreach ( $terms as $term ) : ?>
					<li class="category-item">
						<a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
							<?php echo esc_html( $term->name ); ?>
							<span class="category-count">(<?php echo esc_html( $term->count ); ?>)</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
		endif;

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		return $instance;
	}
}

function khasolar_pro_register_product_categories_widget() {
	register_widget( 'Khasolar_Pro_Product_Categories_Widget' );
}
add_action( 'widgets_init', 'khasolar_pro_register_product_categories_widget' );
