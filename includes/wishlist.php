<?php

/*
 * Wishlist page (YITH WooCommerce Wishlist): themed template (wishlist-page.php) around the wishlist shortcode.
 * Wishlist markup: woocommerce/wishlist-view.php (theme override, also used for the mobile view).
 */

/* Pages otherwise render through index.php without a container, so the wishlist gets its own template */
function tomatribe_wishlist_template($template) {
  if (function_exists('yith_wcwl_is_wishlist_page') && yith_wcwl_is_wishlist_page()) {
    $wishlist_template = locate_template('wishlist-page.php');
    if ($wishlist_template) return $wishlist_template;
  }
  return $template;
}
add_filter('template_include', 'tomatribe_wishlist_template');

/* "Copy link" feedback in the share section (woocommerce/share.php) */
function tomatribe_wishlist_scripts() {
  if (!function_exists('yith_wcwl_is_wishlist_page') || !yith_wcwl_is_wishlist_page()) return;

  wp_enqueue_script('wishlist-js', get_template_directory_uri() . '/assets/custom/js/wishlist.js', array('jquery'), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'tomatribe_wishlist_scripts', 20);
