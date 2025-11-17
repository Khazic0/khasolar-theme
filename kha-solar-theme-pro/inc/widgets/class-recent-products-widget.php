<?php
/**
 * Recent Products Widget
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

class Khasolar_Pro_Recent_Products_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'khasolar_pro_recent_products',
			__( 'Kha Solar: Recent Products', 'kha-solar-theme-pro' ),
			array( 'description' => __( 'Display recent solar products', 'kha-solar-theme-pro' ) )
		);
	}

	public function widget( $args, $instance ) {
		$title  = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Products', 'kha-solar-theme-pro' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$products = new WP_Query(
			array(
				'post_type'      => 'kha_product',
				'posts_per_page' => $number,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		if ( $products->have_posts() ) :
			?>
			<ul class="recent-products-widget">
				<?php
				while ( $products->have_posts() ) :
					$products->the_post();
					$price = get_post_meta( get_the_ID(), '_kha_product_price', true );
					?>
					<li class="product-item">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="product-thumb">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'thumbnail' ); ?>
								</a>
							</div>
						<?php endif; ?>
						<div class="product-details">
							<h4 class="product-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h4>
							<?php if ( ! empty( $price ) ) : ?>
								<span class="product-price"><?php echo esc_html( number_format( $price, 0, ',', '.' ) ); ?> đ</span>
							<?php endif; ?>
						</div>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php
			wp_reset_postdata();
		endif;

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function form( $instance ) {
		$title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'kha-solar-theme-pro' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of products:', 'kha-solar-theme-pro' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance           = array();
		$instance['title']  = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number'] = ! empty( $new_instance['number'] ) ? absint( $new_instance['number'] ) : 5;
		return $instance;
	}
}

function khasolar_pro_register_recent_products_widget() {
	register_widget( 'Khasolar_Pro_Recent_Products_Widget' );
}
add_action( 'widgets_init', 'khasolar_pro_register_recent_products_widget' );
