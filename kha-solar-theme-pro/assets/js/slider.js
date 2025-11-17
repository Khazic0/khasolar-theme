/**
 * Slider JavaScript (Swiper Integration)
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		// Hero Slider
		if ($('.hero-slider').length) {
			new Swiper('.hero-slider', {
				loop: true,
				autoplay: {
					delay: 5000,
					disableOnInteraction: false
				},
				speed: 1000,
				effect: 'fade',
				fadeEffect: {
					crossFade: true
				},
				navigation: {
					nextEl: '.hero-slider .swiper-button-next',
					prevEl: '.hero-slider .swiper-button-prev'
				},
				pagination: {
					el: '.hero-slider .swiper-pagination',
					clickable: true
				}
			});
		}

		// Testimonials Slider
		if ($('.testimonials-slider').length) {
			new Swiper('.testimonials-slider', {
				loop: true,
				autoplay: {
					delay: 6000,
					disableOnInteraction: false
				},
				speed: 800,
				spaceBetween: 30,
				slidesPerView: 1,
				pagination: {
					el: '.testimonials-slider .swiper-pagination',
					clickable: true
				},
				breakpoints: {
					768: {
						slidesPerView: 2
					},
					1024: {
						slidesPerView: 3
					}
				}
			});
		}

	});

})(jQuery);
