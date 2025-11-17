<?php
/**
 * The header template file
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'kha-solar-theme-pro' ); ?></a>

	<?php
	// Get header layout
	$header_layout = khasolar_pro_get_header_layout();
	get_template_part( 'template-parts/header/header', $header_layout );
	?>

	<div id="content" class="site-content">
