<?php
/**
 * Kha Solar Theme Pro Functions
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define Constants
 */
define( 'KHASOLAR_PRO_VERSION', '1.0.0' );
define( 'KHASOLAR_PRO_DIR', get_template_directory() );
define( 'KHASOLAR_PRO_URI', get_template_directory_uri() );
define( 'KHASOLAR_PRO_INC', KHASOLAR_PRO_DIR . '/inc' );

/**
 * Theme Setup
 */
function khasolar_pro_setup() {
	// Make theme available for translation
	load_theme_textdomain( 'kha-solar-theme-pro', KHASOLAR_PRO_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails
	add_theme_support( 'post-thumbnails' );

	// Set custom image sizes
	add_image_size( 'khasolar-pro-featured', 1200, 600, true );
	add_image_size( 'khasolar-pro-thumbnail', 400, 300, true );
	add_image_size( 'khasolar-pro-medium', 800, 600, true );

	// Register navigation menus
	register_nav_menus(
		array(
			'primary'   => esc_html__( 'Primary Menu', 'kha-solar-theme-pro' ),
			'secondary' => esc_html__( 'Secondary Menu', 'kha-solar-theme-pro' ),
			'footer'    => esc_html__( 'Footer Menu', 'kha-solar-theme-pro' ),
			'mobile'    => esc_html__( 'Mobile Menu', 'kha-solar-theme-pro' ),
		)
	);

	// Switch default core markup to output valid HTML5
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

	// Add theme support for selective refresh for widgets
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for custom logo
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 300,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Add support for custom header
	add_theme_support(
		'custom-header',
		array(
			'default-image'      => '',
			'width'              => 1920,
			'height'             => 400,
			'flex-height'        => true,
			'flex-width'         => true,
			'default-text-color' => '000000',
			'header-text'        => true,
		)
	);

	// Add support for custom background
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)
	);

	// Add support for editor styles
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	// Add support for wide and full alignment
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds
	add_theme_support( 'responsive-embeds' );

	// Add support for WooCommerce if plugin exists
	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'khasolar_pro_setup' );

/**
 * Set the content width
 */
function khasolar_pro_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'khasolar_pro_content_width', 1200 );
}
add_action( 'after_setup_theme', 'khasolar_pro_content_width', 0 );

/**
 * Register Widget Areas
 */
