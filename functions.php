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
  wp_enqueue_style( 'bootstrap-css',get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css');
  wp_enqueue_style( 'custom-styles', get_template_directory_uri() . '/assets/css/style.min.css', array(), '1.0' );
  wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/slick/css/slick.min.css', array(), '1.8.1');
  wp_enqueue_style('slick-theme-css',  get_template_directory_uri() . '/assets/slick/css/slick-theme.min.css', array('slick-css'), '1.8.1');
  wp_enqueue_style( 'additional-styles', get_template_directory_uri() . '/assets/custom/css/custom.css', array(), '1.0' );
  wp_style_add_data( 'theme-style', 'rtl', 'replace' );
// js
  wp_enqueue_script('bootstrap-js',get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.bundle.min.js',array('jquery'), _S_VERSION, true );
  wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/assets/js/main.min.js', array(), _S_VERSION, true );
  wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/slick/js/slick.min.js', array('jquery'), '1.8.1', true);
  wp_enqueue_script( 'additional-js', get_template_directory_uri() . '/assets/custom/js/custom.js', array(), _S_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );

// Disable automatic <p> and <br> tags in Contact Form 7 forms
add_filter('wpcf7_autop_or_not', '__return_false');

// custom functions
require get_template_directory() . '/includes/custom.php';

?>