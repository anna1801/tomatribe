<?php

/* Format an amount using WooCommerce decimal/thousand settings */
function tomatribe_format_price($amount) {
  return number_format((float) $amount, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());
}

/* Build "₹ min" or "₹ min – ₹ max" markup */
function tomatribe_price_range_html($min, $max) {
  $html = '<i class="fa fa-inr"></i> ' . tomatribe_format_price($min);
  if ((float) $min !== (float) $max) {
    $html .= ' – <i class="fa fa-inr"></i> ' . tomatribe_format_price($max);
  }
  return $html;
}

/*
 * Current and regular price markup for simple and variable products.
 * Returns null when the product has no price.
 */
function tomatribe_get_product_prices($product) {
  if ($product->is_type('variable')) {
    $min = $product->get_variation_price('min', true);
    $max = $product->get_variation_price('max', true);
    $regular_min = $product->get_variation_regular_price('min', true);
    $regular_max = $product->get_variation_regular_price('max', true);
  } else {
    if ($product->get_price() === '') return null;
    $min = $max = wc_get_price_to_display($product);
    $regular_min = $regular_max = wc_get_price_to_display($product, array('price' => $product->get_regular_price()));
  }

  if ($min === '' || $min === null) return null;

  $show_regular = $product->is_on_sale() && ((float) $regular_min !== (float) $min || (float) $regular_max !== (float) $max);

  return array(
    'price'   => tomatribe_price_range_html($min, $max),
    'regular' => $show_regular ? tomatribe_price_range_html($regular_min, $regular_max) : '',
  );
}

/* Five Font Awesome stars (full, half or empty) for an average rating */
function tomatribe_rating_stars_html($rating) {
  $html = '';
  for ($i = 1; $i <= 5; $i++) {
    if ($rating >= $i) {
      $html .= '<i class="fa fa-star"></i>';
    } elseif ($rating >= $i - 0.5) {
      $html .= '<i class="fa fa-star-half-o"></i>';
    } else {
      $html .= '<i class="fa fa-star-o"></i>';
    }
  }
  return $html;
}

?>
