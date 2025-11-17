<?php
/**
 * Template part for displaying posts
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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
				<span class="posted-on">
					<?php echo esc_html( get_the_date() ); ?>
				</span>
				<span class="author">
					<?php esc_html_e( 'bởi', 'khasolar-theme' ); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a>
				</span>
			</div>
			<?php
		endif;
		?>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'large' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		if ( is_singular() ) :
			the_content();
		else :
			the_excerpt();
			?>
			<a href="<?php the_permalink(); ?>" class="btn">
				<?php esc_html_e( 'Đọc thêm', 'khasolar-theme' ); ?>
			</a>
			<?php
		endif;

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'khasolar-theme' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>

	<footer class="entry-footer">
		<?php
		$categories_list = get_the_category_list( ', ' );
		if ( $categories_list ) {
			printf(
				'<span class="cat-links">' . esc_html__( 'Danh mục: %s', 'khasolar-theme' ) . '</span>',
				$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		$tags_list = get_the_tag_list( '', ', ' );
		if ( $tags_list ) {
			printf(
				'<span class="tags-links"> | ' . esc_html__( 'Tags: %s', 'khasolar-theme' ) . '</span>',
				$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		?>
	</footer>

</article>
