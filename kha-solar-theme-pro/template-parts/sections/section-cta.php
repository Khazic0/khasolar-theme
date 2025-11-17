<?php
/**
 * Homepage Section - Call to Action
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

?>

<section class="cta-section section-padding bg-gradient">
	<div class="container">
		<div class="cta-content text-center text-white">
			<h2 class="cta-title"><?php echo esc_html( khasolar_pro_get_option( 'cta_title', __( 'Ready to Switch to Solar Energy?', 'kha-solar-theme-pro' ) ) ); ?></h2>
			<p class="cta-subtitle"><?php echo esc_html( khasolar_pro_get_option( 'cta_subtitle', __( 'Get a free consultation and quote today!', 'kha-solar-theme-pro' ) ) ); ?></p>
			<div class="cta-buttons">
				<a href="<?php echo esc_url( home_url( '/lien-he' ) ); ?>" class="btn btn-light btn-lg">
					<?php esc_html_e( 'Get Free Quote', 'kha-solar-theme-pro' ); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/tinh-toan' ) ); ?>" class="btn btn-outline-light btn-lg">
					<?php esc_html_e( 'Calculate Savings', 'kha-solar-theme-pro' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
