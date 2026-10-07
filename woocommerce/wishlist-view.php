<?php
/**
 * Wishlist items as a grid of product cards (same .latest-product-card markup as template/product-card.php).
 * Theme override of yith-woocommerce-wishlist/templates/wishlist-view.php; also used for the mobile view
 * (woocommerce/wishlist-view-mobile.php). Styling: .tp-wishlist in assets/scss/_general.scss.
 *
 * Keeps YITH's hooks for its script: .cart.wishlist_table (data-id / data-token), [data-row-id] items,
 * a.remove_from_wishlist and .product-quantity inputs. No "responsive" class, so YITH doesn't
 * swap in its own mobile table on resize.
 *
 * @var YITH_WCWL_Wishlist $wishlist
 * @var YITH_WCWL_Wishlist_Item[] $wishlist_items
 */

if (!defined('YITH_WCWL')) {
    exit;
}

$show_cb = !empty($show_cb);
$show_remove_product = !empty($show_remove_product);
$show_quantity = !empty($show_quantity);
$show_stock_status = !empty($show_stock_status);
$show_dateadded = !empty($show_dateadded);
$show_variation = !empty($show_variation);
$no_interactions = !empty($no_interactions);
$enable_drag_n_drop = !empty($enable_drag_n_drop);
$show_add_to_cart = !empty($show_add_to_cart);
?>

