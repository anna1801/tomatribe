<?php
/*
 * Category page results: toolbar, product grid and pagination for the main query.
 * Also returned on its own for AJAX filter requests (see includes/category-filters.php).
 */
global $wp_query;

$total = (int) $wp_query->found_posts;
$per_page = (int) $wp_query->get('posts_per_page');
$paged = max(1, (int) get_query_var('paged'));
$active_filters = tomatribe_active_filter_count();

$orderby_options = apply_filters('woocommerce_catalog_orderby', array(
    'menu_order' => 'Default sorting',
    'popularity' => 'Sort by popularity',
    'rating'     => 'Sort by average rating',
    'date'       => 'Sort by latest',
    'price'      => 'Sort by price: low to high',
    'price-desc' => 'Sort by price: high to low',
));
$default_orderby = apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'menu_order'));
$orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : $default_orderby;
?>
<div class="tf-toolbar">
    <button type="button" class="tf-filter-toggle" aria-controls="tf-sidebar">
        <i class="pe-7s-filter"></i> Filters
        <?php if ($active_filters) : ?>
            <span class="tf-filter-badge"> <?php echo esc_html($active_filters); ?> </span>
        <?php endif; ?>
    </button>
    <p class="tf-result-count">
        <?php
            if ($total <= $per_page || $per_page < 1) :
                echo esc_html(sprintf(_n('%d product', '%d products', $total), $total));
            else :
                $first = ($paged - 1) * $per_page + 1;
                $last = min($total, $paged * $per_page);
                echo esc_html(sprintf('Showing %d–%d of %d products', $first, $last, $total));
            endif;
        ?>
    </p>
    <div class="tf-sort">
        <label for="tf-orderby" class="visually-hidden"> Sort by </label>
        <select id="tf-orderby" class="tf-orderby" data-default="<?php echo esc_attr($default_orderby); ?>">
            <?php foreach ($orderby_options as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($orderby, $value); ?>> <?php echo esc_html($label); ?> </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<?php if (have_posts()) : ?>
    <div class="row g-4">
        <?php while (have_posts()) : the_post(); ?>
            <div class="col-12 col-sm-6 col-xl-4">
                <?php get_template_part('template/product-card'); ?>
            </div>
        <?php endwhile; ?>
    </div>

    <?php
        $links = paginate_links(array(
            'type'      => 'array',
            'prev_text' => '<i class="pe-7s-angle-left"></i>',
            'next_text' => '<i class="pe-7s-angle-right"></i>',
        ));
        if ($links) :
    ?>
        <div class="paginatoin-area text-center mt-5">
            <ul class="pagination-box">
                <?php
                    foreach ($links as $link) :
                        if (strpos($link, 'current') !== false) :
                            echo '<li class="active">' . str_replace(array('<span', '</span>'), array('<a', '</a>'), $link) . '</li>';
                        else :
                            echo '<li>' . $link . '</li>';
                        endif;
                    endforeach;
                ?>
            </ul>
        </div>
    <?php endif; ?>
<?php else : ?>
    <div class="tf-empty">
        <p> No products match the selected filters. </p>
        <?php if ($active_filters) : ?>
            <button type="button" class="tf-clear-all"> Clear all filters </button>
        <?php endif; ?>
    </div>
<?php endif; ?>
