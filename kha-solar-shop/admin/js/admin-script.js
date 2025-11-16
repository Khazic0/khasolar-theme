/**
 * Kha Solar Shop - Admin Scripts
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		/**
		 * Product Gallery Management
		 */
		let productGalleryFrame;

		// Add images to gallery
		$('#kha_add_gallery_images').on('click', function(e) {
			e.preventDefault();

			// If the media frame already exists, reopen it.
			if (productGalleryFrame) {
				productGalleryFrame.open();
				return;
			}

			// Create the media frame.
			productGalleryFrame = wp.media({
				title: 'Select Product Images',
				button: {
					text: 'Add to Gallery'
				},
				multiple: true
			});

			// When images are selected, run a callback.
			productGalleryFrame.on('select', function() {
				const selection = productGalleryFrame.state().get('selection');
				const galleryIds = [];
				const $container = $('#kha_product_gallery_images');

				selection.map(function(attachment) {
					attachment = attachment.toJSON();
					galleryIds.push(attachment.id);

					// Add image to gallery
					$container.append(
						'<div class="kha-gallery-image" data-id="' + attachment.id + '">' +
							'<img src="' + attachment.sizes.thumbnail.url + '" alt="">' +
							'<button type="button" class="remove-gallery-image">×</button>' +
						'</div>'
					);
				});

				// Update hidden input with all IDs
				updateGalleryInput();
			});

			// Open the modal.
			productGalleryFrame.open();
		});

		// Remove image from gallery
		$(document).on('click', '.remove-gallery-image', function(e) {
			e.preventDefault();
			$(this).closest('.kha-gallery-image').remove();
			updateGalleryInput();
		});

		// Update gallery hidden input
		function updateGalleryInput() {
			const ids = [];
			$('#kha_product_gallery_images .kha-gallery-image').each(function() {
				const imageId = $(this).find('img').attr('src');
				// Extract ID from image element or data attribute
				const id = $(this).data('id');
				if (id) {
					ids.push(id);
				}
			});
			$('#kha_product_gallery').val(ids.join(','));
		}

		/**
		 * Order Management
		 */

		// Confirm order status change
		$('select[name="new_status"]').on('change', function() {
			const newStatus = $(this).val();
			const statusText = $(this).find('option:selected').text();

			if (!confirm('Are you sure you want to change the order status to: ' + statusText + '?')) {
				// Revert to previous value
				$(this).val($(this).data('previous-value'));
				return false;
			}
		}).on('focus', function() {
			// Store current value
			$(this).data('previous-value', $(this).val());
		});

		/**
		 * Auto-generate SKU
		 */
		$('#title').on('blur', function() {
			const $sku = $('#kha_product_sku');

			// Only auto-generate if SKU is empty
			if ($sku.val() === '') {
				const title = $(this).val();
				const sku = 'KHS-' + title
					.substring(0, 20)
					.toUpperCase()
					.replace(/[^A-Z0-9]/g, '-')
					.replace(/-+/g, '-')
					.replace(/^-|-$/g, '');

				$sku.val(sku);
			}
		});

		/**
		 * Price formatting helper
		 */
		$('input[name^="kha_product_"][name$="_price"]').on('blur', function() {
			const value = $(this).val();
			if (value) {
				// Round to nearest thousand
				const rounded = Math.round(value / 1000) * 1000;
				$(this).val(rounded);
			}
		});

		/**
		 * Stock management
		 */
		$('#kha_product_stock').on('change', function() {
			const stock = parseInt($(this).val()) || 0;
			const $status = $('#kha_product_stock_status');

			// Auto-update stock status based on quantity
			if (stock <= 0) {
				$status.val('outofstock');
			} else {
				$status.val('instock');
			}
		});

	});

})(jQuery);
