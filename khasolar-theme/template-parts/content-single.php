<?php
/**
 * Template part for displaying single posts
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<span class="posted-on">
				<strong><?php esc_html_e( 'Ngày đăng:', 'khasolar-theme' ); ?></strong> <?php echo esc_html( get_the_date() ); ?>
			</span>
			<span class="author">
				<strong><?php esc_html_e( 'Tác giả:', 'khasolar-theme' ); ?></strong> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a>
			</span>
			<?php if ( get_comments_number() > 0 ) : ?>
				<span class="comments-link">
					<strong><?php esc_html_e( 'Bình luận:', 'khasolar-theme' ); ?></strong> <?php comments_number( '0', '1', '%' ); ?>
				</span>
			<?php endif; ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'large' ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Trang:', 'khasolar-theme' ),
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
				'<div class="cat-links"><strong>' . esc_html__( 'Danh mục:', 'khasolar-theme' ) . '</strong> %s</div>',
				$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		$tags_list = get_the_tag_list( '', ', ' );
		if ( $tags_list ) {
			printf(
				'<div class="tags-links"><strong>' . esc_html__( 'Tags:', 'khasolar-theme' ) . '</strong> %s</div>',
				$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		?>
	</footer>

</article>

<?php
// Previous/Next post navigation.
the_post_navigation(
	array(
		'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Bài trước:', 'khasolar-theme' ) . '</span> <span class="nav-title">%title</span>',
		'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Bài sau:', 'khasolar-theme' ) . '</span> <span class="nav-title">%title</span>',
	)
);
