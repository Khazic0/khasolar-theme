<?php
/**
 * Template part for displaying a message when no content is found
 *
 * @package KhaSolarTheme
 * @since 1.0.0
 */
?>

<section class="no-results not-found">

	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Không tìm thấy nội dung', 'khasolar-theme' ); ?></h1>
	</header>

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'khasolar-theme' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :
			?>

			<p><?php esc_html_e( 'Xin lỗi, không tìm thấy kết quả nào phù hợp. Vui lòng thử lại với từ khóa khác.', 'khasolar-theme' ); ?></p>
			<?php
			get_search_form();

		else :
			?>

			<p><?php esc_html_e( 'Có vẻ như chúng tôi không thể tìm thấy nội dung bạn đang tìm kiếm. Vui lòng thử tìm kiếm.', 'khasolar-theme' ); ?></p>
			<?php
			get_search_form();

		endif;
		?>
	</div>

</section>
