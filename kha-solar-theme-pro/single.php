<?php
/**
 * The template for displaying single posts
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
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content/content', 'single' );

					// Post navigation
					khasolar_pro_post_navigation();

					// Comments
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile;
				?>

			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>
</main>

<?php
get_footer();
