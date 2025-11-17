<?php
/**
 * The template for displaying 404 pages
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main error-404">
	<div class="container">
		<div class="row">
			<div class="col-main">

				<section class="error-404-content">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( '404', 'kha-solar-theme-pro' ); ?></h1>
						<p class="error-message"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'kha-solar-theme-pro' ); ?></p>
					</header>

					<div class="page-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'kha-solar-theme-pro' ); ?></p>

						<?php get_search_form(); ?>

						<div class="error-404-widgets">
							<div class="widget-area">
								<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'kha-solar-theme-pro' ); ?></h2>
								<ul>
									<?php
									wp_list_categories(
										array(
											'orderby'    => 'count',
											'order'      => 'DESC',
											'show_count' => 1,
											'title_li'   => '',
											'number'     => 10,
										)
									);
									?>
								</ul>
							</div>

							<div class="widget-area">
								<h2 class="widget-title"><?php esc_html_e( 'Recent Posts', 'kha-solar-theme-pro' ); ?></h2>
								<ul>
									<?php
									wp_get_archives(
										array(
											'type'  => 'postbypost',
											'limit' => 10,
										)
									);
									?>
								</ul>
							</div>

							<div class="widget-area">
								<h2 class="widget-title"><?php esc_html_e( 'Archives', 'kha-solar-theme-pro' ); ?></h2>
								<ul>
									<?php
									wp_get_archives(
										array(
											'type'  => 'monthly',
											'limit' => 12,
										)
									);
									?>
								</ul>
							</div>
						</div>

						<div class="home-link">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
								<?php esc_html_e( 'Back to Homepage', 'kha-solar-theme-pro' ); ?>
							</a>
						</div>

					</div>
				</section>

			</div>
		</div>
	</div>
</main>

<?php
get_footer();
