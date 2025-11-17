/**
 * Admin Panel JavaScript
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

(function($) {
	'use strict';

	// Tab switching
	$('.khasolar-pro-tabs-nav a').on('click', function(e) {
		e.preventDefault();
		var target = $(this).attr('href');

		$('.khasolar-pro-tabs-nav a').removeClass('active');
		$(this).addClass('active');

		$('.khasolar-pro-tab-panel').removeClass('active');
		$(target).addClass('active');
	});

	// Color Picker
	$('.khasolar-color-picker').wpColorPicker();

	// Color Presets
	$('.color-preset').on('click', function() {
		var primary = $(this).data('primary');
		var secondary = $(this).data('secondary');
		var accent = $(this).data('accent');

		$('#primary_color').wpColorPicker('color', primary);
		$('#secondary_color').wpColorPicker('color', secondary);
		$('#accent_color').wpColorPicker('color', accent);
	});

	// Media Uploader
	$('.khasolar-upload-button').on('click', function(e) {
		e.preventDefault();

		var button = $(this);
		var targetInput = button.data('target');

		var mediaUploader = wp.media({
			title: khasolarProAdmin.selectImage,
			button: { text: khasolarProAdmin.useImage },
			multiple: false
		});

		mediaUploader.on('select', function() {
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			$(targetInput).val(attachment.url);
		});

		mediaUploader.open();
	});

	// Sortable Sections
	if ($('#homepage-sections-sortable').length) {
		$('#homepage-sections-sortable').sortable({
			handle: '.dashicons-menu',
			placeholder: 'section-item-placeholder',
			update: function() {
				// Handle order updates if needed
			}
		});
	}

	// Export Settings
	$('#khasolar-export-settings').on('click', function() {
		if (!confirm(khasolarProAdmin.confirmExport)) {
			return;
		}

		$.ajax({
			url: khasolarProAdmin.ajaxurl,
			type: 'POST',
			data: {
				action: 'khasolar_pro_export_settings',
				nonce: khasolarProAdmin.nonce
			},
			success: function(response) {
				if (response.success) {
					var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(response.data.data);
					var downloadAnchor = document.createElement('a');
					downloadAnchor.setAttribute("href", dataStr);
					downloadAnchor.setAttribute("download", "khasolar-pro-settings-" + Date.now() + ".json");
					downloadAnchor.click();
				}
			}
		});
	});

	// Import Settings
	$('#khasolar-import-settings').on('click', function() {
		var fileInput = $('#khasolar-import-file')[0];

		if (!fileInput.files.length) {
			alert('Please select a file first.');
			return;
		}

		if (!confirm(khasolarProAdmin.confirmImport)) {
			return;
		}

		var reader = new FileReader();
		reader.onload = function(e) {
			$.ajax({
				url: khasolarProAdmin.ajaxurl,
				type: 'POST',
				data: {
					action: 'khasolar_pro_import_settings',
					nonce: khasolarProAdmin.nonce,
					json: e.target.result
				},
				success: function(response) {
					if (response.success) {
						alert(response.data.message);
						location.reload();
					} else {
						alert(response.data.message);
					}
				}
			});
		};
		reader.readAsText(fileInput.files[0]);
	});

	// Reset Settings
	$('#khasolar-reset-settings').on('click', function() {
		if (!confirm('Are you sure you want to reset all settings? This cannot be undone.')) {
			return;
		}

		$.ajax({
			url: khasolarProAdmin.ajaxurl,
			type: 'POST',
			data: {
				action: 'khasolar_pro_reset_settings',
				nonce: khasolarProAdmin.nonce
			},
			success: function(response) {
				if (response.success) {
					alert(response.data.message);
					location.reload();
				}
			}
		});
	});

})(jQuery);
