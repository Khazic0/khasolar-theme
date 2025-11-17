<?php
/**
 * The template for displaying archive pages
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<div class="row">
			<div class="col" style="flex: 1;">

				<?php
				khasolar_breadcrumbs();

				if ( have_posts() ) :
					?>

					<header class="page-header">
						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
						?>
					</header>

					<?php
					// Start the Loop.
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;

					// Pagination.
					khasolar_pagination();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>

			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>
</main>

<?php
get_footer();
