<?php
/**
 * Pagination functionality
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display pagination
 */
function khasolar_pro_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	// Add current page to the array
	if ( $paged >= 1 ) {
		$links[] = $paged;
	}

	// Add the pages around the current page to the array
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	// Previous Post Link
	$prev_link = '';
	if ( get_previous_posts_link() ) {
		$prev_link = get_previous_posts_link( __( '&laquo; Previous', 'kha-solar-theme-pro' ) );
	}

	// Link to first page, plus ellipses if necessary
	$first_link = '';
	if ( ! in_array( 1, $links, true ) ) {
		$first_link = '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '" class="page-numbers">1</a>';

		if ( ! in_array( 2, $links, true ) ) {
			$first_link .= '<span class="page-numbers dots">&hellip;</span>';
		}
	}

	// Link to current page, plus 2 pages in either direction if necessary
	$pages_link = '';
	sort( $links );
	foreach ( (array) $links as $link ) {
		if ( $paged === $link ) {
			$pages_link .= '<span aria-current="page" class="page-numbers current">' . $link . '</span>';
		} else {
			$pages_link .= '<a href="' . esc_url( get_pagenum_link( $link ) ) . '" class="page-numbers">' . $link . '</a>';
		}
	}

	// Link to last page, plus ellipses if necessary
	$last_link = '';
	if ( ! in_array( $max, $links, true ) ) {
		if ( ! in_array( $max - 1, $links, true ) ) {
			$last_link = '<span class="page-numbers dots">&hellip;</span>';
		}

		$last_link .= '<a href="' . esc_url( get_pagenum_link( $max ) ) . '" class="page-numbers">' . $max . '</a>';
	}

	// Next Post Link
	$next_link = '';
	if ( get_next_posts_link() ) {
		$next_link = get_next_posts_link( __( 'Next &raquo;', 'kha-solar-theme-pro' ) );
	}

	// Output pagination
	echo '<nav class="pagination" role="navigation" aria-label="' . esc_attr__( 'Posts pagination', 'kha-solar-theme-pro' ) . '">';
	echo '<div class="nav-links">';
	echo $prev_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $first_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $pages_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $last_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $next_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</div>';
	echo '</nav>';
}

/**
 * Display post navigation (previous/next post)
 */
function khasolar_pro_post_navigation() {
	$prev_post = get_previous_post();
	$next_post = get_next_post();

	if ( ! $prev_post && ! $next_post ) {
		return;
	}

	?>
	<nav class="post-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Post navigation', 'kha-solar-theme-pro' ); ?>">
		<div class="nav-links">
			<?php if ( $prev_post ) : ?>
				<div class="nav-previous">
					<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev">
						<span class="nav-subtitle"><?php esc_html_e( 'Previous Post', 'kha-solar-theme-pro' ); ?></span>
						<span class="nav-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
					</a>
				</div>
			<?php endif; ?>

			<?php if ( $next_post ) : ?>
				<div class="nav-next">
					<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
						<span class="nav-subtitle"><?php esc_html_e( 'Next Post', 'kha-solar-theme-pro' ); ?></span>
						<span class="nav-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</nav>
	<?php
}

/**
 * Numbered pagination for custom queries
 *
 * @param WP_Query $query Custom WP_Query object.
 * @param string   $class Additional CSS classes.
 */
function khasolar_pro_custom_pagination( $query = null, $class = '' ) {
	if ( ! $query ) {
		global $wp_query;
		$query = $wp_query;
	}

	if ( $query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

	$pagination = paginate_links(
		array(
			'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, $paged ),
			'total'     => $query->max_num_pages,
			'type'      => 'array',
			'prev_text' => __( '&laquo; Previous', 'kha-solar-theme-pro' ),
			'next_text' => __( 'Next &raquo;', 'kha-solar-theme-pro' ),
			'mid_size'  => 2,
		)
	);

	if ( ! empty( $pagination ) ) {
		$class_attr = ! empty( $class ) ? ' ' . esc_attr( $class ) : '';
		echo '<nav class="pagination custom-pagination' . $class_attr . '" role="navigation" aria-label="' . esc_attr__( 'Posts pagination', 'kha-solar-theme-pro' ) . '">';
		echo '<div class="nav-links">';
		foreach ( $pagination as $page ) {
			echo $page; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div>';
		echo '</nav>';
	}
}

/**
 * AJAX Load More pagination
 *
 * @param WP_Query $query Custom WP_Query object.
 * @param string   $action AJAX action name.
 */
function khasolar_pro_load_more_button( $query = null, $action = 'khasolar_pro_load_more' ) {
	if ( ! $query ) {
		global $wp_query;
		$query = $wp_query;
	}

	if ( $query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

	if ( $paged >= $query->max_num_pages ) {
		return;
	}

	?>
	<div class="load-more-wrapper">
		<button class="btn btn-primary load-more-btn" data-page="<?php echo esc_attr( $paged ); ?>" data-max="<?php echo esc_attr( $query->max_num_pages ); ?>" data-action="<?php echo esc_attr( $action ); ?>">
			<?php esc_html_e( 'Load More', 'kha-solar-theme-pro' ); ?>
		</button>
		<span class="load-more-loading" style="display: none;">
			<?php esc_html_e( 'Loading...', 'kha-solar-theme-pro' ); ?>
		</span>
	</div>
	<?php
}
