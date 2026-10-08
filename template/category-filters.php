<?php
/*
 * Category / shop page filter sidebar. Off-canvas drawer below 992px.
 * Usage: get_template_part('template/category-filters', null, array('term' => $term)); (no term on the shop page)
 * Filter changes are applied over AJAX by assets/custom/js/category-filters.js.
 */
$term = isset($args['term']) ? $args['term'] : null;
$data = tomatribe_category_filter_data($term);
$form_action = $term ? get_term_link($term) : wc_get_page_permalink('shop');
$selected_sub_cats = tomatribe_filter_values('sub_cat');
$active_filters = tomatribe_active_filter_count();
?>
<aside class="tf-sidebar" id="tf-sidebar" aria-label="Product filters">
    <div class="tf-sidebar-bar">
        <button type="button" class="tf-sidebar-close" aria-label="Close filters"> <i class="pe-7s-close"></i> </button>
    </div>

    <div class="tf-sidebar-inner">
        <div class="tf-sidebar-head">
            <h3 class="tf-sidebar-title"> Filters </h3>
            <button type="button" class="tf-clear-all"<?php echo $active_filters ? '' : ' hidden'; ?>> Clear all </button>
        </div>

        <form class="tf-form" action="<?php echo esc_url($form_action); ?>" method="get">

            <?php foreach ($data['attributes'] as $attribute) : $selected = tomatribe_filter_values($attribute['name']); ?>
                <div class="tf-group is-open">
                    <button type="button" class="tf-group-toggle" aria-expanded="true">
                        <?php echo esc_html($attribute['label']); ?> <i class="pe-7s-angle-up"></i>
                    </button>
                    <div class="tf-group-body">
                        <div class="tf-boxes">
                            <?php foreach ($attribute['terms'] as $option) : ?>
                                <label class="tf-box">
                                    <input type="checkbox" name="<?php echo esc_attr($attribute['name']); ?>" value="<?php echo esc_attr($option['slug']); ?>" <?php checked(in_array($option['slug'], $selected, true)); ?>>
                                    <span> <?php echo esc_html($option['name']); ?> (<?php echo esc_html($option['count']); ?>) </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($data['sub_cats']) : ?>
                <div class="tf-group is-open">
                    <button type="button" class="tf-group-toggle" aria-expanded="true">
                        <?php echo $term ? 'Product type' : 'Category'; ?> <i class="pe-7s-angle-up"></i>
                    </button>
                    <div class="tf-group-body">
                        <?php foreach ($data['sub_cats'] as $sub_cat) : ?>
                            <label class="tf-check">
                                <input type="checkbox" name="sub_cat" value="<?php echo esc_attr($sub_cat['slug']); ?>" <?php checked(in_array($sub_cat['slug'], $selected_sub_cats, true)); ?>>
                                <span class="tf-check-mark"></span>
                                <span> <?php echo esc_html($sub_cat['name']); ?> (<?php echo esc_html($sub_cat['count']); ?>) </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
                if ($data['price']) :
                    $price_min = $data['price']['min'];
                    $price_max = $data['price']['max'];
                    $from = isset($_GET['min_price']) ? max($price_min, min($price_max, (int) floor((float) $_GET['min_price']))) : $price_min;
                    $to = isset($_GET['max_price']) ? max($from, min($price_max, (int) ceil((float) $_GET['max_price']))) : $price_max;
                    $currency = get_woocommerce_currency_symbol();
            ?>
                <div class="tf-group is-open">
                    <button type="button" class="tf-group-toggle" aria-expanded="true">
                        Price <i class="pe-7s-angle-up"></i>
                    </button>
                    <div class="tf-group-body">
                        <div class="tf-price" data-min="<?php echo esc_attr($price_min); ?>" data-max="<?php echo esc_attr($price_max); ?>">
                            <div class="tf-range">
                                <div class="tf-range-track"></div>
                                <input type="range" class="tf-range-from" min="<?php echo esc_attr($price_min); ?>" max="<?php echo esc_attr($price_max); ?>" step="1" value="<?php echo esc_attr($from); ?>" aria-label="Minimum price">
                                <input type="range" class="tf-range-to" min="<?php echo esc_attr($price_min); ?>" max="<?php echo esc_attr($price_max); ?>" step="1" value="<?php echo esc_attr($to); ?>" aria-label="Maximum price">
                            </div>
                            <div class="tf-price-inputs">
                                <label class="tf-price-field">
                                    <span> <?php echo $currency; ?> </span>
                                    <input type="number" class="tf-price-from" name="min_price" min="<?php echo esc_attr($price_min); ?>" max="<?php echo esc_attr($price_max); ?>" step="1" value="<?php echo esc_attr($from); ?>" aria-label="Minimum price">
                                </label>
                                <span class="tf-price-sep"> to </span>
                                <label class="tf-price-field">
                                    <span> <?php echo $currency; ?> </span>
                                    <input type="number" class="tf-price-to" name="max_price" min="<?php echo esc_attr($price_min); ?>" max="<?php echo esc_attr($price_max); ?>" step="1" value="<?php echo esc_attr($to); ?>" aria-label="Maximum price">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$data['attributes'] && !$data['sub_cats'] && !$data['price']) : ?>
                <p class="tf-no-filters"> No filters available<?php echo $term ? ' for this category' : ''; ?>. </p>
            <?php endif; ?>
        </form>
    </div>

    <div class="tf-sidebar-foot">
        <button type="button" class="tf-show-results"> Show results </button>
    </div>
</aside>
<div class="tf-overlay"></div>
