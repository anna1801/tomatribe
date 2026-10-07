<?php
/**
 * My Account navigation: profile (initials, name, email) and the menu with icons.
 * Theme override of woocommerce/templates/myaccount/navigation.php (same menu items, classes and hooks).
 *
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
$name = trim($current_user->first_name . ' ' . $current_user->last_name);

do_action('woocommerce_before_account_navigation');
?>

<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_html_e('Account pages', 'woocommerce'); ?>">
    <div class="tp-account-profile">
        <span class="tp-account-avatar" aria-hidden="true"> <?php echo esc_html(tomatribe_account_initials($current_user)); ?> </span>
        <span class="tp-account-profile-text">
            <span class="tp-account-name"> <?php echo esc_html($name ? $name : $current_user->display_name); ?> </span>
            <span class="tp-account-email"> <?php echo esc_html($current_user->user_email); ?> </span>
        </span>
    </div>

    <ul>
        <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
            <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" <?php echo wc_is_current_account_menu_item($endpoint) ? 'aria-current="page"' : ''; ?>>
                    <i class="<?php echo esc_attr(tomatribe_account_menu_icon($endpoint)); ?>" aria-hidden="true"></i>
                    <?php echo esc_html($label); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>
