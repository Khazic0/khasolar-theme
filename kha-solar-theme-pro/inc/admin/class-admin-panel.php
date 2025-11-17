<?php
/**
 * Admin Panel Class
 *
 * @package KhaSolarThemePro
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Panel Class
 */
class Khasolar_Pro_Admin_Panel {

	/**
	 * Option key
	 *
	 * @var string
	 */
	private $option_key = 'khasolar_pro_options';

	/**
	 * Options array
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_ajax_khasolar_pro_export_settings', array( $this, 'export_settings' ) );
		add_action( 'wp_ajax_khasolar_pro_import_settings', array( $this, 'import_settings' ) );
		add_action( 'wp_ajax_khasolar_pro_reset_settings', array( $this, 'reset_settings' ) );

		$this->options = get_option( $this->option_key, array() );
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_theme_page(
			__( 'Kha Solar Theme Pro Settings', 'kha-solar-theme-pro' ),
			__( 'Theme Settings', 'kha-solar-theme-pro' ),
			'manage_options',
			'khasolar-pro-settings',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting(
			'khasolar_pro_options_group',
			$this->option_key,
			array( $this, 'sanitize_options' )
		);
	}

	/**
	 * Sanitize options
	 *
	 * @param array $input Input options.
	 * @return array Sanitized options.
	 */
	public function sanitize_options( $input ) {
		$sanitized = array();

		// Text fields
		$text_fields = array(
			'site_logo',
			'contact_phone',
			'contact_email',
			'contact_address',
			'social_facebook',
			'social_twitter',
			'social_instagram',
			'social_linkedin',
			'social_youtube',
			'copyright_text',
			'primary_color',
			'secondary_color',
			'accent_color',
			'body_font',
			'heading_font',
			'body_font_size',
			'heading_font_size',
			'header_layout',
			'footer_columns',
		);

		foreach ( $text_fields as $field ) {
			if ( isset( $input[ $field ] ) ) {
				$sanitized[ $field ] = sanitize_text_field( $input[ $field ] );
			}
		}

		// Textarea fields
		$textarea_fields = array( 'custom_css', 'header_scripts', 'footer_scripts' );
		foreach ( $textarea_fields as $field ) {
			if ( isset( $input[ $field ] ) ) {
				$sanitized[ $field ] = wp_kses_post( $input[ $field ] );
			}
		}

		// Boolean fields
		$boolean_fields = array(
			'enable_sticky_header',
			'enable_search_icon',
			'enable_mini_cart',
			'enable_breadcrumbs',
			'enable_back_to_top',
			'show_payment_icons',
			'enable_homepage_builder',
			'enable_lazy_loading',
			'disable_emojis',
			'remove_query_strings',
		);

		foreach ( $boolean_fields as $field ) {
			$sanitized[ $field ] = isset( $input[ $field ] ) ? (bool) $input[ $field ] : false;
		}

		// Homepage sections
		if ( isset( $input['homepage_sections'] ) && is_array( $input['homepage_sections'] ) ) {
			$sanitized['homepage_sections'] = array_map( 'sanitize_text_field', $input['homepage_sections'] );
		}

		// Homepage sections order
		if ( isset( $input['homepage_sections_order'] ) && is_array( $input['homepage_sections_order'] ) ) {
			$sanitized['homepage_sections_order'] = array_map( 'sanitize_text_field', $input['homepage_sections_order'] );
		}

		return $sanitized;
	}

