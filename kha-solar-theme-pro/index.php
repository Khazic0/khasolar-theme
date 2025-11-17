<?php
/**
 * The main template file
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

				<?php
				if ( have_posts() ) :

					if ( is_home() && ! is_front_page() ) :
						?>
						<header class="page-header">
							<h1 class="page-title"><?php single_post_title(); ?></h1>
						</header>
						<?php
					endif;

					// Start the Loop
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content/content', get_post_type() );

					endwhile;

					// Pagination
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
