<?php
/*
 * Checkout page: breadcrumb, heading, steps (Basket / Checkout / Confirmation), the [woocommerce_checkout] shortcode
 * (page content, markup in woocommerce/checkout/) and store highlights. Also renders the order received and order pay endpoints.
 * Used via includes/checkout.php. Styling: .tp-checkout-section in assets/scss/_general.scss.
 */
$is_received = function_exists('is_wc_endpoint_url') && is_wc_endpoint_url('order-received');
$title = $is_received ? __('Order received', 'woocommerce') : get_the_title();

get_header();
?>

<section class="tp-checkout-section<?php echo $is_received ? ' is-order-received' : ''; ?>">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('current' => $title)); ?>

        <div class="latest-products-heading">
            <span class="latest-products-subtitle"> <?php echo $is_received ? 'Thank You' : 'Secure Checkout'; ?> </span>
            <h2> <?php echo esc_html($title); ?> </h2>
        </div>

        <ol class="tp-checkout-steps">
            <li class="is-done">
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>"> <span class="tp-step-number"> <i class="fa fa-check" aria-hidden="true"></i> </span> <?php esc_html_e('Basket', 'woocommerce'); ?> </a>
            </li>
            <li class="<?php echo $is_received ? 'is-done' : 'is-current'; ?>"<?php echo $is_received ? '' : ' aria-current="step"'; ?>>
                <span class="tp-step-number"> <?php echo $is_received ? '<i class="fa fa-check" aria-hidden="true"></i>' : '2'; ?> </span> <?php esc_html_e('Checkout', 'woocommerce'); ?>
            </li>
            <li class="<?php echo $is_received ? 'is-current' : ''; ?>"<?php echo $is_received ? ' aria-current="step"' : ''; ?>>
                <span class="tp-step-number"> 3 </span> Confirmation
            </li>
        </ol>

        <?php while (have_posts()) : the_post(); ?>
            <div class="tp-checkout">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>

        <?php
            if (have_rows('features_tomatribe', 'option')) :
                echo '<ul class="tp-highlights">';
                    while (have_rows('features_tomatribe', 'option')) : the_row();
                        $icon = get_sub_field('icon');
                        $text = get_sub_field('text');
                        echo '<li> <i class="' . esc_attr($icon) . '"></i> ' . $text . ' </li>';
                    endwhile;
                echo '</ul>';
            endif;
        ?>
    </div>
</section>

<?php get_footer(); ?>
