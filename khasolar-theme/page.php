<?php
/**
 * The template for displaying pages
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

				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>

			</div>

			<?php
			// Show sidebar only if not a full-width page.
			if ( ! is_page_template( 'page-full-width.php' ) ) {
				get_sidebar();
			}
			?>

		</div>
	</div>
</main>

<?php
get_footer();
