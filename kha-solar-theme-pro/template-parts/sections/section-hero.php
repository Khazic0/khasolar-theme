<?php
/**
 * Homepage Section - Hero Slider
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>

<section class="hero-section">
	<div class="hero-slider swiper">
		<div class="swiper-wrapper">
			<!-- Slide 1 -->
			<div class="swiper-slide">
				<div class="hero-slide" style="background-image: url('<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/hero-1.jpg' ); ?>');">
					<div class="hero-overlay"></div>
					<div class="container">
						<div class="hero-content">
							<h1 class="hero-title" data-swiper-animation="fadeInUp">
								<?php echo esc_html( khasolar_pro_get_option( 'hero_title_1', __( 'Solar Energy Solutions', 'kha-solar-theme-pro' ) ) ); ?>
							</h1>
							<p class="hero-subtitle" data-swiper-animation="fadeInUp" data-swiper-animation-delay="200">
								<?php echo esc_html( khasolar_pro_get_option( 'hero_subtitle_1', __( 'Sustainable Power for a Brighter Future', 'kha-solar-theme-pro' ) ) ); ?>
							</p>
							<div class="hero-buttons" data-swiper-animation="fadeInUp" data-swiper-animation-delay="400">
								<a href="<?php echo esc_url( home_url( '/san-pham' ) ); ?>" class="btn btn-primary btn-lg">
									<?php esc_html_e( 'View Products', 'kha-solar-theme-pro' ); ?>
								</a>
								<a href="<?php echo esc_url( home_url( '/lien-he' ) ); ?>" class="btn btn-secondary btn-lg">
									<?php esc_html_e( 'Contact Us', 'kha-solar-theme-pro' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Slide 2 -->
			<div class="swiper-slide">
				<div class="hero-slide" style="background-image: url('<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/hero-2.jpg' ); ?>');">
					<div class="hero-overlay"></div>
					<div class="container">
						<div class="hero-content">
							<h1 class="hero-title" data-swiper-animation="fadeInLeft">
								<?php echo esc_html( khasolar_pro_get_option( 'hero_title_2', __( 'Quality Solar Panels', 'kha-solar-theme-pro' ) ) ); ?>
							</h1>
							<p class="hero-subtitle" data-swiper-animation="fadeInLeft" data-swiper-animation-delay="200">
								<?php echo esc_html( khasolar_pro_get_option( 'hero_subtitle_2', __( 'Premium products with warranty up to 25 years', 'kha-solar-theme-pro' ) ) ); ?>
							</p>
							<div class="hero-buttons" data-swiper-animation="fadeInLeft" data-swiper-animation-delay="400">
								<a href="<?php echo esc_url( home_url( '/tinh-toan' ) ); ?>" class="btn btn-primary btn-lg">
									<?php esc_html_e( 'Calculate Now', 'kha-solar-theme-pro' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Slide 3 -->
			<div class="swiper-slide">
				<div class="hero-slide" style="background-image: url('<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/hero-3.jpg' ); ?>');">
					<div class="hero-overlay"></div>
					<div class="container">
						<div class="hero-content">
							<h1 class="hero-title" data-swiper-animation="fadeInRight">
								<?php echo esc_html( khasolar_pro_get_option( 'hero_title_3', __( 'Professional Installation', 'kha-solar-theme-pro' ) ) ); ?>
							</h1>
							<p class="hero-subtitle" data-swiper-animation="fadeInRight" data-swiper-animation-delay="200">
								<?php echo esc_html( khasolar_pro_get_option( 'hero_subtitle_3', __( 'Expert team with 10+ years of experience', 'kha-solar-theme-pro' ) ) ); ?>
							</p>
							<div class="hero-buttons" data-swiper-animation="fadeInRight" data-swiper-animation-delay="400">
								<a href="<?php echo esc_url( home_url( '/gioi-thieu' ) ); ?>" class="btn btn-primary btn-lg">
									<?php esc_html_e( 'Learn More', 'kha-solar-theme-pro' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Navigation -->
		<div class="swiper-button-next"></div>
		<div class="swiper-button-prev"></div>

		<!-- Pagination -->
		<div class="swiper-pagination"></div>
	</div>
</section>
