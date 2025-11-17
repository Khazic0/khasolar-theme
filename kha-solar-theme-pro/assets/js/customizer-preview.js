/**
 * Customizer Preview JavaScript
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

(function($) {
	'use strict';

	// Site title
	wp.customize('blogname', function(value) {
		value.bind(function(newVal) {
			$('.site-title a').text(newVal);
		});
	});

	// Site description
	wp.customize('blogdescription', function(value) {
		value.bind(function(newVal) {
			$('.site-description').text(newVal);
		});
	});

	// Container width
	wp.customize('khasolar_pro_container_width', function(value) {
		value.bind(function(newVal) {
			$('.container').css('max-width', newVal + 'px');
		});
	});

})(jQuery);
