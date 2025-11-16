/**
 * Kha Solar Shop - Product Filtering
 *
 * Dynamic AJAX-powered product filtering with no page reload
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	var filterState = {
		search: '',
		priceMin: 0,
		priceMax: 0,
		categories: [],
		brands: [],
		powerRanges: [],
		stockStatus: [],
		onSale: false,
		isNew: false,
		orderby: 'date',
		paged: 1
	};

	var isFiltering = false;
	var priceSlider = null;

	/**
	 * Initialize filtering system.
	 */
	function init() {
		if ($('.kha-filter-sidebar').length === 0) {
			return;
		}

		initPriceSlider();
		initFilterCheckboxes();
		initQuickSearch();
		initSortDropdown();
		initPagination();
		initResetButton();
		initMobileFilters();
		initURLParams();
		
		// Load filters from URL on page load
		loadFiltersFromURL();
	}

	/**
	 * Initialize price range slider.
	 */
	function initPriceSlider() {
		var priceRange = $('.kha-price-range-slider');
		
		if (priceRange.length === 0) {
			return;
		}

		var minPrice = parseInt(priceRange.data('min')) || 0;
		var maxPrice = parseInt(priceRange.data('max')) || 100000000;

		// Create slider HTML
		var sliderHtml = '<div class="kha-price-slider" id="kha-price-slider"></div>';
		sliderHtml += '<div class="kha-price-values">';
		sliderHtml += '<span class="kha-price-min-display">' + formatPrice(minPrice) + '</span>';
		sliderHtml += '<span class="kha-price-max-display">' + formatPrice(maxPrice) + '</span>';
		sliderHtml += '</div>';
		
		priceRange.html(sliderHtml);

		// Initialize custom range slider
		var slider = $('#kha-price-slider');
		var minHandle = $('<div class="kha-slider-handle kha-slider-min"></div>');
		var maxHandle = $('<div class="kha-slider-handle kha-slider-max"></div>');
		var range = $('<div class="kha-slider-range"></div>');
		
		slider.append(range);
		slider.append(minHandle);
		slider.append(maxHandle);

		var isDraggingMin = false;
		var isDraggingMax = false;

		// Min handle drag
		minHandle.on('mousedown touchstart', function(e) {
			e.preventDefault();
			isDraggingMin = true;
		});

		// Max handle drag
		maxHandle.on('mousedown touchstart', function(e) {
			e.preventDefault();
			isDraggingMax = true;
		});

		$(document).on('mousemove touchmove', function(e) {
			if (!isDraggingMin && !isDraggingMax) return;

			var sliderOffset = slider.offset().left;
			var sliderWidth = slider.width();
			var mouseX = (e.pageX || e.originalEvent.touches[0].pageX) - sliderOffset;
			var percent = Math.max(0, Math.min(100, (mouseX / sliderWidth) * 100));

			if (isDraggingMin) {
				var maxPercent = parseFloat(maxHandle.css('left')) || 100;
				if (percent < maxPercent) {
					minHandle.css('left', percent + '%');
					updatePriceRange();
				}
			} else if (isDraggingMax) {
				var minPercent = parseFloat(minHandle.css('left')) || 0;
				if (percent > minPercent) {
					maxHandle.css('left', percent + '%');
					updatePriceRange();
				}
			}
		});

		$(document).on('mouseup touchend', function() {
			if (isDraggingMin || isDraggingMax) {
				isDraggingMin = false;
				isDraggingMax = false;
				applyFilters();
			}
		});

		function updatePriceRange() {
			var minPercent = parseFloat(minHandle.css('left')) || 0;
			var maxPercent = parseFloat(maxHandle.css('left')) || 100;

			range.css({
				'left': minPercent + '%',
				'width': (maxPercent - minPercent) + '%'
			});

			var minValue = Math.round((minPercent / 100) * maxPrice);
			var maxValue = Math.round((maxPercent / 100) * maxPrice);

			filterState.priceMin = minValue;
			filterState.priceMax = maxValue;

			$('.kha-price-min-display').text(formatPrice(minValue));
			$('.kha-price-max-display').text(formatPrice(maxValue));
		}

		// Initialize positions
		minHandle.css('left', '0%');
		maxHandle.css('left', '100%');
		updatePriceRange();
	}

	/**
	 * Initialize filter checkboxes.
	 */
	function initFilterCheckboxes() {
		// Category filters
		$('.kha-category-filter input[type="checkbox"]').on('change', function() {
			var categoryId = $(this).val();
			if ($(this).is(':checked')) {
				filterState.categories.push(categoryId);
			} else {
				filterState.categories = filterState.categories.filter(function(id) {
					return id !== categoryId;
				});
			}
			filterState.paged = 1;
			applyFilters();
		});

		// Brand filters
		$('.kha-brand-filter input[type="checkbox"]').on('change', function() {
			var brandId = $(this).val();
			if ($(this).is(':checked')) {
				filterState.brands.push(brandId);
			} else {
				filterState.brands = filterState.brands.filter(function(id) {
					return id !== brandId;
				});
			}
			filterState.paged = 1;
			applyFilters();
		});

		// Power range filters
		$('.kha-power-filter input[type="checkbox"]').on('change', function() {
			var powerRange = $(this).val();
			if ($(this).is(':checked')) {
				filterState.powerRanges.push(powerRange);
			} else {
				filterState.powerRanges = filterState.powerRanges.filter(function(range) {
					return range !== powerRange;
				});
			}
			filterState.paged = 1;
			applyFilters();
		});

		// Stock status filters
		$('.kha-stock-filter input[type="checkbox"]').on('change', function() {
			var status = $(this).val();
			
			if (status === 'on_sale') {
				filterState.onSale = $(this).is(':checked');
			} else if (status === 'is_new') {
				filterState.isNew = $(this).is(':checked');
			} else {
				if ($(this).is(':checked')) {
					filterState.stockStatus.push(status);
				} else {
					filterState.stockStatus = filterState.stockStatus.filter(function(s) {
						return s !== status;
					});
				}
			}
			filterState.paged = 1;
			applyFilters();
		});
	}

	/**
	 * Initialize quick search.
	 */
	function initQuickSearch() {
		var searchInput = $('.kha-filter-search input');
		var searchTimeout;

		searchInput.on('input', function() {
			clearTimeout(searchTimeout);
			
			var searchTerm = $(this).val();
			
			searchTimeout = setTimeout(function() {
				filterState.search = searchTerm;
				filterState.paged = 1;
				applyFilters();
			}, 500);
		});
	}

	/**
	 * Initialize sort dropdown.
	 */
	function initSortDropdown() {
		$('.kha-sort-dropdown').on('change', function() {
			filterState.orderby = $(this).val();
			filterState.paged = 1;
			applyFilters();
		});
	}

	/**
	 * Initialize pagination.
	 */
	function initPagination() {
		$(document).on('click', '.kha-pagination a.page-numbers', function(e) {
			e.preventDefault();
			
			var page = 1;
			if ($(this).hasClass('next')) {
				page = filterState.paged + 1;
			} else if ($(this).hasClass('prev')) {
				page = filterState.paged - 1;
			} else {
				page = parseInt($(this).text()) || 1;
			}
			
			filterState.paged = page;
			applyFilters();
			
			// Scroll to top of products
			$('html, body').animate({
				scrollTop: $('.kha-products-grid').offset().top - 100
			}, 300);
		});
	}

	/**
	 * Initialize reset button.
	 */
	function initResetButton() {
		$('.kha-reset-filters').on('click', function(e) {
			e.preventDefault();
			resetFilters();
		});

		// Remove individual filter pills
		$(document).on('click', '.kha-active-filter-pill .kha-remove-filter', function(e) {
			e.preventDefault();
			var filterType = $(this).parent().data('filter-type');
			var filterValue = $(this).parent().data('filter-value');
			
			removeFilter(filterType, filterValue);
		});
	}

	/**
	 * Initialize mobile filters.
	 */
	function initMobileFilters() {
		$('.kha-mobile-filter-toggle').on('click', function(e) {
			e.preventDefault();
			$('.kha-filter-sidebar').toggleClass('kha-filters-open');
			$('body').toggleClass('kha-filters-active');
		});

		$('.kha-filter-close, .kha-filter-overlay').on('click', function(e) {
			e.preventDefault();
			$('.kha-filter-sidebar').removeClass('kha-filters-open');
			$('body').removeClass('kha-filters-active');
		});
	}

	/**
	 * Initialize URL params.
	 */
	function initURLParams() {
		// Enable back/forward browser navigation
		window.addEventListener('popstate', function(e) {
			if (e.state && e.state.filters) {
				filterState = e.state.filters;
				applyFilters(false);
			}
		});
	}

	/**
	 * Load filters from URL parameters.
	 */
	function loadFiltersFromURL() {
		var urlParams = new URLSearchParams(window.location.search);
		
		if (urlParams.has('filter') && urlParams.get('filter') === '1') {
			if (urlParams.has('price_min')) {
				filterState.priceMin = parseInt(urlParams.get('price_min'));
			}
			if (urlParams.has('price_max')) {
				filterState.priceMax = parseInt(urlParams.get('price_max'));
			}
			if (urlParams.has('categories')) {
				filterState.categories = urlParams.get('categories').split(',');
			}
			if (urlParams.has('brands')) {
				filterState.brands = urlParams.get('brands').split(',');
			}
			if (urlParams.has('orderby')) {
				filterState.orderby = urlParams.get('orderby');
			}
			
			// Apply filters without adding to history
			applyFilters(false);
		}
	}

	/**
	 * Apply filters with AJAX.
	 */
	function applyFilters(updateURL = true) {
		if (isFiltering) {
			return;
		}

		isFiltering = true;
		showLoadingSkeleton();

		$.ajax({
			url: khaFilterConfig.ajaxUrl,
			type: 'POST',
			data: {
				action: 'kha_filter_products',
				nonce: khaFilterConfig.nonce,
				filters: filterState
			},
			success: function(response) {
				if (response.success) {
					updateProductGrid(response.data);
					updateActiveFilters();
					updateProductCount(response.data.found_posts);
					
					if (updateURL) {
						updateURLParams();
					}
				}
				isFiltering = false;
			},
			error: function() {
				isFiltering = false;
				hideLoadingSkeleton();
				showError();
			}
		});
	}

	/**
	 * Update product grid with new results.
	 */
	function updateProductGrid(data) {
		$('.kha-products-grid').html(data.products);
		$('.kha-pagination-wrapper').html(data.pagination);
		hideLoadingSkeleton();
		
		// Reinitialize lazy loading if needed
		if (typeof initLazyLoading === 'function') {
			initLazyLoading();
		}
	}

	/**
	 * Show loading skeleton.
	 */
	function showLoadingSkeleton() {
		var skeletonHtml = '';
		for (var i = 0; i < 8; i++) {
			skeletonHtml += '<div class="kha-product-skeleton">';
			skeletonHtml += '<div class="kha-skeleton-image"></div>';
			skeletonHtml += '<div class="kha-skeleton-title"></div>';
			skeletonHtml += '<div class="kha-skeleton-price"></div>';
			skeletonHtml += '</div>';
		}
		
		$('.kha-products-grid').html(skeletonHtml);
		$('.kha-products-grid').addClass('kha-loading');
	}

	/**
	 * Hide loading skeleton.
	 */
	function hideLoadingSkeleton() {
		$('.kha-products-grid').removeClass('kha-loading');
	}

	/**
	 * Update active filters display.
	 */
	function updateActiveFilters() {
		var filtersHtml = '';
		var hasFilters = false;

		// Price filter
		if (filterState.priceMin > 0 || filterState.priceMax > 0) {
			hasFilters = true;
			filtersHtml += '<span class="kha-active-filter-pill" data-filter-type="price">';
			filtersHtml += 'Giá: ' + formatPrice(filterState.priceMin) + ' - ' + formatPrice(filterState.priceMax);
			filtersHtml += '<button class="kha-remove-filter">×</button>';
			filtersHtml += '</span>';
		}

		// Category filters
		filterState.categories.forEach(function(catId) {
			hasFilters = true;
			var catName = $('.kha-category-filter input[value="' + catId + '"]').parent().text().trim();
			filtersHtml += '<span class="kha-active-filter-pill" data-filter-type="category" data-filter-value="' + catId + '">';
			filtersHtml += catName;
			filtersHtml += '<button class="kha-remove-filter">×</button>';
			filtersHtml += '</span>';
		});

		// Brand filters
		filterState.brands.forEach(function(brandId) {
			hasFilters = true;
			var brandName = $('.kha-brand-filter input[value="' + brandId + '"]').parent().text().trim();
			filtersHtml += '<span class="kha-active-filter-pill" data-filter-type="brand" data-filter-value="' + brandId + '">';
			filtersHtml += brandName;
			filtersHtml += '<button class="kha-remove-filter">×</button>';
			filtersHtml += '</span>';
		});

		if (hasFilters) {
			$('.kha-active-filters').html('<strong>Đang lọc:</strong> ' + filtersHtml).show();
		} else {
			$('.kha-active-filters').hide();
		}
	}

	/**
	 * Update product count display.
	 */
	function updateProductCount(count) {
		$('.kha-product-count').text('Tìm thấy ' + count + ' sản phẩm');
	}

	/**
	 * Update URL parameters without reload.
	 */
	function updateURLParams() {
		var params = new URLSearchParams();
		params.set('filter', '1');

		if (filterState.priceMin > 0) {
			params.set('price_min', filterState.priceMin);
		}
		if (filterState.priceMax > 0) {
			params.set('price_max', filterState.priceMax);
		}
		if (filterState.categories.length > 0) {
			params.set('categories', filterState.categories.join(','));
		}
		if (filterState.brands.length > 0) {
			params.set('brands', filterState.brands.join(','));
		}
		if (filterState.orderby !== 'date') {
			params.set('orderby', filterState.orderby);
		}
		if (filterState.paged > 1) {
			params.set('paged', filterState.paged);
		}

		var newURL = window.location.pathname + '?' + params.toString();
		
		// Update browser history
		history.pushState({ filters: filterState }, '', newURL);
	}

	/**
	 * Remove specific filter.
	 */
	function removeFilter(filterType, filterValue) {
		if (filterType === 'price') {
			filterState.priceMin = 0;
			filterState.priceMax = 0;
		} else if (filterType === 'category') {
			filterState.categories = filterState.categories.filter(function(id) {
				return id !== filterValue;
			});
			$('.kha-category-filter input[value="' + filterValue + '"]').prop('checked', false);
		} else if (filterType === 'brand') {
			filterState.brands = filterState.brands.filter(function(id) {
				return id !== filterValue;
			});
			$('.kha-brand-filter input[value="' + filterValue + '"]').prop('checked', false);
		}

		filterState.paged = 1;
		applyFilters();
	}

	/**
	 * Reset all filters.
	 */
	function resetFilters() {
		filterState = {
			search: '',
			priceMin: 0,
			priceMax: 0,
			categories: [],
			brands: [],
			powerRanges: [],
			stockStatus: [],
			onSale: false,
			isNew: false,
			orderby: 'date',
			paged: 1
		};

		// Reset UI
		$('.kha-filter-sidebar input[type="checkbox"]').prop('checked', false);
		$('.kha-filter-search input').val('');
		$('.kha-sort-dropdown').val('date');
		
		// Reset price slider
		$('.kha-slider-min').css('left', '0%');
		$('.kha-slider-max').css('left', '100%');
		$('.kha-slider-range').css({'left': '0%', 'width': '100%'});

		applyFilters();
	}

	/**
	 * Format price for display.
	 */
	function formatPrice(price) {
		if (price === 0) return '0₫';
		
		var formatted = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		return formatted + '₫';
	}

	/**
	 * Show error message.
	 */
	function showError() {
		var errorHtml = '<div class="kha-filter-error">';
		errorHtml += '<p>Đã xảy ra lỗi khi tải sản phẩm. Vui lòng thử lại.</p>';
		errorHtml += '</div>';
		
		$('.kha-products-grid').html(errorHtml);
	}

	// Initialize on document ready
	$(document).ready(function() {
		init();
	});

})(jQuery);
