<?php
/**
 * Empty cart: bag icon, message, short text, "Return to shop" and (when it has items) "View wishlist".
 * Theme override of woocommerce/templates/cart/cart-empty.php. Styling: .tp-cart-empty in assets/scss/_general.scss.
 * The message itself still comes from the woocommerce_cart_is_empty hook (.wc-empty-cart-message, used by cart.js).
 *
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

$wishlist_count = function_exists('tomatribe_wishlist_count') ? (int) tomatribe_wishlist_count() : 0;
?>

<div class="tp-cart-empty">
    <span class="tp-cart-empty-icon" aria-hidden="true"> <i class="pe-7s-shopbag"></i> </span>

    <?php
    /*
     * @hooked wc_empty_cart_message - 10
     */
    do_action('woocommerce_cart_is_empty');
    ?>

    <p class="tp-cart-empty-text"> Looks like you haven't added anything yet. Explore our latest pieces and find something you love. </p>

    <div class="tp-cart-empty-actions">
        <?php if (wc_get_page_id('shop') > 0) : ?>
            <p class="return-to-shop">
                <a class="button wc-backward" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
                    <?php echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce'))); ?>
                </a>
            </p>
        <?php endif; ?>

        <?php if ($wishlist_count > 0 && function_exists('YITH_WCWL')) : ?>
            <p class="tp-cart-empty-wishlist">
                <a class="button" href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>"> <i class="pe-7s-like" aria-hidden="true"></i> View wishlist (<?php echo esc_html($wishlist_count); ?>) </a>
            </p>
        <?php endif; ?>
    </div>
</div>
