<?php
/**
 * The template for displaying 404 pages (not found)
 * @package WordPress
 * @subpackage supermarket-ecommerce
 * @since 1.0
 * @version 0.1
 */

get_header(); ?>

<div class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Xin Lỗi,Sóc Vàng Không Tìm Thấy Trang Bạn Yêu Cầu.','supermarket-ecommerce' ); ?></h1>
					<div class="home-btn">
						<a href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'Trở Về Trang Chủ','supermarket-ecommerce' ); ?></a>
					</div>
				</header>
				<div class="page-content">
					<p><?php esc_html_e( 'Bạn Có Thể Tìm Kiếm Sản Phẩm Bạn Mong Muốn Ở Mục Search Bên Dưới?','supermarket-ecommerce' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</section>
		</main>
	</div>
</div>

<?php get_footer();