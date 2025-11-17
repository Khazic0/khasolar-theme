<?php
/**
 * Custom template tags for this theme
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display posted on date
 */
function khasolar_pro_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		/* translators: %s: post date */
		esc_html_x( 'Posted on %s', 'post date', 'kha-solar-theme-pro' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display post author
 */
function khasolar_pro_posted_by() {
	$byline = sprintf(
		/* translators: %s: post author */
		esc_html_x( 'by %s', 'post author', 'kha-solar-theme-pro' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display post categories
 */
function khasolar_pro_entry_categories() {
	if ( 'post' === get_post_type() ) {
		$categories_list = get_the_category_list( esc_html__( ', ', 'kha-solar-theme-pro' ) );
		if ( $categories_list ) {
			/* translators: %s: list of categories */
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'kha-solar-theme-pro' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

/**
 * Display post tags
 */
function khasolar_pro_entry_tags() {
	if ( 'post' === get_post_type() ) {
		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'kha-solar-theme-pro' ) );
		if ( $tags_list ) {
			/* translators: %s: list of tags */
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'kha-solar-theme-pro' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

/**
 * Display entry footer
 */
function khasolar_pro_entry_footer() {
	khasolar_pro_entry_categories();
	khasolar_pro_entry_tags();

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'kha-solar-theme-pro' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			wp_kses(
				/* translators: %s: post title */
				__( 'Edit <span class="screen-reader-text">%s</span>', 'kha-solar-theme-pro' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			wp_kses_post( get_the_title() )
		),
		'<span class="edit-link">',
		'</span>'
	);
}

/**
 * Display post thumbnail
 */
function khasolar_pro_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
		?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'khasolar-pro-featured' ); ?>
		</div>
		<?php
	else :
		?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				'khasolar-pro-thumbnail',
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
			?>
		</a>
		<?php
	endif;
}

/**
 * Get reading time
 */
function khasolar_pro_reading_time() {
	$content      = get_post_field( 'post_content', get_the_ID() );
	$word_count   = str_word_count( wp_strip_all_tags( $content ) );
	$reading_time = ceil( $word_count / 200 );

	/* translators: %s: reading time in minutes */
	return sprintf( _n( '%s min read', '%s min read', $reading_time, 'kha-solar-theme-pro' ), number_format_i18n( $reading_time ) );
}

/**
 * Display social share buttons
 */
function khasolar_pro_social_share() {
	if ( ! is_singular() ) {
		return;
	}

	$post_url   = urlencode( get_permalink() );
	$post_title = urlencode( get_the_title() );
	?>
	<div class="social-share">
		<h4><?php esc_html_e( 'Share this:', 'kha-solar-theme-pro' ); ?></h4>
		<ul class="share-buttons">
			<li>
				<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer" class="share-facebook">
					<span class="screen-reader-text"><?php esc_html_e( 'Share on Facebook', 'kha-solar-theme-pro' ); ?></span>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
				</a>
			</li>
			<li>
				<a href="https://twitter.com/intent/tweet?text=<?php echo $post_title; ?>&url=<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer" class="share-twitter">
					<span class="screen-reader-text"><?php esc_html_e( 'Share on Twitter', 'kha-solar-theme-pro' ); ?></span>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
				</a>
			</li>
			<li>
				<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_url; ?>&title=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="share-linkedin">
					<span class="screen-reader-text"><?php esc_html_e( 'Share on LinkedIn', 'kha-solar-theme-pro' ); ?></span>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
				</a>
			</li>
			<li>
				<a href="mailto:?subject=<?php echo $post_title; ?>&body=<?php echo $post_url; ?>" class="share-email">
					<span class="screen-reader-text"><?php esc_html_e( 'Share via Email', 'kha-solar-theme-pro' ); ?></span>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z"/></svg>
				</a>
			</li>
		</ul>
	</div>
	<?php
}
