/**
 * Kha Solar Shop - Product Comparison
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	let comparisonProducts = JSON.parse(localStorage.getItem('kha_comparison') || '[]');

	$(document).ready(function() {

		updateComparisonCounter();

		// Add to comparison
		$(document).on('change', '.kha-compare-checkbox', function() {
			const productId = $(this).data('product-id');

			if ($(this).is(':checked')) {
				addToComparison(productId);
			} else {
				removeFromComparison(productId);
			}
		});

		// Remove from comparison
		$(document).on('click', '.kha-comparison-remove', function() {
			const productId = $(this).data('product-id');
			removeFromComparison(productId);
			$(this).closest('td').fadeOut(300, function() {
				location.reload();
			});
		});

		// Clear all comparisons
		$('.kha-clear-comparison').on('click', function() {
			if (confirm('Are you sure you want to clear all comparisons?')) {
				comparisonProducts = [];
				localStorage.setItem('kha_comparison', JSON.stringify(comparisonProducts));
				location.reload();
			}
		});

		// View comparison
		$('.kha-view-comparison-btn').on('click', function() {
			window.location.href = khaSolar.comparisonUrl || '/comparison/';
		});

	});

	function addToComparison(productId) {
		if (!comparisonProducts.includes(productId)) {
			if (comparisonProducts.length >= 4) {
				alert('You can compare up to 4 products at a time');
				$('.kha-compare-checkbox[data-product-id="' + productId + '"]').prop('checked', false);
				return;
			}
			comparisonProducts.push(productId);
			localStorage.setItem('kha_comparison', JSON.stringify(comparisonProducts));
			updateComparisonCounter();
		}
	}

	function removeFromComparison(productId) {
		comparisonProducts = comparisonProducts.filter(id => id !== productId);
		localStorage.setItem('kha_comparison', JSON.stringify(comparisonProducts));
		updateComparisonCounter();
		$('.kha-compare-checkbox[data-product-id="' + productId + '"]').prop('checked', false);
	}

	function updateComparisonCounter() {
		const count = comparisonProducts.length;
		const $counter = $('.kha-comparison-counter');

		if (count > 0) {
			$counter.addClass('active');
			$counter.find('.kha-comparison-count').text(count + ' Products');
		} else {
			$counter.removeClass('active');
		}

		// Update checkboxes
		comparisonProducts.forEach(function(productId) {
			$('.kha-compare-checkbox[data-product-id="' + productId + '"]').prop('checked', true);
		});
	}

})(jQuery);
