<?php
/**
 * Template part for displaying posts
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php khasolar_pro_post_thumbnail(); ?>

	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				khasolar_pro_posted_on();
				khasolar_pro_posted_by();
				?>
				<span class="reading-time"><?php echo esc_html( khasolar_pro_reading_time() ); ?></span>
			</div>
			<?php
		endif;
		?>
	</header>

	<div class="entry-content">
		<?php
		if ( is_singular() ) {
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'kha-solar-theme-pro' ),
					'after'  => '</div>',
				)
			);
		} else {
			the_excerpt();
			?>
			<a href="<?php the_permalink(); ?>" class="read-more">
				<?php esc_html_e( 'Read More', 'kha-solar-theme-pro' ); ?> &rarr;
			</a>
			<?php
		}
		?>
	</div>

	<footer class="entry-footer">
		<?php khasolar_pro_entry_footer(); ?>
	</footer>
</article>
