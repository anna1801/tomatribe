<?php
/**
 * Checkout form ([woocommerce_checkout] shortcode): customer details on the left, "Your order" card on the right.
 * Theme override of woocommerce/templates/checkout/form-checkout.php. Styling: .tp-checkout-section in assets/scss/_general.scss.
 *
 * Only the layout wrappers are new: the form, #customer_details, #order_review and all hooks are WooCommerce's,
 * so checkout.js (order review refresh, payment, validation) works as before.
 *
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}

$item_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout tp-checkout-layout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">

    <div class="tp-checkout-main">
        <?php if ($checkout->get_checkout_fields()) : ?>

            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

            <div class="col2-set" id="customer_details">
                <div class="col-1">
                    <?php do_action('woocommerce_checkout_billing'); ?>
                </div>

                <div class="col-2">
                    <?php do_action('woocommerce_checkout_shipping'); ?>
                </div>
            </div>

            <?php do_action('woocommerce_checkout_after_customer_details'); ?>

        <?php endif; ?>
    </div>

    <aside class="tp-checkout-sidebar">
        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

        <div class="tp-checkout-sidebar-head">
            <h3 id="order_review_heading"> <?php esc_html_e('Your order', 'woocommerce'); ?> <span class="tp-checkout-count">(<?php echo esc_html($item_count); ?>)</span> </h3>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>"> <?php esc_html_e('Edit', 'woocommerce'); ?> </a>
        </div>

        <?php do_action('woocommerce_checkout_before_order_review'); ?>

        <div id="order_review" class="woocommerce-checkout-review-order">
            <?php do_action('woocommerce_checkout_order_review'); ?>
        </div>

        <?php do_action('woocommerce_checkout_after_order_review'); ?>
    </aside>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
