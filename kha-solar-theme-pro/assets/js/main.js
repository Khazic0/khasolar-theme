/**
 * Main Theme JavaScript
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

(function($) {
	'use strict';

	// Document Ready
	$(document).ready(function() {

		// Smooth Scroll
		$('a[href^="#"]').not('[href="#"]').on('click', function(e) {
			var target = $(this.getAttribute('href'));
			if (target.length) {
				e.preventDefault();
				$('html, body').stop().animate({
					scrollTop: target.offset().top - 100
				}, 600);
			}
		});

		// Back to Top
		var $backToTop = $('.back-to-top');
		if ($backToTop.length) {
			$(window).on('scroll', function() {
				if ($(this).scrollTop() > 300) {
					$backToTop.addClass('show');
				} else {
					$backToTop.removeClass('show');
				}
			});

			$backToTop.on('click', function(e) {
				e.preventDefault();
				$('html, body').animate({ scrollTop: 0 }, 600);
			});
		}

		// Sticky Header
		var $header = $('.site-header');
		if ($('body').hasClass('has-sticky-header') && $header.length) {
			var headerOffset = $header.offset().top;
			var headerHeight = $header.outerHeight();

			$(window).on('scroll', function() {
				var scrollTop = $(this).scrollTop();
				if (scrollTop > headerOffset + headerHeight) {
					$header.addClass('sticky');
				} else {
					$header.removeClass('sticky');
				}
			});
		}

		// Search Modal
		$('.search-toggle .search-icon').on('click', function() {
			$('.search-modal').fadeIn(300);
			$('.search-modal .search-field').focus();
		});

		$('.search-close, .search-modal').on('click', function(e) {
			if (e.target === this) {
				$('.search-modal').fadeOut(300);
			}
		});

		// Stats Counter (Animated numbers)
		if ($('.counter').length) {
			var counted = false;
			$(window).on('scroll', function() {
				var $counters = $('.counter');
				var windowTop = $(window).scrollTop();
				var windowBottom = windowTop + $(window).height();

				$counters.each(function() {
					var $this = $(this);
					var elemTop = $this.offset().top;

					if (!$this.hasClass('counted') && elemTop < windowBottom) {
						$this.addClass('counted');
						$this.prop('Counter', 0).animate({
							Counter: $this.data('count')
						}, {
							duration: 2000,
							easing: 'swing',
							step: function(now) {
								$this.text(Math.ceil(now));
							}
						});
					}
				});
			});
		}

		// Product Tabs
		$('.product-tabs .tabs-nav a').on('click', function(e) {
			e.preventDefault();
			var target = $(this).attr('href');

			$(this).parent().addClass('active').siblings().removeClass('active');
			$(target).addClass('active').siblings().removeClass('active');
		});

	});

})(jQuery);
