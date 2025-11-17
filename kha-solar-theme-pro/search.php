<?php
/**
 * The template for displaying search results
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<div class="row">
			<div class="col-main">

				<?php khasolar_pro_breadcrumbs(); ?>

				<?php if ( have_posts() ) : ?>

					<header class="page-header">
						<h1 class="page-title">
							<?php
							/* translators: %s: search query */
							printf( esc_html__( 'Search Results for: %s', 'kha-solar-theme-pro' ), '<span>' . get_search_query() . '</span>' );
							?>
						</h1>
					</header>

					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/content', 'search' );
					endwhile;

					khasolar_pro_pagination();

				else :

					get_template_part( 'template-parts/content/content', 'none' );

				endif;
				?>

			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>
</main>

<?php
get_footer();
