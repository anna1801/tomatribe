<?php
/*
 * Single product page: gallery, summary (category, title, rating, price, swatches, size guide,
 * add to cart, short description, share), ACF tabs, store highlights and similar products.
 * Helpers and ACF fields: includes/single-product.php. Script: assets/custom/js/single-product.js.
 */
get_header();

while (have_posts()) : the_post();
    $product = wc_get_product(get_the_ID());
    if (!$product) continue;

    $category = tomatribe_product_primary_category($product->get_id());
    $prices = tomatribe_get_product_prices($product);
    $discount = tomatribe_product_discount($product);
    $size_guide = tomatribe_size_guide($product);
    $tabs = tomatribe_product_tabs($product);
    $review_count = $product->get_review_count();
    $short_description = apply_filters('woocommerce_short_description', $product->get_short_description());

    $share_url = rawurlencode(get_permalink());
    $share_title = rawurlencode(get_the_title());
    $share_image = rawurlencode((string) wp_get_attachment_image_url($product->get_image_id(), 'full'));

    // Product schema (WooCommerce prints it in the footer)
    if (isset(WC()->structured_data)) {
        WC()->structured_data->generate_product_data($product);
    }
?>

<section class="tp-product-section">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('term' => $category, 'current' => get_the_title())); ?>
        <?php woocommerce_output_all_notices(); ?>

        <div id="product-<?php the_ID(); ?>" <?php wc_product_class('tp-product', $product); ?>>
            <div class="row g-4 g-xl-5">
                <div class="col-12 col-lg-6">
                    <?php get_template_part('template/product-gallery', null, array('product' => $product)); ?>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="tp-summary">
                        <div class="tp-summary-head">
                            <div class="tp-summary-title">
                                <?php if ($category) : ?>
                                    <a class="tp-category" href="<?php echo esc_url(get_term_link($category)); ?>"> <?php echo esc_html($category->name); ?> </a>
                                <?php endif; ?>
                                <h1 class="tp-title"> <?php the_title(); ?> </h1>
                                <?php if (wc_review_ratings_enabled()) : ?>
                                    <div class="tp-rating">
                                        <span class="tp-stars" role="img" aria-label="<?php echo esc_attr(sprintf('Rated %s out of 5', wc_format_decimal($product->get_average_rating(), 1))); ?>">
                                            <?php echo tomatribe_rating_stars_html((float) $product->get_average_rating()); ?>
                                        </span>
                                        <?php if (in_array('reviews-tab', wp_list_pluck($tabs, 'id'), true)) : ?>
                                            <a class="tp-review-link" href="#tp-panel-reviews-tab">
                                                <?php echo esc_html($review_count ? sprintf(_n('%d review', '%d reviews', $review_count), $review_count) : 'Write a review'); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if (shortcode_exists('yith_wcwl_add_to_wishlist')) : ?>
                                <div class="tp-wishlist" title="Wishlist">
                                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '"]'); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($prices) : ?>
                            <div class="tp-price-box">
                                <span class="tp-price"> <?php echo $prices['price']; ?> </span>
                                <?php if ($prices['regular']) : ?>
                                    <del class="tp-regular-price"> <?php echo $prices['regular']; ?> </del>
                                <?php endif; ?>
                                <?php if ($discount) : ?>
                                    <span class="tp-discount"> <?php echo esc_html(($discount['varies'] ? 'Up to ' : '') . $discount['percent'] . '% OFF'); ?> </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <p class="tp-sku"<?php echo $product->get_sku() ? '' : ' hidden'; ?>> SKU: <span data-sku="<?php echo esc_attr($product->get_sku()); ?>"><?php echo esc_html($product->get_sku()); ?></span> </p>

                        <div class="tp-purchase">
                            <?php get_template_part('template/product-add-to-cart', null, array('product' => $product, 'size_guide' => $size_guide)); ?>
                        </div>

                        <?php if ($short_description) : ?>
                            <div class="tp-short-description">
                                <?php echo $short_description; ?>
                            </div>
                        <?php endif; ?>

                        <div class="tp-share">
                            <span> Share </span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="fa fa-facebook"></i></a>
                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&media=<?php echo $share_image; ?>&description=<?php echo $share_title; ?>" target="_blank" rel="noopener" aria-label="Share on Pinterest"><i class="fa fa-pinterest-p"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" rel="noopener" aria-label="Share on X"><i class="fa fa-twitter"></i></a>
                            <a href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_url; ?>" aria-label="Share by email"><i class="fa fa-paper-plane-o"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($tabs) : ?>
                <div class="tp-tabs">
                    <div class="tp-tab-nav" role="tablist">
                        <?php foreach ($tabs as $index => $tab) : ?>
                            <button type="button" class="tp-tab<?php echo $index ? '' : ' is-active'; ?>" id="tp-tab-<?php echo esc_attr($tab['id']); ?>" role="tab" aria-controls="tp-panel-<?php echo esc_attr($tab['id']); ?>" aria-selected="<?php echo $index ? 'false' : 'true'; ?>"<?php echo $index ? ' tabindex="-1"' : ''; ?>>
                                <?php echo esc_html($tab['title']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <?php foreach ($tabs as $index => $tab) : ?>
                        <div class="tp-tab-panel" id="tp-panel-<?php echo esc_attr($tab['id']); ?>" role="tabpanel" aria-labelledby="tp-tab-<?php echo esc_attr($tab['id']); ?>" tabindex="0"<?php echo $index ? ' hidden' : ''; ?>>
                            <?php echo $tab['content']; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php 
                if (have_rows('features_single_products', 'option')) : 
                    echo '<ul class="tp-highlights">';
                        while (have_rows('features_single_products', 'option')) : the_row(); 
                            $icon = get_sub_field('icon');
                            $text = get_sub_field('text');
                            echo '<li> <i class="'.$icon.'"></i> '.$text.' </li>';
                        endwhile; 
                    echo '</ul>';
                endif; 
            ?>

        </div>
    </div>
</section>

<?php if ($size_guide) get_template_part('template/size-guide', null, array('guide' => $size_guide)); ?>

<?php
    $related_ids = wc_get_related_products($product->get_id(), 4);
    if ($related_ids) :
        $related = new WP_Query(array(
            'post_type'           => 'product',
            'post__in'            => $related_ids,
            'orderby'             => 'post__in',
            'posts_per_page'      => 4,
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
        ));
?>
    <section class="latest-products-section tp-related">
        <div class="container">
            <div class="latest-products-heading">
                <h2> Similar products </h2>
            </div>
            <div class="row g-4">
                <?php while ($related->have_posts()) : $related->the_post(); ?>
                    <div class="col-6 col-lg-3">
                        <?php get_template_part('template/product-card'); ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
