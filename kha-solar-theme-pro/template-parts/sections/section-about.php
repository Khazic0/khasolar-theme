<?php
/**
 * Homepage Section - About Us
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>

<section class="about-section section-padding">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="about-image">
					<img src="<?php echo esc_url( khasolar_pro_get_option( 'about_image', KHASOLAR_PRO_URI . '/assets/images/about.jpg' ) ); ?>" alt="<?php esc_attr_e( 'About Kha Solar', 'kha-solar-theme-pro' ); ?>">
					<div class="about-badge">
						<div class="badge-content">
							<span class="badge-number">25+</span>
							<span class="badge-text"><?php esc_html_e( 'Years', 'kha-solar-theme-pro' ); ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="about-content">
					<h2 class="section-title"><?php echo esc_html( khasolar_pro_get_option( 'about_title', __( 'Leading Solar Energy Provider', 'kha-solar-theme-pro' ) ) ); ?></h2>
					<p class="about-description">
						<?php
						echo wp_kses_post(
							khasolar_pro_get_option(
								'about_description',
								__( 'With over 25 years of experience in the renewable energy industry, we have helped thousands of homes and businesses transition to clean, sustainable solar power. Our mission is to make solar energy accessible and affordable for everyone.', 'kha-solar-theme-pro' )
							)
						);
						?>
					</p>
					<ul class="about-features">
						<li>
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="20 6 9 17 4 12"></polyline>
							</svg>
							<?php esc_html_e( 'Certified and experienced installation team', 'kha-solar-theme-pro' ); ?>
						</li>
						<li>
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="20 6 9 17 4 12"></polyline>
							</svg>
							<?php esc_html_e( 'Premium products from top manufacturers', 'kha-solar-theme-pro' ); ?>
						</li>
						<li>
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="20 6 9 17 4 12"></polyline>
							</svg>
							<?php esc_html_e( 'Comprehensive warranty and support', 'kha-solar-theme-pro' ); ?>
						</li>
						<li>
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<polyline points="20 6 9 17 4 12"></polyline>
							</svg>
							<?php esc_html_e( 'Competitive pricing and financing options', 'kha-solar-theme-pro' ); ?>
						</li>
					</ul>
					<a href="<?php echo esc_url( home_url( '/gioi-thieu' ) ); ?>" class="btn btn-primary btn-lg">
						<?php esc_html_e( 'Learn More About Us', 'kha-solar-theme-pro' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
