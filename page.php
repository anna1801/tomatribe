<?php
/*
 * Default page template: breadcrumb, heading and the page content (privacy policy, refund policy, terms etc.).
 * Styling: .tp-page-section in assets/scss/_general.scss.
 */
get_header();
?>

<section class="tp-page-section">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('current' => get_the_title())); ?>

        <?php while (have_posts()) : the_post(); ?>
            <div class="latest-products-heading">
                <h1> <?php the_title(); ?> </h1>
            </div>

            <div class="tp-page-content">
                <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<nav class="tp-page-links">',
                        'after' => '</nav>',
                    ));
                ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
