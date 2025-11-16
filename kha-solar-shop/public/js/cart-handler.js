/**
 * Kha Solar Shop - Cart Handler
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		// Update cart quantity
		$(document).on('click', '.kha-qty-btn', function() {
			const $button = $(this);
			const $input = $button.siblings('.kha-qty-input');
			const currentQty = parseInt($input.val()) || 1;
			let newQty = currentQty;

			if ($button.hasClass('kha-qty-increase')) {
				newQty = currentQty + 1;
			} else if ($button.hasClass('kha-qty-decrease') && currentQty > 1) {
				newQty = currentQty - 1;
			}

			$input.val(newQty);
			updateCartItem($input.data('cart-item-id'), newQty);
		});

		// Direct quantity input
		$(document).on('change', '.kha-qty-input', function() {
			const $input = $(this);
			const quantity = parseInt($input.val()) || 1;
			const cartItemId = $input.data('cart-item-id');

			if (quantity < 1) {
				$input.val(1);
				return;
			}

			updateCartItem(cartItemId, quantity);
		});

		// Remove from cart
		$(document).on('click', '.kha-cart-remove', function(e) {
			e.preventDefault();

			if (!confirm(khaSolar.i18n.removeConfirm)) {
				return;
			}

			const cartItemId = $(this).data('cart-item-id');
			removeCartItem(cartItemId);
		});

		// Update cart item
		function updateCartItem(cartItemId, quantity) {
			$.ajax({
				url: khaSolar.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_update_cart',
					nonce: khaSolar.nonce,
					cart_item_id: cartItemId,
					quantity: quantity
				},
				success: function(response) {
					if (response.success) {
						location.reload();
					}
				}
			});
		}

		// Remove cart item
		function removeCartItem(cartItemId) {
			$.ajax({
				url: khaSolar.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_remove_from_cart',
					nonce: khaSolar.nonce,
					cart_item_id: cartItemId
				},
				success: function(response) {
					if (response.success) {
						location.reload();
					}
				}
			});
		}

	});

})(jQuery);
