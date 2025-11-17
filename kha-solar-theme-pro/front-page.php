<?php
/**
 * The front page template file
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main front-page">

	<?php
	// Check if homepage builder is enabled
	if ( khasolar_pro_is_homepage_builder_enabled() ) :

		// Get enabled sections
		$sections = khasolar_pro_get_homepage_sections();

		if ( ! empty( $sections ) ) :
			foreach ( $sections as $section ) {
				get_template_part( 'template-parts/sections/section', $section );
			}
		endif;

	else :
		// Default homepage content
		?>
		<div class="container">
			<div class="row">
				<div class="col-main">
					<?php
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							the_content();
						endwhile;
					endif;
					?>
				</div>
			</div>
		</div>
		<?php
	endif;
	?>

</main>

<?php
get_footer();
