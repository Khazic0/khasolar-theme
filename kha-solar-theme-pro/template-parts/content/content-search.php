<?php
/**
 * Template part for displaying search results
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>
	<div class="search-result-content">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="search-result-thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'thumbnail' ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="search-result-details">
			<header class="entry-header">
				<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta">
						<?php khasolar_pro_posted_on(); ?>
					</div>
				<?php endif; ?>
			</header>

			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div>

			<a href="<?php the_permalink(); ?>" class="read-more">
				<?php esc_html_e( 'Read More', 'kha-solar-theme-pro' ); ?> &rarr;
			</a>
		</div>
	</div>
</article>
