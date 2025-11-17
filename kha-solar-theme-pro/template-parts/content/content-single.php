<?php
/**
 * Template part for displaying single posts
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( khasolar_pro_get_option( 'show_featured_image', true ) ) : ?>
		<?php khasolar_pro_post_thumbnail(); ?>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( 'post' === get_post_type() && khasolar_pro_get_option( 'show_post_meta', true ) ) : ?>
			<div class="entry-meta">
				<?php
				khasolar_pro_posted_on();
				khasolar_pro_posted_by();
				?>
				<span class="reading-time"><?php echo esc_html( khasolar_pro_reading_time() ); ?></span>
			</div>
		<?php endif; ?>
	</header>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'kha-solar-theme-pro' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>

	<footer class="entry-footer">
		<?php khasolar_pro_entry_footer(); ?>
	</footer>

	<?php khasolar_pro_social_share(); ?>
</article>
