<?php
/*
 * Breadcrumb: Home / parent categories / category [/ current page].
 * get_template_part('template/breadcrumb', null, array('term' => $product_cat_term, 'current' => 'Page title'));
 * Without "current" the term itself is the current (last) item.
 * Every item is a link; the current one points to "current_url" (default: the term link or this page's permalink, none on the 404 page).
 */
$term = isset($args['term']) ? $args['term'] : null;
$current = isset($args['current']) ? $args['current'] : '';
$current_url = isset($args['current_url']) ? $args['current_url'] : '';

$links = array();
if ($term) {
    foreach (array_reverse(get_ancestors($term->term_id, 'product_cat', 'taxonomy')) as $ancestor_id) {
        $links[] = get_term($ancestor_id, 'product_cat');
    }
    if ($current !== '') {
        $links[] = $term;
    } else {
        $current = $term->name;
        if ($current_url === '') {
            $term_link = get_term_link($term);
            $current_url = is_wp_error($term_link) ? '' : $term_link;
        }
    }
}
if ($current_url === '' && is_singular()) {
    $current_url = get_permalink();
}
?>
<nav class="breadcrumb-wrap tf-breadcrumb" aria-label="Breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>" aria-label="Home"><i class="fa fa-home" aria-hidden="true"></i></a></li>
        <?php foreach ($links as $link) : ?>
            <li class="breadcrumb-item"><a href="<?php echo esc_url(get_term_link($link)); ?>"> <?php echo esc_html($link->name); ?> </a></li>
        <?php endforeach; ?>
        <li class="breadcrumb-item active">
            <?php if ($current_url) : ?>
                <a href="<?php echo esc_url($current_url); ?>" aria-current="page"> <?php echo esc_html($current); ?> </a>
            <?php else : ?>
                <span aria-current="page"> <?php echo esc_html($current); ?> </span>
            <?php endif; ?>
        </li>
    </ol>
</nav>
