<?php
/*
 * Size guide drawer (opens from the right) showing the product's ACF "size_guide" field.
 * get_template_part('template/size-guide', null, array('guide' => tomatribe_size_guide($product)));
 */
$guide = $args['guide'];
?>
<div class="tp-drawer" id="tp-size-guide" role="dialog" aria-modal="true" aria-labelledby="tp-size-guide-title" aria-hidden="true">
    <div class="tp-drawer-overlay" data-drawer-close></div>
    <div class="tp-drawer-panel">
        <div class="tp-drawer-head">
            <h3 class="tp-drawer-title" id="tp-size-guide-title"> Size Guide </h3>
            <button type="button" class="tp-drawer-close" data-drawer-close aria-label="Close size guide"> &times; </button>
        </div>
        <div class="tp-drawer-body tp-size-guide-content">
            <?php echo $guide; ?>
        </div>
    </div>
</div>
