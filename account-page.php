<?php
/*
 * My Account page: breadcrumb, heading (current endpoint's title) and the [woocommerce_my_account] shortcode
 * (page content, markup in woocommerce/myaccount/). Used via includes/account.php.
 * Styling: .tp-account-section in assets/scss/_general.scss.
 */
$title = tomatribe_account_title();

get_header();
?>

<section class="tp-account-section<?php echo is_user_logged_in() ? ' is-logged-in' : ' is-logged-out'; ?>">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('current' => $title)); ?>

        <div class="latest-products-heading">
            <span class="latest-products-subtitle"> <?php echo is_user_logged_in() ? 'My Account' : 'Welcome'; ?> </span>
            <h2> <?php echo esc_html($title); ?> </h2>
        </div>

        <?php while (have_posts()) : the_post(); ?>
            <div class="tp-account">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
