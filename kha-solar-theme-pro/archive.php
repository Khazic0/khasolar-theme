<?php
/**
 * The template for displaying archive pages
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
						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
						?>
					</header>

					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/content', get_post_type() );
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
