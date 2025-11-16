/**
 * Kha Solar Shop - Cart Handler
 *
 * Handles all cart operations with AJAX
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Cart Handler Object
	 */
	var KhaCart = {

		/**
		 * Initialize
		 */
		init: function() {
			this.bindEvents();
			this.initMiniCart();
		},

		/**
		 * Bind event handlers
		 */
		bindEvents: function() {
			// Add to cart from product pages
			$(document).on('click', '.kha-add-to-cart-btn', this.addToCart.bind(this));

			// Update quantity
			$(document).on('click', '.kha-qty-increase', this.increaseQuantity.bind(this));
			$(document).on('click', '.kha-qty-decrease', this.decreaseQuantity.bind(this));
			$(document).on('change', '.kha-qty-input', this.updateQuantity.bind(this));

			// Remove from cart
			$(document).on('click', '.kha-cart-item-remove, .kha-mini-item-remove', this.removeFromCart.bind(this));

			// Mini cart toggle
			$(document).on('click', '#kha-open-mini-cart', this.openMiniCart.bind(this));
			$(document).on('click', '#kha-close-mini-cart, #kha-close-mini-cart-btn', this.closeMiniCart.bind(this));

			// Close mini cart on escape
			$(document).on('keydown', function(e) {
				if (e.key === 'Escape') {
					KhaCart.closeMiniCart();
				}
			});
		},

		/**
		 * Initialize mini cart
		 */
		initMiniCart: function() {
			// Auto-close after 3 seconds on page load if just added item
			if (window.location.hash === '#cart-updated') {
				this.openMiniCart();
				setTimeout(function() {
					KhaCart.closeMiniCart();
				}, 3000);
				// Remove hash
				history.replaceState(null, null, ' ');
			}
		},

		/**
		 * Add product to cart
		 */
		addToCart: function(e) {
			e.preventDefault();

			var $btn = $(e.currentTarget);
			var productId = $btn.data('product-id');
			var quantity = $('.kha-product-quantity input').val() || 1;

			// Disable button
			$btn.prop('disabled', true).addClass('kha-loading');

			$.ajax({
				url: khaCartConfig.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_add_to_cart',
					nonce: khaCartConfig.nonce,
					product_id: productId,
					quantity: quantity
				},
				success: function(response) {
					if (response.success) {
						// Update cart count
						KhaCart.updateCartCount(response.data.cart_count);

						// Update mini cart
						KhaCart.updateMiniCart(response.data.mini_cart_html);

						// Show success toast
						KhaCart.showToast(response.data.message, 'success');

						// Open mini cart for 2 seconds
						KhaCart.openMiniCart();
						setTimeout(function() {
							KhaCart.closeMiniCart();
						}, 2000);
					} else {
						KhaCart.showToast(response.data.message, 'error');
					}
				},
				error: function() {
					KhaCart.showToast('Đã xảy ra lỗi. Vui lòng thử lại.', 'error');
				},
				complete: function() {
					$btn.prop('disabled', false).removeClass('kha-loading');
				}
			});
		},

		/**
		 * Increase quantity
		 */
		increaseQuantity: function(e) {
			e.preventDefault();
			var $btn = $(e.currentTarget);
			var $input = $btn.siblings('.kha-qty-input');
			var currentVal = parseInt($input.val()) || 1;

			$input.val(currentVal + 1).trigger('change');
		},

		/**
		 * Decrease quantity
		 */
		decreaseQuantity: function(e) {
			e.preventDefault();
			var $btn = $(e.currentTarget);
			var $input = $btn.siblings('.kha-qty-input');
			var currentVal = parseInt($input.val()) || 1;

			if (currentVal > 1) {
				$input.val(currentVal - 1).trigger('change');
			}
		},

		/**
		 * Update quantity
		 */
		updateQuantity: function(e) {
			var $input = $(e.currentTarget);
			var productId = $input.data('product-id');
			var quantity = parseInt($input.val()) || 1;

			// Prevent negative or zero values
			if (quantity < 1) {
				quantity = 1;
				$input.val(1);
			}

			// Show loading on cart item
			var $cartItem = $input.closest('.kha-cart-item, .kha-mini-cart-item');
			$cartItem.addClass('kha-updating');

			$.ajax({
				url: khaCartConfig.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_update_cart_quantity',
					nonce: khaCartConfig.nonce,
					product_id: productId,
					quantity: quantity
				},
				success: function(response) {
					if (response.success) {
						// Update subtotal for this item
						var $subtotal = $('.kha-subtotal-value[data-product-id="' + productId + '"]');
						$subtotal.text(response.data.item_subtotal);

						// Update cart totals
						KhaCart.updateCartTotals(response.data);

						// Update cart count
						KhaCart.updateCartCount(response.data.cart_count);

						// Update shipping notice if exists
						if (response.data.shipping_notice_html) {
							$('.kha-shipping-notice').replaceWith(response.data.shipping_notice_html);
						}
					} else {
						KhaCart.showToast(response.data.message, 'error');
						// Revert to previous value
						$input.val($input.data('prev-value') || 1);
					}
				},
				error: function() {
					KhaCart.showToast('Đã xảy ra lỗi. Vui lòng thử lại.', 'error');
					$input.val($input.data('prev-value') || 1);
				},
				complete: function() {
					$cartItem.removeClass('kha-updating');
				}
			});

			// Store previous value
			$input.data('prev-value', quantity);
		},

		/**
		 * Remove from cart
		 */
		removeFromCart: function(e) {
			e.preventDefault();

			if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
				return;
			}

			var $btn = $(e.currentTarget);
			var productId = $btn.data('product-id');
			var $cartItem = $btn.closest('.kha-cart-item, .kha-mini-cart-item');

			// Disable button
			$btn.prop('disabled', true);
			$cartItem.addClass('kha-removing');

			$.ajax({
				url: khaCartConfig.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_remove_cart_item',
					nonce: khaCartConfig.nonce,
					product_id: productId
				},
				success: function(response) {
					if (response.success) {
						// Animate removal
						$cartItem.fadeOut(300, function() {
							$(this).remove();

							// Update cart totals
							KhaCart.updateCartTotals(response.data);

							// Update cart count
							KhaCart.updateCartCount(response.data.cart_count);

							// If cart is now empty, reload page or show empty state
							if (response.data.cart_count === 0) {
								if ($('.kha-cart-page').length) {
									// Reload cart page
									location.reload();
								} else {
									// Update mini cart to show empty state
									$('#kha-mini-cart-items').html(response.data.empty_cart_html);
									$('.kha-mini-cart-footer').remove();
								}
							}

							// Update shipping notice if exists
							if (response.data.shipping_notice_html) {
								$('.kha-shipping-notice').replaceWith(response.data.shipping_notice_html);
							}
						});

						KhaCart.showToast(response.data.message, 'success');
					} else {
						KhaCart.showToast(response.data.message, 'error');
						$btn.prop('disabled', false);
						$cartItem.removeClass('kha-removing');
					}
				},
				error: function() {
					KhaCart.showToast('Đã xảy ra lỗi. Vui lòng thử lại.', 'error');
					$btn.prop('disabled', false);
					$cartItem.removeClass('kha-removing');
				}
			});
		},

		/**
		 * Open mini cart
		 */
		openMiniCart: function() {
			$('#kha-mini-cart-panel').addClass('kha-active');
			$('body').addClass('kha-mini-cart-open');
		},

		/**
		 * Close mini cart
		 */
		closeMiniCart: function() {
			$('#kha-mini-cart-panel').removeClass('kha-active');
			$('body').removeClass('kha-mini-cart-open');
		},

		/**
		 * Update cart count
		 */
		updateCartCount: function(count) {
			var $badge = $('#kha-cart-badge, #kha-mini-cart-count');

			if (count > 0) {
				$badge.text(count).show();

				// Animate badge
				$badge.addClass('kha-bounce');
				setTimeout(function() {
					$badge.removeClass('kha-bounce');
				}, 500);
			} else {
				$badge.hide();
			}
		},

		/**
		 * Update cart totals
		 */
		updateCartTotals: function(data) {
			if (data.cart_subtotal) {
				$('#kha-cart-subtotal, #kha-mini-cart-subtotal').text(data.cart_subtotal);
			}

			if (data.shipping_fee) {
				$('#kha-cart-shipping').html(data.shipping_fee);
			}

			if (data.cart_total) {
				$('#kha-cart-total, #kha-mini-cart-total').text(data.cart_total);
			}
		},

		/**
		 * Update mini cart HTML
		 */
		updateMiniCart: function(html) {
			if (html) {
				$('#kha-mini-cart-items').html(html);
			}
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
			}, 10);

			setTimeout(function() {
				$toast.removeClass('kha-toast-show');
				setTimeout(function() {
					$toast.remove();
				}, 300);
			}, 3000);
		}

	};

	// Initialize on document ready
	$(document).ready(function() {
		KhaCart.init();
	});

})(jQuery);
