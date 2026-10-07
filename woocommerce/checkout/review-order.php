<?php
/**
 * Checkout order review ("Your order" card): items with thumbnails, then the totals.
 * Theme override of woocommerce/templates/checkout/review-order.php.
 *
 * The root keeps .woocommerce-checkout-review-order-table: checkout.js replaces it with this template's
 * output after every update. Totals stay table rows because shipping (cart/cart-shipping.php) outputs <tr>s.
 *
 * @package WooCommerce\Templates
 * @version 11.0.0
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-checkout-review-order-table tp-review">
    <ul class="tp-review-items">
        <?php
        do_action('woocommerce_review_order_before_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $visible = apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key);

            if (!($_product instanceof WC_Product) || !$_product->exists() || $cart_item['quantity'] <= 0 || !$visible) {
                continue;
            }
        ?>
            <li class="tp-review-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                <span class="tp-review-thumb">
                    <?php echo apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key); ?>
                    <span class="tp-review-qty" aria-label="<?php echo esc_attr(sprintf(__('Quantity: %s', 'woocommerce'), $cart_item['quantity'])); ?>"> <?php echo esc_html($cart_item['quantity']); ?> </span>
                </span>

                <span class="tp-review-details product-name">
                    <span class="tp-review-name"> <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)); ?> </span>
                    <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity screen-reader-text">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); ?>
                    <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                </span>

                <span class="tp-review-total product-total">
                    <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                </span>
            </li>
        <?php endforeach; ?>

        <?php do_action('woocommerce_review_order_after_cart_contents'); ?>
    </ul>

    <table class="tp-totals-table">
        <tr class="cart-subtotal">
            <th> <?php esc_html_e('Subtotal', 'woocommerce'); ?> </th>
            <td> <?php wc_cart_totals_subtotal_html(); ?> </td>
        </tr>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <th> <?php esc_html_e('Coupon', 'woocommerce'); ?> <span class="tp-coupon-chip"> <i class="fa fa-tag" aria-hidden="true"></i> <?php echo esc_html($coupon->get_code()); ?> </span> </th>
                <td> <?php wc_cart_totals_coupon_html($coupon); ?> </td>
            </tr>
        <?php endforeach; ?>

        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

            <?php do_action('woocommerce_review_order_before_shipping'); ?>

            <?php wc_cart_totals_shipping_html(); ?>

            <?php do_action('woocommerce_review_order_after_shipping'); ?>

        <?php endif; ?>

        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <tr class="fee">
                <th> <?php echo esc_html($fee->name); ?> </th>
                <td> <?php wc_cart_totals_fee_html($fee); ?> </td>
            </tr>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <th> <?php echo esc_html($tax->label); ?> </th>
                        <td> <?php echo wp_kses_post($tax->formatted_amount); ?> </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr class="tax-total">
                    <th> <?php echo esc_html(WC()->countries->tax_or_vat()); ?> </th>
                    <td> <?php wc_cart_totals_taxes_total_html(); ?> </td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_review_order_before_order_total'); ?>

        <tr class="order-total">
            <th> <?php esc_html_e('Total', 'woocommerce'); ?> </th>
            <td> <?php wc_cart_totals_order_total_html(); ?> </td>
        </tr>

        <?php do_action('woocommerce_review_order_after_order_total'); ?>
    </table>
</div>
