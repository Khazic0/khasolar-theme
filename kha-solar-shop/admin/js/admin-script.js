/**
 * Kha Solar Shop - Admin Scripts
 *
 * Interactive functionality for product management interface
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Tab Switching
	 */
	function initTabs() {
		$('.kha-tabs a').on('click', function(e) {
			e.preventDefault();
			
			var targetTab = $(this).attr('href');
			
			// Update tab active states
			$('.kha-tabs li').removeClass('active');
			$(this).parent().addClass('active');
			
			// Show target tab content
			$('.kha-tab-content').removeClass('active');
			$(targetTab).addClass('active');
		});
		
		// Activate first tab by default
		$('.kha-tabs li:first').addClass('active');
		$('.kha-tab-content:first').addClass('active');
	}

	/**
	 * SKU Generator
	 */
	function initSKUGenerator() {
		$('.kha-generate-sku-btn').on('click', function(e) {
			e.preventDefault();
			
			var productTitle = $('#title').val() || 'PRODUCT';
			var prefix = productTitle.substring(0, 3).toUpperCase();
			var timestamp = Date.now().toString().slice(-6);
			var random = Math.floor(Math.random() * 100).toString().padStart(2, '0');
			
			var sku = prefix + '-' + timestamp + random;
			
			$('#kha_product_sku').val(sku);
			
			// Flash effect
			$('#kha_product_sku').css('background-color', '#d4edda');
			setTimeout(function() {
				$('#kha_product_sku').css('background-color', '');
			}, 500);
		});
	}

	/**
	 * Price Formatting with Thousand Separators
	 */
	function initPriceFormatting() {
		$('.kha-price-input').on('blur', function() {
			var value = $(this).val().replace(/[^\d]/g, '');
			if (value) {
				var formatted = parseInt(value).toLocaleString('vi-VN');
				// Store raw value in a hidden field or data attribute
				$(this).data('raw-value', value);
				// Display formatted value temporarily
				var originalVal = $(this).val();
				$(this).val(formatted);
				
				// Revert to raw value on focus for editing
				$(this).one('focus', function() {
					$(this).val($(this).data('raw-value') || originalVal.replace(/[^\d]/g, ''));
				});
			}
		});
	}

	/**
	 * Stock Quantity Controls (+/-)
	 */
	function initQuantityControls() {
		// Decrease button
		$(document).on('click', '.kha-quantity-decrease', function(e) {
			e.preventDefault();
			var input = $(this).siblings('.kha-quantity-input');
			var currentValue = parseInt(input.val()) || 0;
			if (currentValue > 0) {
				input.val(currentValue - 1);
			}
		});
		
		// Increase button
		$(document).on('click', '.kha-quantity-increase', function(e) {
			e.preventDefault();
			var input = $(this).siblings('.kha-quantity-input');
			var currentValue = parseInt(input.val()) || 0;
			input.val(currentValue + 1);
		});
		
		// Prevent negative values
		$('.kha-quantity-input').on('change', function() {
			var value = parseInt($(this).val()) || 0;
			if (value < 0) {
				$(this).val(0);
			}
		});
	}

	/**
	 * Toggle Switch for Stock Management
	 */
	function initStockToggle() {
		$('#kha_manage_stock').on('change', function() {
			if ($(this).is(':checked')) {
				$('.kha-stock-fields').slideDown(200);
			} else {
				$('.kha-stock-fields').slideUp(200);
			}
		});
		
		// Initialize on page load
		if ($('#kha_manage_stock').is(':checked')) {
			$('.kha-stock-fields').show();
		} else {
			$('.kha-stock-fields').hide();
		}
	}

	/**
	 * Quick Fill Buttons
	 */
	function initQuickFill() {
		// Warranty quick fill
		$('.kha-warranty-fill').on('click', function(e) {
			e.preventDefault();
			var years = $(this).data('years');
			$('#kha_warranty_years').val(years);
		});
		
		// Efficiency quick fill
		$('.kha-efficiency-fill').on('click', function(e) {
			e.preventDefault();
			var efficiency = $(this).data('efficiency');
			$('#kha_efficiency').val(efficiency);
		});
		
		// Origin country quick fill
		$('.kha-origin-fill').on('click', function(e) {
			e.preventDefault();
			var country = $(this).data('country');
			$('#kha_origin_country').val(country);
		});
	}

	/**
	 * Product Gallery Management
	 */
	var galleryFrame;
	var galleryImages = [];

	function initGallery() {
		// Load existing gallery images
		var existingGallery = $('#kha_product_gallery').val();
		if (existingGallery) {
			try {
				galleryImages = JSON.parse(existingGallery);
				renderGallery();
			} catch (e) {
				galleryImages = [];
			}
		}

		// Add images button
		$('.kha-add-gallery-btn').on('click', function(e) {
			e.preventDefault();

			// If the media frame already exists, reopen it
			if (galleryFrame) {
				galleryFrame.open();
				return;
			}

			// Create a new media frame
			galleryFrame = wp.media({
				title: 'Select Gallery Images',
				button: {
					text: 'Add to Gallery'
				},
				multiple: true
			});

			// When images are selected
			galleryFrame.on('select', function() {
				var selection = galleryFrame.state().get('selection');
				
				selection.each(function(attachment) {
					attachment = attachment.toJSON();
					
					// Check if we haven't exceeded max images (8)
					if (galleryImages.length < 8) {
						// Check if image isn't already in gallery
						if (!galleryImages.includes(attachment.id)) {
							galleryImages.push(attachment.id);
						}
					}
				});
				
				renderGallery();
				updateGalleryField();
			});

			// Open the modal
			galleryFrame.open();
		});

		// Remove image
		$(document).on('click', '.kha-gallery-remove', function(e) {
			e.preventDefault();
			var imageId = $(this).data('image-id');
			galleryImages = galleryImages.filter(function(id) {
				return id != imageId;
			});
			renderGallery();
			updateGalleryField();
		});

		// Sortable gallery
		if ($.fn.sortable) {
			$('.kha-gallery-grid').sortable({
				items: '.kha-gallery-image',
				cursor: 'move',
				scrollSensitivity: 40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'kha-gallery-placeholder-item',
				update: function() {
					var newOrder = [];
					$('.kha-gallery-image').each(function() {
						newOrder.push($(this).data('image-id'));
					});
					galleryImages = newOrder;
					updateGalleryField();
				}
			});
		}
	}

	function renderGallery() {
		var container = $('.kha-gallery-grid');
		container.empty();

		if (galleryImages.length === 0) {
			$('.kha-gallery-placeholder').show();
			return;
		}

		$('.kha-gallery-placeholder').hide();

		// Fetch image URLs via AJAX
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'kha_get_gallery_images',
				image_ids: galleryImages,
				nonce: $('#kha_product_data_nonce').val()
			},
			success: function(response) {
				if (response.success) {
					$.each(response.data, function(index, image) {
						var imageHtml = '<div class="kha-gallery-image" data-image-id="' + image.id + '">' +
							'<img src="' + image.url + '" alt="" />' +
							'<button type="button" class="kha-gallery-remove" data-image-id="' + image.id + '">×</button>' +
							'</div>';
						container.append(imageHtml);
					});

					// Re-initialize sortable after rendering
					if ($.fn.sortable) {
						container.sortable('destroy').sortable({
							items: '.kha-gallery-image',
							cursor: 'move',
							opacity: 0.65,
							update: function() {
								var newOrder = [];
								$('.kha-gallery-image').each(function() {
									newOrder.push($(this).data('image-id'));
								});
								galleryImages = newOrder;
								updateGalleryField();
							}
						});
					}
				}
			}
		});
	}

	function updateGalleryField() {
		$('#kha_product_gallery').val(JSON.stringify(galleryImages));
	}

	/**
	 * Bundle Product Search
	 */
	var bundleProducts = [];
	var searchTimeout;

	function initBundleSearch() {
		// Load existing bundle products
		var existingBundles = $('#kha_bundle_products').val();
		if (existingBundles) {
			try {
				bundleProducts = JSON.parse(existingBundles);
				renderBundleProducts();
			} catch (e) {
				bundleProducts = [];
			}
		}

		// Search input
		$('.kha-bundle-search-input').on('keyup', function() {
			var searchTerm = $(this).val();
			
			clearTimeout(searchTimeout);
			
			if (searchTerm.length < 2) {
				$('.kha-bundle-search-results').empty().hide();
				return;
			}

			searchTimeout = setTimeout(function() {
				searchBundleProducts(searchTerm);
			}, 300);
		});

		// Click on search result
		$(document).on('click', '.kha-bundle-search-result', function() {
			var productId = $(this).data('product-id');
			
			if (!bundleProducts.includes(productId)) {
				bundleProducts.push(productId);
				updateBundleField();
				renderBundleProducts();
			}
			
			$('.kha-bundle-search-input').val('');
			$('.kha-bundle-search-results').empty().hide();
		});

		// Remove bundle product
		$(document).on('click', '.kha-bundle-remove', function(e) {
			e.preventDefault();
			var productId = $(this).data('product-id');
			bundleProducts = bundleProducts.filter(function(id) {
				return id != productId;
			});
			updateBundleField();
			renderBundleProducts();
		});
	}

	function searchBundleProducts(searchTerm) {
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'kha_search_products',
				search: searchTerm,
				exclude: bundleProducts,
				nonce: $('#kha_product_data_nonce').val()
			},
			success: function(response) {
				if (response.success) {
					var resultsHtml = '';
					
					$.each(response.data, function(index, product) {
						resultsHtml += '<div class="kha-bundle-search-result" data-product-id="' + product.id + '">';
						if (product.image) {
							resultsHtml += '<img src="' + product.image + '" alt="" />';
						}
						resultsHtml += '<div>';
						resultsHtml += '<div>' + product.title + '</div>';
						if (product.price) {
							resultsHtml += '<div class="kha-text-muted">' + product.price + '</div>';
						}
						resultsHtml += '</div>';
						resultsHtml += '</div>';
					});
					
					if (resultsHtml) {
						$('.kha-bundle-search-results').html(resultsHtml).show();
					} else {
						$('.kha-bundle-search-results').html('<div class="kha-bundle-search-result">No products found</div>').show();
					}
				}
			}
		});
	}

	function renderBundleProducts() {
		var container = $('.kha-selected-bundles');
		container.empty();

		if (bundleProducts.length === 0) {
			return;
		}

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'kha_get_bundle_products',
				product_ids: bundleProducts,
				nonce: $('#kha_product_data_nonce').val()
			},
			success: function(response) {
				if (response.success) {
					$.each(response.data, function(index, product) {
						var itemHtml = '<div class="kha-bundle-item">';
						if (product.image) {
							itemHtml += '<img src="' + product.image + '" alt="" />';
						}
						itemHtml += '<div class="kha-bundle-item-info">';
						itemHtml += '<div class="kha-bundle-item-title">' + product.title + '</div>';
						if (product.price) {
							itemHtml += '<div class="kha-bundle-item-price">' + product.price + '</div>';
						}
						itemHtml += '</div>';
						itemHtml += '<button type="button" class="kha-bundle-remove" data-product-id="' + product.id + '">Remove</button>';
						itemHtml += '</div>';
						container.append(itemHtml);
					});
				}
			}
		});
	}

	function updateBundleField() {
		$('#kha_bundle_products').val(JSON.stringify(bundleProducts));
	}

	/**
	 * Product Type Toggle
	 */
	function initProductTypeToggle() {
		$('input[name="kha_product_type"]').on('change', function() {
			if ($(this).val() === 'bundle') {
				$('.kha-bundle-fields').slideDown(200);
			} else {
				$('.kha-bundle-fields').slideUp(200);
			}
		});

		// Initialize on page load
		var selectedType = $('input[name="kha_product_type"]:checked').val();
		if (selectedType === 'bundle') {
			$('.kha-bundle-fields').show();
		} else {
			$('.kha-bundle-fields').hide();
		}
	}

	/**
	 * Form Validation
	 */
	function initValidation() {
		$('#post').on('submit', function(e) {
			var errors = [];

			// Check if regular price is set when sale price is set
			var regularPrice = $('#kha_regular_price').val();
			var salePrice = $('#kha_sale_price').val();

			if (salePrice && !regularPrice) {
				errors.push('Regular price is required when sale price is set.');
			}

			if (salePrice && regularPrice && parseFloat(salePrice) >= parseFloat(regularPrice)) {
				errors.push('Sale price must be less than regular price.');
			}

			// Check bundle products
			if ($('input[name="kha_product_type"]:checked').val() === 'bundle') {
				if (bundleProducts.length === 0) {
					errors.push('Bundle products are required for bundle type.');
				}
			}

			if (errors.length > 0) {
				e.preventDefault();
				alert('Please fix the following errors:\n\n' + errors.join('\n'));
				return false;
			}
		});
	}

	/**
	 * Initialize All Functions
	 */
	$(document).ready(function() {
		// Only run on product edit pages
		if ($('.kha-product-data-panel').length === 0) {
			return;
		}

		initTabs();
		initSKUGenerator();
		initPriceFormatting();
		initQuantityControls();
		initStockToggle();
		initQuickFill();
		initGallery();
		initBundleSearch();
		initProductTypeToggle();
		initValidation();

		// Hide search results when clicking outside
		$(document).on('click', function(e) {
			if (!$(e.target).closest('.kha-bundle-search').length) {
				$('.kha-bundle-search-results').hide();
			}
		});
	});

})(jQuery);
