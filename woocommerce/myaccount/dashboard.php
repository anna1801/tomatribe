<?php
/**
 * My Account dashboard: greeting, shortcut cards (orders, addresses, account details, wishlist) and recent orders.
 * Theme override of woocommerce/templates/myaccount/dashboard.php (hooks kept).
 *
 * @package WooCommerce\Templates
 * @version 4.4.0
 *
 * @var WP_User $current_user
 */

if (!defined('ABSPATH')) {
    exit;
}

$allowed_html = array(
    'a' => array(
        'href' => array(),
    ),
);

$first_name = $current_user->first_name ? $current_user->first_name : $current_user->display_name;

$shortcuts = array(
    array(
        'url'   => wc_get_endpoint_url('orders'),
        'icon'  => 'pe-7s-box2',
        'title' => __('Orders', 'woocommerce'),
        'text'  => 'Track, view and reorder your purchases',
    ),
    array(
        'url'   => wc_get_endpoint_url('edit-address'),
        'icon'  => 'pe-7s-map-marker',
        'title' => __('Addresses', 'woocommerce'),
        'text'  => wc_shipping_enabled() ? 'Manage your billing and shipping addresses' : 'Manage your billing address',
    ),
    array(
        'url'   => wc_get_endpoint_url('edit-account'),
        'icon'  => 'pe-7s-user',
        'title' => __('Account details', 'woocommerce'),
        'text'  => 'Update your name, email and password',
    ),
);

if (function_exists('YITH_WCWL')) {
    $shortcuts[] = array(
        'url'   => YITH_WCWL()->get_wishlist_url(),
        'icon'  => 'pe-7s-like',
        'title' => __('Wishlist', 'yith-woocommerce-wishlist'),
        'text'  => 'Pieces you have saved for later',
    );
}

$recent_orders = wc_get_orders(array(
    'customer' => get_current_user_id(),
    'limit'    => 3,
    'orderby'  => 'date',
    'order'    => 'DESC',
    'type'     => 'shop_order',
));
?>

<div class="tp-account-welcome">
    <h3> Welcome back, <?php echo esc_html($first_name); ?> </h3>
    <p>
        <?php
        printf(
            /* translators: 1: user display name 2: logout url */
            wp_kses(__('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'), $allowed_html),
            '<strong>' . esc_html($current_user->display_name) . '</strong>',
            esc_url(wc_logout_url())
        );
        ?>
    </p>
</div>

<div class="tp-account-shortcuts">
    <?php foreach ($shortcuts as $shortcut) : ?>
        <a class="tp-account-shortcut" href="<?php echo esc_url($shortcut['url']); ?>">
            <i class="<?php echo esc_attr($shortcut['icon']); ?>" aria-hidden="true"></i>
            <span class="tp-account-shortcut-title"> <?php echo esc_html($shortcut['title']); ?> </span>
            <span class="tp-account-shortcut-text"> <?php echo esc_html($shortcut['text']); ?> </span>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($recent_orders) : ?>
    <div class="tp-account-recent">
        <div class="tp-account-recent-head">
            <h3> Recent orders </h3>
            <a href="<?php echo esc_url(wc_get_endpoint_url('orders')); ?>"> View all </a>
        </div>

        <ul class="tp-account-recent-list">
            <?php foreach ($recent_orders as $order) :
                $item_count = $order->get_item_count() - $order->get_item_count_refunded();
            ?>
                <li class="tp-account-recent-item is-status-<?php echo esc_attr($order->get_status()); ?>">
                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                        <span class="tp-account-recent-number"> <?php echo esc_html(_x('#', 'hash before order number', 'woocommerce') . $order->get_order_number()); ?> </span>
                        <span class="tp-account-recent-date"> <?php echo esc_html(wc_format_datetime($order->get_date_created())); ?> </span>
                        <span class="tp-order-status"> <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?> </span>
                        <span class="tp-account-recent-total"> <?php echo wp_kses_post(sprintf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'), $order->get_formatted_order_total(), $item_count)); ?> </span>
                        <i class="pe-7s-angle-right" aria-hidden="true"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php
    /**
     * My Account dashboard.
     *
     * @since 2.6.0
     */
    do_action('woocommerce_account_dashboard');

    /**
     * Deprecated woocommerce_before_my_account action.
     *
     * @deprecated 2.6.0
     */
    do_action('woocommerce_before_my_account');

    /**
     * Deprecated woocommerce_after_my_account action.
     *
     * @deprecated 2.6.0
     */
    do_action('woocommerce_after_my_account');
