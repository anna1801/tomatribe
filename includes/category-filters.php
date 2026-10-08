<?php

/*
 * Product category page (taxonomy-product_cat.php) and shop page (shop-page.php): AJAX filters by sub category, attribute and price.
 * On the shop page the "sub categories" are the top level categories.
 *
 * Filtering runs on the main query through WooCommerce's own query vars
 * (filter_{attribute}, query_type_{attribute}, min_price, max_price, orderby)
 * plus "sub_cat" for sub categories, so a filtered URL also works on a full page load.
 * AJAX requests load the same URL with "tf_ajax=1" and get only template/category-products.php back.
 */

/* Products per page on category and shop pages */
define('TOMATRIBE_CATEGORY_PER_PAGE', 12);

function tomatribe_is_category_page() {
  return function_exists('is_product_category') && is_product_category();
}

/* Shop page (is_shop() is also true for product searches, which keep search.php) */
function tomatribe_is_shop_page() {
  return function_exists('is_shop') && is_shop() && !is_search();
}

/* Pages with the filter sidebar */
function tomatribe_is_filter_page() {
  return tomatribe_is_category_page() || tomatribe_is_shop_page();
}

/* Shop page: themed template with the filter sidebar instead of WooCommerce's archive-product.php (after WooCommerce's loader at 10) */
function tomatribe_shop_template($template) {
  if (tomatribe_is_shop_page()) {
    $shop_template = locate_template('shop-page.php');
    if ($shop_template) return $shop_template;
  }
  return $template;
}
add_filter('template_include', 'tomatribe_shop_template', 20);

/*
 * The theme doesn't declare WooCommerce support, so WooCommerce would replace the category query
 * with a dummy page. Unhook that on category pages so WordPress loads taxonomy-product_cat.php.
 */
function tomatribe_category_template_support() {
  if (tomatribe_is_filter_page()) {
    remove_action('template_redirect', array('WC_Template_Loader', 'unsupported_theme_init'));
  }
}
add_action('wp', 'tomatribe_category_template_support');

/* Comma separated slugs from the query string, e.g. ?filter_size=s,m */
function tomatribe_filter_values($key) {
  if (empty($_GET[$key]) || !is_string($_GET[$key])) return array();
  return array_values(array_filter(array_map('sanitize_title', explode(',', wc_clean(wp_unslash($_GET[$key]))))));
}

/* Number of selected filters (each attribute term, sub category and the price range count once) */
function tomatribe_active_filter_count() {
  $count = count(tomatribe_filter_values('sub_cat'));
  foreach (array_keys($_GET) as $key) {
    if (strpos($key, 'filter_') === 0) {
      $count += count(tomatribe_filter_values($key));
    }
  }
  if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
    $count++;
  }
  return $count;
}

/* Products per page */
function tomatribe_category_product_query($q) {
  if ($q->is_tax('product_cat') || ($q->is_post_type_archive('product') && !$q->is_search())) {
    $q->set('posts_per_page', TOMATRIBE_CATEGORY_PER_PAGE);
  }
}
add_action('woocommerce_product_query', 'tomatribe_category_product_query');

/*
 * Sub category filter (?sub_cat=slug-1,slug-2), applied through post__in.
 * A product_cat tax_query clause would change the page's queried object to the sub category.
 */
function tomatribe_sub_cat_post_in($post_in) {
  if (!tomatribe_is_filter_page()) return $post_in;

  $slugs = tomatribe_filter_values('sub_cat');
  if (!$slugs) return $post_in;

  // Shop page: any category
  $parent = tomatribe_is_category_page() ? get_queried_object() : null;
  $term_ids = array();
  foreach ($slugs as $slug) {
    $sub = get_term_by('slug', $slug, 'product_cat');
    if ($sub && (!$parent || term_is_ancestor_of($parent, $sub, 'product_cat'))) {
      $term_ids[] = $sub->term_id;
      $term_ids = array_merge($term_ids, get_term_children($sub->term_id, 'product_cat'));
    }
  }
  if (!$term_ids) return $post_in;

  $ids = get_objects_in_term($term_ids, 'product_cat');
  $ids = (!is_wp_error($ids) && $ids) ? array_map('intval', $ids) : array();
  if ($post_in) {
    $ids = array_intersect($post_in, $ids);
  }
  // An empty post__in means "no restriction", so match nothing explicitly
  return $ids ? $ids : array(0);
}
add_filter('loop_shop_post_in', 'tomatribe_sub_cat_post_in');

