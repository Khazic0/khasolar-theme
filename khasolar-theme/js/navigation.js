/**
 * Navigation JavaScript
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 *
 * @package KhaSolarTheme
 */

(function($) {
	'use strict';

	// Mobile menu toggle.
	var menuToggle = $('.menu-toggle');
	var siteNavigation = $('#site-navigation');

	menuToggle.on('click', function() {
		siteNavigation.toggleClass('toggled');
		$(this).attr('aria-expanded', siteNavigation.hasClass('toggled'));
	});

	// Smooth scroll for anchor links.
	$('a[href^="#"]').on('click', function(e) {
		var target = $(this.getAttribute('href'));
		if (target.length) {
			e.preventDefault();
			$('html, body').stop().animate({
				scrollTop: target.offset().top - 100
			}, 600);
		}
	});

	// Sticky header on scroll.
	var header = $('.site-header');
	var headerHeight = header.outerHeight();

	$(window).on('scroll', function() {
		if ($(window).scrollTop() > headerHeight) {
			header.addClass('scrolled');
		} else {
			header.removeClass('scrolled');
		}
	});

	// Add dropdown indicator to menu items with children.
	$('.main-navigation .menu-item-has-children > a').after('<button class="dropdown-toggle" aria-label="Toggle submenu"><span class="dashicons dashicons-arrow-down-alt2"></span></button>');

	// Toggle dropdowns on mobile.
	$('.main-navigation .dropdown-toggle').on('click', function(e) {
		e.preventDefault();
		$(this).toggleClass('toggled');
		$(this).next('.sub-menu').slideToggle();
	});

	// Keyboard navigation for dropdowns.
	$('.main-navigation li a').on('focus', function() {
		$(this).parents('li').addClass('focus');
	});

	$('.main-navigation li a').on('blur', function() {
		$(this).parents('li').removeClass('focus');
	});

	// Back to top button.
	if ($('.back-to-top').length === 0) {
		$('body').append('<a href="#" class="back-to-top" title="Back to top"><span class="dashicons dashicons-arrow-up-alt2"></span></a>');
	}

	var backToTop = $('.back-to-top');

	$(window).on('scroll', function() {
		if ($(window).scrollTop() > 300) {
			backToTop.fadeIn();
		} else {
			backToTop.fadeOut();
		}
	});

	backToTop.on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({scrollTop: 0}, 600);
		return false;
	});

})(jQuery);
