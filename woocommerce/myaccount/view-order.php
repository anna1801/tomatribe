<?php
/**
 * My Account > view order: summary strip (order number, date, status), order updates, then the order details.
 * Theme override of woocommerce/templates/myaccount/view-order.php. The status sentence is kept for its filter.
 *
 * @package WooCommerce\Templates
 * @version 10.6.0
 *
 * @var WC_Order $order
 * @var int      $order_id
 */

defined('ABSPATH') || exit;

$notes = $order->get_customer_order_notes();
?>

<ul class="woocommerce-order-overview tp-order-overview">
    <li>
        <?php esc_html_e('Order number:', 'woocommerce'); ?>
        <strong> #<?php echo esc_html($order->get_order_number()); ?> </strong>
    </li>
    <li>
        <?php esc_html_e('Date:', 'woocommerce'); ?>
        <strong> <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?> </strong>
    </li>
    <li class="is-status-<?php echo esc_attr($order->get_status()); ?>">
        <?php esc_html_e('Status:', 'woocommerce'); ?>
        <strong> <span class="tp-order-status"> <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?> </span> </strong>
    </li>
    <li>
        <?php esc_html_e('Total:', 'woocommerce'); ?>
        <strong> <?php echo wp_kses_post($order->get_formatted_order_total()); ?> </strong>
    </li>
</ul>

<p class="tp-order-status-text screen-reader-text">
    <?php
    echo wp_kses_post(
        apply_filters(
            'woocommerce_order_details_status',
            sprintf(
                /* translators: 1: order number 2: order date 3: order status */
                esc_html__('Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce'),
                '<mark class="order-number">' . $order->get_order_number() . '</mark>',
                '<mark class="order-date">' . wc_format_datetime($order->get_date_created()) . '</mark>',
                '<mark class="order-status">' . wc_get_order_status_name($order->get_status()) . '</mark>'
            ),
            $order
        )
    );
    ?>
</p>

<?php if ($notes) : ?>
    <h2> <?php esc_html_e('Order updates', 'woocommerce'); ?> </h2>
    <ol class="woocommerce-OrderUpdates commentlist notes">
        <?php foreach ($notes as $note) : ?>
            <li class="woocommerce-OrderUpdate comment note">
                <div class="woocommerce-OrderUpdate-inner comment_container">
                    <div class="woocommerce-OrderUpdate-text comment-text">
                        <p class="woocommerce-OrderUpdate-meta meta"> <?php echo date_i18n(esc_html__('l jS \o\f F Y, h:ia', 'woocommerce'), strtotime($note->comment_date)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> </p>
                        <div class="woocommerce-OrderUpdate-description description">
                            <?php echo wp_kses_post(wpautop(wptexturize($note->comment_content))); ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>

<?php do_action('woocommerce_view_order', $order_id); ?>
