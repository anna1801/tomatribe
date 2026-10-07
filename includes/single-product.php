<?php

/*
 * Single product page (single-product.php): ACF fields, variation swatches,
 * sale discount, size guide and AJAX add to cart.
 */

/*
 * ACF fields:
 * - Swatch (Color attribute terms):       swatch color for the color buttons
 */
function tomatribe_product_acf_fields() {
  if (!function_exists('acf_add_local_field_group')) return;

  acf_add_local_field_group(array(
    'key'      => 'group_tomatribe_swatch',
    'title'    => 'Swatch',
    'fields'   => array(
      array(
        'key'          => 'field_tomatribe_swatch_color',
        'label'        => 'Swatch Color',
        'name'         => 'swatch_color',
        'type'         => 'color_picker',
        'instructions' => 'Color of the swatch on the product page. Without it the color name is shown.',
      ),
    ),
    'location' => array(
      array(array('param' => 'taxonomy', 'operator' => '==', 'value' => 'pa_color')),
      array(array('param' => 'taxonomy', 'operator' => '==', 'value' => 'pa_colour')),
    ),
  ));
}
add_action('acf/init', 'tomatribe_product_acf_fields');

/* Products use the ACF "product_tab" repeater instead of the main description editor */
function tomatribe_remove_product_editor() {
  remove_post_type_support('product', 'editor');
}
add_action('init', 'tomatribe_remove_product_editor', 20);

/* Category shown above the title and in the breadcrumb: the Yoast primary category, else the deepest assigned one */
function tomatribe_product_primary_category($product_id) {
  $primary = (int) get_post_meta($product_id, '_yoast_wpseo_primary_product_cat', true);
  if ($primary && has_term($primary, 'product_cat', $product_id)) {
    return get_term($primary, 'product_cat');
  }

  $terms = get_the_terms($product_id, 'product_cat');
  if (!$terms || is_wp_error($terms)) return null;

  $default = (int) get_option('default_product_cat');
  $best = null;
  $best_depth = -1;
  foreach ($terms as $term) {
    $depth = count(get_ancestors($term->term_id, 'product_cat', 'taxonomy'));
    // Prefer any real category over "Uncategorized"
    if ($term->term_id === $default) $depth = -0.5;
    if ($depth > $best_depth) {
      $best = $term;
      $best_depth = $depth;
    }
  }
  return $best;
}

/* "size", "color" or "text": how an attribute's options are displayed */
function tomatribe_attribute_type($attribute_name) {
  $name = strtolower(preg_replace('/^pa_/', '', $attribute_name));
  if (strpos($name, 'colo') !== false) return 'color';
  if (strpos($name, 'size') !== false) return 'size';
  return 'text';
}

/*
 * Options of a variation attribute in display order:
 * array( array('value' => option value used by the variation form, 'label' => name, 'color' => hex or '') )
 */
function tomatribe_attribute_choices($product, $attribute_name, $options) {
  $choices = array();

  if (taxonomy_exists($attribute_name)) {
    foreach (wc_get_product_terms($product->get_id(), $attribute_name, array('fields' => 'all')) as $term) {
      if (!in_array($term->slug, $options, true)) continue;
      $choices[] = array(
        'value' => $term->slug,
        'label' => apply_filters('woocommerce_variation_option_name', $term->name, $term, $attribute_name, $product),
        'color' => (string) sanitize_hex_color(get_term_meta($term->term_id, 'swatch_color', true)),
      );
    }
  } else {
    foreach ($options as $option) {
      $choices[] = array(
        'value' => $option,
        'label' => apply_filters('woocommerce_variation_option_name', $option, null, $attribute_name, $product),
        'color' => '',
      );
    }
  }

  return $choices;
}

/*
 * Sale discount in percent: array('percent' => highest discount, 'varies' => true when variations differ).
 * Null when the product isn't on sale.
 */
