<?php
/**
 * Login / register (My Account, logged out): one card with "Log in" and "Register" tabs.
 * Theme override of woocommerce/templates/myaccount/form-login.php: the forms, fields, nonces and hooks are WooCommerce's.
 *
 * The Register tab opens with ?action=register (header link, tomatribe_register_url()) or after a failed registration;
 * assets/custom/js/account.js switches tabs without reloading. Styling: .tp-auth in assets/scss/_general.scss.
 *
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$registration = tomatribe_registration_enabled();
// phpcs:ignore WordPress.Security.NonceVerification
$active = ($registration && ((isset($_GET['action']) && 'register' === $_GET['action']) || !empty($_POST['register']))) ? 'register' : 'login';
$login_url = wc_get_page_permalink('myaccount');

do_action('woocommerce_before_customer_login_form'); ?>

<div class="tp-auth" id="customer_login">

    <?php if ($registration) : ?>
        <div class="tp-auth-tabs" role="tablist">
            <a href="<?php echo esc_url($login_url); ?>" class="tp-auth-tab<?php echo 'login' === $active ? ' is-active' : ''; ?>" id="tp-auth-tab-login" data-tab="login" role="tab" aria-controls="tp-auth-login" aria-selected="<?php echo 'login' === $active ? 'true' : 'false'; ?>"> <?php esc_html_e('Log in', 'woocommerce'); ?> </a>
            <a href="<?php echo esc_url(tomatribe_register_url()); ?>" class="tp-auth-tab<?php echo 'register' === $active ? ' is-active' : ''; ?>" id="tp-auth-tab-register" data-tab="register" role="tab" aria-controls="tp-auth-register" aria-selected="<?php echo 'register' === $active ? 'true' : 'false'; ?>"> <?php esc_html_e('Register', 'woocommerce'); ?> </a>
        </div>
    <?php endif; ?>

    <div class="tp-auth-panel" id="tp-auth-login" data-panel="login" <?php echo $registration ? 'role="tabpanel" aria-labelledby="tp-auth-tab-login"' : ''; ?><?php echo 'login' === $active ? '' : ' hidden'; ?>>
        <h2> Welcome back </h2>
        <p class="tp-auth-intro"> Log in to track your orders, manage your addresses and check out faster. </p>

        <form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

            <?php do_action('woocommerce_login_form_start'); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
            </p>

            <?php do_action('woocommerce_login_form'); ?>

            <p class="form-row tp-auth-row">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
                </label>
                <a class="tp-auth-lost" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
            </p>
            <p class="form-row">
                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
            </p>

            <?php do_action('woocommerce_login_form_end'); ?>

        </form>

        <?php if ($registration) : ?>
            <p class="tp-auth-switch"> New here? <a href="<?php echo esc_url(tomatribe_register_url()); ?>" data-tab="register"> Create an account </a> </p>
        <?php endif; ?>
    </div>

    <?php if ($registration) : ?>
        <div class="tp-auth-panel" id="tp-auth-register" data-panel="register" role="tabpanel" aria-labelledby="tp-auth-tab-register"<?php echo 'register' === $active ? '' : ' hidden'; ?>>
            <h2> Create an account </h2>
            <p class="tp-auth-intro"> Save your details for faster checkout, follow your orders and keep your wishlist in one place. </p>

            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?> >

                <?php do_action('woocommerce_register_form_start'); ?>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
                    </p>

                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
                </p>

                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
                    </p>

                <?php else : ?>

                    <p class="tp-auth-note"><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>

                <?php endif; ?>

                <?php do_action('woocommerce_register_form'); ?>

                <p class="woocommerce-form-row form-row">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
                </p>

                <?php do_action('woocommerce_register_form_end'); ?>

            </form>

            <p class="tp-auth-switch"> Already have an account? <a href="<?php echo esc_url($login_url); ?>" data-tab="login"> Log in </a> </p>
        </div>
    <?php endif; ?>

</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
