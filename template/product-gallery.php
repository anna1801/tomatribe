<?php
/*
 * Product page gallery: main image slider (hover zoom, lightbox button) + thumbnails.
 * get_template_part('template/product-gallery', null, array('product' => $product));
 * Slider, zoom, lightbox and variation images are handled in assets/custom/js/single-product.js.
 */
$product = $args['product'];
$image_ids = array_values(array_unique(array_filter(array_merge(array($product->get_image_id()), $product->get_gallery_image_ids()))));
?>
<div class="tp-gallery">
    <div class="tp-gallery-stage">
        <div class="tp-gallery-main">
            <?php if ($image_ids) : ?>
                <?php foreach ($image_ids as $index => $image_id) : $full = wp_get_attachment_image_src($image_id, 'full'); ?>
                    <div class="tp-gallery-slide" data-image-id="<?php echo esc_attr($image_id); ?>">
                        <div class="tp-gallery-zoom" data-full="<?php echo esc_url($full[0]); ?>" data-width="<?php echo esc_attr($full[1]); ?>" data-height="<?php echo esc_attr($full[2]); ?>">
                            <?php echo wp_get_attachment_image($image_id, 'woocommerce_single', false, array('class' => 'tp-gallery-image', 'loading' => $index ? 'lazy' : 'eager')); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="tp-gallery-slide">
                    <?php echo wc_placeholder_img('woocommerce_single', array('class' => 'tp-gallery-image')); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php if ($image_ids) : ?>
            <button type="button" class="tp-gallery-expand" aria-label="View larger image"><i class="pe-7s-search"></i></button>
        <?php endif; ?>
    </div>

    <?php if (count($image_ids) > 1) : ?>
        <div class="tp-gallery-thumbs">
            <?php foreach ($image_ids as $index => $image_id) : ?>
                <button type="button" class="tp-gallery-thumb<?php echo $index ? '' : ' is-active'; ?>" data-index="<?php echo esc_attr($index); ?>" aria-label="<?php echo esc_attr(sprintf('Show image %d', $index + 1)); ?>">
                    <?php echo wp_get_attachment_image($image_id, 'medium', false, array('loading' => 'lazy')); ?>
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
