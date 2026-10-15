<?php
/*
 * Site-wide image popup from the "website_popup_img" image field (Theme Settings options page),
 * linked to the "cta_link" URL field when it's set.
 * Included from footer.php; opened once per browser session by assets/src/general.js.
 */
$popup_image = get_field('website_popup_img', 'option');
// Not on cart / checkout, so it never gets in the way of an order
if (!$popup_image || empty($popup_image['ID']) || (function_exists('is_cart') && (is_cart() || is_checkout()))) return;
?>
<div class="tt-popup" id="tt-popup" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr($popup_image['alt'] ? $popup_image['alt'] : get_bloginfo('name')); ?>" aria-hidden="true" data-popup-key="<?php echo esc_attr($popup_image['ID']); ?>">
    <div class="tt-popup-overlay" data-popup-close></div>
    <div class="tt-popup-panel">
        <button type="button" class="tt-popup-close" data-popup-close aria-label="Close popup"> <i class="pe-7s-close"></i> </button>
        <?php
            $popup_img = wp_get_attachment_image($popup_image['ID'], 'large', false, array('class' => 'tt-popup-image', 'loading' => 'lazy'));
            $cta_link = get_field('website_popup_cta_link', 'option');
            if ($cta_link) :
                echo '<a href="' . esc_url($cta_link) . '" target="_blank" class="tt-popup-link"> ' . $popup_img . ' </a>';
            else :
                echo $popup_img;
            endif;
        ?>
    </div>
</div>
