<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds custom classes to the array of body classes
 */
function khasolar_pro_extra_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'khasolar_pro_extra_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments
 */
function khasolar_pro_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'khasolar_pro_pingback_header' );

/**
 * Display logo or site title
 */
function khasolar_pro_site_branding() {
	if ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		?>
		<div class="site-title-wrapper">
			<h1 class="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?>
				</a>
			</h1>
			<?php
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}

/**
 * Display mini cart
 */
function khasolar_pro_mini_cart() {
	if ( ! function_exists( 'kha_get_cart_count' ) ) {
		return;
	}

	$cart_count = kha_get_cart_count();
	?>
	<div class="mini-cart">
		<a href="<?php echo esc_url( home_url( '/gio-hang' ) ); ?>" class="cart-icon">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
				<line x1="3" y1="6" x2="21" y2="6"></line>
				<path d="M16 10a4 4 0 0 1-8 0"></path>
			</svg>
			<?php if ( $cart_count > 0 ) : ?>
				<span class="cart-count"><?php echo esc_html( $cart_count ); ?></span>
			<?php endif; ?>
		</a>
	</div>
	<?php
}

/**
 * Display search icon
 */
function khasolar_pro_search_icon() {
	?>
	<div class="search-toggle">
		<button class="search-icon" aria-label="<?php esc_attr_e( 'Search', 'kha-solar-theme-pro' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<circle cx="11" cy="11" r="8"></circle>
				<path d="m21 21-4.35-4.35"></path>
			</svg>
		</button>
	</div>
	<div class="search-modal" style="display: none;">
		<div class="search-modal-content">
			<button class="search-close">&times;</button>
			<?php get_search_form(); ?>
		</div>
	</div>
	<?php
}

/**
 * Display contact info from theme options
 */
function khasolar_pro_contact_info() {
	$phone = khasolar_pro_get_option( 'contact_phone', '' );
	$email = khasolar_pro_get_option( 'contact_email', '' );

	if ( empty( $phone ) && empty( $email ) ) {
		return;
	}
	?>
	<div class="contact-info">
		<?php if ( ! empty( $phone ) ) : ?>
			<span class="contact-phone">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
				</svg>
				<a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
			</span>
		<?php endif; ?>

		<?php if ( ! empty( $email ) ) : ?>
			<span class="contact-email">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
					<polyline points="22,6 12,13 2,6"></polyline>
				</svg>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			</span>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Display social links
 */
function khasolar_pro_social_links() {
	$facebook  = khasolar_pro_get_option( 'social_facebook', '' );
	$twitter   = khasolar_pro_get_option( 'social_twitter', '' );
	$instagram = khasolar_pro_get_option( 'social_instagram', '' );
	$linkedin  = khasolar_pro_get_option( 'social_linkedin', '' );
	$youtube   = khasolar_pro_get_option( 'social_youtube', '' );

	if ( empty( $facebook ) && empty( $twitter ) && empty( $instagram ) && empty( $linkedin ) && empty( $youtube ) ) {
		return;
	}
	?>
	<div class="social-links">
		<?php if ( ! empty( $facebook ) ) : ?>
			<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer" class="social-facebook" aria-label="Facebook">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
			</a>
		<?php endif; ?>

		<?php if ( ! empty( $twitter ) ) : ?>
			<a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer" class="social-twitter" aria-label="Twitter">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
			</a>
		<?php endif; ?>

		<?php if ( ! empty( $instagram ) ) : ?>
			<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="social-instagram" aria-label="Instagram">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
			</a>
		<?php endif; ?>

		<?php if ( ! empty( $linkedin ) ) : ?>
			<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="social-linkedin" aria-label="LinkedIn">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
			</a>
		<?php endif; ?>

		<?php if ( ! empty( $youtube ) ) : ?>
			<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer" class="social-youtube" aria-label="YouTube">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
			</a>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Display back to top button
 */
function khasolar_pro_back_to_top() {
	if ( ! khasolar_pro_get_option( 'enable_back_to_top', true ) ) {
		return;
	}
	?>
	<a href="#" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'kha-solar-theme-pro' ); ?>">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
			<polyline points="18 15 12 9 6 15"></polyline>
		</svg>
	</a>
	<?php
}
add_action( 'wp_footer', 'khasolar_pro_back_to_top' );

/**
 * Display copyright text
 */
function khasolar_pro_copyright() {
	$copyright_text = khasolar_pro_get_option( 'copyright_text', '' );

	if ( empty( $copyright_text ) ) {
		$copyright_text = sprintf(
			/* translators: 1: year, 2: site name */
			esc_html__( 'Copyright &copy; %1$s %2$s. All rights reserved.', 'kha-solar-theme-pro' ),
			date_i18n( 'Y' ),
			get_bloginfo( 'name' )
		);
	}

	echo '<div class="copyright-text">' . wp_kses_post( $copyright_text ) . '</div>';
}

/**
 * Display payment icons
 */
function khasolar_pro_payment_icons() {
	if ( ! khasolar_pro_get_option( 'show_payment_icons', true ) ) {
		return;
	}
	?>
	<div class="payment-icons">
		<span class="payment-label"><?php esc_html_e( 'We accept:', 'kha-solar-theme-pro' ); ?></span>
		<img src="<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/payment-visa.svg' ); ?>" alt="Visa">
		<img src="<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/payment-mastercard.svg' ); ?>" alt="Mastercard">
		<img src="<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/payment-momo.svg' ); ?>" alt="MoMo">
		<img src="<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/payment-vnpay.svg' ); ?>" alt="VNPay">
	</div>
	<?php
}
