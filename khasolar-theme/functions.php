<?php
/**
 * Kha Solar Theme Functions
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup.
 */
function khasolar_theme_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Set post thumbnail size.
	set_post_thumbnail_size( 1200, 675, true );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'khasolar-theme' ),
			'footer'  => __( 'Footer Menu', 'khasolar-theme' ),
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Load translation files.
	load_theme_textdomain( 'khasolar-theme', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'khasolar_theme_setup' );

/**
 * Set the content width in pixels.
 */
function khasolar_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'khasolar_content_width', 1200 );
}
add_action( 'after_setup_theme', 'khasolar_content_width', 0 );

/**
 * Register widget areas.
 */
function khasolar_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'khasolar-theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'khasolar-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Widget 1', 'khasolar-theme' ),
			'id'            => 'footer-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'khasolar-theme' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Widget 2', 'khasolar-theme' ),
			'id'            => 'footer-2',
			'description'   => __( 'Add widgets here to appear in your footer.', 'khasolar-theme' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Widget 3', 'khasolar-theme' ),
			'id'            => 'footer-3',
			'description'   => __( 'Add widgets here to appear in your footer.', 'khasolar-theme' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'khasolar_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function khasolar_scripts() {
	// Enqueue theme stylesheet.
	wp_enqueue_style( 'khasolar-style', get_stylesheet_uri(), array(), '1.0.0' );

	// Enqueue custom CSS.
	wp_enqueue_style( 'khasolar-custom', get_template_directory_uri() . '/css/custom.css', array(), '1.0.0' );

	// Enqueue theme JavaScript.
	wp_enqueue_script( 'khasolar-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '1.0.0', true );

	// Enqueue comment reply script.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'khasolar_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom logo support.
 */
function khasolar_custom_logo_setup() {
	$defaults = array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	);
	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'khasolar_custom_logo_setup' );

/**
 * Breadcrumbs function.
 *
 * @return void
 */
function khasolar_breadcrumbs() {
	// Don't show on homepage.
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="breadcrumb">';
	echo '<ul>';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Trang chủ', 'khasolar-theme' ) . '</a></li>';

	if ( is_category() || is_single() ) {
		echo '<li>';
		the_category( ', ' );
		echo '</li>';
		if ( is_single() ) {
			echo '<li>' . esc_html( get_the_title() ) . '</li>';
		}
	} elseif ( is_page() ) {
		echo '<li>' . esc_html( get_the_title() ) . '</li>';
	} elseif ( is_search() ) {
		echo '<li>' . esc_html__( 'Kết quả tìm kiếm', 'khasolar-theme' ) . '</li>';
	} elseif ( is_404() ) {
		echo '<li>' . esc_html__( 'Không tìm thấy', 'khasolar-theme' ) . '</li>';
	}

	echo '</ul>';
	echo '</nav>';
}

/**
 * Excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function khasolar_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'khasolar_excerpt_length', 999 );

/**
 * Excerpt more.
 *
 * @param string $more The excerpt more text.
 * @return string Modified excerpt more text.
 */
function khasolar_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'khasolar_excerpt_more' );

/**
 * Add body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array Modified body classes.
 */
function khasolar_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'khasolar_body_classes' );

/**
 * Pagination for archive pages.
 */
function khasolar_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$big = 999999999;

	$pages = paginate_links(
		array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'total'     => $wp_query->max_num_pages,
			'type'      => 'array',
			'prev_text' => __( '« Trước', 'khasolar-theme' ),
			'next_text' => __( 'Sau »', 'khasolar-theme' ),
		)
	);

	if ( is_array( $pages ) ) {
		echo '<nav class="pagination" role="navigation">';
		foreach ( $pages as $page ) {
			echo wp_kses_post( $page );
		}
		echo '</nav>';
	}
}

/**
 * Add support for lazy loading images.
 */
function khasolar_add_lazy_loading( $attr, $attachment, $size ) {
	$attr['loading'] = 'lazy';
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'khasolar_add_lazy_loading', 10, 3 );

/**
 * Disable emoji scripts.
 */
function khasolar_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'khasolar_disable_emojis' );
