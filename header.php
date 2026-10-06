<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>







<header class="tv-header">

    <!-- ================= DESKTOP HEADER ================= -->
    <div class="tv-desktop-header">

        <!-- TOP BAR -->
        <div class="tv-topbar">
            <div class="tv-container tv-topbar-inner">

                <!-- Social -->
                <div class="tv-social">
                    <span>Follow Us</span>
                    <a href="https://www.facebook.com" target="_blank">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com" target="_blank">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a href="https://www.youtube.com" target="_blank">
                        <i class="fa fa-youtube-play"></i>
                    </a>
                </div>

                <!-- Top message -->
                <div class="tv-top-message">
                    Handcrafted • Sustainable • Tribal Inspired
                </div>

                <!-- Contact -->
                <div class="tv-contact">
                    <a href="tel:+917219111073">
                        <i class="fa fa-phone"></i> +91 7219 111 073
                    </a>
                </div>

            </div>
        </div>


        <!-- MAIN NAVIGATION -->
        <div class="tv-navigation">
            <div class="tv-container">

                <div class="tv-nav-wrapper">

                    <!-- LEFT MENU -->
                    <nav class="tv-nav tv-nav-left">

                        <a href="index.html" class="tv-nav-link active">
                            Home
                        </a>

                        <a href="about.html" class="tv-nav-link">
                            About Us
                        </a>

                        <!-- Categories -->
                        <div class="tv-nav-dropdown">

                            <a href="#" class="tv-nav-link">
                                Categories
                                <i class="fa fa-angle-down"></i>
                            </a>

                            <div class="tv-mega-menu">

                               <div class="tv-mega-column">

    <a href="product-category.html" class="tv-mega-title">
        Menswear
    </a>

    <a href="product-sub-category.html">Shirts</a>
    <a href="product-sub-category.html">T-Shirts</a>
    <a href="product-sub-category.html">Trousers</a>
    <a href="product-sub-category.html">Jeans</a>
    <a href="product-sub-category.html">Shorts</a>
    <a href="product-sub-category.html">Jackets</a>
    <a href="product-sub-category.html">Kurtas</a>

</div>


<div class="tv-mega-column">

    <a href="product-category.html" class="tv-mega-title">
        Womenswear
    </a>

    <a href="product-sub-category.html">Dresses</a>
    <a href="product-sub-category.html">Tops</a>
    <a href="product-sub-category.html">Tunics</a>
    <a href="product-sub-category.html">Skirts</a>
    <a href="product-sub-category.html">Co-Ord Sets</a>
    <a href="product-sub-category.html">Kimonos</a>
    <a href="product-sub-category.html">Jumpsuits</a>

