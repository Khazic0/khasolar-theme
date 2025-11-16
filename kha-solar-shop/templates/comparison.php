<?php
/**
 * Product Comparison Template
 *
 * @package KhaSolar
 * @since   1.0.0
 */

// Get product IDs from localStorage via JavaScript
?>

<div class="kha-comparison-wrapper">
	<div class="kha-comparison-header">
		<h2><?php esc_html_e( 'Product Comparison', 'kha-solar' ); ?></h2>
		<button class="kha-clear-comparison"><?php esc_html_e( 'Clear All', 'kha-solar' ); ?></button>
	</div>

	<div id="kha-comparison-content">
		<div class="kha-comparison-empty">
			<div class="kha-comparison-empty-icon">⚖️</div>
			<h3><?php esc_html_e( 'No products to compare', 'kha-solar' ); ?></h3>
			<p><?php esc_html_e( 'Add products to comparison from the shop page', 'kha-solar' ); ?></p>
			<a href="<?php echo esc_url( kha_solar_get_page_url( 'shop' ) ); ?>" class="kha-add-products-btn">
				<?php esc_html_e( 'Browse Products', 'kha-solar' ); ?>
			</a>
		</div>
	</div>
</div>

<script>
jQuery(document).ready(function($) {
	var comparisonProducts = JSON.parse(localStorage.getItem('kha_comparison') || '[]');

	if (comparisonProducts.length > 0) {
		loadComparisonTable(comparisonProducts);
	}

	function loadComparisonTable(productIds) {
		// Build comparison table
		var table = '<div class="kha-comparison-table-wrapper">';
		table += '<table class="kha-comparison-table">';
		table += '<thead><tr>';
		table += '<th style="text-align: left;"><?php esc_html_e( 'Compare', 'kha-solar' ); ?></th>';

		// This would need AJAX to load actual product data
		productIds.forEach(function(productId) {
			table += '<th>Product ' + productId + '</th>';
		});

		table += '</tr></thead>';
		table += '<tbody>';

		// Add comparison rows (this is a simplified version)
		var specs = [
			{label: '<?php esc_html_e( 'Price', 'kha-solar' ); ?>', key: 'price'},
			{label: '<?php esc_html_e( 'Power', 'kha-solar' ); ?>', key: 'power'},
			{label: '<?php esc_html_e( 'Efficiency', 'kha-solar' ); ?>', key: 'efficiency'},
			{label: '<?php esc_html_e( 'Warranty', 'kha-solar' ); ?>', key: 'warranty'}
		];

		specs.forEach(function(spec) {
			table += '<tr>';
			table += '<td><strong>' + spec.label + '</strong></td>';
			productIds.forEach(function() {
				table += '<td>-</td>';
			});
			table += '</tr>';
		});

		table += '</tbody>';
		table += '</table>';
		table += '</div>';

		$('#kha-comparison-content').html(table);
	}
});
</script>
