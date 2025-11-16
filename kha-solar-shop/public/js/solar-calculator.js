/**
 * Kha Solar Shop - Solar Calculator
 *
 * @package KhaSolar
 * @since   1.0.0
 */

(function($) {
	'use strict';

	$(document).ready(function() {

		$('.kha-calculator-form').on('submit', function(e) {
			e.preventDefault();

			const dailyUsage = parseFloat($('#kha_daily_usage').val());

			if (!dailyUsage || dailyUsage <= 0) {
				alert('Please enter a valid daily energy usage');
				return;
			}

			calculateSolarSystem(dailyUsage);
		});

		function calculateSolarSystem(dailyUsage) {
			$.ajax({
				url: khaSolar.ajaxUrl,
				type: 'POST',
				data: {
					action: 'kha_calculate_solar',
					nonce: khaSolar.nonce,
					daily_usage: dailyUsage
				},
				beforeSend: function() {
					$('.kha-calculate-btn').prop('disabled', true).text('Calculating...');
				},
				success: function(response) {
					if (response.success) {
						displayResults(response.data);
					} else {
						alert(response.data.message || 'Calculation failed');
					}
					$('.kha-calculate-btn').prop('disabled', false).text('Calculate System Size');
				},
				error: function() {
					alert('Error occurred during calculation');
					$('.kha-calculate-btn').prop('disabled', false).text('Calculate System Size');
				}
			});
		}

		function displayResults(data) {
			let html = '';

			html += '<div class="kha-results-header">';
			html += '<h3>Solar System Recommendations</h3>';
			html += '<p>Based on your daily usage of ' + data.daily_usage + ' kWh</p>';
			html += '</div>';

			html += '<div class="kha-results-grid">';

			html += '<div class="kha-result-card">';
			html += '<div class="kha-result-label">System Size</div>';
			html += '<div class="kha-result-value highlight">' + data.actual_system_size_kw + '<span class="kha-result-unit">kW</span></div>';
			html += '</div>';

			html += '<div class="kha-result-card">';
			html += '<div class="kha-result-label">Solar Panels Needed</div>';
			html += '<div class="kha-result-value">' + data.panel_count + '<span class="kha-result-unit">panels</span></div>';
			html += '<div class="kha-form-hint">(' + data.panel_wattage + 'W each)</div>';
			html += '</div>';

			html += '<div class="kha-result-card">';
			html += '<div class="kha-result-label">Monthly Production</div>';
			html += '<div class="kha-result-value">' + data.monthly_production + '<span class="kha-result-unit">kWh</span></div>';
			html += '</div>';

			html += '</div>';

			html += '<div class="kha-savings-highlight">';
			html += '<h4>Estimated Monthly Savings</h4>';
			html += '<div class="kha-savings-amount">' + formatCurrency(data.monthly_savings) + '</div>';
			html += '<p>Annual Savings: <strong>' + formatCurrency(data.annual_savings) + '</strong></p>';
			html += '</div>';

			if (data.recommended_products && data.recommended_products.length > 0) {
				html += '<div class="kha-recommended-products">';
				html += '<h4>Recommended Products for Your System</h4>';
				html += '<div class="kha-recommended-grid">';

				data.recommended_products.forEach(function(product) {
					html += '<div class="kha-product-card">';
					html += '<div class="kha-product-image">';
					if (product.image) {
						html += '<img src="' + product.image + '" alt="' + product.title + '">';
					}
					html += '</div>';
					html += '<div class="kha-product-info">';
					html += '<div class="kha-product-title"><a href="' + product.url + '">' + product.title + '</a></div>';
					if (product.price) {
						html += '<div class="kha-product-price"><span class="kha-price-current">' + product.price + '</span></div>';
					}
					html += '<button class="kha-btn kha-btn-primary kha-add-to-cart" data-product-id="' + product.id + '">Add to Cart</button>';
					html += '</div>';
					html += '</div>';
				});

				html += '</div>';
				html += '</div>';
			}

			$('.kha-calculator-results').html(html).addClass('active');
			$('.kha-calculator-results')[0].scrollIntoView({ behavior: 'smooth' });
		}

		function formatCurrency(amount) {
			return new Intl.NumberFormat('vi-VN', {
				style: 'decimal',
				minimumFractionDigits: 0,
				maximumFractionDigits: 0
			}).format(amount) + ' ₫';
		}

	});

})(jQuery);
