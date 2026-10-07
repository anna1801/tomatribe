<?php
/*
 * Cart page: breadcrumb, heading, the [woocommerce_cart] shortcode (page content, markup in woocommerce/cart/) and store highlights.
 * Used for the cart page via includes/cart.php. Cart styling: .tp-cart-section in assets/scss/_general.scss.
 */
get_header();
?>

<section class="tp-cart-section">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('current' => get_the_title())); ?>

        <div class="latest-products-heading">
            <span class="latest-products-subtitle"> Shopping Bag </span>
            <h2> <?php the_title(); ?> </h2>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <div class="tp-cart">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>

        <?php
            if (have_rows('features_single_products', 'option')) :
                echo '<ul class="tp-highlights">';
                    while (have_rows('features_single_products', 'option')) : the_row();
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
