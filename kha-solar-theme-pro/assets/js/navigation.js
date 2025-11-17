/**
 * Navigation JavaScript
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

(function($) {
	'use strict';

	// Mobile Menu Toggle
	$('.menu-toggle').on('click', function() {
		$(this).toggleClass('active');
		$('#site-navigation').toggleClass('toggled');
		$('body').toggleClass('menu-open');
	});

	// Submenu Toggle for Mobile
	$('.main-navigation .menu-item-has-children > a').on('click', function(e) {
		if ($(window).width() <= 768) {
			e.preventDefault();
			$(this).parent().toggleClass('open');
			$(this).next('.sub-menu').slideToggle(300);
		}
	});

	// Close menu when clicking outside
	$(document).on('click', function(e) {
		if (!$(e.target).closest('#site-navigation, .menu-toggle').length) {
			if ($('#site-navigation').hasClass('toggled')) {
				$('.menu-toggle').removeClass('active');
				$('#site-navigation').removeClass('toggled');
				$('body').removeClass('menu-open');
			}
		}
	});

	// Close menu on ESC key
	$(document).on('keyup', function(e) {
		if (e.key === 'Escape' && $('#site-navigation').hasClass('toggled')) {
			$('.menu-toggle').removeClass('active');
			$('#site-navigation').removeClass('toggled');
			$('body').removeClass('menu-open');
		}
	});

})(jQuery);
