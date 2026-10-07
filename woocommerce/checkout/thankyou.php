<?php
/**
 * Order received (thank you) page: check icon, personal thank you, order summary strip, buttons,
 * then the order details and addresses (woocommerce_thankyou hook).
 * Theme override of woocommerce/templates/checkout/thankyou.php. Styling: .is-order-received in assets/scss/_general.scss.
 *
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-order">

    <?php
    if ($order) :

        do_action('woocommerce_before_thankyou', $order->get_id());
        ?>

        <?php if ($order->has_status('failed')) : ?>

            <div class="tp-thankyou-hero is-failed">
                <span class="tp-cart-empty-icon" aria-hidden="true"> <i class="pe-7s-close-circle"></i> </span>
                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"> <?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?> </p>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions tp-thankyou-actions">
                    <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"> <?php esc_html_e('Pay', 'woocommerce'); ?> </a>
                    <?php if (is_user_logged_in()) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button tp-button-outline"> <?php esc_html_e('My account', 'woocommerce'); ?> </a>
                    <?php endif; ?>
                </p>
            </div>

        <?php else : ?>

            <?php
            $is_owner = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
            $first_name = $order->get_billing_first_name();
            ?>

            <div class="tp-thankyou-hero">
                <span class="tp-cart-empty-icon" aria-hidden="true"> <i class="pe-7s-check"></i> </span>

                <h3 class="tp-thankyou-title"> <?php echo esc_html($first_name ? sprintf('Thank you, %s!', $first_name) : 'Thank you!'); ?> </h3>

                <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                    <?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html(__('Thank you. Your order has been received.', 'woocommerce')), $order); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <?php if ($order->get_billing_email()) : ?>
                        <br> A confirmation has been sent to <strong><?php echo esc_html($order->get_billing_email()); ?></strong>.
                    <?php endif; ?>
                </p>
            </div>

            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                <li class="woocommerce-order-overview__order order">
                    <?php esc_html_e('Order number:', 'woocommerce'); ?>
                    <strong> #<?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> </strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    <?php esc_html_e('Date:', 'woocommerce'); ?>
                    <strong> <?php echo wc_format_datetime($order->get_date_created()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> </strong>
                </li>

                <li class="woocommerce-order-overview__total total">
                    <?php esc_html_e('Total:', 'woocommerce'); ?>
                    <strong> <?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> </strong>
                </li>

                <?php if ($order->get_payment_method_title()) : ?>
                    <li class="woocommerce-order-overview__payment-method method">
                        <?php esc_html_e('Payment method:', 'woocommerce'); ?>
                        <strong> <?php echo wp_kses_post($order->get_payment_method_title()); ?> </strong>
                    </li>
                <?php endif; ?>

            </ul>

            <div class="tp-thankyou-actions">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button"> <?php esc_html_e('Continue shopping', 'woocommerce'); ?> </a>
                <?php if ($is_owner) : ?>
                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="button tp-button-outline"> <?php esc_html_e('View order', 'woocommerce'); ?> </a>
                <?php endif; ?>
            </div>

        <?php endif; ?>

        <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

    <?php else : ?>

        <div class="tp-thankyou-hero">
            <span class="tp-cart-empty-icon" aria-hidden="true"> <i class="pe-7s-check"></i> </span>
            <?php wc_get_template('checkout/order-received.php', array('order' => false)); ?>
        </div>

    <?php endif; ?>

</div>
