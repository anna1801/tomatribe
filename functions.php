<?php
if ( ! defined( '_S_VERSION' ) ) {
  // Replace the version number of the theme on each release.
  define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'theme_setup' ) ) :
  function theme_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
  }
endif;
add_action( 'after_setup_theme', 'theme_setup' );

/* Register menu */
function register_my_menu() {
  register_nav_menu('header-menu',__( 'Header Menu' ));
  register_nav_menu('footer-menu',__( 'Footer menu' ));
  register_nav_menu('bottom-footer-menu',__( 'Bottom Footer menu' ));
}
add_action( 'init', 'register_my_menu' );

//Disable Gutenburg Editor
add_filter('use_block_editor_for_post', '__return_false', 10);

// support SVG
  function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter('upload_mimes', 'cc_mime_types');

/* Convert to WEBP URL*/
function webpUrl($url) {
  if($url && strpos($url, 'uploads') !== false){
    $url = str_replace("uploads","uploads-webpc/uploads", $url);
    $url = $url . '.webp';
  }
  return $url;
}

/* Enqueue scripts and styles.*/
function theme_scripts() {
  // css
  wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), _S_VERSION );
  wp_enqueue_style( 'bootstrap-css',get_template_directory_uri() . '/assets/css/vendor/bootstrap.min.css');
  wp_enqueue_style('pe-icon-7-stroke-css', get_template_directory_uri() . '/assets/css/vendor/pe-icon-7-stroke.css');
  wp_enqueue_style('font-awesome-css', get_template_directory_uri() . '/assets/css/vendor/font-awesome.min.css', array(), '4.7.0');
  wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/css/plugins/slick.min.css', array(), '1.9.0');
  wp_enqueue_style('animate-css', get_template_directory_uri() . '/assets/css/plugins/animate.css', array(), '3.7.0');
  wp_enqueue_style('nice-select-css', get_template_directory_uri() . '/assets/css/plugins/nice-select.css', array(), '1.0');
  wp_enqueue_style( 'main-css', get_template_directory_uri() . '/assets/css/style.min.css', array(), filemtime( get_template_directory() . '/assets/css/style.min.css' ) );
  wp_style_add_data( 'theme-style', 'rtl', 'replace' );
// js
  wp_enqueue_script('bootstrap-js',get_template_directory_uri() . '/assets/js/vendor/bootstrap.bundle.min.js', array('jquery'), _S_VERSION, true );
  wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/js/plugins/slick.min.js', array('jquery'), '1.9.0', true);
  wp_enqueue_script('nice-select-js', get_template_directory_uri() . '/assets/js/plugins/nice-select.min.js', array('jquery'), '1.0', true);
  wp_enqueue_script('image-zoom-js', get_template_directory_uri() . '/assets/js/plugins/image-zoom.min.js', array('jquery'), '1.7.21', true);
  wp_enqueue_script( 'main-js', get_template_directory_uri() . '/assets/js/main.min.js', array(), _S_VERSION, true );
  wp_enqueue_script( 'additional-js', get_template_directory_uri() . '/assets/custom/js/custom.js', array('jquery'), _S_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );

// Disable automatic <p> and <br> tags in Contact Form 7 forms
add_filter('wpcf7_autop_or_not', '__return_false');

//woocommerce support
function tomatribe_woocommerce_setup() {
    add_theme_support( 'woocommerce' );

    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

}
add_action( 'after_setup_theme', 'tomatribe_woocommerce_setup' );

// custom functions
require get_template_directory() . '/includes/custom.php';
require get_template_directory() . '/includes/nav-walker.php';
require get_template_directory() . '/includes/header-counts.php';
require get_template_directory() . '/includes/mini-cart.php';
require get_template_directory() . '/includes/product-search.php';
require get_template_directory() . '/includes/category-filters.php';
require get_template_directory() . '/includes/single-product.php';
require get_template_directory() . '/includes/cart.php';
require get_template_directory() . '/includes/wishlist.php';
require get_template_directory() . '/includes/checkout.php';
require get_template_directory() . '/includes/account.php';

?>