	/**
	 * Render admin page
	 */
	public function render_admin_page() {
		?>
		<div class="wrap khasolar-pro-admin-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<?php settings_errors(); ?>

			<div class="khasolar-pro-admin-container">
				<form method="post" action="options.php" class="khasolar-pro-admin-form">
					<?php settings_fields( 'khasolar_pro_options_group' ); ?>

					<div class="khasolar-pro-tabs">
						<ul class="khasolar-pro-tabs-nav">
							<li><a href="#tab-general" class="active"><?php esc_html_e( 'General', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-header"><?php esc_html_e( 'Header', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-footer"><?php esc_html_e( 'Footer', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-colors"><?php esc_html_e( 'Colors & Styling', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-typography"><?php esc_html_e( 'Typography', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-homepage"><?php esc_html_e( 'Homepage Builder', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-shop"><?php esc_html_e( 'Shop Settings', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-advanced"><?php esc_html_e( 'Advanced', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-import-export"><?php esc_html_e( 'Import/Export', 'kha-solar-theme-pro' ); ?></a></li>
							<li><a href="#tab-system-info"><?php esc_html_e( 'System Info', 'kha-solar-theme-pro' ); ?></a></li>
						</ul>

						<div class="khasolar-pro-tabs-content">
							<?php $this->render_tab_general(); ?>
							<?php $this->render_tab_header(); ?>
							<?php $this->render_tab_footer(); ?>
							<?php $this->render_tab_colors(); ?>
							<?php $this->render_tab_typography(); ?>
							<?php $this->render_tab_homepage(); ?>
							<?php $this->render_tab_shop(); ?>
							<?php $this->render_tab_advanced(); ?>
							<?php $this->render_tab_import_export(); ?>
							<?php $this->render_tab_system_info(); ?>
						</div>
					</div>

					<?php submit_button( __( 'Save Settings', 'kha-solar-theme-pro' ), 'primary large' ); ?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render General tab
	 */
	private function render_tab_general() {
		?>
		<div id="tab-general" class="khasolar-pro-tab-panel active">
			<h2><?php esc_html_e( 'General Settings', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="site_logo"><?php esc_html_e( 'Custom Logo', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $this->option_key ); ?>[site_logo]" id="site_logo" value="<?php echo esc_attr( $this->get_option( 'site_logo' ) ); ?>" class="regular-text" />
						<button type="button" class="button khasolar-upload-button" data-target="#site_logo"><?php esc_html_e( 'Upload Logo', 'kha-solar-theme-pro' ); ?></button>
						<p class="description"><?php esc_html_e( 'Upload a custom logo or use WordPress Customizer.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="contact_phone"><?php esc_html_e( 'Contact Phone', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $this->option_key ); ?>[contact_phone]" id="contact_phone" value="<?php echo esc_attr( $this->get_option( 'contact_phone' ) ); ?>" class="regular-text" />
						<p class="description"><?php esc_html_e( 'Display phone number in header/footer.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="contact_email"><?php esc_html_e( 'Contact Email', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="email" name="<?php echo esc_attr( $this->option_key ); ?>[contact_email]" id="contact_email" value="<?php echo esc_attr( $this->get_option( 'contact_email' ) ); ?>" class="regular-text" />
						<p class="description"><?php esc_html_e( 'Display email address in header/footer.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="contact_address"><?php esc_html_e( 'Address', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<textarea name="<?php echo esc_attr( $this->option_key ); ?>[contact_address]" id="contact_address" rows="3" class="large-text"><?php echo esc_textarea( $this->get_option( 'contact_address' ) ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Display address in footer.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Social Links', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<p>
							<label for="social_facebook"><?php esc_html_e( 'Facebook', 'kha-solar-theme-pro' ); ?></label><br>
							<input type="url" name="<?php echo esc_attr( $this->option_key ); ?>[social_facebook]" id="social_facebook" value="<?php echo esc_attr( $this->get_option( 'social_facebook' ) ); ?>" class="regular-text" placeholder="https://facebook.com/username" />
						</p>
						<p>
							<label for="social_twitter"><?php esc_html_e( 'Twitter', 'kha-solar-theme-pro' ); ?></label><br>
							<input type="url" name="<?php echo esc_attr( $this->option_key ); ?>[social_twitter]" id="social_twitter" value="<?php echo esc_attr( $this->get_option( 'social_twitter' ) ); ?>" class="regular-text" placeholder="https://twitter.com/username" />
						</p>
						<p>
							<label for="social_instagram"><?php esc_html_e( 'Instagram', 'kha-solar-theme-pro' ); ?></label><br>
							<input type="url" name="<?php echo esc_attr( $this->option_key ); ?>[social_instagram]" id="social_instagram" value="<?php echo esc_attr( $this->get_option( 'social_instagram' ) ); ?>" class="regular-text" placeholder="https://instagram.com/username" />
						</p>
						<p>
							<label for="social_linkedin"><?php esc_html_e( 'LinkedIn', 'kha-solar-theme-pro' ); ?></label><br>
							<input type="url" name="<?php echo esc_attr( $this->option_key ); ?>[social_linkedin]" id="social_linkedin" value="<?php echo esc_attr( $this->get_option( 'social_linkedin' ) ); ?>" class="regular-text" placeholder="https://linkedin.com/in/username" />
						</p>
						<p>
							<label for="social_youtube"><?php esc_html_e( 'YouTube', 'kha-solar-theme-pro' ); ?></label><br>
							<input type="url" name="<?php echo esc_attr( $this->option_key ); ?>[social_youtube]" id="social_youtube" value="<?php echo esc_attr( $this->get_option( 'social_youtube' ) ); ?>" class="regular-text" placeholder="https://youtube.com/c/username" />
						</p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Performance', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[enable_lazy_loading]" value="1" <?php checked( $this->get_option( 'enable_lazy_loading', true ), true ); ?> />
							<?php esc_html_e( 'Enable lazy loading for images', 'kha-solar-theme-pro' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[disable_emojis]" value="1" <?php checked( $this->get_option( 'disable_emojis', false ), true ); ?> />
							<?php esc_html_e( 'Disable WordPress emojis', 'kha-solar-theme-pro' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[remove_query_strings]" value="1" <?php checked( $this->get_option( 'remove_query_strings', false ), true ); ?> />
							<?php esc_html_e( 'Remove query strings from static resources', 'kha-solar-theme-pro' ); ?>
						</label>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Header tab
	 */
	private function render_tab_header() {
		?>
		<div id="tab-header" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'Header Settings', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="header_layout"><?php esc_html_e( 'Header Layout', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<select name="<?php echo esc_attr( $this->option_key ); ?>[header_layout]" id="header_layout">
							<option value="layout-1" <?php selected( $this->get_option( 'header_layout', 'layout-1' ), 'layout-1' ); ?>><?php esc_html_e( 'Layout 1 - Classic', 'kha-solar-theme-pro' ); ?></option>
							<option value="layout-2" <?php selected( $this->get_option( 'header_layout' ), 'layout-2' ); ?>><?php esc_html_e( 'Layout 2 - Centered', 'kha-solar-theme-pro' ); ?></option>
							<option value="layout-3" <?php selected( $this->get_option( 'header_layout' ), 'layout-3' ); ?>><?php esc_html_e( 'Layout 3 - Split', 'kha-solar-theme-pro' ); ?></option>
						</select>
						<p class="description"><?php esc_html_e( 'Choose header layout style.', 'kha-solar-theme-pro' ); ?></p>

						<div class="header-layout-preview">
							<img src="<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/header-layout-1.jpg' ); ?>" alt="Layout 1" class="header-preview" data-layout="layout-1">
							<img src="<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/header-layout-2.jpg' ); ?>" alt="Layout 2" class="header-preview" data-layout="layout-2">
							<img src="<?php echo esc_url( KHASOLAR_PRO_URI . '/assets/images/header-layout-3.jpg' ); ?>" alt="Layout 3" class="header-preview" data-layout="layout-3">
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Header Options', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[enable_sticky_header]" value="1" <?php checked( $this->get_option( 'enable_sticky_header', true ), true ); ?> />
							<?php esc_html_e( 'Enable sticky header on scroll', 'kha-solar-theme-pro' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[enable_search_icon]" value="1" <?php checked( $this->get_option( 'enable_search_icon', true ), true ); ?> />
							<?php esc_html_e( 'Show search icon in header', 'kha-solar-theme-pro' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[enable_mini_cart]" value="1" <?php checked( $this->get_option( 'enable_mini_cart', true ), true ); ?> />
							<?php esc_html_e( 'Show mini cart icon in header', 'kha-solar-theme-pro' ); ?>
						</label>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Footer tab
	 */
	private function render_tab_footer() {
		?>
		<div id="tab-footer" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'Footer Settings', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="footer_columns"><?php esc_html_e( 'Footer Widget Columns', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<select name="<?php echo esc_attr( $this->option_key ); ?>[footer_columns]" id="footer_columns">
							<option value="2" <?php selected( $this->get_option( 'footer_columns', '4' ), '2' ); ?>><?php esc_html_e( '2 Columns', 'kha-solar-theme-pro' ); ?></option>
							<option value="3" <?php selected( $this->get_option( 'footer_columns', '4' ), '3' ); ?>><?php esc_html_e( '3 Columns', 'kha-solar-theme-pro' ); ?></option>
							<option value="4" <?php selected( $this->get_option( 'footer_columns', '4' ), '4' ); ?>><?php esc_html_e( '4 Columns', 'kha-solar-theme-pro' ); ?></option>
						</select>
						<p class="description"><?php esc_html_e( 'Number of widget columns in footer.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="copyright_text"><?php esc_html_e( 'Copyright Text', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<textarea name="<?php echo esc_attr( $this->option_key ); ?>[copyright_text]" id="copyright_text" rows="3" class="large-text"><?php echo esc_textarea( $this->get_option( 'copyright_text' ) ); ?></textarea>
						<p class="description"><?php esc_html_e( 'HTML allowed. Leave blank for default copyright.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Footer Options', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[show_payment_icons]" value="1" <?php checked( $this->get_option( 'show_payment_icons', true ), true ); ?> />
							<?php esc_html_e( 'Show payment method icons', 'kha-solar-theme-pro' ); ?>
						</label>
						<br>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[enable_back_to_top]" value="1" <?php checked( $this->get_option( 'enable_back_to_top', true ), true ); ?> />
							<?php esc_html_e( 'Enable back to top button', 'kha-solar-theme-pro' ); ?>
						</label>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Colors tab
	 */
	private function render_tab_colors() {
		?>
		<div id="tab-colors" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'Colors & Styling', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="primary_color"><?php esc_html_e( 'Primary Color', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $this->option_key ); ?>[primary_color]" id="primary_color" value="<?php echo esc_attr( $this->get_option( 'primary_color', '#0066cc' ) ); ?>" class="khasolar-color-picker" />
						<p class="description"><?php esc_html_e( 'Main brand color.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="secondary_color"><?php esc_html_e( 'Secondary Color', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $this->option_key ); ?>[secondary_color]" id="secondary_color" value="<?php echo esc_attr( $this->get_option( 'secondary_color', '#6c757d' ) ); ?>" class="khasolar-color-picker" />
						<p class="description"><?php esc_html_e( 'Secondary accent color.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="accent_color"><?php esc_html_e( 'Accent Color', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="text" name="<?php echo esc_attr( $this->option_key ); ?>[accent_color]" id="accent_color" value="<?php echo esc_attr( $this->get_option( 'accent_color', '#28a745' ) ); ?>" class="khasolar-color-picker" />
						<p class="description"><?php esc_html_e( 'Highlight and CTA color.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Color Presets', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<button type="button" class="button color-preset" data-primary="#0066cc" data-secondary="#6c757d" data-accent="#28a745"><?php esc_html_e( 'Default (Blue)', 'kha-solar-theme-pro' ); ?></button>
						<button type="button" class="button color-preset" data-primary="#e74c3c" data-secondary="#95a5a6" data-accent="#f39c12"><?php esc_html_e( 'Red & Orange', 'kha-solar-theme-pro' ); ?></button>
						<button type="button" class="button color-preset" data-primary="#27ae60" data-secondary="#34495e" data-accent="#f39c12"><?php esc_html_e( 'Green & Gold', 'kha-solar-theme-pro' ); ?></button>
						<button type="button" class="button color-preset" data-primary="#9b59b6" data-secondary="#7f8c8d" data-accent="#e74c3c"><?php esc_html_e( 'Purple & Red', 'kha-solar-theme-pro' ); ?></button>
						<p class="description"><?php esc_html_e( 'Click to apply preset color scheme.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Typography tab
	 */
	private function render_tab_typography() {
		$google_fonts = array(
			'System Default',
			'Open Sans',
			'Roboto',
			'Lato',
			'Poppins',
			'Montserrat',
			'Raleway',
			'Playfair Display',
			'Merriweather',
			'PT Sans',
			'Ubuntu',
		);
		?>
		<div id="tab-typography" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'Typography', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="body_font"><?php esc_html_e( 'Body Font', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<select name="<?php echo esc_attr( $this->option_key ); ?>[body_font]" id="body_font">
							<?php foreach ( $google_fonts as $font ) : ?>
								<option value="<?php echo esc_attr( $font ); ?>" <?php selected( $this->get_option( 'body_font', 'Open Sans' ), $font ); ?>><?php echo esc_html( $font ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Choose font family for body text.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="heading_font"><?php esc_html_e( 'Heading Font', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<select name="<?php echo esc_attr( $this->option_key ); ?>[heading_font]" id="heading_font">
							<?php foreach ( $google_fonts as $font ) : ?>
								<option value="<?php echo esc_attr( $font ); ?>" <?php selected( $this->get_option( 'heading_font', 'Poppins' ), $font ); ?>><?php echo esc_html( $font ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Choose font family for headings.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="body_font_size"><?php esc_html_e( 'Body Font Size', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="number" name="<?php echo esc_attr( $this->option_key ); ?>[body_font_size]" id="body_font_size" value="<?php echo esc_attr( $this->get_option( 'body_font_size', '16' ) ); ?>" min="12" max="24" step="1" /> px
						<p class="description"><?php esc_html_e( 'Base font size (12-24px).', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="heading_font_size"><?php esc_html_e( 'H1 Font Size', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="number" name="<?php echo esc_attr( $this->option_key ); ?>[heading_font_size]" id="heading_font_size" value="<?php echo esc_attr( $this->get_option( 'heading_font_size', '40' ) ); ?>" min="24" max="72" step="1" /> px
						<p class="description"><?php esc_html_e( 'H1 heading size (24-72px).', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Homepage tab
	 */
	private function render_tab_homepage() {
		$sections = array(
			'hero'         => __( 'Hero Slider', 'kha-solar-theme-pro' ),
			'features'     => __( 'Features Grid', 'kha-solar-theme-pro' ),
			'products'     => __( 'Products Showcase', 'kha-solar-theme-pro' ),
			'stats'        => __( 'Stats Counter', 'kha-solar-theme-pro' ),
			'testimonials' => __( 'Testimonials', 'kha-solar-theme-pro' ),
			'cta'          => __( 'Call to Action', 'kha-solar-theme-pro' ),
			'about'        => __( 'About Section', 'kha-solar-theme-pro' ),
			'blog'         => __( 'Latest Blog Posts', 'kha-solar-theme-pro' ),
		);
		?>
		<div id="tab-homepage" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'Homepage Builder', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Enable Homepage Builder', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[enable_homepage_builder]" value="1" <?php checked( $this->get_option( 'enable_homepage_builder', true ), true ); ?> />
							<?php esc_html_e( 'Use homepage builder sections', 'kha-solar-theme-pro' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Disable to show default page content.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Homepage Sections', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<div id="homepage-sections-sortable" class="homepage-sections">
							<?php
							$enabled_sections = $this->get_option( 'homepage_sections', array_fill_keys( array_keys( $sections ), true ) );
							foreach ( $sections as $key => $label ) :
								$checked = isset( $enabled_sections[ $key ] ) ? $enabled_sections[ $key ] : false;
								?>
								<div class="section-item" data-section="<?php echo esc_attr( $key ); ?>">
									<span class="dashicons dashicons-menu"></span>
									<label>
										<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[homepage_sections][<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( $checked, true ); ?> />
										<?php echo esc_html( $label ); ?>
									</label>
								</div>
							<?php endforeach; ?>
						</div>
						<p class="description"><?php esc_html_e( 'Check to enable sections and drag to reorder.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Shop tab
	 */
	private function render_tab_shop() {
		?>
		<div id="tab-shop" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'Shop Settings', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Product Layout', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<select name="<?php echo esc_attr( $this->option_key ); ?>[shop_layout]">
							<option value="grid" <?php selected( $this->get_option( 'shop_layout', 'grid' ), 'grid' ); ?>><?php esc_html_e( 'Grid View', 'kha-solar-theme-pro' ); ?></option>
							<option value="list" <?php selected( $this->get_option( 'shop_layout' ), 'list' ); ?>><?php esc_html_e( 'List View', 'kha-solar-theme-pro' ); ?></option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Products Per Page', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="number" name="<?php echo esc_attr( $this->option_key ); ?>[products_per_page]" value="<?php echo esc_attr( $this->get_option( 'products_per_page', '12' ) ); ?>" min="6" max="48" step="6" />
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Product Card Style', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<select name="<?php echo esc_attr( $this->option_key ); ?>[product_card_style]">
							<option value="style-1" <?php selected( $this->get_option( 'product_card_style', 'style-1' ), 'style-1' ); ?>><?php esc_html_e( 'Style 1 - Classic', 'kha-solar-theme-pro' ); ?></option>
							<option value="style-2" <?php selected( $this->get_option( 'product_card_style' ), 'style-2' ); ?>><?php esc_html_e( 'Style 2 - Modern', 'kha-solar-theme-pro' ); ?></option>
							<option value="style-3" <?php selected( $this->get_option( 'product_card_style' ), 'style-3' ); ?>><?php esc_html_e( 'Style 3 - Minimal', 'kha-solar-theme-pro' ); ?></option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Advanced tab
	 */
	private function render_tab_advanced() {
		?>
		<div id="tab-advanced" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'Advanced Settings', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="custom_css"><?php esc_html_e( 'Custom CSS', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<textarea name="<?php echo esc_attr( $this->option_key ); ?>[custom_css]" id="custom_css" rows="10" class="large-text code"><?php echo esc_textarea( $this->get_option( 'custom_css' ) ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Add custom CSS code here. Will be added to <head>.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="header_scripts"><?php esc_html_e( 'Header Scripts', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<textarea name="<?php echo esc_attr( $this->option_key ); ?>[header_scripts]" id="header_scripts" rows="5" class="large-text code"><?php echo esc_textarea( $this->get_option( 'header_scripts' ) ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Scripts to be inserted in <head>. Google Analytics, etc.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="footer_scripts"><?php esc_html_e( 'Footer Scripts', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<textarea name="<?php echo esc_attr( $this->option_key ); ?>[footer_scripts]" id="footer_scripts" rows="5" class="large-text code"><?php echo esc_textarea( $this->get_option( 'footer_scripts' ) ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Scripts to be inserted before </body>. Chat widgets, etc.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Breadcrumbs', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $this->option_key ); ?>[enable_breadcrumbs]" value="1" <?php checked( $this->get_option( 'enable_breadcrumbs', true ), true ); ?> />
							<?php esc_html_e( 'Enable breadcrumbs navigation', 'kha-solar-theme-pro' ); ?>
						</label>
						<br>
						<label for="breadcrumb_separator"><?php esc_html_e( 'Separator:', 'kha-solar-theme-pro' ); ?></label>
						<input type="text" name="<?php echo esc_attr( $this->option_key ); ?>[breadcrumb_separator]" id="breadcrumb_separator" value="<?php echo esc_attr( $this->get_option( 'breadcrumb_separator', '/' ) ); ?>" style="width: 60px;" />
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Import/Export tab
	 */
	private function render_tab_import_export() {
		?>
		<div id="tab-import-export" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'Import/Export Settings', 'kha-solar-theme-pro' ); ?></h2>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Export Settings', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<button type="button" class="button button-primary" id="khasolar-export-settings">
							<?php esc_html_e( 'Export Settings', 'kha-solar-theme-pro' ); ?>
						</button>
						<p class="description"><?php esc_html_e( 'Download theme settings as JSON file.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Import Settings', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<input type="file" id="khasolar-import-file" accept=".json" />
						<button type="button" class="button button-secondary" id="khasolar-import-settings">
							<?php esc_html_e( 'Import Settings', 'kha-solar-theme-pro' ); ?>
						</button>
						<p class="description"><?php esc_html_e( 'Upload and apply settings JSON file.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Reset Settings', 'kha-solar-theme-pro' ); ?></label>
					</th>
					<td>
						<button type="button" class="button button-secondary" id="khasolar-reset-settings">
							<?php esc_html_e( 'Reset to Defaults', 'kha-solar-theme-pro' ); ?>
						</button>
						<p class="description"><?php esc_html_e( 'Reset all theme settings to default values.', 'kha-solar-theme-pro' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Render System Info tab
	 */
	private function render_tab_system_info() {
		global $wp_version;
		?>
		<div id="tab-system-info" class="khasolar-pro-tab-panel">
			<h2><?php esc_html_e( 'System Information', 'kha-solar-theme-pro' ); ?></h2>

			<table class="widefat striped">
				<tbody>
					<tr>
						<td><strong><?php esc_html_e( 'Theme Version', 'kha-solar-theme-pro' ); ?></strong></td>
						<td><?php echo esc_html( KHASOLAR_PRO_VERSION ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'WordPress Version', 'kha-solar-theme-pro' ); ?></strong></td>
						<td><?php echo esc_html( $wp_version ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'PHP Version', 'kha-solar-theme-pro' ); ?></strong></td>
						<td><?php echo esc_html( phpversion() ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Server Software', 'kha-solar-theme-pro' ); ?></strong></td>
						<td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'MySQL Version', 'kha-solar-theme-pro' ); ?></strong></td>
						<td><?php echo esc_html( $GLOBALS['wpdb']->db_version() ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Active Plugins', 'kha-solar-theme-pro' ); ?></strong></td>
						<td><?php echo esc_html( count( get_option( 'active_plugins' ) ) ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Kha Solar Shop Plugin', 'kha-solar-theme-pro' ); ?></strong></td>
						<td>
							<?php
							if ( defined( 'KHA_SOLAR_SHOP_VERSION' ) ) {
								echo '<span style="color: green;">✓ ' . esc_html__( 'Active', 'kha-solar-theme-pro' ) . ' (v' . esc_html( KHA_SOLAR_SHOP_VERSION ) . ')</span>';
							} else {
								echo '<span style="color: red;">✗ ' . esc_html__( 'Not Active', 'kha-solar-theme-pro' ) . '</span>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Memory Limit', 'kha-solar-theme-pro' ); ?></strong></td>
						<td><?php echo esc_html( ini_get( 'memory_limit' ) ); ?></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Max Upload Size', 'kha-solar-theme-pro' ); ?></strong></td>
						<td><?php echo esc_html( size_format( wp_max_upload_size() ) ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Get option value
	 *
	 * @param string $key Option key.
	 * @param mixed  $default Default value.
	 * @return mixed Option value.
	 */
	private function get_option( $key, $default = '' ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : $default;
	}

	/**
	 * Export settings
	 */
	public function export_settings() {
		check_ajax_referer( 'khasolar_pro_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'kha-solar-theme-pro' ) ) );
		}

		$options = get_option( $this->option_key, array() );
		$export  = array(
			'version'   => KHASOLAR_PRO_VERSION,
			'timestamp' => current_time( 'timestamp' ),
			'options'   => $options,
		);

		wp_send_json_success( array( 'data' => wp_json_encode( $export, JSON_PRETTY_PRINT ) ) );
	}

	/**
	 * Import settings
	 */
	public function import_settings() {
		check_ajax_referer( 'khasolar_pro_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'kha-solar-theme-pro' ) ) );
		}

		$json = isset( $_POST['json'] ) ? wp_unslash( $_POST['json'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( empty( $json ) ) {
			wp_send_json_error( array( 'message' => __( 'No data provided.', 'kha-solar-theme-pro' ) ) );
		}

		$data = json_decode( $json, true );

		if ( ! $data || ! isset( $data['options'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid JSON format.', 'kha-solar-theme-pro' ) ) );
		}

		update_option( $this->option_key, $data['options'] );

		wp_send_json_success( array( 'message' => __( 'Settings imported successfully.', 'kha-solar-theme-pro' ) ) );
	}

	/**
	 * Reset settings
	 */
	public function reset_settings() {
		check_ajax_referer( 'khasolar_pro_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'kha-solar-theme-pro' ) ) );
		}

		delete_option( $this->option_key );

		wp_send_json_success( array( 'message' => __( 'Settings reset to defaults.', 'kha-solar-theme-pro' ) ) );
	}
}