</div>


                            </div>

                        </div>

                    </nav>


                    <!-- CENTER LOGO -->
                    <div class="tv-logo">

                        <a href="index.html">

                            <img
                                src="assets/img/logo/logo.png"
                                alt="Toma Tribe by Shubhangi"
                            style="    width: 80px;">

                        </a>

                    </div>


                    <!-- RIGHT MENU -->
                    <nav class="tv-nav tv-nav-right">

                        <a href="blog.html" class="tv-nav-link">
                            Blog
                        </a>

                        <a href="contact-us.html" class="tv-nav-link">
                            Contact
                        </a>

                        <a href="reviews.html" class="tv-nav-link">
                            Reviews
                        </a>


                        <!-- SEARCH -->
                        <button class="tv-icon-btn search-trigger"
                                type="button"
                                aria-label="Search">

                            <i class="pe-7s-search"></i>

                        </button>


                        <!-- ACCOUNT -->
                        <div class="tv-account">

                            <button class="tv-icon-btn" type="button">

                                <i class="pe-7s-user"></i>

                            </button>

                            <div class="tv-account-dropdown">

                                <a href="login.html">Login</a>
                                <a href="register.html">Register</a>
                                <a href="my-account.html">My Account</a>

                            </div>

                        </div>


                        <!-- WISHLIST -->
                        <a href="#" class="tv-icon-btn tv-icon-with-count">

                            <i class="pe-7s-like"></i>

                            <span class="tv-count">0</span>

                        </a>


                        <!-- CART -->
                        <a href="cart.html"
                           class="tv-icon-btn tv-icon-with-count">

                            <i class="pe-7s-shopbag"></i>

                            <span class="tv-count">2</span>

                        </a>

                    </nav>

                </div>

            </div>
        </div>


        <!-- SEARCH BOX -->
        <div class="tv-search-panel">

            <div class="tv-container">

                <form>

                    <input
                        type="text"
                        placeholder="Search products..."
                        class="tv-search-input"
                    >

                    <button type="submit">
                        <i class="pe-7s-search"></i>
                    </button>

                </form>

            </div>

        </div>

    </div>


    <!-- ================= MOBILE HEADER ================= -->

    <div class="tv-mobile-header">

        <div class="tv-mobile-inner">

            <!-- Mobile menu -->
            <button class="tv-mobile-menu-btn mobile-menu-btn"
                    type="button">

                <span></span>
                <span></span>
                <span></span>

            </button>


            <!-- Mobile logo -->
            <div class="tv-mobile-logo">

                <a href="index.html">

                    <img
                        src="assets/img/logo/logo.png"
                        alt="Toma Tribe by Shubhangi"
                    >

                </a>

            </div>


            <!-- Mobile cart -->
            <a href="cart.html"
               class="tv-mobile-cart">

                <i class="pe-7s-shopbag"></i>

                <span>2</span>

            </a>

        </div>

    </div>


    <!-- ================= MOBILE OFF CANVAS ================= -->

    <aside class="tv-mobile-offcanvas">

        <div class="tv-mobile-overlay off-canvas-overlay"></div>

        <div class="tv-mobile-panel">

            <!-- Close -->
            <button class="tv-mobile-close btn-close-off-canvas"
                    type="button">

                <i class="pe-7s-close"></i>

            </button>


            <!-- Logo -->
            <div class="tv-mobile-panel-logo">

                <a href="index.html">

                    <img
                        src="assets/img/logo/logo.png"
                        alt="Toma Tribe by Shubhangi"
                     style="width:50%;">

                </a>

            </div>


            <!-- Search -->
            <div class="tv-mobile-search">

                <form>

                    <input
                        type="text"
                        placeholder="Search products..."
                    >

                    <button type="submit">
                        <i class="pe-7s-search"></i>
                    </button>

                </form>

            </div>


            <!-- Mobile navigation -->
            <nav class="tv-mobile-navigation">

                <a href="index.html" class="active">
                    Home
                </a>

                <a href="about.html">
                    About Us
                </a>


                <div class="tv-mobile-category">

                    <div class="tv-mobile-category-title">
                        <a href="#">
                            Categories
                        </a>

                        <button type="button">
                            <i class="fa fa-angle-down"></i>
                        </button>
                    </div>




                    <div class="tv-mobile-submenu">

                        <a href="product-category.html"
                           class="tv-mobile-main-category">
                            Menswear
                        </a>

    <a href="product-sub-category.html">Shirts</a>
    <a href="product-sub-category.html">T-Shirts</a>
    <a href="product-sub-category.html">Trousers</a>
    <a href="product-sub-category.html">Jeans</a>
    <a href="product-sub-category.html">Shorts</a>
    <a href="product-sub-category.html">Jackets</a>
    <a href="product-sub-category.html">Kurtas</a>


                        <a href="product-category.html"
                           class="tv-mobile-main-category">
                            Womenswear
                        </a>

                           <a href="product-sub-category.html">Dresses</a>
    <a href="product-sub-category.html">Tops</a>
    <a href="product-sub-category.html">Tunics</a>
    <a href="product-sub-category.html">Skirts</a>
    <a href="product-sub-category.html">Co-Ord Sets</a>
    <a href="product-sub-category.html">Kimonos</a>
    <a href="product-sub-category.html">Jumpsuits</a>

                    </div>

                </div>


                <a href="blog.html">
                    Blog
                </a>

                <a href="contact-us.html">
                    Contact Us
                </a>

                <a href="reviews.html">
                    Reviews
                </a>

            </nav>


            <!-- Account -->
            <div class="tv-mobile-account">

                <div class="tv-mobile-account-title">
                    My Account
                </div>

                <a href="my-account.html">
                    My Account
                </a>

                <a href="login.html">
                    Login
                </a>

                <a href="register.html">
                    Register
                </a>

            </div>


            <!-- Contact -->
            <div class="tv-mobile-contact">

                <a href="tel:+917219111073">
                    <i class="fa fa-phone"></i>
                    +91 7219 111 073
                </a>

                <a href="mailto:info@tomatribe.in">
                    <i class="fa fa-envelope-o"></i>
                    info@tomatribe.in
                </a>

            </div>


            <!-- Social -->
            <div class="tv-mobile-social">

                <a href="https://www.facebook.com"
                   target="_blank">
                    <i class="fa fa-facebook"></i>
                </a>

                <a href="https://www.instagram.com"
                   target="_blank">
                    <i class="fa fa-instagram"></i>
                </a>

                <a href="https://www.youtube.com"
                   target="_blank">
                    <i class="fa fa-youtube-play"></i>
                </a>

            </div>

        </div>

    </aside>

</header>













<main>