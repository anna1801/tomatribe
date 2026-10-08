<?php

/*
 * Checkout page: themed template (checkout-page.php) around the [woocommerce_checkout] shortcode,
 * also used for the order received (thank you) and order pay endpoints.
 * Checkout markup: woocommerce/checkout/form-checkout.php and review-order.php (theme overrides).
 */

/* Pages otherwise render through index.php without a container, so the checkout gets its own template */
function tomatribe_checkout_template($template) {
  if (function_exists('is_checkout') && is_checkout()) {
    $checkout_template = locate_template('checkout-page.php');
    if ($checkout_template) return $checkout_template;
  }
  return $template;
}
add_filter('template_include', 'tomatribe_checkout_template');

/*
 * Order received page and My Account > view order: product thumbnail before each item name in the order details table.
 * The name filter is only added while that table's items render, so order emails sent in the same request are untouched.
 */
function tomatribe_order_item_thumbnail($name, $item) {
  $product = is_callable(array($item, 'get_product')) ? $item->get_product() : null;
  if (!$product) return $name;

  return '<span class="tp-order-item-thumb">' . $product->get_image('woocommerce_thumbnail') . '</span><span class="tp-order-item-name">' . $name . '</span>';
}

function tomatribe_order_item_thumbnails_on() {
  if (is_wc_endpoint_url('order-received') || is_wc_endpoint_url('view-order')) {
    add_filter('woocommerce_order_item_name', 'tomatribe_order_item_thumbnail', 10, 2);
  }
}
add_action('woocommerce_order_details_before_order_table_items', 'tomatribe_order_item_thumbnails_on');

function tomatribe_order_item_thumbnails_off() {
  remove_filter('woocommerce_order_item_name', 'tomatribe_order_item_thumbnail', 10);
}
add_action('woocommerce_order_details_after_order_table_items', 'tomatribe_order_item_thumbnails_off');

/*
 * Stripe card fields: they render inside Stripe's iframe, so page CSS can't reach them.
 * The Stripe plugin uses wc_stripe_upe_params['appearance'] (Stripe Appearance API) instead of
 * copying styles from the page when it is set: square, bordered fields like the other checkout inputs.
 * https://docs.stripe.com/elements/appearance-api
 */
function tomatribe_stripe_appearance($params) {
  if (!is_array($params)) return $params;

  $params['appearance'] = array(
    'theme'     => 'stripe',
    'variables' => array(
      'colorPrimary'         => '#222222',
      'colorBackground'      => '#ffffff',
      'colorText'            => '#222222',
      'colorTextSecondary'   => '#777777',
      'colorTextPlaceholder' => '#aaaaaa',
      'colorDanger'          => '#c0392b',
      'colorIcon'            => '#777777',
      'fontFamily'           => 'Outfit, sans-serif',
      'fontSizeBase'         => '15px',
      'borderRadius'         => '0px',
      'spacingUnit'          => '4px',
      'gridRowSpacing'       => '18px',
      'gridColumnSpacing'    => '16px',
      'focusBoxShadow'       => 'none',
      'focusOutline'         => 'none',
    ),
    'rules'     => array(
      '.Label'              => array(
        'marginBottom'  => '7px',
        'color'         => '#222222',
        'fontSize'      => '11px',
        'fontWeight'    => '700',
        'letterSpacing' => '1.5px',
        'textTransform' => 'uppercase',
      ),
      '.Input'              => array(
        'padding'   => '14px 16px',
        'border'    => '1px solid #e2e2e2',
        'boxShadow' => 'none',
        'fontSize'  => '15px',
      ),
      '.Input:hover'        => array(
        'border' => '1px solid #c9c9c9',
      ),
      '.Input:focus'        => array(
        'border'    => '1px solid #222222',
        'boxShadow' => 'none',
      ),
      '.Input--invalid'     => array(
        'border'    => '1px solid #c0392b',
        'boxShadow' => 'none',
        'color'     => '#c0392b',
      ),
      '.Input::placeholder' => array(
        'color' => '#aaaaaa',
      ),
      '.Error'              => array(
        'marginTop' => '6px',
        'fontSize'  => '12px',
      ),
      // Payment method list / tabs (Card, and any other enabled methods)
      '.AccordionItem'      => array(
        'border'    => '1px solid #e8dfcd',
        'boxShadow' => 'none',
      ),
      '.Tab'                => array(
        'border'    => '1px solid #e2e2e2',
        'boxShadow' => 'none',
      ),
      '.Tab:hover'          => array(
        'border' => '1px solid #222222',
      ),
      '.Tab--selected'      => array(
        'border'    => '1px solid #222222',
        'boxShadow' => 'none',
      ),
      '.TabLabel--selected' => array(
        'color' => '#222222',
      ),
      '.CheckboxInput'      => array(
        'borderRadius' => '0px',
      ),
    ),
  );

  return $params;
}
add_filter('wc_stripe_upe_params', 'tomatribe_stripe_appearance');
