<?php
/**
 * Breadcrumbs functionality
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display breadcrumbs
 */
function khasolar_pro_breadcrumbs() {
	// Check if breadcrumbs are enabled
	if ( ! khasolar_pro_get_option( 'enable_breadcrumbs', true ) ) {
		return;
	}

	// Don't display on homepage
	if ( is_front_page() ) {
		return;
	}

	$separator    = khasolar_pro_get_option( 'breadcrumb_separator', '/' );
	$home_title   = khasolar_pro_get_option( 'breadcrumb_home_text', __( 'Home', 'kha-solar-theme-pro' ) );
	$breadcrumbs  = array();
	$breadcrumbs_schema = array();

	// Home link
	$breadcrumbs[] = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( $home_title ) . '</a>';
	$breadcrumbs_schema[] = array(
		'@type'    => 'ListItem',
		'position' => 1,
		'name'     => $home_title,
		'item'     => home_url( '/' ),
	);

	$position = 2;

	if ( is_category() ) {
		$cat       = get_queried_object();
		$cat_id    = $cat->term_id;
		$cat_title = $cat->name;

		// Get parent categories
		if ( $cat->parent > 0 ) {
			$parent_cats = array();
			$parent_id   = $cat->parent;

			while ( $parent_id ) {
				$parent      = get_category( $parent_id );
				$parent_cats[] = '<a href="' . esc_url( get_category_link( $parent->term_id ) ) . '">' . esc_html( $parent->name ) . '</a>';
				$parent_id   = $parent->parent;
			}

			$parent_cats = array_reverse( $parent_cats );
			foreach ( $parent_cats as $parent_cat ) {
				$breadcrumbs[] = $parent_cat;
				$position++;
			}
		}

		$breadcrumbs[] = '<span class="current">' . esc_html( $cat_title ) . '</span>';
		$breadcrumbs_schema[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => $cat_title,
		);
	} elseif ( is_tag() ) {
		$tag       = get_queried_object();
		$tag_title = $tag->name;

		$breadcrumbs[] = '<span class="current">' . esc_html( $tag_title ) . '</span>';
		$breadcrumbs_schema[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => $tag_title,
		);
	} elseif ( is_archive() ) {
		$breadcrumbs[] = '<span class="current">' . get_the_archive_title() . '</span>';
		$breadcrumbs_schema[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => get_the_archive_title(),
		);
	} elseif ( is_search() ) {
		/* translators: %s: search query */
		$search_title = sprintf( __( 'Search Results for: %s', 'kha-solar-theme-pro' ), get_search_query() );

		$breadcrumbs[] = '<span class="current">' . esc_html( $search_title ) . '</span>';
		$breadcrumbs_schema[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => $search_title,
		);
	} elseif ( is_404() ) {
		$breadcrumbs[] = '<span class="current">' . esc_html__( '404 Not Found', 'kha-solar-theme-pro' ) . '</span>';
		$breadcrumbs_schema[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => __( '404 Not Found', 'kha-solar-theme-pro' ),
		);
	} elseif ( is_single() ) {
		$post_type = get_post_type();

		if ( 'post' === $post_type ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$cat = $categories[0];
				$breadcrumbs[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
				$breadcrumbs_schema[] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'name'     => $cat->name,
					'item'     => get_category_link( $cat->term_id ),
				);
				$position++;
			}
		} elseif ( 'kha_product' === $post_type ) {
			$archive_link = home_url( '/san-pham' );
			$breadcrumbs[] = '<a href="' . esc_url( $archive_link ) . '">' . esc_html__( 'Products', 'kha-solar-theme-pro' ) . '</a>';
			$breadcrumbs_schema[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => __( 'Products', 'kha-solar-theme-pro' ),
				'item'     => $archive_link,
			);
			$position++;
		}

		$breadcrumbs[] = '<span class="current">' . get_the_title() . '</span>';
		$breadcrumbs_schema[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => get_the_title(),
		);
	} elseif ( is_page() ) {
		// Get parent pages
		if ( $post = get_queried_object() ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			if ( $post->post_parent ) {
				$parent_id   = $post->post_parent;
				$parent_pages = array();

				while ( $parent_id ) {
					$page        = get_post( $parent_id );
					$parent_pages[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . get_the_title( $page->ID ) . '</a>';
					$parent_id   = $page->post_parent;
				}

				$parent_pages = array_reverse( $parent_pages );
				foreach ( $parent_pages as $parent_page ) {
					$breadcrumbs[] = $parent_page;
					$position++;
				}
			}

			$breadcrumbs[] = '<span class="current">' . get_the_title() . '</span>';
			$breadcrumbs_schema[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => get_the_title(),
			);
		}
	}

	// Output breadcrumbs
	if ( ! empty( $breadcrumbs ) ) {
		$schema = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $breadcrumbs_schema,
		);

		echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'kha-solar-theme-pro' ) . '">';
		echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
		echo implode( ' <span class="separator">' . esc_html( $separator ) . '</span> ', $breadcrumbs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</nav>';
	}
}
