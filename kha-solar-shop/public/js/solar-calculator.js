/**
 * Solar System Calculator JavaScript
 *
 * Handles calculator interactions, AJAX calls, and animations.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Solar Calculator Object
	 */
	var KhaSolarCalculator = {

		// Current calculation results
		currentResults: null,

		/**
		 * Initialize calculator
		 */
		init: function() {
			this.bindEvents();
			this.initializeSliders();

			// Check if we have URL parameters (shareable link)
			if (this.hasUrlParameters()) {
				this.loadFromUrl();
			}
		},

		/**
		 * Bind event handlers
		 */
		bindEvents: function() {
			// Slider inputs
			$('#monthly_bill').on('input', this.updateBillDisplay.bind(this));
			$('#roof_area').on('input', this.updateAreaDisplay.bind(this));

			// Calculate button
			$('#kha-calculate-btn').on('click', this.calculate.bind(this));

			// Results actions
			$('#kha-view-products-btn').on('click', this.viewProducts.bind(this));
			$('#kha-recalculate-btn').on('click', this.recalculate.bind(this));
			$('#kha-share-results-btn').on('click', this.shareResults.bind(this));

			// Product actions
			$('#kha-add-bundle-to-cart').on('click', this.addBundleToCart.bind(this));
			$('#kha-request-consultation-btn').on('click', this.openConsultationModal.bind(this));

			// Modal
			$('#kha-close-consultation-modal').on('click', this.closeConsultationModal.bind(this));
			$('.kha-modal-overlay').on('click', this.closeConsultationModal.bind(this));

			// Lead form
			$('#kha-lead-form').on('submit', this.submitLead.bind(this));
		},

		/**
		 * Initialize sliders with default values
		 */
		initializeSliders: function() {
			this.updateBillDisplay();
			this.updateAreaDisplay();
		},

		/**
		 * Update monthly bill display
		 */
		updateBillDisplay: function() {
			var value = $('#monthly_bill').val();
			var formatted = this.formatPrice(value);
			$('#monthly_bill_display').text(formatted);
		},

		/**
		 * Update roof area display
		 */
		updateAreaDisplay: function() {
			var value = $('#roof_area').val();
			$('#roof_area_display').text(value + ' m²');
		},

		/**
		 * Calculate system requirements
		 */
		calculate: function(e) {
			if (e) {
				e.preventDefault();
			}

			var monthlyBill = parseInt($('#monthly_bill').val());
			var roofArea = parseInt($('#roof_area').val());
			var withBattery = $('#with_battery').is(':checked');

			// Show loading state
			$('#kha-calculate-btn').prop('disabled', true).html('<span class="dashicons dashicons-update dashicons-spin"></span> Đang tính toán...');

			// AJAX request
			$.ajax({
				url: khaCalculatorConfig.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_calculate_solar_system',
					nonce: khaCalculatorConfig.nonce,
					monthly_bill: monthlyBill,
					roof_area: roofArea,
					with_battery: withBattery
				},
				success: function(response) {
					if (response.success) {
						KhaSolarCalculator.currentResults = response.data;
						KhaSolarCalculator.displayResults(response.data);
						KhaSolarCalculator.showStep(2);
					} else {
						KhaSolarCalculator.showToast(response.data.message || 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
					}
				},
				error: function() {
					KhaSolarCalculator.showToast('Không thể kết nối đến máy chủ. Vui lòng thử lại.', 'error');
				},
				complete: function() {
					$('#kha-calculate-btn').prop('disabled', false).html('<span class="dashicons dashicons-chart-line"></span> Tính Toán Ngay');
				}
			});
		},

		/**
		 * Display calculation results with animation
		 */
		displayResults: function(data) {
			// Animate consumption values
			this.animateValue('#result_consumption_monthly', 0, data.consumption_kwh_monthly, 1000, 1);
			this.animateValue('#result_consumption_daily', 0, data.consumption_kwh_daily, 1000, 1);

			// Animate system specs
			this.animateValue('#result_system_kw', 0, data.recommended_system_kw, 1000, 1);
			this.animateValue('#result_panel_area', 0, data.panel_area_needed, 1000, 0);
			this.animateValue('#result_investment', 0, data.estimated_investment, 1500, 0, true);

			// Animate financial benefits
			this.animateValue('#result_monthly_savings', 0, data.monthly_savings, 1500, 0, true);
			this.animateValue('#result_roi_years', 0, data.roi_years, 1000, 1);

			// Update lifetime savings
			$('#result_lifetime_savings').text(this.formatPrice(data.total_savings_lifetime));

			// Show/hide area warning
			if (!data.area_sufficient) {
				$('#area_warning').slideDown();
			} else {
				$('#area_warning').slideUp();
			}
		},

		/**
		 * Animate number from start to end
		 */
		animateValue: function(selector, start, end, duration, decimals, isCurrency) {
			var $element = $(selector);
			var range = end - start;
			var current = start;
			var increment = end > start ? 1 : -1;
			var stepTime = Math.abs(Math.floor(duration / range));

			// Use easing for smoother animation
			$({ value: start }).animate({ value: end }, {
				duration: duration,
				easing: 'swing',
				step: function() {
					var value = this.value;
					if (decimals > 0) {
						value = value.toFixed(decimals);
					} else {
						value = Math.round(value);
					}

					if (isCurrency) {
						$element.text(KhaSolarCalculator.formatNumber(value));
					} else {
						$element.text(value);
					}
				},
				complete: function() {
					var finalValue = end;
					if (decimals > 0) {
						finalValue = end.toFixed(decimals);
					}

					if (isCurrency) {
						$element.text(KhaSolarCalculator.formatNumber(finalValue));
					} else {
						$element.text(finalValue);
					}
				}
			});
		},

		/**
		 * Show products step
		 */
		viewProducts: function(e) {
			e.preventDefault();

			if (!this.currentResults) {
				return;
			}

			// Load products via AJAX
			this.loadProductRecommendations();
			this.showStep(3);
		},

		/**
		 * Load product recommendations
		 */
		loadProductRecommendations: function() {
			var monthlyBill = parseInt($('#monthly_bill').val());
			var roofArea = parseInt($('#roof_area').val());
			var withBattery = $('#with_battery').is(':checked');

			$('#kha-recommended-products').html('<div class="kha-loading"><span class="dashicons dashicons-update dashicons-spin"></span> Đang tải sản phẩm...</div>');

			$.ajax({
				url: khaCalculatorConfig.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_get_product_recommendations',
					nonce: khaCalculatorConfig.nonce,
					monthly_bill: monthlyBill,
					roof_area: roofArea,
					with_battery: withBattery,
					system_kw: this.currentResults.recommended_system_kw
				},
				success: function(response) {
					if (response.success) {
						KhaSolarCalculator.displayProducts(response.data.products);
						KhaSolarCalculator.displayBundlePricing(response.data.bundle);
					} else {
						$('#kha-recommended-products').html('<p class="kha-error">Không thể tải sản phẩm. Vui lòng thử lại.</p>');
					}
				},
				error: function() {
					$('#kha-recommended-products').html('<p class="kha-error">Không thể kết nối đến máy chủ.</p>');
				}
			});
		},

		/**
		 * Display product recommendations
		 */
		displayProducts: function(products) {
			var html = '<div class="kha-product-recommendations">';

			// Inverter
			if (products.inverter) {
				html += this.buildProductCard(products.inverter, 'Inverter');
			}

			// Solar Panels
			if (products.panels) {
				html += this.buildProductCard(products.panels, 'Tấm Pin Năng Lượng Mặt Trời');
			}

			// Battery
			if (products.battery) {
				html += this.buildProductCard(products.battery, 'Bộ Lưu Trữ Pin');
			}

			html += '</div>';

			$('#kha-recommended-products').html(html);
		},

		/**
		 * Build product card HTML
		 */
		buildProductCard: function(product, category) {
			var quantityText = product.quantity > 1 ? ' × ' + product.quantity : '';
			var totalPrice = product.total_price_formatted || product.price;

			var html = '<div class="kha-product-card">';
			html += '<div class="kha-product-category">' + category + '</div>';

			if (product.image) {
				html += '<div class="kha-product-image">';
				html += '<img src="' + product.image + '" alt="' + product.title + '">';
				html += '</div>';
			}

			html += '<div class="kha-product-info">';
			html += '<h4 class="kha-product-title">' + product.title + quantityText + '</h4>';

			if (product.power) {
				html += '<div class="kha-product-spec">';
				html += '<span class="dashicons dashicons-editor-bold"></span>';
				html += 'Công suất: ' + product.power + 'W';
				html += '</div>';
			}

			html += '<div class="kha-product-price">' + totalPrice + '</div>';

			html += '<a href="' + product.url + '" class="kha-btn kha-btn-outline kha-btn-small">';
			html += 'Xem chi tiết';
			html += '</a>';

			html += '</div>';
			html += '</div>';

			return html;
		},

		/**
		 * Display bundle pricing
		 */
		displayBundlePricing: function(bundle) {
			if (!bundle || bundle.bundle_price <= 0) {
				$('#kha-bundle-pricing').hide();
				return;
			}

			$('#bundle_total_price').text(this.formatPrice(bundle.total_price));
			$('#bundle_discount_amount').text('-' + this.formatPrice(bundle.discount_amount));
			$('#bundle_final_price').text(this.formatPrice(bundle.bundle_price));

			$('#kha-bundle-pricing').slideDown();
		},

		/**
		 * Add bundle to cart
		 */
		addBundleToCart: function(e) {
			e.preventDefault();

			// Implementation would add all products as bundle
			this.showToast('Chức năng đang phát triển. Vui lòng thêm từng sản phẩm.', 'info');
		},

		/**
		 * Recalculate - go back to step 1
		 */
		recalculate: function(e) {
			e.preventDefault();
			this.showStep(1);
		},

		/**
		 * Share results - copy shareable URL
		 */
		shareResults: function(e) {
			e.preventDefault();

			var monthlyBill = parseInt($('#monthly_bill').val());
			var roofArea = parseInt($('#roof_area').val());
			var withBattery = $('#with_battery').is(':checked') ? 'yes' : 'no';

			var url = window.location.origin + window.location.pathname +
				'?bill=' + monthlyBill +
				'&area=' + roofArea +
				'&battery=' + withBattery;

			// Copy to clipboard
			this.copyToClipboard(url);
			this.showToast('Đã sao chép liên kết chia sẻ!', 'success');
		},

		/**
		 * Copy text to clipboard
		 */
		copyToClipboard: function(text) {
			var $temp = $('<input>');
			$('body').append($temp);
			$temp.val(text).select();
			document.execCommand('copy');
			$temp.remove();
		},

		/**
		 * Show specific step
		 */
		showStep: function(stepNumber) {
			$('.kha-calculator-step').removeClass('kha-step-active');
			$('#kha-calculator-step-' + stepNumber).addClass('kha-step-active');

			// Scroll to top of calculator
			$('html, body').animate({
				scrollTop: $('.kha-calculator-wrapper').offset().top - 100
			}, 500);
		},

		/**
		 * Open consultation modal
		 */
		openConsultationModal: function(e) {
			e.preventDefault();

			// Populate hidden fields with calculation data
			if (this.currentResults) {
				$('#calc_monthly_bill').val($('#monthly_bill').val());
				$('#calc_roof_area').val($('#roof_area').val());
				$('#calc_with_battery').val($('#with_battery').is(':checked') ? 'yes' : 'no');
				$('#calc_system_kw').val(this.currentResults.recommended_system_kw);
			}

			$('#kha-consultation-modal').fadeIn(300);
			$('body').addClass('kha-modal-open');
		},

		/**
		 * Close consultation modal
		 */
		closeConsultationModal: function(e) {
			if (e) {
				e.preventDefault();
			}
			$('#kha-consultation-modal').fadeOut(300);
			$('body').removeClass('kha-modal-open');
		},

		/**
		 * Submit lead form
		 */
		submitLead: function(e) {
			e.preventDefault();

			var $form = $('#kha-lead-form');
			var $submitBtn = $form.find('button[type="submit"]');

			// Disable submit button
			$submitBtn.prop('disabled', true).html('Đang gửi...');

			$.ajax({
				url: khaCalculatorConfig.ajaxUrl,
				type: 'POST',
				data: $form.serialize() + '&action=kha_save_calculator_lead',
				success: function(response) {
					if (response.success) {
						KhaSolarCalculator.showToast(response.data.message || 'Yêu cầu tư vấn đã được gửi thành công!', 'success');
						KhaSolarCalculator.closeConsultationModal();
						$form[0].reset();
					} else {
						KhaSolarCalculator.showToast(response.data.message || 'Có lỗi xảy ra. Vui lòng thử lại.', 'error');
					}
				},
				error: function() {
					KhaSolarCalculator.showToast('Không thể gửi yêu cầu. Vui lòng thử lại.', 'error');
				},
				complete: function() {
					$submitBtn.prop('disabled', false).html('Gửi Yêu Cầu <span class="dashicons dashicons-arrow-right-alt2"></span>');
				}
			});
		},

		/**
		 * Check if URL has parameters
		 */
		hasUrlParameters: function() {
			var urlParams = new URLSearchParams(window.location.search);
			return urlParams.has('bill');
		},

		/**
		 * Load calculator from URL parameters
		 */
		loadFromUrl: function() {
			var urlParams = new URLSearchParams(window.location.search);

			if (urlParams.has('bill')) {
				$('#monthly_bill').val(urlParams.get('bill'));
				this.updateBillDisplay();
			}

			if (urlParams.has('area')) {
				$('#roof_area').val(urlParams.get('area'));
				this.updateAreaDisplay();
			}

			if (urlParams.has('battery')) {
				$('#with_battery').prop('checked', urlParams.get('battery') === 'yes');
			}

			// Auto-calculate if parameters present
			setTimeout(function() {
				KhaSolarCalculator.calculate();
			}, 500);
		},

		/**
		 * Format price (Vietnamese dong)
		 */
		formatPrice: function(amount) {
			return this.formatNumber(amount) + '₫';
		},

		/**
		 * Format number with thousands separator
		 */
		formatNumber: function(num) {
			return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		},

		/**
		 * Show toast notification
		 */
		showToast: function(message, type) {
			type = type || 'info';

			var $toast = $('<div class="kha-toast kha-toast-' + type + '">' + message + '</div>');
			$('body').append($toast);

			setTimeout(function() {
				$toast.addClass('kha-toast-show');
			}, 100);

			setTimeout(function() {
				$toast.removeClass('kha-toast-show');
				setTimeout(function() {
					$toast.remove();
				}, 300);
			}, 3000);
		}

	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		if ($('.kha-calculator-page').length > 0) {
			KhaSolarCalculator.init();
		}
	});

})(jQuery);
