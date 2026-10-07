<?php
/*
 * Breadcrumb: Home / parent categories / category [/ current page].
 * get_template_part('template/breadcrumb', null, array('term' => $product_cat_term, 'current' => 'Page title'));
 * Without "current" the term itself is the current (last) item.
 */
$term = isset($args['term']) ? $args['term'] : null;
$current = isset($args['current']) ? $args['current'] : '';

$links = array();
if ($term) {
    foreach (array_reverse(get_ancestors($term->term_id, 'product_cat', 'taxonomy')) as $ancestor_id) {
        $links[] = get_term($ancestor_id, 'product_cat');
    }
    if ($current !== '') {
        $links[] = $term;
    } else {
        $current = $term->name;
    }
}
?>
<nav class="breadcrumb-wrap tf-breadcrumb" aria-label="Breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"> Home </a></li>
        <?php foreach ($links as $link) : ?>
            <li class="breadcrumb-item"><a href="<?php echo esc_url(get_term_link($link)); ?>"> <?php echo esc_html($link->name); ?> </a></li>
        <?php endforeach; ?>
        <li class="breadcrumb-item active" aria-current="page"> <?php echo esc_html($current); ?> </li>
    </ol>
</nav>
