<?php

/* Number of items in the cart */
function tomatribe_cart_count() {
  return (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
}

/* Number of products in the YITH wishlist(s) */
function tomatribe_wishlist_count() {
  return function_exists('yith_wcwl_count_all_products') ? yith_wcwl_count_all_products() : 0;
}

/* Cart count markup for the desktop header and the mobile header */
function tomatribe_cart_count_html($mobile = false) {
  $class = $mobile ? 'tv-cart-count' : 'tv-count tv-cart-count';
  return '<span class="' . $class . '">' . esc_html(tomatribe_cart_count()) . '</span>';
}

/* Refresh the cart counts whenever WooCommerce updates the cart via AJAX */
function tomatribe_cart_count_fragments($fragments) {
  $fragments['.tv-icon-with-count .tv-cart-count'] = tomatribe_cart_count_html();
  $fragments['.tv-mobile-cart .tv-cart-count'] = tomatribe_cart_count_html(true);
  return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'tomatribe_cart_count_fragments');

/* AJAX: current wishlist count (used by custom.js after wishlist changes) */
function tomatribe_ajax_wishlist_count() {
  wp_send_json_success(array('count' => tomatribe_wishlist_count()));
}
add_action('wp_ajax_tomatribe_wishlist_count', 'tomatribe_ajax_wishlist_count');
add_action('wp_ajax_nopriv_tomatribe_wishlist_count', 'tomatribe_ajax_wishlist_count');

/* Cart fragments script (refreshes the cart count on the cart page, removals, etc.) + AJAX url for custom.js */
function tomatribe_header_counts_scripts() {
  if (function_exists('WC')) {
    wp_enqueue_script('wc-cart-fragments');
  }
  wp_localize_script('additional-js', 'tomatribe', array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
  ));
}
add_action('wp_enqueue_scripts', 'tomatribe_header_counts_scripts', 20);
