<?php
/**
 * Homepage Section - Latest Blog Posts
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

$blog_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
	)
);

if ( ! $blog_query->have_posts() ) {
	return;
}
?>

<section class="blog-section section-padding bg-light">
	<div class="container">
		<div class="section-header text-center">
			<h2 class="section-title"><?php echo esc_html( khasolar_pro_get_option( 'blog_title', __( 'Latest News & Articles', 'kha-solar-theme-pro' ) ) ); ?></h2>
			<p class="section-subtitle"><?php echo esc_html( khasolar_pro_get_option( 'blog_subtitle', __( 'Stay updated with solar energy trends and tips', 'kha-solar-theme-pro' ) ) ); ?></p>
		</div>

		<div class="blog-grid row">
			<?php
			while ( $blog_query->have_posts() ) :
				$blog_query->the_post();
				?>
				<div class="col-lg-4 col-md-6">
					<article class="blog-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="blog-image">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) ); ?>
								</a>
								<div class="blog-date">
									<span class="date-day"><?php echo get_the_date( 'd' ); ?></span>
									<span class="date-month"><?php echo get_the_date( 'M' ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<div class="blog-content">
							<div class="blog-meta">
								<span class="meta-category">
									<?php
									$categories = get_the_category();
									if ( ! empty( $categories ) ) {
										echo esc_html( $categories[0]->name );
									}
									?>
								</span>
								<span class="meta-reading-time"><?php echo esc_html( khasolar_pro_reading_time() ); ?></span>
							</div>

							<h3 class="blog-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h3>

							<div class="blog-excerpt">
								<?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
							</div>

							<a href="<?php the_permalink(); ?>" class="blog-read-more">
								<?php esc_html_e( 'Read More', 'kha-solar-theme-pro' ); ?> &rarr;
							</a>
						</div>
					</article>
				</div>
			<?php endwhile; ?>
		</div>

		<div class="section-footer text-center">
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-lg btn-primary">
				<?php esc_html_e( 'View All Articles', 'kha-solar-theme-pro' ); ?>
			</a>
		</div>
	</div>
</section>

<?php wp_reset_postdata(); ?>
