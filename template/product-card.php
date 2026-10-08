<?php
/*
 * Product card. Use inside a product loop: get_template_part('template/product-card');
 */
$product = wc_get_product(get_the_ID());
if (!$product) return;

$permalink = get_permalink();
$tags = get_the_terms(get_the_ID(), 'product_tag');
$rating = (float) $product->get_average_rating();
?>
<div class="latest-product-card">
    <div class="latest-product-image">
        <a href="<?php echo esc_url($permalink); ?>">
            <?php echo $product->get_image('woocommerce_full'); ?>
        </a>

        <?php if ($discount = tomatribe_product_discount($product)) : ?>
            <span class="latest-product-badge"> <?php echo esc_html(($discount['varies'] ? 'Up to ' : '') . $discount['percent'] . '% OFF'); ?> </span>
        <?php elseif (get_post_time('U', true) >= strtotime('-1 week')) : ?>
            <span class="latest-product-badge"> NEW </span>
        <?php endif; ?>
        <div class="latest-product-actions">
            <?php if (shortcode_exists('yith_wcwl_add_to_wishlist')) : ?>
                <div class="latest-product-wishlist" title="Wishlist">
                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="'.$product->get_id().'"]'); ?>
                </div>
            <?php endif; ?>
            <a href="<?php echo esc_url($permalink); ?>" title="Quick View">
                <i class="pe-7s-search"></i>
            </a>
        </div>
        <div class="latest-product-cart">
            <?php
                $cart_classes = 'add_to_cart_button product_type_'.$product->get_type();
                if ($product->is_purchasable() && $product->is_in_stock() && $product->supports('ajax_add_to_cart')) :
                    $cart_classes .= ' ajax_add_to_cart';
                endif;
            ?>
            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="<?php echo esc_attr($cart_classes); ?>" data-quantity="1" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" rel="nofollow">
                <i class="fa fa-shopping-bag"></i> <?php echo esc_html($product->add_to_cart_text()); ?>
            </a>
        </div>
    </div>
    <div class="latest-product-info">
        <?php if ($tags && !is_wp_error($tags)) : ?>
            <span class="latest-product-category"> <?php echo esc_html($tags[0]->name); ?> </span>
        <?php endif; ?>
        <h3> <a href="<?php echo esc_url($permalink); ?>"> <?php the_title(); ?> </a> </h3>
        <div class="latest-product-rating">
            <?php echo tomatribe_rating_stars_html($rating); ?>
        </div>
        <?php if ($prices = tomatribe_get_product_prices($product)) : ?>
            <div class="latest-product-price">
                <span> <?php echo $prices['price']; ?> </span>
                <?php if ($prices['regular']) : ?>
                    <del> <?php echo $prices['regular']; ?> </del>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
