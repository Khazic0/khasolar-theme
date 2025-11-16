/**
 * Kha Solar Shop - Search Autocomplete
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	let searchTimeout;

	$(document).ready(function() {

		const $searchInput = $('.kha-search-input');
		const $searchButton = $('.kha-search-button');
		const $autocomplete = $('<div class="kha-search-autocomplete"></div>');

		// Add autocomplete container
		$searchInput.closest('.kha-search-bar').append($autocomplete);

		// Search on input
		$searchInput.on('input', function() {
			const searchTerm = $(this).val().trim();

			clearTimeout(searchTimeout);

			if (searchTerm.length < 2) {
				$autocomplete.removeClass('active').html('');
				return;
			}

			searchTimeout = setTimeout(function() {
				searchProducts(searchTerm);
			}, 300);
		});

		// Search products via AJAX
		function searchProducts(searchTerm) {
			$.ajax({
				url: khaSolar.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_search_products',
					nonce: khaSolar.nonce,
					search: searchTerm
				},
				beforeSend: function() {
					$autocomplete.html('<div class="kha-loading"><div class="kha-spinner"></div></div>').addClass('active');
				},
				success: function(response) {
					if (response.success && response.data.products.length > 0) {
						displayResults(response.data.products);
					} else {
						$autocomplete.html('<div style="padding: 20px; text-align: center; color: #999;">No products found</div>');
					}
				},
				error: function() {
					$autocomplete.html('<div style="padding: 20px; text-align: center; color: #dc3545;">Error loading results</div>');
				}
			});
		}

		// Display search results
		function displayResults(products) {
			let html = '';

			products.forEach(function(product) {
				html += '<a href="' + product.url + '" class="kha-search-result">';
				if (product.image) {
					html += '<img src="' + product.image + '" alt="' + product.title + '" class="kha-search-result-image">';
				} else {
					html += '<div class="kha-search-result-image" style="background: #f0f0f0;"></div>';
				}
				html += '<div class="kha-search-result-info">';
				html += '<div class="kha-search-result-title">' + product.title + '</div>';
				if (product.price) {
					html += '<div class="kha-search-result-price">' + product.price + '</div>';
				}
				html += '</div>';
				html += '</a>';
			});

			$autocomplete.html(html);
		}

		// Close autocomplete when clicking outside
		$(document).on('click', function(e) {
			if (!$(e.target).closest('.kha-search-bar').length) {
				$autocomplete.removeClass('active');
			}
		});

		// Show autocomplete when focusing on input
		$searchInput.on('focus', function() {
			if ($autocomplete.html() !== '') {
				$autocomplete.addClass('active');
			}
		});

	});

})(jQuery);
