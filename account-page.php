<?php
/*
 * My Account page: breadcrumb, heading (current endpoint's title) and the [woocommerce_my_account] shortcode
 * (page content, markup in woocommerce/myaccount/). Used via includes/account.php.
 * Styling: .tp-account-section in assets/scss/_general.scss.
 */
$title = tomatribe_account_title();
// The login / register card has its own headings, so no page heading there (lost / reset password keeps it)
$show_heading = is_user_logged_in() || is_wc_endpoint_url('lost-password');

get_header();
?>

<section class="tp-account-section<?php echo is_user_logged_in() ? ' is-logged-in' : ' is-logged-out'; ?>">
    <div class="container">
        <?php get_template_part('template/breadcrumb', null, array('current' => $title)); ?>

        <?php if ($show_heading) : ?>
            <div class="latest-products-heading">
                <span class="latest-products-subtitle"> <?php echo is_user_logged_in() ? 'My Account' : 'Welcome'; ?> </span>
                <h2> <?php echo esc_html($title); ?> </h2>
            </div>
        <?php endif; ?>

        <?php while (have_posts()) : the_post(); ?>
            <div class="tp-account">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