/*
 * Filter options for a category (or the whole shop when $term is null), counted against all visible products in it:
 * - sub_cats:   direct child categories, or top level categories for the shop (counts include their own children)
 * - attributes: product attributes used in the category
 * - price:      min / max price
 */
function tomatribe_category_filter_data($term) {
  global $wpdb;

  $data = array('sub_cats' => array(), 'attributes' => array(), 'price' => null);

  $tax_query = $term ? array(array('taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $term->term_id)) : array();
  $ids = get_posts(array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'fields'         => 'ids',
    'posts_per_page' => -1,
    'no_found_rows'  => true,
    'tax_query'      => WC()->query->get_tax_query($tax_query),
  ));
  if (!$ids) return $data;

  $id_list = implode(',', array_map('absint', $ids));
  $taxonomies = array_merge(array('product_cat'), wc_get_attribute_taxonomy_names());
  $tax_list = "'" . implode("','", array_map('esc_sql', $taxonomies)) . "'";

  // Products per term
  $objects = array();
  $rows = $wpdb->get_results("
    SELECT tt.term_id, tt.taxonomy, tr.object_id
    FROM {$wpdb->term_relationships} tr
    INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
    WHERE tt.taxonomy IN ($tax_list) AND tr.object_id IN ($id_list)
  ");
  foreach ($rows as $row) {
    $objects[$row->taxonomy][$row->term_id][] = (int) $row->object_id;
  }

  // Sub categories
  $children = get_terms(array('taxonomy' => 'product_cat', 'parent' => $term ? $term->term_id : 0, 'hide_empty' => false));
  if (!is_wp_error($children)) {
    foreach ($children as $child) {
      // Shop page: leave out the default "Uncategorized" category
      if (!$term && (int) $child->term_id === (int) get_option('default_product_cat')) continue;

      $products = array();
      foreach (array_merge(array($child->term_id), get_term_children($child->term_id, 'product_cat')) as $term_id) {
        if (isset($objects['product_cat'][$term_id])) {
          $products = array_merge($products, $objects['product_cat'][$term_id]);
        }
      }
      if ($count = count(array_unique($products))) {
        $data['sub_cats'][] = array('slug' => $child->slug, 'name' => $child->name, 'count' => $count);
      }
    }
  }

  // Attributes
  foreach (wc_get_attribute_taxonomies() as $attribute) {
    $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
    if (empty($objects[$taxonomy])) continue;

    // get_terms() applies the attribute's sort order set in Products > Attributes
    $terms = get_terms(array('taxonomy' => $taxonomy, 'include' => array_keys($objects[$taxonomy]), 'hide_empty' => false));
    if (is_wp_error($terms) || !$terms) continue;

    $options = array();
    foreach ($terms as $attribute_term) {
      $options[] = array(
        'slug'  => $attribute_term->slug,
        'name'  => $attribute_term->name,
        'count' => count(array_unique($objects[$taxonomy][$attribute_term->term_id])),
      );
    }
    $data['attributes'][] = array(
      'label' => $attribute->attribute_label,
      'name'  => 'filter_' . $attribute->attribute_name,
      'terms' => $options,
    );
  }

  // Price range
  $prices = $wpdb->get_row("SELECT MIN(min_price) AS min_price, MAX(max_price) AS max_price FROM {$wpdb->wc_product_meta_lookup} WHERE product_id IN ($id_list)");
  if ($prices && $prices->max_price !== null) {
    $min = (int) floor($prices->min_price);
    $max = (int) ceil($prices->max_price);
    if ($min < $max) {
      $data['price'] = array('min' => $min, 'max' => $max);
    }
  }

  return $data;
}

/* AJAX filter request: return only the results markup */
function tomatribe_category_ajax_results() {
  if (!tomatribe_is_filter_page() || empty($_GET['tf_ajax'])) return;

  // Keep the AJAX flag out of the pagination links
  add_filter('paginate_links', function ($link) {
    return remove_query_arg('tf_ajax', $link);
  });

  get_template_part('template/category-products');
  exit;
}
add_action('template_redirect', 'tomatribe_category_ajax_results', 20);

/* Filter script on category and shop pages only */
function tomatribe_category_filter_scripts() {
  if (!tomatribe_is_filter_page()) return;
  wp_enqueue_script('category-filters-js', get_template_directory_uri() . '/assets/custom/js/category-filters.js', array('jquery'), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'tomatribe_category_filter_scripts');
