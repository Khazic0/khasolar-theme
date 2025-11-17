<?php
/**
 * Template Name: Full Width
 * Template Post Type: page
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main fullwidth-template">
	<div class="container-fluid">
		<?php khasolar_pro_breadcrumbs(); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content/content', 'page' );

			// Comments
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