function tomatribe_product_discount($product) {
  if (!$product->is_on_sale()) return null;

  $pairs = array();
  if ($product->is_type('variable')) {
    $prices = $product->get_variation_prices(true);
    foreach ($prices['price'] as $id => $price) {
      $pairs[] = array($prices['regular_price'][$id], $price);
    }
  } else {
    $pairs[] = array(wc_get_price_to_display($product, array('price' => $product->get_regular_price())), wc_get_price_to_display($product));
  }

  $discounts = array();
  foreach ($pairs as $pair) {
    $regular = (float) $pair[0];
    $price = (float) $pair[1];
    $discounts[] = ($regular > 0 && $price < $regular) ? (int) round(100 * ($regular - $price) / $regular) : 0;
  }

  $percent = max($discounts);
  if ($percent < 1) return null;

  return array('percent' => $percent, 'varies' => count(array_unique($discounts)) > 1);
}

/* Size guide: the "size_guide" WYSIWYG field (ACF field group "Product Details") */
function tomatribe_size_guide($product) {
  if (!function_exists('get_field')) return '';
  return (string) get_field('size_guide', $product->get_id());
}

/* Product page tabs: ACF "product_tab" repeater (tab_name + description), plus reviews when enabled */
function tomatribe_product_tabs($product) {
  $tabs = array();

  $rows = function_exists('get_field') ? get_field('product_tab', $product->get_id()) : array();
  foreach ((array) $rows as $row) {
    if (!empty($row['tab_name'])) {
      $tabs[] = array('id' => 'tab-' . count($tabs), 'title' => $row['tab_name'], 'content' => wp_kses_post($row['description']));
    }
  }

  if (comments_open($product->get_id()) || $product->get_review_count()) {
    ob_start();
    tomatribe_review_summary($product);
    comments_template();
    $tabs[] = array('id' => 'reviews-tab', 'title' => sprintf('Reviews (%d)', $product->get_review_count()), 'content' => ob_get_clean());
  }

  return $tabs;
}

/* Reviews tab header: average rating, per-star breakdown and a link to the review form */
function tomatribe_review_summary($product) {
  $count = $product->get_review_count();
  if (!$count || !wc_review_ratings_enabled()) return;

  $average = (float) $product->get_average_rating();
  $rating_counts = $product->get_rating_counts();
  $can_review = comments_open($product->get_id()) && (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id()));
?>
  <div class="tp-review-summary">
    <div class="tp-review-score">
      <span class="tp-review-average"><?php echo esc_html(number_format($average, 1)); ?></span>
      <div>
        <span class="tp-stars" role="img" aria-label="<?php echo esc_attr(sprintf('Rated %s out of 5', number_format($average, 1))); ?>">
          <?php echo tomatribe_rating_stars_html($average); ?>
        </span>
        <p class="tp-review-total"><?php echo esc_html(sprintf(_n('Based on %d review', 'Based on %d reviews', $count), $count)); ?></p>
      </div>
    </div>

    <ul class="tp-review-bars">
      <?php for ($star = 5; $star >= 1; $star--) :
        $star_count = isset($rating_counts[$star]) ? (int) $rating_counts[$star] : 0;
        $percent = round($star_count / $count * 100);
      ?>
        <li>
          <span class="tp-review-bar-label"><?php echo $star; ?> <i class="fa fa-star"></i></span>
          <span class="tp-review-bar" role="img" aria-label="<?php echo esc_attr(sprintf('%d%% of reviews have %d stars', $percent, $star)); ?>"><span style="width: <?php echo $percent; ?>%"></span></span>
          <span class="tp-review-bar-count"><?php echo $star_count; ?></span>
        </li>
      <?php endfor; ?>
    </ul>

    <?php if ($can_review) : ?>
      <a class="tp-review-write" href="#review_form_wrapper"> Write a review </a>
    <?php endif; ?>
  </div>
<?php
}

/* Review list items: initials instead of the grey gravatar, and theme stars below the author line */
remove_action('woocommerce_review_before', 'woocommerce_review_display_gravatar', 10);
remove_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);

