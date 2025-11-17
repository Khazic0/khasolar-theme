<?php
/**
 * The sidebar template file
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="sidebar col" style="flex: 0 0 300px;">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
