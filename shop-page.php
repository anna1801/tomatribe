<?php
/*
 * Shop page: filter sidebar + product grid, like the category page (see includes/category-filters.php).
 * Used for the shop page via tomatribe_shop_template(). Styling: .tf-category in assets/scss/_general.scss.
 */
get_header();

$shop_title = woocommerce_page_title(false);
?>

<section class="latest-products-section tf-category">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('current' => $shop_title, 'current_url' => wc_get_page_permalink('shop'))); ?>

        <div class="latest-products-heading">
            <span class="latest-products-subtitle"> All Products </span>
            <h2> <?php echo esc_html($shop_title); ?> </h2>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-3">
                <?php get_template_part('template/category-filters'); ?>
            </div>
            <div class="col-12 col-lg-9">
                <div class="tf-results" id="tf-results" aria-live="polite">
                    <?php get_template_part('template/category-products'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
