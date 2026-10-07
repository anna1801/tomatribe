<?php

/*
 * My Account page: themed template (account-page.php) around the [woocommerce_my_account] shortcode,
 * for every endpoint (dashboard, orders, view order, downloads, addresses, payment methods, account details)
 * and the logged out login / register / lost password forms.
 * Markup overrides: woocommerce/myaccount/navigation.php, dashboard.php, view-order.php.
 */

/* Pages otherwise render through index.php without a container, so the account gets its own template */
function tomatribe_account_template($template) {
  if (function_exists('is_account_page') && is_account_page()) {
    $account_template = locate_template('account-page.php');
    if ($account_template) return $account_template;
  }
  return $template;
}
add_filter('template_include', 'tomatribe_account_template');

/* Page heading: the current endpoint's title ("Orders", "Order #187", "Addresses" ...), else the page title */
function tomatribe_account_title() {
  if (!is_user_logged_in()) {
    return is_wc_endpoint_url('lost-password') ? __('Lost password', 'woocommerce') : get_the_title();
  }

  $endpoint = WC()->query->get_current_endpoint();
  $title = $endpoint ? WC()->query->get_endpoint_title($endpoint) : '';
  return $title ? $title : get_the_title();
}

/* Account menu icons (Pe-icon-7-stroke), by endpoint; other plugins' items get a generic one */
function tomatribe_account_menu_icon($endpoint) {
  $icons = array(
    'dashboard'       => 'pe-7s-home',
    'orders'          => 'pe-7s-box2',
    'downloads'       => 'pe-7s-download',
    'edit-address'    => 'pe-7s-map-marker',
    'payment-methods' => 'pe-7s-credit',
    'edit-account'    => 'pe-7s-user',
    'customer-logout' => 'pe-7s-power',
  );
  return isset($icons[$endpoint]) ? $icons[$endpoint] : 'pe-7s-angle-right';
}

/* Initials for the account avatar: first + last name, else the display name */
function tomatribe_account_initials($user) {
  $name = trim($user->first_name . ' ' . $user->last_name);
  if ($name === '') $name = $user->display_name;

  $initials = '';
  foreach (array_slice(preg_split('/\s+/', $name), 0, 2) as $part) {
    $initials .= function_exists('mb_substr') ? mb_substr($part, 0, 1) : substr($part, 0, 1);
  }
  return function_exists('mb_strtoupper') ? mb_strtoupper($initials) : strtoupper($initials);
}
