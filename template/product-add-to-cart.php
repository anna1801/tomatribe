<?php
/*
 * Product page add to cart: attribute swatches (size, color, ...) for variable products, stock and button.
 * get_template_part('template/product-add-to-cart', null, array('product' => $product, 'size_guide' => $size_guide));
 *
 * Variable products use WooCommerce's variation form script: the swatch buttons drive hidden
 * attribute selects (see assets/custom/js/single-product.js). The form is submitted via AJAX
 * (includes/single-product.php) and still works as a normal form post.
 */
$product = $args['product'];
$size_guide = $args['size_guide'];
$form_action = apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink());

if ($product->is_type('variable')) :
    $attributes = $product->get_variation_attributes();
    $get_variations = count($product->get_children()) <= apply_filters('woocommerce_ajax_variation_threshold', 30, $product);
    $available_variations = $get_variations ? $product->get_available_variations() : false;
    $variations_attr = wc_esc_json(wp_json_encode($available_variations));
?>
    <form class="variations_form cart tp-cart-form" action="<?php echo esc_url($form_action); ?>" method="post" enctype="multipart/form-data" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_variations="<?php echo $variations_attr; ?>">
        <?php if (empty($available_variations) && $available_variations !== false) : ?>
            <p class="stock out-of-stock"> This product is currently out of stock and unavailable. </p>
        <?php else : ?>
            <div class="variations tp-attributes">
                <?php foreach ($attributes as $attribute_name => $options) :
                    $type = tomatribe_attribute_type($attribute_name);
                    $label = wc_attribute_label($attribute_name, $product);
                ?>
                    <div class="tp-attribute tp-attribute-<?php echo esc_attr($type); ?>">
                        <div class="tp-attribute-head">
                            <span class="tp-attribute-label"> <?php echo esc_html($label); ?>: <span class="tp-attribute-value"></span> </span>
                            <?php if ($type === 'size' && $size_guide) : ?>
                                <button type="button" class="tp-size-guide-btn" aria-controls="tp-size-guide" aria-haspopup="dialog"> Size Guide </button>
                            <?php endif; ?>
                        </div>
                        <div class="tp-swatches" role="group" aria-label="<?php echo esc_attr($label); ?>">
                            <?php foreach (tomatribe_attribute_choices($product, $attribute_name, $options) as $choice) :
                                $is_color = $type === 'color' && $choice['color'];
                            ?>
                                <button type="button" class="tp-swatch<?php echo $is_color ? ' tp-swatch-color' : ''; ?>" data-value="<?php echo esc_attr($choice['value']); ?>" title="<?php echo esc_attr($choice['label']); ?>" aria-pressed="false"<?php echo $is_color ? ' style="--swatch: ' . esc_attr($choice['color']) . '"' : ''; ?>>
                                    <?php if ($is_color) : ?>
                                        <span class="visually-hidden"> <?php echo esc_html($choice['label']); ?> </span>
                                    <?php else : ?>
                                        <?php echo esc_html($choice['label']); ?>
                                    <?php endif; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <div class="tp-attribute-select" hidden>
                            <?php wc_dropdown_variation_attribute_options(array('options' => $options, 'attribute' => $attribute_name, 'product' => $product)); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <a class="reset_variations" href="#"> Clear selection </a>
            </div>

            <div class="single_variation_wrap">
                <div class="woocommerce-variation single_variation" role="alert" aria-relevant="additions"></div>
                <div class="woocommerce-variation-add-to-cart variations_button">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="single_add_to_cart_button tp-add-to-cart"> <?php echo esc_html($product->single_add_to_cart_text()); ?> </button>
                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">
                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                    <input type="hidden" name="variation_id" class="variation_id" value="0">
                </div>
            </div>
        <?php endif; ?>
    </form>
    <?php wc_get_template('single-product/add-to-cart/variation.php'); ?>

<?php elseif ($product->is_type('simple')) : ?>
    <?php echo wc_get_stock_html($product); ?>
    <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
        <form class="cart tp-cart-form" action="<?php echo esc_url($form_action); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">
            <button type="submit" class="single_add_to_cart_button tp-add-to-cart"> <?php echo esc_html($product->single_add_to_cart_text()); ?> </button>
        </form>
    <?php endif; ?>

<?php else : ?>
    <?php woocommerce_template_single_add_to_cart(); ?>
<?php endif; ?>
