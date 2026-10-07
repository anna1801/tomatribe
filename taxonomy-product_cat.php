<?php
/*
 * Product category page: filter sidebar + product grid (see includes/category-filters.php).
 */
get_header();

$term = get_queried_object();
?>

<section class="latest-products-section tf-category">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('term' => $term)); ?>

        <div class="latest-products-heading">
            <span class="latest-products-subtitle"> <?php echo $term->parent ? esc_html(get_term($term->parent, 'product_cat')->name) : 'Shop'; ?> </span>
            <h2> <?php echo esc_html($term->name); ?> </h2>
            <?php if ($term->description) : ?>
                <p> <?php echo wp_kses_post($term->description); ?> </p>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-3">
                <?php get_template_part('template/category-filters', null, array('term' => $term)); ?>
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
