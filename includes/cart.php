<?php

/*
 * Cart page: themed template (cart-page.php) around the WooCommerce Cart block.
 */

/* Pages otherwise render through index.php without a container, so the cart gets its own template */
function tomatribe_cart_template($template) {
  if (function_exists('is_cart') && is_cart()) {
    $cart_template = locate_template('cart-page.php');
    if ($cart_template) return $cart_template;
  }
  return $template;
}
add_filter('template_include', 'tomatribe_cart_template');
