/**
 * Chat Bubble JavaScript
 *
 * Handles chat bubble interactions, greeting display, and analytics tracking.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Chat Bubble Object
	 */
	var KhaChatBubble = {

		/**
		 * Cookie name for greeting display
		 */
		cookieName: 'kha_chat_greeting_seen',

		/**
		 * Initialize chat bubble
		 */
		init: function() {
			this.bindEvents();
			this.initAnimation();
			this.showGreetingIfNew();
		},

		/**
		 * Bind event handlers
		 */
		bindEvents: function() {
			// Greeting close button
			$('.kha-greeting-close').on('click', this.closeGreeting.bind(this));

			// Chat button clicks
			$('.kha-chat-button').on('click', this.trackClick.bind(this));

			// Phone modal (desktop)
			$('#kha-phone-button').on('click', this.showPhoneModal.bind(this));
			$('#kha-phone-modal-close').on('click', this.closePhoneModal.bind(this));
			$('.kha-phone-modal-overlay').on('click', this.closePhoneModal.bind(this));

			// Auto-hide greeting after 10 seconds
			setTimeout(function() {
				KhaChatBubble.closeGreeting();
			}, 10000);
		},

		/**
		 * Initialize animation based on settings
		 */
		initAnimation: function() {
			if (typeof khaChatConfig === 'undefined') {
				return;
			}

			var animation = khaChatConfig.animation;
			var $buttons = $('.kha-chat-button');

			if (animation === 'pulse') {
				$buttons.addClass('kha-anim-pulse');
			} else if (animation === 'bounce') {
				$buttons.addClass('kha-anim-bounce');
			}
		},

		/**
		 * Show greeting bubble if this is first visit
		 */
		showGreetingIfNew: function() {
			// Check if greeting was already seen
			if (this.getCookie(this.cookieName)) {
				return;
			}

			// Show greeting after 3 seconds delay
			setTimeout(function() {
				$('#kha-greeting-bubble').fadeIn(400);
			}, 3000);
		},

		/**
		 * Close greeting bubble
		 */
		closeGreeting: function(e) {
			if (e) {
				e.preventDefault();
			}

			$('#kha-greeting-bubble').fadeOut(300);

			// Set cookie to not show again for 24 hours
			this.setCookie(this.cookieName, '1', 1);
		},

		/**
		 * Show phone modal (desktop)
		 */
		showPhoneModal: function(e) {
			e.preventDefault();
			$('#kha-phone-modal').fadeIn(300);
			$('body').addClass('kha-modal-open');
		},

		/**
		 * Close phone modal
		 */
		closePhoneModal: function(e) {
			if (e) {
				e.preventDefault();
			}
			$('#kha-phone-modal').fadeOut(300);
			$('body').removeClass('kha-modal-open');
		},

		/**
		 * Track chat button clicks
		 */
		trackClick: function(e) {
			var $button = $(e.currentTarget);
			var channel = $button.data('channel');

			// Track with Google Analytics if available
			if (typeof gtag !== 'undefined') {
				gtag('event', 'chat_click', {
					'event_category': 'engagement',
					'event_label': channel,
					'value': 1
				});
			}

			// Track with Google Analytics (older version)
			if (typeof ga !== 'undefined') {
				ga('send', 'event', 'engagement', 'chat_click', channel);
			}

			// Track with Facebook Pixel if available
			if (typeof fbq !== 'undefined') {
				fbq('track', 'Contact', {
					channel: channel
				});
			}

			// Console log for debugging
			if (window.console && console.log) {
				console.log('Chat click tracked:', channel);
			}
		},

		/**
		 * Set cookie
		 *
		 * @param {string} name Cookie name
		 * @param {string} value Cookie value
		 * @param {number} days Days until expiration
		 */
		setCookie: function(name, value, days) {
			var expires = '';
			if (days) {
				var date = new Date();
				date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
				expires = '; expires=' + date.toUTCString();
			}
			document.cookie = name + '=' + (value || '') + expires + '; path=/';
		},

		/**
		 * Get cookie
		 *
		 * @param {string} name Cookie name
		 * @return {string|null} Cookie value or null
		 */
		getCookie: function(name) {
			var nameEQ = name + '=';
			var ca = document.cookie.split(';');
			for (var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) === ' ') {
					c = c.substring(1, c.length);
				}
				if (c.indexOf(nameEQ) === 0) {
					return c.substring(nameEQ.length, c.length);
				}
			}
			return null;
		}

	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		if ($('.kha-chat-bubble').length > 0) {
			KhaChatBubble.init();
		}
	});

})(jQuery);
