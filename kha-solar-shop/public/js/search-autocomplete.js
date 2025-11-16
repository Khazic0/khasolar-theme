/**
 * Kha Solar Shop - Search Autocomplete
 *
 * AJAX-powered autocomplete search similar to Shopee
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	var searchInput = $('#kha-search-input');
	var searchResults = $('#kha-search-results');
	var searchForm = $('.kha-search-form');
	var searchTimeout = null;
	var currentResults = [];
	var selectedIndex = -1;

	/**
	 * Initialize autocomplete functionality.
	 */
	function init() {
		if (searchInput.length === 0) {
			return;
		}

		// Bind events
		searchInput.on('input', handleInput);
		searchInput.on('focus', handleFocus);
		searchInput.on('keydown', handleKeydown);
		
		// Close dropdown when clicking outside
		$(document).on('click', function(e) {
			if (!$(e.target).closest('.kha-search-box').length) {
				hideResults();
			}
		});

		// Handle result clicks
		searchResults.on('click', '.kha-search-result-item', function(e) {
			e.preventDefault();
			window.location.href = $(this).data('url');
		});

		// Handle category clicks
		searchResults.on('click', '.kha-category-result', function(e) {
			e.preventDefault();
			window.location.href = $(this).data('url');
		});

		// Handle popular search clicks
		searchResults.on('click', '.kha-popular-search', function(e) {
			e.preventDefault();
			searchInput.val($(this).text());
			searchForm.submit();
		});

		// Handle "View all results" click
		searchResults.on('click', '.kha-view-all-btn', function(e) {
			e.preventDefault();
			searchForm.submit();
		});
	}

	/**
	 * Handle input event with debouncing.
	 */
	function handleInput() {
		var searchTerm = searchInput.val().trim();

		// Clear previous timeout
		clearTimeout(searchTimeout);

		if (searchTerm.length < 2) {
			hideResults();
			return;
		}

		// Show loading state
		showLoading();

		// Debounce - wait 300ms after user stops typing
		searchTimeout = setTimeout(function() {
			performSearch(searchTerm);
		}, 300);
	}

	/**
	 * Handle focus event.
	 */
	function handleFocus() {
		var searchTerm = searchInput.val().trim();
		
		if (searchTerm.length >= 2 && currentResults.length > 0) {
			searchResults.show();
		}
	}

	/**
	 * Handle keyboard navigation.
	 */
	function handleKeydown(e) {
		if (!searchResults.is(':visible')) {
			return;
		}

		var items = searchResults.find('.kha-search-result-item, .kha-category-result');
		var itemCount = items.length;

		switch (e.keyCode) {
			case 38: // Arrow up
				e.preventDefault();
				selectedIndex = Math.max(-1, selectedIndex - 1);
				updateSelection(items);
				break;

			case 40: // Arrow down
				e.preventDefault();
				selectedIndex = Math.min(itemCount - 1, selectedIndex + 1);
				updateSelection(items);
				break;

			case 13: // Enter
				if (selectedIndex >= 0) {
					e.preventDefault();
					var url = items.eq(selectedIndex).data('url');
					if (url) {
						window.location.href = url;
					}
				}
				break;

			case 27: // Escape
				hideResults();
				break;
		}
	}

	/**
	 * Update visual selection for keyboard navigation.
	 */
	function updateSelection(items) {
		items.removeClass('kha-selected');
		
		if (selectedIndex >= 0) {
			items.eq(selectedIndex).addClass('kha-selected');
			
			// Scroll into view if needed
			var selectedItem = items.eq(selectedIndex);
			var container = searchResults;
			var itemTop = selectedItem.position().top;
			var containerHeight = container.height();
			
			if (itemTop < 0) {
				container.scrollTop(container.scrollTop() + itemTop);
			} else if (itemTop > containerHeight - 50) {
				container.scrollTop(container.scrollTop() + itemTop - containerHeight + 50);
			}
		}
	}

	/**
	 * Perform AJAX search.
	 */
	function performSearch(searchTerm) {
		$.ajax({
			url: khaSearchConfig.ajaxUrl,
			type: 'POST',
			data: {
				action: 'kha_autocomplete_search',
				search: searchTerm,
				nonce: khaSearchConfig.nonce
			},
			success: function(response) {
				if (response.success) {
					currentResults = response.data;
					renderResults(response.data, searchTerm);
				} else {
					showError();
				}
			},
			error: function() {
				showError();
			}
		});
	}

	/**
	 * Render search results.
	 */
	function renderResults(data, searchTerm) {
		var html = '';
		selectedIndex = -1;

		// No results
		if (data.products.length === 0 && data.categories.length === 0) {
			html += '<div class="kha-no-results">';
			html += '<div class="kha-no-results-icon">🔍</div>';
			html += '<p>' + khaSearchConfig.strings.noResults + '</p>';
			
			if (data.popular && data.popular.length > 0) {
				html += '<div class="kha-popular-searches-section">';
				html += '<p class="kha-popular-title">' + khaSearchConfig.strings.popularTitle + ':</p>';
				data.popular.forEach(function(item) {
					html += '<a href="#" class="kha-popular-search">' + escapeHtml(item.term) + '</a>';
				});
				html += '</div>';
			}
			
			html += '</div>';
			searchResults.html(html).show();
			return;
		}

		// Products section
		if (data.products.length > 0) {
			html += '<div class="kha-search-section">';
			html += '<div class="kha-section-header">';
			html += '🔍 ' + khaSearchConfig.strings.products + ' (' + data.products.length + ')';
			html += '</div>';

			data.products.forEach(function(product) {
				html += '<div class="kha-search-result-item" data-url="' + escapeHtml(product.url) + '">';
				
				if (product.image) {
					html += '<div class="kha-result-image">';
					html += '<img src="' + escapeHtml(product.image) + '" alt="" loading="lazy">';
					html += '</div>';
				} else {
					html += '<div class="kha-result-image kha-no-image">';
					html += '<span class="dashicons dashicons-camera"></span>';
					html += '</div>';
				}
				
				html += '<div class="kha-result-content">';
				html += '<div class="kha-result-title">' + product.title + '</div>';
				if (product.price) {
					html += '<div class="kha-result-price">' + escapeHtml(product.price) + '</div>';
				}
				html += '</div>';
				
				html += '</div>';
			});

			html += '</div>';
		}

		// Categories section
		if (data.categories.length > 0) {
			html += '<div class="kha-search-section kha-categories-section">';
			html += '<div class="kha-section-header">';
			html += '📁 ' + khaSearchConfig.strings.categories + ' (' + data.categories.length + ')';
			html += '</div>';

			data.categories.forEach(function(category) {
				html += '<div class="kha-category-result" data-url="' + escapeHtml(category.url) + '">';
				html += '<span class="dashicons dashicons-category"></span>';
				html += '<span>' + category.name + '</span>';
				html += '<span class="kha-category-count">(' + category.count + ')</span>';
				html += '</div>';
			});

			html += '</div>';
		}

		// Popular searches
		if (data.popular && data.popular.length > 0) {
			html += '<div class="kha-search-section kha-popular-section">';
			html += '<div class="kha-section-header">🔥 ' + khaSearchConfig.strings.popularTitle + '</div>';
			
			data.popular.forEach(function(item) {
				html += '<a href="#" class="kha-popular-search">' + escapeHtml(item.term) + '</a>';
			});
			
			html += '</div>';
		}

		// View all results button
		if (data.products.length > 0) {
			html += '<div class="kha-search-footer">';
			html += '<button type="button" class="kha-view-all-btn">';
			html += khaSearchConfig.strings.viewAll + ' (' + data.products.length + '+)';
			html += '</button>';
			html += '</div>';
		}

		searchResults.html(html).show();
	}

	/**
	 * Show loading state.
	 */
	function showLoading() {
		var html = '<div class="kha-search-loading">';
		html += '<div class="kha-spinner"></div>';
		html += '<p>' + khaSearchConfig.strings.searching + '</p>';
		html += '</div>';
		
		searchResults.html(html).show();
	}

	/**
	 * Show error state.
	 */
	function showError() {
		var html = '<div class="kha-search-error">';
		html += '<p>Đã xảy ra lỗi. Vui lòng thử lại.</p>';
		html += '</div>';
		
		searchResults.html(html).show();
	}

	/**
	 * Hide results dropdown.
	 */
	function hideResults() {
		searchResults.hide();
		selectedIndex = -1;
	}

	/**
	 * Escape HTML to prevent XSS.
	 */
	function escapeHtml(text) {
		if (!text) return '';
		
		var map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		};
		
		return String(text).replace(/[&<>"']/g, function(m) {
			return map[m];
		});
	}

	// Initialize on document ready
	$(document).ready(function() {
		init();
	});

})(jQuery);
