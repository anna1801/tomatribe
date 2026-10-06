<?php

/*
 * Front-end search returns products only.
 * Hidden products ("Search results" visibility off) and, if WooCommerce is set to hide them,
 * out-of-stock products are excluded.
 */
function tomatribe_product_search_query($query) {
  if (is_admin() || !$query->is_main_query() || !$query->is_search() || !function_exists('WC')) {
    return;
  }

  $query->set('post_type', 'product');
  $query->set('posts_per_page', 12);
  $query->set('tax_query', WC()->query->get_tax_query((array) $query->get('tax_query'), true));
}
add_action('pre_get_posts', 'tomatribe_product_search_query');
