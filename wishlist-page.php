<?php
/*
 * Wishlist page: breadcrumb, heading, the YITH wishlist shortcode (page content, cards in woocommerce/wishlist-view.php)
 * and store highlights. Used for the wishlist page via includes/wishlist.php. Styling: .tp-wishlist-section in assets/scss/_general.scss.
 */
get_header();
?>

<section class="tp-wishlist-section">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('current' => get_the_title())); ?>

        <div class="latest-products-heading">
            <span class="latest-products-subtitle"> Saved For Later </span>
            <h2> <?php the_title(); ?> </h2>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <div class="tp-wishlist-content">
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
