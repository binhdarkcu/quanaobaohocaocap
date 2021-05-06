<?php
define('TEMPLATE_PATH',get_bloginfo('template_url'));
define('HOME_URL',get_home_url());
define('BlOG_NAME',get_bloginfo('blog_name'));
define('SLOGAN', get_bloginfo('description'));


register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'supermarket-ecommerce' ),
) );

/* Excerpt Limit Begin */
function supermarket_ecommerce_string_limit_words($string, $word_limit) {
	$words = explode(' ', $string, ($word_limit + 1));
	if(count($words) > $word_limit)
	array_pop($words);
	return implode(' ', $words);
}

// Change number or products per row to 3
add_filter('loop_shop_columns', 'supermarket_ecommerce_loop_columns');
	if (!function_exists('supermarket_ecommerce_loop_columns')) {
		function supermarket_ecommerce_loop_columns() {
	return 3; // 3 products per row
	}
}

add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // Return the number of products you wanna show per page.
	if(wp_is_mobile() == TRUE) {
			$cols = 10;
	} else {
			$cols = 20;
	}

  return $cols;
}

add_theme_support('post-thumbnails',array('post','page','ads','tin_tuc'));

add_filter( 'wc_add_to_cart_message_html', 'custom_add_to_cart_message_html', 10, 2 );
function custom_add_to_cart_message_html( $message, $products ) {
    $count = 0;
    foreach ( $products as $product_id => $qty ) {
        $count += $qty;
    }
    // The custom message is just below
    $added_text = sprintf( _n("%s Sản phẩm %s", "%s Sản phẩm %s", $count, "woocommerce" ),
        $count, __("đã được thêm vào giỏ hàng.", "woocommerce") );

    // Output success messages
    if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
        $return_to = apply_filters( 'woocommerce_continue_shopping_redirect', wc_get_raw_referer() ? wp_validate_redirect( wc_get_raw_referer(), false ) : wc_get_page_permalink( 'shop' ) );
        $message   = sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', esc_url( $return_to ), esc_html__( 'Continue shopping', 'woocommerce' ), esc_html( $added_text ) );
    } else {
        $message   = sprintf( '<a href="%s" style="margin-left: 8px!important;" class="button wc-forward">%s</a> %s', esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'Xem giỏ hàng', 'woocommerce' ), esc_html( $added_text ) );
    }
    return $message;
}


add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );

function post_remove ()      //creating functions post_remove for removing menu item
{
   remove_menu_page('edit.php');
}

add_action('admin_menu', 'post_remove');   //adding action for triggering function call

function cau_truc_schema_product ($data) {
    global $product;

    $data['brand'] = $product->get_attribute('pa_brand') ? $product->get_attribute('pa_brand') : null;
    $data['mpn'] = $product->get_sku() ? $product->get_sku() : null;
    $data['id'] = $product->get_id() ? $product->get_id() : null;

    return $data;
}
add_filter( 'woocommerce_structured_data_product', 'cau_truc_schema_product' );

add_action( 'wp_enqueue_scripts', 'wsis_dequeue_stylesandscripts_select2', 100 );

function wsis_dequeue_stylesandscripts_select2() {
    if ( class_exists( 'woocommerce' ) ) {
        wp_dequeue_style( 'selectWoo' );
        wp_deregister_style( 'selectWoo' );

        wp_dequeue_script( 'selectWoo');
        wp_deregister_script('selectWoo');
    }
}