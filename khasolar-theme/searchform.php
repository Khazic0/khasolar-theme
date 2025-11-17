<?php
/**
 * Search form template
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Tìm kiếm:', 'label', 'khasolar-theme' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Tìm kiếm&hellip;', 'placeholder', 'khasolar-theme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="search-submit">
		<span class="dashicons dashicons-search"></span>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Tìm kiếm', 'submit button', 'khasolar-theme' ); ?></span>
	</button>
</form>
