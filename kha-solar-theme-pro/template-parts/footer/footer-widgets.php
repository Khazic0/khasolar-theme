<?php
/**
 * Footer Widgets Area
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

$footer_columns = khasolar_pro_get_footer_layout();
$has_widgets    = false;

for ( $i = 1; $i <= $footer_columns; $i++ ) {
	if ( is_active_sidebar( 'footer-' . $i ) ) {
		$has_widgets = true;
		break;
	}
}

if ( ! $has_widgets ) {
	return;
}

$column_class = '';
switch ( $footer_columns ) {
	case '2':
		$column_class = 'col-md-6';
		break;
	case '3':
		$column_class = 'col-md-4';
		break;
	case '4':
	default:
		$column_class = 'col-md-3';
		break;
}
?>

<div class="footer-widgets">
	<div class="container">
		<div class="row">
			<?php for ( $i = 1; $i <= $footer_columns; $i++ ) : ?>
				<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
					<div class="footer-column <?php echo esc_attr( $column_class ); ?>">
						<?php dynamic_sidebar( 'footer-' . $i ); ?>
					</div>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	</div>
</div>
