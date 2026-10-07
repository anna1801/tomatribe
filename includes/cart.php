<?php

/*
 * Cart page: themed template (cart-page.php) around the [woocommerce_cart] shortcode.
 * Cart markup: woocommerce/cart/cart.php and cart-totals.php (theme overrides).
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

/* Cross-sells below the cart instead of inside the totals sidebar */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');

/* Shipping row label (cart & checkout totals): the matching shipping zone's name instead of "Shipping".
   Keeps the default for the "Locations not covered by your other zones" zone (id 0). */
function tomatribe_shipping_package_name($name, $index, $package) {
  if (!class_exists('WC_Shipping_Zones') || empty($package['destination'])) return $name;

  $zone = WC_Shipping_Zones::get_zone_matching_package($package);
  return ($zone && $zone->get_id() && $zone->get_zone_name()) ? $zone->get_zone_name() : $name;
}
add_filter('woocommerce_shipping_package_name', 'tomatribe_shipping_package_name', 10, 3);

/*
 * Free shipping progress bar on the cart: from the "Free shipping" method (minimum order amount) of the
 * customer's shipping zone. Null when the zone has no such method.
 */
function tomatribe_free_shipping_progress() {
  $cart = WC()->cart;
  if (!$cart || !$cart->needs_shipping() || !class_exists('WC_Shipping_Zones')) return null;

  $packages = $cart->get_shipping_packages();
  $package = reset($packages);
  if (!$package) return null;

  $zone = WC_Shipping_Zones::get_zone_matching_package($package);
  foreach ($zone->get_shipping_methods(true) as $method) {
    if ($method->id !== 'free_shipping' || !in_array($method->requires, array('min_amount', 'either'), true)) continue;

    $min = (float) wc_format_decimal($method->min_amount);
    if ($min <= 0) continue;

    // Same total WooCommerce checks (WC_Shipping_Free_Shipping::is_available)
    $total = $cart->get_displayed_subtotal();
    if ('no' === $method->ignore_discounts) {
      $total -= $cart->get_discount_total();
      if ($cart->display_prices_including_tax()) $total -= $cart->get_discount_tax();
    }
    $total = round($total, wc_get_price_decimals());

    return array(
      'remaining' => max(0, $min - $total),
      'percent'   => min(100, max(0, $total / $min * 100)),
    );
  }
  return null;
}

/* Cart & checkout totals: "Remove" instead of "[Remove]" after an applied coupon */
function tomatribe_cart_coupon_html($html) {
  if (!is_cart() && !is_checkout()) return $html;
  return str_replace('>' . __('[Remove]', 'woocommerce') . '</a>', '>' . __('Remove', 'woocommerce') . '</a>', $html);
}
add_filter('woocommerce_cart_totals_coupon_html', 'tomatribe_cart_coupon_html');

/* Quantity stepper, auto update and sidebar coupon (works with WooCommerce's cart.js) */
function tomatribe_cart_scripts() {
  if (!function_exists('is_cart') || !is_cart()) return;

  wp_enqueue_script('cart-js', get_template_directory_uri() . '/assets/custom/js/cart.js', array('jquery', 'wc-cart'), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'tomatribe_cart_scripts', 20);
