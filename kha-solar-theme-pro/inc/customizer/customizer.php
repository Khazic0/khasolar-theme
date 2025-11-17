<?php
/**
 * Customizer functionality
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function khasolar_pro_customize_register( $wp_customize ) {

	// Remove default sections we don't need
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'background_image' );

	// Theme Options Panel
	$wp_customize->add_panel(
		'khasolar_pro_options',
		array(
			'title'       => __( 'Theme Options', 'kha-solar-theme-pro' ),
			'description' => __( 'Customize theme settings', 'kha-solar-theme-pro' ),
			'priority'    => 30,
		)
	);

	// Layout Section
	$wp_customize->add_section(
		'khasolar_pro_layout',
		array(
			'title'    => __( 'Layout', 'kha-solar-theme-pro' ),
			'panel'    => 'khasolar_pro_options',
			'priority' => 10,
		)
	);

	// Container Width
	$wp_customize->add_setting(
		'khasolar_pro_container_width',
		array(
			'default'           => 1200,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'khasolar_pro_container_width',
		array(
			'label'       => __( 'Container Width (px)', 'kha-solar-theme-pro' ),
			'section'     => 'khasolar_pro_layout',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 960,
				'max'  => 1920,
				'step' => 10,
			),
		)
	);

	// Sidebar Position
	$wp_customize->add_setting(
		'khasolar_pro_sidebar_position',
		array(
			'default'           => 'right',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'khasolar_pro_sidebar_position',
		array(
			'label'   => __( 'Sidebar Position', 'kha-solar-theme-pro' ),
			'section' => 'khasolar_pro_layout',
			'type'    => 'radio',
			'choices' => array(
				'left'  => __( 'Left', 'kha-solar-theme-pro' ),
				'right' => __( 'Right', 'kha-solar-theme-pro' ),
			),
		)
	);

	// Blog Section
	$wp_customize->add_section(
		'khasolar_pro_blog',
		array(
			'title'    => __( 'Blog', 'kha-solar-theme-pro' ),
			'panel'    => 'khasolar_pro_options',
			'priority' => 20,
		)
	);

	// Excerpt Length
	$wp_customize->add_setting(
		'khasolar_pro_excerpt_length',
		array(
			'default'           => 30,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'khasolar_pro_excerpt_length',
		array(
			'label'       => __( 'Excerpt Length (words)', 'kha-solar-theme-pro' ),
			'section'     => 'khasolar_pro_blog',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 10,
				'max'  => 100,
				'step' => 5,
			),
		)
	);

	// Show Featured Image on Posts
	$wp_customize->add_setting(
		'khasolar_pro_show_featured_image',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'khasolar_pro_show_featured_image',
		array(
			'label'   => __( 'Show Featured Image on Single Posts', 'kha-solar-theme-pro' ),
			'section' => 'khasolar_pro_blog',
			'type'    => 'checkbox',
		)
	);

	// Show Post Meta
	$wp_customize->add_setting(
		'khasolar_pro_show_post_meta',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'khasolar_pro_show_post_meta',
		array(
			'label'   => __( 'Show Post Meta (Date, Author)', 'kha-solar-theme-pro' ),
			'section' => 'khasolar_pro_blog',
			'type'    => 'checkbox',
		)
	);

	// Social Links Section
	$wp_customize->add_section(
		'khasolar_pro_social',
		array(
			'title'    => __( 'Social Links', 'kha-solar-theme-pro' ),
			'panel'    => 'khasolar_pro_options',
			'priority' => 30,
		)
	);

	$social_networks = array(
		'facebook'  => __( 'Facebook URL', 'kha-solar-theme-pro' ),
		'twitter'   => __( 'Twitter URL', 'kha-solar-theme-pro' ),
		'instagram' => __( 'Instagram URL', 'kha-solar-theme-pro' ),
		'linkedin'  => __( 'LinkedIn URL', 'kha-solar-theme-pro' ),
		'youtube'   => __( 'YouTube URL', 'kha-solar-theme-pro' ),
	);

	foreach ( $social_networks as $network => $label ) {
		$wp_customize->add_setting(
			'khasolar_pro_social_' . $network,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'khasolar_pro_social_' . $network,
			array(
				'label'   => $label,
				'section' => 'khasolar_pro_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'khasolar_pro_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously
 */
function khasolar_pro_customize_preview_js() {
	wp_enqueue_script(
		'khasolar-pro-customizer-preview',
		KHASOLAR_PRO_URI . '/assets/js/customizer-preview.js',
		array( 'customize-preview' ),
		KHASOLAR_PRO_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'khasolar_pro_customize_preview_js' );
