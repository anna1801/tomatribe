<?php
/**
 * Cart page ([woocommerce_cart] shortcode): items list on the left, totals sidebar on the right.
 * Theme override of woocommerce/templates/cart/cart.php. Styling: .tp-cart-section in assets/scss/_general.scss,
 * quantity stepper / auto update / sidebar coupon: assets/custom/js/cart.js.
 *
 * Keeps WooCommerce's class names (woocommerce-cart-form, cart_item, product-remove, coupon, cart_totals ...)
 * so its cart.js still updates quantities, removes items and applies coupons via AJAX.
 * The coupon field sits in the sidebar, outside the form, and is tied to it with the form="" attribute.
 *
 * @package WooCommerce\Templates
 * @version 11.0.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="tp-cart-layout">
    <form class="woocommerce-cart-form tp-cart-main" id="tp-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <?php $free_shipping = tomatribe_free_shipping_progress(); ?>
        <?php if ($free_shipping) : ?>
            <div class="tp-free-shipping<?php echo $free_shipping['remaining'] <= 0 ? ' is-unlocked' : ''; ?>">
                <p>
                    <i class="<?php echo $free_shipping['remaining'] <= 0 ? 'pe-7s-check' : 'pe-7s-car'; ?>" aria-hidden="true"></i>
                    <?php if ($free_shipping['remaining'] > 0) : ?>
                        <span> You're <strong><?php echo wc_price($free_shipping['remaining']); ?></strong> away from free shipping </span>
                    <?php else : ?>
                        <span> You've unlocked <strong>free shipping</strong> </span>
                    <?php endif; ?>
                </p>
                <div class="tp-free-shipping-bar" role="progressbar" aria-label="Free shipping progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr(round($free_shipping['percent'])); ?>">
                    <span style="width: <?php echo esc_attr(round($free_shipping['percent'], 2)); ?>%"></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="tp-cart-items woocommerce-cart-form__contents">
            <div class="tp-cart-items-head" aria-hidden="true">
                <span> <?php esc_html_e('Product', 'woocommerce'); ?> </span>
                <span> <?php esc_html_e('Total', 'woocommerce'); ?> </span>
            </div>

            <?php do_action('woocommerce_before_cart_contents'); ?>

            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                $visible = apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key);

                if (!($_product instanceof WC_Product) || !$_product->exists() || $cart_item['quantity'] <= 0 || !$visible) {
                    continue;
                }

                $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
                $category = tomatribe_product_primary_category($product_id);

                // Sale: regular price struck through and "% OFF", like the product page
                $discount = tomatribe_product_discount($_product);
                $regular_price = '';
                if ($discount) {
                    $regular_args = array('price' => $_product->get_regular_price());
                    $regular_price = wc_price(WC()->cart->display_prices_including_tax() ? wc_get_price_including_tax($_product, $regular_args) : wc_get_price_excluding_tax($_product, $regular_args));
                }

                if ($_product->is_sold_individually()) {
                    $min_quantity = 1;
                    $max_quantity = 1;
                } else {
                    $min_quantity = 0;
                    $max_quantity = $_product->get_max_purchase_quantity();
                }
                $product_quantity = woocommerce_quantity_input(
                    array(
                        'input_name'   => "cart[{$cart_item_key}][qty]",
                        'input_value'  => $cart_item['quantity'],
                        'max_value'    => $max_quantity,
                        'min_value'    => $min_quantity,
                        'product_name' => $product_name,
                    ),
                    $_product,
                    false
                );
            ?>
                <div class="woocommerce-cart-form__cart-item tp-cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                    <div class="tp-cart-item-image product-thumbnail">
                        <?php if ($product_permalink) : ?>
                            <a href="<?php echo esc_url($product_permalink); ?>" tabindex="-1"> <?php echo $thumbnail; ?> </a>
                        <?php else : ?>
                            <?php echo $thumbnail; ?>
                        <?php endif; ?>
                    </div>

                    <div class="tp-cart-item-details product-name">
                        <?php if ($category) : ?>
                            <span class="tp-cart-item-category"> <?php echo esc_html($category->name); ?> </span>
                        <?php endif; ?>

                        <?php
                        if ($product_permalink) {
                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a class="tp-cart-item-name" href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                        } else {
                            echo '<span class="tp-cart-item-name">' . wp_kses_post($product_name) . '</span>';
                        }

                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
                        ?>

                        <div class="tp-cart-item-price product-price">
                            <span class="tp-cart-item-current"> <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?> </span>
                            <?php if ($discount) : ?>
                                <del> <?php echo $regular_price; ?> </del>
                                <span class="tp-discount"> <?php echo esc_html($discount['percent'] . '% OFF'); ?> </span>
                            <?php endif; ?>
                        </div>

                        <?php
                        echo wc_get_formatted_cart_item_data($cart_item);

                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                            echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                        }
                        ?>

                        <div class="tp-cart-item-actions">
                            <div class="tp-qty product-quantity<?php echo $_product->is_sold_individually() ? ' is-fixed' : ''; ?>">
                                <button type="button" class="tp-qty-btn tp-qty-minus" aria-label="<?php echo esc_attr(sprintf(__('Reduce quantity of %s', 'woocommerce'), wp_strip_all_tags($product_name))); ?>"> &minus; </button>
                                <?php echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); ?>
                                <button type="button" class="tp-qty-btn tp-qty-plus" aria-label="<?php echo esc_attr(sprintf(__('Increase quantity of %s', 'woocommerce'), wp_strip_all_tags($product_name))); ?>"> + </button>
                            </div>

                            <div class="product-remove">
                                <?php
                                // Direct child link of .product-remove: removed via AJAX by WooCommerce's cart.js
                                echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a role="button" href="%s" class="remove tp-cart-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="pe-7s-trash" aria-hidden="true"></i></a>',
                                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                                        esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                                        esc_attr($product_id),
                                        esc_attr($_product->get_sku())
                                    ),
                                    $cart_item_key
                                );
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="tp-cart-item-total product-subtotal">
                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php do_action('woocommerce_cart_contents'); ?>

            <div class="actions tp-cart-actions">
                <?php // Quantity changes submit this automatically (cart.js); kept for no-JS ?>
                <button type="submit" class="button tp-cart-update" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"> <?php esc_html_e('Update cart', 'woocommerce'); ?> </button>

                <?php do_action('woocommerce_cart_actions'); ?>

                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </div>

            <?php do_action('woocommerce_after_cart_contents'); ?>
        </div>

        <a class="tp-continue-shopping" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"> <i class="pe-7s-angle-left" aria-hidden="true"></i> <?php esc_html_e('Continue shopping', 'woocommerce'); ?> </a>

        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>

    <aside class="tp-cart-sidebar">
        <h2 class="tp-cart-sidebar-title"> <?php esc_html_e('Cart totals', 'woocommerce'); ?> </h2>

        <?php if (wc_coupons_enabled()) : ?>
            <details class="tp-coupon coupon">
                <summary> <span> <i class="fa fa-tag" aria-hidden="true"></i> <?php esc_html_e('Add coupons', 'woocommerce'); ?> </span> </summary>
                <div class="tp-coupon-form">
                    <label for="coupon_code" class="screen-reader-text"> <?php esc_html_e('Coupon:', 'woocommerce'); ?> </label>
                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" form="tp-cart-form" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>">
                    <button type="submit" class="button tp-coupon-apply" name="apply_coupon" form="tp-cart-form" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"> <?php esc_html_e('Apply', 'woocommerce'); ?> </button>
                </div>
                <?php do_action('woocommerce_cart_coupon'); ?>
            </details>
        <?php endif; ?>

        <?php do_action('woocommerce_before_cart_collaterals'); ?>

        <div class="cart-collaterals">
            <?php
                /**
                 * @hooked woocommerce_cart_totals - 10 (cross-sells are moved below the cart in includes/cart.php)
                 */
                do_action('woocommerce_cart_collaterals');
            ?>
        </div>
    </aside>
</div>

<?php do_action('woocommerce_after_cart'); ?>
