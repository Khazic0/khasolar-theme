/**
 * Kha Solar Shop - Product Comparison
 *
 * Handles product comparison functionality
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	var comparisonProducts = [];
	var maxProducts = 3;

	/**
	 * Initialize comparison functionality.
	 */
	function init() {
		// Get config from localized script
		if (typeof khaComparisonConfig !== 'undefined') {
			maxProducts = khaComparisonConfig.maxProducts;
		}

		// Load from localStorage
		loadFromStorage();

		// Initialize UI
		initCompareCheckboxes();
		initFloatingBar();
		initComparisonPage();
		initShareModal();

		// Update UI
		updateFloatingBar();
	}

	/**
	 * Load comparison from localStorage.
	 */
	function loadFromStorage() {
		var stored = localStorage.getItem('kha_comparison');
		if (stored) {
			try {
				comparisonProducts = JSON.parse(stored);
			} catch (e) {
				comparisonProducts = [];
			}
		}
	}

	/**
	 * Save comparison to localStorage.
	 */
	function saveToStorage() {
		localStorage.setItem('kha_comparison', JSON.stringify(comparisonProducts));
	}

	/**
	 * Initialize compare checkboxes on product cards.
	 */
	function initCompareCheckboxes() {
		// Add checkboxes to product cards if not already present
		$('.kha-product-card').each(function() {
			if ($(this).find('.kha-compare-checkbox').length === 0) {
				var productId = $(this).find('a').attr('href');
				if (productId) {
					// Extract product ID from URL
					var matches = productId.match(/\d+/);
					if (matches) {
						productId = parseInt(matches[0]);
						var isChecked = comparisonProducts.includes(productId);
						
						var checkbox = $('<label class="kha-compare-checkbox">' +
							'<input type="checkbox" value="' + productId + '"' + (isChecked ? ' checked' : '') + '> ' +
							'<span>So sánh</span>' +
							'</label>');
						
						$(this).find('.kha-product-info').append(checkbox);
					}
				}
			}
		});

		// Handle checkbox change
		$(document).on('change', '.kha-compare-checkbox input', function(e) {
			e.preventDefault();
			var productId = parseInt($(this).val());
			
			if ($(this).is(':checked')) {
				addToComparison(productId);
			} else {
				removeFromComparison(productId);
			}
		});
	}

	/**
	 * Add product to comparison.
	 */
	function addToComparison(productId) {
		if (comparisonProducts.includes(productId)) {
			return;
		}

		if (comparisonProducts.length >= maxProducts) {
			showToast(khaComparisonConfig.strings.maxReached, 'warning');
			$('.kha-compare-checkbox input[value="' + productId + '"]').prop('checked', false);
			return;
		}

		comparisonProducts.push(productId);
		saveToStorage();
		updateFloatingBar();
		showToast(khaComparisonConfig.strings.added, 'success');
	}

	/**
	 * Remove product from comparison.
	 */
	function removeFromComparison(productId) {
		comparisonProducts = comparisonProducts.filter(function(id) {
			return id !== productId;
		});
		
		saveToStorage();
		updateFloatingBar();
		updateComparisonPage();
		showToast(khaComparisonConfig.strings.removed, 'info');

		// Uncheck checkbox
		$('.kha-compare-checkbox input[value="' + productId + '"]').prop('checked', false);
	}

	/**
	 * Clear all products from comparison.
	 */
	function clearComparison() {
		comparisonProducts = [];
		saveToStorage();
		updateFloatingBar();
		updateComparisonPage();
		
		// Uncheck all checkboxes
		$('.kha-compare-checkbox input').prop('checked', false);
		
		showToast('Đã xóa tất cả sản phẩm khỏi so sánh', 'info');
	}

	/**
	 * Initialize floating comparison bar.
	 */
	function initFloatingBar() {
		// Clear all button
		$('.kha-comparison-clear-btn').on('click', function(e) {
			e.preventDefault();
			if (confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi so sánh?')) {
				clearComparison();
			}
		});

		// Remove individual product from bar
		$(document).on('click', '.kha-comparison-product-remove', function(e) {
			e.preventDefault();
			var productId = parseInt($(this).data('product-id'));
			removeFromComparison(productId);
		});
	}

	/**
	 * Update floating comparison bar.
	 */
	function updateFloatingBar() {
		var bar = $('#kha-comparison-bar');
		var count = comparisonProducts.length;

		if (count > 0) {
			bar.fadeIn(300);
			$('.kha-comparison-count').text('(' + count + '/' + maxProducts + ')');
			
			// Load product thumbnails
			loadProductThumbnails();
		} else {
			bar.fadeOut(300);
		}
	}

	/**
	 * Load product thumbnails for floating bar.
	 */
	function loadProductThumbnails() {
		if (comparisonProducts.length === 0) {
			return;
		}

		$.ajax({
			url: khaComparisonConfig.ajaxUrl,
			type: 'POST',
			data: {
				action: 'kha_get_comparison_data',
				product_ids: comparisonProducts,
				nonce: khaComparisonConfig.nonce
			},
			success: function(response) {
				if (response.success && response.data) {
					renderBarProducts(response.data);
				}
			}
		});
	}

	/**
	 * Render products in floating bar.
	 */
	function renderBarProducts(products) {
		var html = '';
		
		products.forEach(function(product) {
			html += '<div class="kha-comparison-product-thumb">';
			if (product.image) {
				html += '<img src="' + product.image + '" alt="' + escapeHtml(product.title) + '">';
			}
			html += '<button class="kha-comparison-product-remove" data-product-id="' + product.id + '">×</button>';
			html += '</div>';
		});

		// Add empty slots
		for (var i = products.length; i < maxProducts; i++) {
			html += '<div class="kha-comparison-product-thumb kha-empty-slot">+</div>';
		}

		$('.kha-comparison-products').html(html);
	}

	/**
	 * Initialize comparison page.
	 */
	function initComparisonPage() {
		if ($('.kha-comparison-page').length === 0) {
			return;
		}

		// Load products from URL or localStorage
		var urlParams = new URLSearchParams(window.location.search);
		var urlProducts = urlParams.get('products');
		
		if (urlProducts) {
			comparisonProducts = urlProducts.split(',').map(function(id) {
				return parseInt(id);
			});
			saveToStorage();
		}

		// Load comparison data
		if (comparisonProducts.length > 0) {
			loadComparisonData();
		}

		// Clear all button
		$('.kha-comparison-clear-all').on('click', function(e) {
			e.preventDefault();
			if (confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi so sánh?')) {
				clearComparison();
			}
		});

		// Share button
		$('.kha-comparison-share').on('click', function(e) {
			e.preventDefault();
			showShareModal();
		});
	}

	/**
	 * Load comparison data from server.
	 */
	function loadComparisonData() {
		$.ajax({
			url: khaComparisonConfig.ajaxUrl,
			type: 'POST',
			data: {
				action: 'kha_get_comparison_data',
				product_ids: comparisonProducts,
				nonce: khaComparisonConfig.nonce
			},
			success: function(response) {
				if (response.success && response.data) {
					renderComparisonTable(response.data);
				}
			},
			error: function() {
				$('#kha-comparison-content').html('<div class="kha-error">Đã xảy ra lỗi khi tải dữ liệu.</div>');
			}
		});
	}

	/**
	 * Render comparison table.
	 */
	function renderComparisonTable(products) {
		if (products.length === 0) {
			return;
		}

		var html = '<div class="kha-comparison-table-wrapper">';
		html += '<table class="kha-comparison-table">';
		
		// Header with products
		html += '<thead><tr><th class="kha-spec-label"></th>';
		products.forEach(function(product) {
			html += '<th class="kha-product-column">';
			html += '<div class="kha-product-header">';
			if (product.image) {
				html += '<img src="' + product.image + '" alt="' + escapeHtml(product.title) + '">';
			}
			html += '<h3>' + product.title + '</h3>';
			html += '<div class="kha-product-price">';
			if (product.on_sale && product.sale_price) {
				html += '<span class="kha-price-sale">' + product.sale_price + '</span> ';
				html += '<span class="kha-price-regular">' + product.regular_price + '</span>';
			} else {
				html += '<span class="kha-price-current">' + product.price + '</span>';
			}
			html += '</div>';
			html += '<div class="kha-product-actions">';
			html += '<a href="' + product.url + '" class="kha-view-product">Xem chi tiết</a>';
			html += '<button class="kha-remove-product" data-product-id="' + product.id + '">Xóa</button>';
			html += '</div>';
			html += '</div></th>';
		});
		html += '</tr></thead>';

		// Body with specifications
		html += '<tbody>';

		// SKU
		if (hasValue(products, 'sku')) {
			html += renderRow('Mã sản phẩm', products, 'sku');
		}

		// Category
		if (hasValue(products, 'categories')) {
			html += renderRow('Danh mục', products, 'categories');
		}

		// Brand
		if (hasValue(products, 'brand')) {
			html += renderRow('Thương hiệu', products, 'brand');
		}

		// Technical specs header
		html += '<tr class="kha-section-header"><td colspan="' + (products.length + 1) + '">📊 THÔNG SỐ KỸ THUẬT</td></tr>';

		// Power output
		if (hasValue(products, 'power_output')) {
			html += renderRow('Công suất', products, 'power_output', true);
		}

		// Voltage
		if (hasValue(products, 'voltage')) {
			html += renderRow('Điện áp', products, 'voltage', true);
		}

		// Efficiency
		if (hasValue(products, 'efficiency')) {
			html += renderRow('Hiệu suất', products, 'efficiency', true);
		}

		// Warranty
		if (hasValue(products, 'warranty_years')) {
			html += renderRow('Bảo hành', products, 'warranty_years', true);
		}

		// Dimensions
		if (hasValue(products, 'dimensions')) {
			html += renderRow('Kích thước', products, 'dimensions');
		}

		// Weight
		if (hasValue(products, 'weight')) {
			html += renderRow('Trọng lượng', products, 'weight', true);
		}

		// Origin
		if (hasValue(products, 'origin_country')) {
			html += renderRow('Xuất xứ', products, 'origin_country', true);
		}

		// Stock status
		html += '<tr class="kha-spec-row">';
		html += '<td class="kha-spec-label">Tình trạng</td>';
		products.forEach(function(product) {
			html += '<td>' + product.stock_label + '</td>';
		});
		html += '</tr>';

		html += '</tbody>';
		html += '</table>';
		html += '</div>';

		$('#kha-comparison-content').html(html);

		// Remove product from comparison
		$('.kha-remove-product').on('click', function(e) {
			e.preventDefault();
			var productId = parseInt($(this).data('product-id'));
			removeFromComparison(productId);
		});
	}

	/**
	 * Check if any product has a value for a field.
	 */
	function hasValue(products, field) {
		return products.some(function(product) {
			return product[field] && product[field] !== '';
		});
	}

	/**
	 * Render a comparison row.
	 */
	function renderRow(label, products, field, highlight) {
		var html = '<tr class="kha-spec-row">';
		html += '<td class="kha-spec-label">' + label + '</td>';

		// Collect all values
		var values = products.map(function(p) { return p[field] || '-'; });
		var uniqueValues = [...new Set(values)];
		var hasDifferences = uniqueValues.length > 1 && highlight;

		products.forEach(function(product) {
			var value = product[field] || '-';
			var className = hasDifferences && value !== '-' ? ' class="kha-highlight-diff"' : '';
			html += '<td' + className + '>' + value + '</td>';
		});

		html += '</tr>';
		return html;
	}

	/**
	 * Update comparison page.
	 */
	function updateComparisonPage() {
		if ($('.kha-comparison-page').length === 0) {
			return;
		}

		if (comparisonProducts.length === 0) {
			$('#kha-comparison-content').html(
				'<div class="kha-comparison-empty">' +
				'<div class="kha-comparison-empty-icon">📊</div>' +
				'<h2>Bạn chưa chọn sản phẩm nào để so sánh</h2>' +
				'<p>Hãy chọn từ danh sách sản phẩm để bắt đầu so sánh.</p>' +
				'<a href="' + (khaComparisonConfig.shopUrl || '/san-pham/') + '" class="kha-back-to-shop">Về Trang Sản Phẩm</a>' +
				'</div>'
			);
		} else {
			loadComparisonData();
		}
	}

	/**
	 * Show share modal.
	 */
	function showShareModal() {
		if (comparisonProducts.length === 0) {
			showToast('Vui lòng chọn sản phẩm để chia sẻ', 'warning');
			return;
		}

		var shareUrl = window.location.origin + window.location.pathname + '?products=' + comparisonProducts.join(',');
		$('#kha-share-url').val(shareUrl);
		$('#kha-share-modal').fadeIn(300);
	}

	/**
	 * Initialize share modal.
	 */
	function initShareModal() {
		// Close modal
		$('.kha-modal-close, .kha-modal-overlay').on('click', function() {
			$('#kha-share-modal').fadeOut(300);
		});

		// Copy URL
		$('.kha-copy-url').on('click', function() {
			var input = $('#kha-share-url');
			input.select();
			document.execCommand('copy');
			showToast('Đã sao chép liên kết', 'success');
		});

		// Share to Facebook
		$('.kha-share-facebook').on('click', function() {
			var url = $('#kha-share-url').val();
			window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url), '_blank');
		});

		// Share to Zalo
		$('.kha-share-zalo').on('click', function() {
			var url = $('#kha-share-url').val();
			window.open('https://zalo.me/share?url=' + encodeURIComponent(url), '_blank');
		});
	}

	/**
	 * Show toast notification.
	 */
	function showToast(message, type) {
		type = type || 'info';
		
		var toast = $('<div class="kha-toast kha-toast-' + type + '">' + message + '</div>');
		$('body').append(toast);
		
		setTimeout(function() {
			toast.addClass('kha-toast-show');
		}, 10);

		setTimeout(function() {
			toast.removeClass('kha-toast-show');
			setTimeout(function() {
				toast.remove();
			}, 300);
		}, 3000);
	}

	/**
	 * Escape HTML to prevent XSS.
	 */
	function escapeHtml(text) {
		var map = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#039;'
		};
		return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
	}

	// Initialize on document ready
	$(document).ready(function() {
		init();
	});

})(jQuery);
