<?php
/**
 * Solar Calculator Template
 *
 * @package KhaSolar
 * @since   1.0.0
 */
?>

<div class="kha-calculator-wrapper">
	<div class="kha-calculator-form">
		<h2><?php esc_html_e( 'Solar System Calculator', 'kha-solar' ); ?></h2>
		<p style="text-align: center; color: #666; margin-bottom: 30px;">
			<?php esc_html_e( 'Calculate the perfect solar system size for your energy needs', 'kha-solar' ); ?>
		</p>

		<form id="kha-solar-calculator-form">
			<div class="kha-form-group">
				<label for="kha_daily_usage" class="kha-form-label">
					<?php esc_html_e( 'Average Daily Energy Usage (kWh)', 'kha-solar' ); ?>
				</label>
				<input
					type="number"
					id="kha_daily_usage"
					name="daily_usage"
					class="kha-form-input"
					placeholder="15"
					step="0.1"
					required
				>
				<p class="kha-form-hint">
					<?php esc_html_e( 'Enter your average daily electricity consumption in kWh (check your electricity bill)', 'kha-solar' ); ?>
				</p>
			</div>

			<button type="submit" class="kha-calculate-btn">
				<?php esc_html_e( 'Calculate System Size', 'kha-solar' ); ?>
			</button>
		</form>
	</div>

	<div class="kha-calculator-results"></div>
</div>

<style>
@media (max-width: 768px) {
	.kha-calculator-wrapper {
		padding: 0 15px;
	}
}
</style>
