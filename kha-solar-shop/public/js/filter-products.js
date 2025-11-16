/**
 * Kha Solar Shop - Product Filter
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		// Category filter
		$('.kha-filter-category').on('change', function() {
			const categoryId = $(this).val();
			window.location.href = updateQueryStringParameter(window.location.href, 'category', categoryId);
		});

		// Sort filter
		$('.kha-filter-sort').on('change', function() {
			const sortBy = $(this).val();
			window.location.href = updateQueryStringParameter(window.location.href, 'orderby', sortBy);
		});

		// Price filter
		$('.kha-filter-price').on('change', function() {
			const priceRange = $(this).val();
			window.location.href = updateQueryStringParameter(window.location.href, 'price', priceRange);
		});

		// Helper function to update query string
		function updateQueryStringParameter(uri, key, value) {
			const re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
			const separator = uri.indexOf('?') !== -1 ? "&" : "?";

			if (uri.match(re)) {
				return uri.replace(re, '$1' + key + "=" + value + '$2');
			} else {
				return uri + separator + key + "=" + value;
			}
		}

	});

})(jQuery);
