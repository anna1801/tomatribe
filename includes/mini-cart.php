<?php

/* Mini cart is not used on the cart & checkout pages (header cart icon links to the cart there) */
function tomatribe_show_mini_cart() {
  return function_exists('WC') && !is_cart() && !is_checkout();
}

/* Off-canvas mini cart content (items, totals, buttons) - also returned as a cart fragment */
function tomatribe_mini_cart_html() {
  if (!function_exists('WC') || !WC()->cart) {
    return '';
  }

  $cart = WC()->cart;

  ob_start();
  ?>
  <div class="minicart-content-box">
    <?php if ($cart->is_empty()) : ?>
      <div class="minicart-empty">
        <i class="pe-7s-shopbag"></i>
        <p><?php esc_html_e('Your cart is currently empty.', 'tomatribe'); ?></p>
        <div class="minicart-button">
          <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><i class="fa fa-shopping-bag"></i> <?php esc_html_e('Continue Shopping', 'tomatribe'); ?></a>
        </div>
      </div>
    <?php else : ?>
      <div class="minicart-item-wrapper">
        <ul>
          <?php foreach ($cart->get_cart() as $cart_item_key => $cart_item) :
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)) {
              continue;
            }

            $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $permalink    = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
            $thumbnail    = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
            $price        = apply_filters('woocommerce_cart_item_price', $cart->get_product_price($_product), $cart_item, $cart_item_key);
            ?>
            <li class="minicart-item">
              <div class="minicart-thumb">
                <?php if ($permalink) : ?>
                  <a href="<?php echo esc_url($permalink); ?>"><?php echo $thumbnail; ?></a>
                <?php else : ?>
                  <?php echo $thumbnail; ?>
                <?php endif; ?>
              </div>
              <div class="minicart-content">
                <h3 class="product-name">
                  <?php if ($permalink) : ?>
                    <a href="<?php echo esc_url($permalink); ?>"><?php echo wp_kses_post($product_name); ?></a>
                  <?php else : ?>
                    <?php echo wp_kses_post($product_name); ?>
                  <?php endif; ?>
                </h3>
                <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                <p>
                  <span class="cart-quantity"><?php echo esc_html($cart_item['quantity']); ?> <strong>&times;</strong></span>
                  <span class="cart-price"><?php echo $price; ?></span>
                </p>
              </div>
              <?php
                // "remove_from_cart_button" is handled by WooCommerce's add-to-cart script (AJAX remove + fragment refresh)
                printf(
                  '<a href="%s" class="minicart-remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><i class="pe-7s-close"></i></a>',
                  esc_url(wc_get_cart_remove_url($cart_item_key)),
                  esc_attr(sprintf(__('Remove %s from cart', 'tomatribe'), wp_strip_all_tags($product_name))),
                  esc_attr($_product->get_id()),
                  esc_attr($cart_item_key),
                  esc_attr($_product->get_sku())
                );
              ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="minicart-pricing-box">
        <ul>
          <li>
            <span><?php esc_html_e('Sub-total', 'tomatribe'); ?></span>
            <span><strong><?php echo $cart->get_cart_subtotal(); ?></strong></span>
          </li>
          <?php foreach ($cart->get_coupons() as $code => $coupon) : ?>
            <li>
              <span><?php echo esc_html(sprintf(__('Coupon: %s', 'tomatribe'), $code)); ?></span>
              <span><strong>-<?php echo wc_price($cart->get_coupon_discount_amount($code, $cart->display_cart_ex_tax)); ?></strong></span>
            </li>
          <?php endforeach; ?>
          <?php if (wc_tax_enabled() && !$cart->display_prices_including_tax()) : ?>
            <?php foreach ($cart->get_tax_totals() as $tax) : ?>
              <li>
                <span><?php echo esc_html($tax->label); ?></span>
                <span><strong><?php echo wp_kses_post($tax->formatted_amount); ?></strong></span>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
          <li class="total">
            <span><?php esc_html_e('Total', 'tomatribe'); ?></span>
            <span><strong><?php echo $cart->get_total(); ?></strong></span>
          </li>
        </ul>
      </div>

      <div class="minicart-button">
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>"><i class="fa fa-shopping-cart"></i> <?php esc_html_e('View Cart', 'tomatribe'); ?></a>
        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"><i class="fa fa-share"></i> <?php esc_html_e('Checkout', 'tomatribe'); ?></a>
      </div>
    <?php endif; ?>
  </div>
  <?php
  return ob_get_clean();
}

/* Refresh the mini cart whenever WooCommerce updates the cart via AJAX (add / remove / fragments refresh) */
function tomatribe_mini_cart_fragment($fragments) {
  $fragments['div.minicart-content-box'] = tomatribe_mini_cart_html();
  return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'tomatribe_mini_cart_fragment');

/* WooCommerce add-to-cart script handles AJAX removal from the mini cart */
function tomatribe_mini_cart_scripts() {
  if (tomatribe_show_mini_cart()) {
    wp_enqueue_script('wc-add-to-cart');
  }
}
add_action('wp_enqueue_scripts', 'tomatribe_mini_cart_scripts', 20);
