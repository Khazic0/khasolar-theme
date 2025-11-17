<?php
/**
 * Homepage Section - Features Grid
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>

<section class="features-section section-padding">
	<div class="container">
		<div class="section-header text-center">
			<h2 class="section-title"><?php echo esc_html( khasolar_pro_get_option( 'features_title', __( 'Why Choose Us', 'kha-solar-theme-pro' ) ) ); ?></h2>
			<p class="section-subtitle"><?php echo esc_html( khasolar_pro_get_option( 'features_subtitle', __( 'Leading provider of solar energy solutions', 'kha-solar-theme-pro' ) ) ); ?></p>
		</div>

		<div class="features-grid">
			<div class="row">
				<!-- Feature 1 -->
				<div class="col-md-4">
					<div class="feature-item">
						<div class="feature-icon">
							<svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<circle cx="12" cy="12" r="5"></circle>
								<line x1="12" y1="1" x2="12" y2="3"></line>
								<line x1="12" y1="21" x2="12" y2="23"></line>
								<line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
								<line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
								<line x1="1" y1="12" x2="3" y2="12"></line>
								<line x1="21" y1="12" x2="23" y2="12"></line>
								<line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
								<line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
							</svg>
						</div>
						<h3 class="feature-title"><?php esc_html_e( 'High Efficiency', 'kha-solar-theme-pro' ); ?></h3>
						<p class="feature-description"><?php esc_html_e( 'Top-tier solar panels with efficiency up to 22.5%, maximizing energy output.', 'kha-solar-theme-pro' ); ?></p>
					</div>
				</div>

				<!-- Feature 2 -->
				<div class="col-md-4">
					<div class="feature-item">
						<div class="feature-icon">
							<svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
							</svg>
						</div>
						<h3 class="feature-title"><?php esc_html_e( '25-Year Warranty', 'kha-solar-theme-pro' ); ?></h3>
						<p class="feature-description"><?php esc_html_e( 'Comprehensive warranty coverage for peace of mind and long-term reliability.', 'kha-solar-theme-pro' ); ?></p>
					</div>
				</div>

				<!-- Feature 3 -->
				<div class="col-md-4">
					<div class="feature-item">
						<div class="feature-icon">
							<svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
								<circle cx="9" cy="7" r="4"></circle>
								<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
								<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
							</svg>
						</div>
						<h3 class="feature-title"><?php esc_html_e( 'Expert Support', 'kha-solar-theme-pro' ); ?></h3>
						<p class="feature-description"><?php esc_html_e( 'Professional installation and 24/7 customer support from certified technicians.', 'kha-solar-theme-pro' ); ?></p>
					</div>
				</div>

				<!-- Feature 4 -->
				<div class="col-md-4">
					<div class="feature-item">
						<div class="feature-icon">
							<svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
							</svg>
						</div>
						<h3 class="feature-title"><?php esc_html_e( 'Smart Monitoring', 'kha-solar-theme-pro' ); ?></h3>
						<p class="feature-description"><?php esc_html_e( 'Real-time monitoring app to track energy production and system performance.', 'kha-solar-theme-pro' ); ?></p>
					</div>
				</div>

				<!-- Feature 5 -->
				<div class="col-md-4">
					<div class="feature-item">
						<div class="feature-icon">
							<svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<line x1="12" y1="1" x2="12" y2="23"></line>
								<path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
							</svg>
						</div>
						<h3 class="feature-title"><?php esc_html_e( 'Cost Savings', 'kha-solar-theme-pro' ); ?></h3>
						<p class="feature-description"><?php esc_html_e( 'Reduce electricity bills up to 90% with our efficient solar solutions.', 'kha-solar-theme-pro' ); ?></p>
					</div>
				</div>

				<!-- Feature 6 -->
				<div class="col-md-4">
					<div class="feature-item">
						<div class="feature-icon">
							<svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
								<polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
								<line x1="12" y1="22.08" x2="12" y2="12"></line>
							</svg>
						</div>
						<h3 class="feature-title"><?php esc_html_e( 'Premium Quality', 'kha-solar-theme-pro' ); ?></h3>
						<p class="feature-description"><?php esc_html_e( 'Certified products from leading manufacturers worldwide with proven quality.', 'kha-solar-theme-pro' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