function khasolar_pro_widgets_init() {
	// Main Sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'Main Sidebar', 'kha-solar-theme-pro' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'kha-solar-theme-pro' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	// Shop Sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'kha-solar-theme-pro' ),
			'id'            => 'sidebar-shop',
			'description'   => esc_html__( 'Add widgets here to appear in shop pages.', 'kha-solar-theme-pro' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	// Footer Widget Areas (4 columns)
	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer widget area number */
				'name'          => sprintf( esc_html__( 'Footer Widget Area %d', 'kha-solar-theme-pro' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %d.', 'kha-solar-theme-pro' ), $i ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	// Homepage Widget Areas
	register_sidebar(
		array(
			'name'          => esc_html__( 'Homepage Top', 'kha-solar-theme-pro' ),
			'id'            => 'homepage-top',
			'description'   => esc_html__( 'Add widgets here to appear at the top of homepage.', 'kha-solar-theme-pro' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Homepage Bottom', 'kha-solar-theme-pro' ),
			'id'            => 'homepage-bottom',
			'description'   => esc_html__( 'Add widgets here to appear at the bottom of homepage.', 'kha-solar-theme-pro' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'khasolar_pro_widgets_init' );

/**
 * Enqueue Scripts and Styles
 */
function khasolar_pro_scripts() {
	// Google Fonts
	$google_fonts_url = khasolar_pro_get_google_fonts_url();
	if ( ! empty( $google_fonts_url ) ) {
		wp_enqueue_style( 'khasolar-pro-google-fonts', $google_fonts_url, array(), null );
	}

	// Main stylesheet
	wp_enqueue_style( 'khasolar-pro-style', get_stylesheet_uri(), array(), KHASOLAR_PRO_VERSION );

	// Compiled CSS
	wp_enqueue_style( 'khasolar-pro-main', KHASOLAR_PRO_URI . '/assets/css/main.css', array(), KHASOLAR_PRO_VERSION );

	// Swiper for sliders
	wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0' );
	wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true );

	// Navigation script
	wp_enqueue_script( 'khasolar-pro-navigation', KHASOLAR_PRO_URI . '/assets/js/navigation.js', array( 'jquery' ), KHASOLAR_PRO_VERSION, true );

	// Main theme script
	wp_enqueue_script( 'khasolar-pro-main', KHASOLAR_PRO_URI . '/assets/js/main.js', array( 'jquery' ), KHASOLAR_PRO_VERSION, true );

	// Slider script
	wp_enqueue_script( 'khasolar-pro-slider', KHASOLAR_PRO_URI . '/assets/js/slider.js', array( 'jquery', 'swiper' ), KHASOLAR_PRO_VERSION, true );

	// Localize script
	wp_localize_script(
		'khasolar-pro-main',
		'khasolarPro',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'khasolar_pro_nonce' ),
		)
	);

	// Comment reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'khasolar_pro_scripts' );

/**
 * Enqueue Admin Scripts and Styles
 */
function khasolar_pro_admin_scripts( $hook ) {
	// Only load on our admin page
	if ( 'toplevel_page_khasolar-pro-settings' !== $hook && 'appearance_page_khasolar-pro-settings' !== $hook ) {
		return;
	}

	// Color Picker
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	// Media Uploader
	wp_enqueue_media();

	// Admin CSS
	wp_enqueue_style( 'khasolar-pro-admin', KHASOLAR_PRO_URI . '/assets/css/admin-panel.css', array(), KHASOLAR_PRO_VERSION );

	// Admin JS
	wp_enqueue_script( 'khasolar-pro-admin', KHASOLAR_PRO_URI . '/assets/js/admin-panel.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ), KHASOLAR_PRO_VERSION, true );

	wp_localize_script(
		'khasolar-pro-admin',
		'khasolarProAdmin',
		array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'khasolar_pro_admin_nonce' ),
			'selectImage'   => esc_html__( 'Select Image', 'kha-solar-theme-pro' ),
			'useImage'      => esc_html__( 'Use Image', 'kha-solar-theme-pro' ),
			'removeImage'   => esc_html__( 'Remove Image', 'kha-solar-theme-pro' ),
			'confirmExport' => esc_html__( 'Are you sure you want to export settings?', 'kha-solar-theme-pro' ),
			'confirmImport' => esc_html__( 'Are you sure you want to import settings? This will overwrite current settings.', 'kha-solar-theme-pro' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'khasolar_pro_admin_scripts' );

/**
 * Get Google Fonts URL
 */
function khasolar_pro_get_google_fonts_url() {
	$body_font    = get_theme_mod( 'khasolar_pro_body_font', 'Open Sans' );
	$heading_font = get_theme_mod( 'khasolar_pro_heading_font', 'Poppins' );

	$fonts = array();

	if ( ! empty( $body_font ) && 'System Default' !== $body_font ) {
		$fonts[] = $body_font . ':300,400,500,600,700';
	}

	if ( ! empty( $heading_font ) && 'System Default' !== $heading_font && $heading_font !== $body_font ) {
		$fonts[] = $heading_font . ':300,400,500,600,700,800';
	}

	if ( empty( $fonts ) ) {
		return '';
	}

	$query_args = array(
		'family'  => implode( '|', $fonts ),
		'subset'  => 'latin,latin-ext,vietnamese',
		'display' => 'swap',
	);

	return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
}

/**
 * Include Required Files
 */
require_once KHASOLAR_PRO_INC . '/admin/class-admin-panel.php';
require_once KHASOLAR_PRO_INC . '/customizer/customizer.php';
require_once KHASOLAR_PRO_INC . '/template-tags.php';
require_once KHASOLAR_PRO_INC . '/template-functions.php';
require_once KHASOLAR_PRO_INC . '/breadcrumbs.php';
require_once KHASOLAR_PRO_INC . '/pagination.php';

// Load custom widgets
require_once KHASOLAR_PRO_INC . '/widgets/class-recent-products-widget.php';
require_once KHASOLAR_PRO_INC . '/widgets/class-product-categories-widget.php';
require_once KHASOLAR_PRO_INC . '/widgets/class-contact-info-widget.php';
require_once KHASOLAR_PRO_INC . '/widgets/class-social-links-widget.php';

/**
 * Initialize Admin Panel
 */
if ( is_admin() ) {
	$khasolar_pro_admin = new Khasolar_Pro_Admin_Panel();
}

/**
 * Get Theme Option
 *
 * @param string $key Option key.
 * @param mixed  $default Default value.
 * @return mixed Option value.
 */
function khasolar_pro_get_option( $key, $default = '' ) {
	$options = get_option( 'khasolar_pro_options', array() );
	return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

/**
 * Get Header Layout
 */
function khasolar_pro_get_header_layout() {
	return khasolar_pro_get_option( 'header_layout', 'layout-1' );
}

/**
 * Get Footer Layout
 */
function khasolar_pro_get_footer_layout() {
	return khasolar_pro_get_option( 'footer_columns', '4' );
}

/**
 * Check if Homepage Builder is enabled
 */
function khasolar_pro_is_homepage_builder_enabled() {
	return (bool) khasolar_pro_get_option( 'enable_homepage_builder', true );
}

/**
 * Get Enabled Homepage Sections
 */
function khasolar_pro_get_homepage_sections() {
	$default_sections = array(
		'hero'         => true,
		'features'     => true,
		'products'     => true,
		'stats'        => true,
		'testimonials' => true,
		'cta'          => true,
		'about'        => false,
		'blog'         => true,
	);

	$enabled_sections = khasolar_pro_get_option( 'homepage_sections', $default_sections );
	$sections_order   = khasolar_pro_get_option( 'homepage_sections_order', array_keys( $default_sections ) );

	$ordered_sections = array();
	foreach ( $sections_order as $section ) {
		if ( isset( $enabled_sections[ $section ] ) && $enabled_sections[ $section ] ) {
			$ordered_sections[] = $section;
		}
	}

	return $ordered_sections;
}

/**
 * Custom Excerpt Length
 */
function khasolar_pro_excerpt_length( $length ) {
	return khasolar_pro_get_option( 'excerpt_length', 30 );
}
add_filter( 'excerpt_length', 'khasolar_pro_excerpt_length', 999 );

/**
 * Custom Excerpt More
 */
function khasolar_pro_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'khasolar_pro_excerpt_more' );

/**
 * Body Classes
 */
function khasolar_pro_body_classes( $classes ) {
	// Header layout
	$header_layout = khasolar_pro_get_header_layout();
	$classes[]     = 'header-' . $header_layout;

	// Sticky header
	if ( khasolar_pro_get_option( 'enable_sticky_header', true ) ) {
		$classes[] = 'has-sticky-header';
	}

	// Sidebar layout
	if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'page-templates/fullwidth.php' ) ) {
		$classes[] = 'has-sidebar';
	} else {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'khasolar_pro_body_classes' );

/**
 * Add custom CSS from theme options
 */
function khasolar_pro_custom_css() {
	$custom_css = khasolar_pro_get_option( 'custom_css', '' );

	if ( ! empty( $custom_css ) ) {
		echo '<style type="text/css">' . wp_strip_all_tags( $custom_css ) . '</style>';
	}

	// Dynamic colors
	$primary_color   = khasolar_pro_get_option( 'primary_color', '#0066cc' );
	$secondary_color = khasolar_pro_get_option( 'secondary_color', '#6c757d' );
	$accent_color    = khasolar_pro_get_option( 'accent_color', '#28a745' );

	$css = "
		:root {
			--color-primary: {$primary_color};
			--color-secondary: {$secondary_color};
			--color-accent: {$accent_color};
		}
	";

	echo '<style type="text/css">' . $css . '</style>';
}
add_action( 'wp_head', 'khasolar_pro_custom_css' );

/**
 * Add custom JS from theme options
 */
function khasolar_pro_custom_js() {
	// Header scripts
	$header_scripts = khasolar_pro_get_option( 'header_scripts', '' );
	if ( ! empty( $header_scripts ) ) {
		echo $header_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'khasolar_pro_custom_js' );

/**
 * Add custom footer scripts
 */
function khasolar_pro_footer_scripts() {
	$footer_scripts = khasolar_pro_get_option( 'footer_scripts', '' );
	if ( ! empty( $footer_scripts ) ) {
		echo $footer_scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_footer', 'khasolar_pro_footer_scripts' );

/**
 * Performance Optimizations
 */
if ( khasolar_pro_get_option( 'enable_lazy_loading', true ) ) {
	/**
	 * Add lazy loading to images
	 */
	function khasolar_pro_add_lazy_loading( $content ) {
		if ( is_admin() || is_feed() ) {
			return $content;
		}

		return str_replace( '<img', '<img loading="lazy"', $content );
	}
	add_filter( 'the_content', 'khasolar_pro_add_lazy_loading', 99 );
	add_filter( 'post_thumbnail_html', 'khasolar_pro_add_lazy_loading', 99 );
}

if ( khasolar_pro_get_option( 'disable_emojis', false ) ) {
	/**
	 * Disable WordPress emojis
	 */
	function khasolar_pro_disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}
	add_action( 'init', 'khasolar_pro_disable_emojis' );
}

if ( khasolar_pro_get_option( 'remove_query_strings', false ) ) {
	/**
	 * Remove query strings from static resources
	 */
	function khasolar_pro_remove_query_strings( $src ) {
		if ( strpos( $src, '?ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}
	add_filter( 'script_loader_src', 'khasolar_pro_remove_query_strings', 15, 1 );
	add_filter( 'style_loader_src', 'khasolar_pro_remove_query_strings', 15, 1 );
}
