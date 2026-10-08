<?php
/*
 * 404 page: breadcrumb, "4(compass)4" mark, message, product search, shop/home buttons and the latest products.
 * Styling: .tp-404-section in assets/scss/_general.scss.
 */
get_header();

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '';
?>

<section class="tp-404-section">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('current' => 'Page not found')); ?>

        <div class="tp-404">
            <div class="tp-404-code" aria-hidden="true">
                <span>4</span>
                <span class="tp-404-icon"> <i class="pe-7s-compass"></i> </span>
                <span>4</span>
            </div>

            <span class="latest-products-subtitle"> Error 404 </span>
            <h1 class="tp-404-title"> This page has wandered off </h1>
            <p class="tp-404-text"> The page you're looking for may have been moved, renamed or is no longer available. Try a search, or head back to the collection. </p>

            <form class="tp-404-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="text" name="s" placeholder="Search products..." aria-label="Search products" required>
                <input type="hidden" name="post_type" value="product">
                <button type="submit" aria-label="Search"> <i class="pe-7s-search"></i> </button>
            </form>

            <div class="tp-404-actions">
                <?php if ($shop_url) : ?>
                    <a class="tp-404-btn" href="<?php echo esc_url($shop_url); ?>"> <i class="pe-7s-shopbag" aria-hidden="true"></i> Continue shopping </a>
                <?php endif; ?>
                <a class="tp-404-btn tp-404-btn-outline" href="<?php echo esc_url(home_url('/')); ?>"> Back to home </a>
            </div>
        </div>

        <?php
            $latest_products = new WP_Query(array(
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => 4,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ));

            if ($latest_products->have_posts()) :
        ?>
            <div class="tp-404-products">
                <div class="latest-products-heading">
                    <span class="latest-products-subtitle"> Just In </span>
                    <h2> You May Like </h2>
                </div>

                <div class="row g-4">
                    <?php while ($latest_products->have_posts()) : $latest_products->the_post(); ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <?php get_template_part('template/product-card'); ?>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