<div class="cart wishlist_table wishlist_view tp-wishlist <?php echo $no_interactions ? 'no-interactions' : ''; ?> <?php echo $enable_drag_n_drop ? 'sortable' : ''; ?>"
    data-pagination="<?php echo esc_attr($pagination); ?>" data-per-page="<?php echo esc_attr($per_page); ?>" data-page="<?php echo esc_attr($current_page); ?>"
    data-id="<?php echo esc_attr($wishlist_id); ?>" data-token="<?php echo esc_attr($wishlist_token); ?>">

    <?php if ($wishlist && $wishlist->has_items()) : ?>
        <div class="tp-wishlist-grid wishlist-items-wrapper">
            <?php
            foreach ($wishlist_items as $item) :
                global $product;

                $product = $item->get_product();
                if (!$product || !$product->exists()) continue;

                $permalink = get_permalink(apply_filters('woocommerce_in_cart_product', $item->get_product_id()));
                $parent = $product->get_parent_id() ? wc_get_product($product->get_parent_id()) : $product;
                $category = tomatribe_product_primary_category($parent->get_id());
                $discount = tomatribe_product_discount($product);
                $prices = tomatribe_get_product_prices($product);
                $in_stock = 'out-of-stock' !== $item->get_stock_status();
            ?>
                <div class="latest-product-card tp-wishlist-item<?php echo $in_stock ? '' : ' is-out-of-stock'; ?>" id="yith-wcwl-row-<?php echo esc_attr($item->get_product_id()); ?>" data-row-id="<?php echo esc_attr($item->get_product_id()); ?>">
                    <div class="latest-product-image product-thumbnail">
                        <?php do_action('yith_wcwl_table_before_product_thumbnail', $item, $wishlist); ?>

                        <a href="<?php echo esc_url($permalink); ?>"> <?php echo wp_kses_post($product->get_image('woocommerce_single')); ?> </a>

                        <?php if (!$in_stock) : ?>
                            <span class="latest-product-badge tp-wishlist-badge-soldout"> <?php echo esc_html(apply_filters('yith_wcwl_out_of_stock_label', __('Out of stock', 'yith-woocommerce-wishlist'))); ?> </span>
                        <?php elseif ($discount) : ?>
                            <span class="latest-product-badge"> <?php echo esc_html(($discount['varies'] ? 'Up to ' : '') . $discount['percent'] . '% OFF'); ?> </span>
                        <?php endif; ?>

                        <?php if ($show_cb) : ?>
                            <label class="product-checkbox tp-wishlist-check">
                                <input type="checkbox" value="yes" name="items[<?php echo esc_attr($item->get_product_id()); ?>][cb]">
                                <span class="screen-reader-text"> <?php esc_html_e('Select', 'yith-woocommerce-wishlist'); ?> </span>
                            </label>
                        <?php endif; ?>

                        <?php if ($show_remove_product) : ?>
                            <div class="product-remove tp-wishlist-remove">
                                <a href="<?php echo esc_url($item->get_remove_url()); ?>" class="remove remove_from_wishlist" title="<?php echo esc_attr(apply_filters('yith_wcwl_remove_product_wishlist_message_title', __('Remove this product', 'yith-woocommerce-wishlist'))); ?>" aria-label="<?php echo esc_attr(apply_filters('yith_wcwl_remove_product_wishlist_message_title', __('Remove this product', 'yith-woocommerce-wishlist'))); ?>"> <i class="pe-7s-close" aria-hidden="true"></i> </a>
                            </div>
                        <?php endif; ?>

                        <?php do_action('yith_wcwl_table_after_product_thumbnail', $item, $wishlist); ?>
                    </div>

                    <div class="latest-product-info product-name">
                        <?php if ($category) : ?>
                            <span class="latest-product-category"> <?php echo esc_html($category->name); ?> </span>
                        <?php endif; ?>

                        <?php do_action('yith_wcwl_table_before_product_name', $item, $wishlist); ?>

                        <h3> <a href="<?php echo esc_url($permalink); ?>"> <?php echo wp_kses_post(apply_filters('woocommerce_in_cartproduct_obj_title', $product->get_title(), $product)); ?> </a> </h3>

                        <?php
                        if ($show_variation && $product->is_type('variation')) {
                            echo '<div class="tp-wishlist-variation">' . wp_kses_post(wc_get_formatted_variation($product, true)) . '</div>';
                        }

                        do_action('yith_wcwl_table_after_product_name', $item, $wishlist);
                        ?>

                        <div class="latest-product-rating">
                            <?php echo tomatribe_rating_stars_html((float) $parent->get_average_rating()); ?>
                        </div>

                        <?php if ($prices) : ?>
                            <div class="latest-product-price product-price">
                                <?php do_action('yith_wcwl_table_before_product_price', $item, $wishlist); ?>
                                <span> <?php echo $prices['price']; ?> </span>
                                <?php if ($prices['regular']) : ?>
                                    <del> <?php echo $prices['regular']; ?> </del>
                                <?php endif; ?>
                                <?php do_action('yith_wcwl_table_after_product_price', $item, $wishlist); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_quantity) : ?>
                            <div class="product-quantity tp-wishlist-quantity">
                                <?php if (!$no_interactions && $wishlist->current_user_can('update_quantity')) : ?>
                                    <label> <?php esc_html_e('Quantity', 'yith-woocommerce-wishlist'); ?> <input type="number" min="1" step="1" name="items[<?php echo esc_attr($item->get_product_id()); ?>][quantity]" value="<?php echo esc_attr($item->get_quantity()); ?>"> </label>
                                <?php else : ?>
                                    <?php echo esc_html(sprintf('%s: %s', __('Quantity', 'yith-woocommerce-wishlist'), $item->get_quantity())); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($show_dateadded && $item->get_date_added()) : ?>
                            <span class="dateadded tp-wishlist-date"> <?php echo esc_html(sprintf(__('Added on: %s', 'yith-woocommerce-wishlist'), $item->get_date_added_formatted())); ?> </span>
                        <?php endif; ?>

                        <div class="product-add-to-cart tp-wishlist-cart">
                            <?php
                            do_action('yith_wcwl_table_before_product_cart', $item, $wishlist);
                            do_action('yith_wcwl_table_product_before_add_to_cart', $item, $wishlist);

                            if (apply_filters('yith_wcwl_table_product_show_add_to_cart', $show_add_to_cart, $item, $wishlist) && $item->is_purchasable() && $in_stock) {
                                woocommerce_template_loop_add_to_cart(array('quantity' => $show_quantity ? $item->get_quantity() : 1));
                            } elseif (!$in_stock) {
                                echo '<span class="tp-wishlist-soldout"> ' . esc_html(apply_filters('yith_wcwl_out_of_stock_label', __('Out of stock', 'yith-woocommerce-wishlist'))) . ' </span>';
                            }

                            do_action('yith_wcwl_table_product_after_add_to_cart', $item, $wishlist);
                            do_action('yith_wcwl_table_after_product_cart', $item, $wishlist);
                            ?>
                        </div>
                    </div>

                    <?php if ($enable_drag_n_drop) : ?>
                        <input type="hidden" name="items[<?php echo esc_attr($item->get_product_id()); ?>][position]" value="<?php echo esc_attr($item->get_position()); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($page_links)) : ?>
            <nav class="tp-wishlist-pagination wishlist-pagination"> <?php echo wp_kses_post($page_links); ?> </nav>
        <?php endif; ?>

    <?php else : ?>
        <div class="tp-cart-empty wishlist-empty">
            <span class="tp-cart-empty-icon" aria-hidden="true"> <i class="pe-7s-like"></i> </span>
            <h3 class="tp-cart-empty-title"> <?php echo esc_html(apply_filters('yith_wcwl_no_product_to_remove_message', __('No products added to the wishlist', 'yith-woocommerce-wishlist'), $wishlist)); ?> </h3>
            <p class="tp-cart-empty-text"> Tap the heart on any product to save it here for later. </p>
            <?php if (wc_get_page_id('shop') > 0) : ?>
                <div class="tp-cart-empty-actions">
                    <p class="return-to-shop">
                        <a class="button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"> <?php echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce'))); ?> </a>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