function tomatribe_review_avatar($comment) {
  $name = trim(get_comment_author($comment));
  echo '<span class="tp-review-avatar" aria-hidden="true">' . esc_html(mb_strtoupper(mb_substr($name ?: '?', 0, 1))) . '</span>';
}
add_action('woocommerce_review_before', 'tomatribe_review_avatar', 10);

function tomatribe_review_rating($comment) {
  $rating = (int) get_comment_meta($comment->comment_ID, 'rating', true);
  if (!$rating || !wc_review_ratings_enabled()) return;
  echo '<span class="tp-stars tp-review-stars" role="img" aria-label="' . esc_attr(sprintf('Rated %d out of 5', $rating)) . '">' . tomatribe_rating_stars_html($rating) . '</span>';
}
add_action('woocommerce_review_meta', 'tomatribe_review_rating', 20);

/* Out of stock variations are shown as unavailable (crossed out) swatches */
function tomatribe_variation_is_active($active, $variation) {
  return $active && $variation->is_in_stock();
}
add_filter('woocommerce_variation_is_active', 'tomatribe_variation_is_active', 10, 2);

/* Load all variations with the page (instead of via AJAX) so swatch availability works for larger size/color grids */
function tomatribe_ajax_variation_threshold() {
  return 100;
}
add_filter('woocommerce_ajax_variation_threshold', 'tomatribe_ajax_variation_threshold');

/*
 * AJAX add to cart from the product page form (simple and variable products).
 * Responds like WooCommerce's own add to cart (fragments + cart hash) so the header counts and mini cart refresh,
 * plus the success / error notices for the page's notices wrapper.
 */
function tomatribe_ajax_add_to_cart() {
  $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
  $quantity = isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : 1;
  $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;

  $variation = array();
  foreach ($_POST as $key => $value) {
    if (strpos($key, 'attribute_') === 0 && is_string($value)) {
      $variation[sanitize_title(wp_unslash($key))] = wc_clean(wp_unslash($value));
    }
  }

  $passed = $product_id && apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation);

  if ($passed && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation) !== false) {
    do_action('woocommerce_ajax_added_to_cart', $product_id);

    // "“Product” has been added to your cart. View cart". With redirect to cart it stays queued for the cart page.
    wc_add_to_cart_message(array($product_id => $quantity), true);
    $notices = get_option('woocommerce_cart_redirect_after_add') === 'yes' ? '' : wc_print_notices(true);

    // Same fragments as WC_AJAX::get_refreshed_fragments()
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();

    wp_send_json(array(
      'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array(
        'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
      )),
      'cart_hash' => WC()->cart->get_cart_hash(),
      'notices'   => $notices,
    ));
  }

  if (!wc_notice_count('error')) {
    wc_add_notice('Could not add this product to the cart. Please try again.', 'error');
  }
  wp_send_json_error(array('notices' => wc_print_notices(true)));
}
add_action('wp_ajax_tomatribe_add_to_cart', 'tomatribe_ajax_add_to_cart');
add_action('wp_ajax_nopriv_tomatribe_add_to_cart', 'tomatribe_ajax_add_to_cart');

/* Product page script */
function tomatribe_single_product_scripts() {
  if (!is_product()) return;

  wp_enqueue_script('wc-add-to-cart-variation');
  wp_enqueue_script('single-product-js', get_template_directory_uri() . '/assets/custom/js/single-product.js', array('jquery', 'slick-js', 'wc-add-to-cart-variation'), _S_VERSION, true);
  wp_localize_script('single-product-js', 'tomatribeProduct', array(
    'ajaxUrl'     => admin_url('admin-ajax.php'),
    'decimals'    => wc_get_price_decimals(),
    'decimalSep'  => wc_get_price_decimal_separator(),
    'thousandSep' => wc_get_price_thousand_separator(),
  ));
}
add_action('wp_enqueue_scripts', 'tomatribe_single_product_scripts', 20);
