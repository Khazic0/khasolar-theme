/**
 * Kha Solar Shop - Add to Cart
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		// Add to cart button click
		$(document).on('click', '.kha-add-to-cart', function(e) {
			e.preventDefault();

			const $button = $(this);
			const productId = $button.data('product-id');
			const quantity = $button.closest('.kha-product-actions').find('.kha-quantity-input').val() || 1;

			if (!productId) {
				return;
			}

			addToCart(productId, quantity, $button);
		});

		// Add to cart function
		function addToCart(productId, quantity, $button) {
			$.ajax({
				url: khaSolar.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_add_to_cart',
					nonce: khaSolar.nonce,
					product_id: productId,
					quantity: quantity
				},
				beforeSend: function() {
					$button.prop('disabled', true).text(khaSolar.i18n.loading);
				},
				success: function(response) {
					if (response.success) {
						// Update cart count
						updateCartCount(response.data.cart_count);

						// Show success message
						showNotification(response.data.message, 'success');

						// Reset button
						$button.prop('disabled', false).html('<svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor"><path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg> Add to Cart');
					} else {
						showNotification(response.data.message || khaSolar.i18n.error, 'error');
						$button.prop('disabled', false);
					}
				},
				error: function() {
					showNotification(khaSolar.i18n.error, 'error');
					$button.prop('disabled', false);
				}
			});
		}

		// Update cart count in header
		function updateCartCount(count) {
			$('.kha-cart-count').text(count);
		}

		// Show notification
		function showNotification(message, type) {
			const $notification = $('<div class="kha-notification ' + type + '">' + message + '</div>');

			$('body').append($notification);

			setTimeout(function() {
				$notification.addClass('show');
			}, 10);

			setTimeout(function() {
				$notification.removeClass('show');
				setTimeout(function() {
					$notification.remove();
				}, 300);
			}, 3000);
		}

		// Add notification styles
		if (!$('#kha-notification-styles').length) {
			$('head').append(
				'<style id="kha-notification-styles">' +
				'.kha-notification {' +
				'position: fixed;' +
				'top: 20px;' +
				'right: 20px;' +
				'padding: 15px 25px;' +
				'background: white;' +
				'border-radius: 8px;' +
				'box-shadow: 0 4px 16px rgba(0,0,0,0.15);' +
				'z-index: 99999;' +
				'transform: translateX(400px);' +
				'transition: transform 0.3s ease;' +
				'max-width: 350px;' +
				'}' +
				'.kha-notification.show {' +
				'transform: translateX(0);' +
				'}' +
				'.kha-notification.success {' +
				'border-left: 4px solid #4CAF50;' +
				'}' +
				'.kha-notification.error {' +
				'border-left: 4px solid #dc3545;' +
				'}' +
				'</style>'
			);
		}

	});

})(jQuery);
