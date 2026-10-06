<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php $header_logo = get_field('header_logo', 'option'); ?>

<header class="tv-header">
    <div class="tv-desktop-header">
        <?php 
            $show_hide_top_header = get_field('show_hide_top_header', 'option');
            if($show_hide_top_header) :
                $top_header_content_alignment = get_field('top_header_content_alignment', 'option');
                if($top_header_content_alignment) {
                    $align = $top_header_content_alignment;
                } else {
                    $align = 'center';
                }
            ?>
            <div class="tv-topbar">
                <div class="tv-container tv-topbar-inner justify-content-<?php echo $align; ?> gap-5">
                    <?php 
                        if (have_rows('top_header_social_links', 'option')) :
                            echo '<div class="tv-social">';
                                echo '<span>Follow Us</span>';
                                while (have_rows('top_header_social_links', 'option')) : the_row(); 
                                    $icon = get_sub_field('icon'); 
                                    $link = get_sub_field('link'); 
                                    echo '<a href="'.$link.'" target="_blank"> <i class="'.$icon.'"></i> </a>';
                                endwhile; 
                            echo '</div>';
                        endif; 

                        $top_header_content = get_field('top_header_content', 'option');
                        if($top_header_content) :
                            echo ' <div class="tv-top-message">'.$top_header_content.'</div>';
                        endif;

                        if (have_rows('top_header_contact', 'option')) :
                            echo '<div class="tv-contact">';
                                while (have_rows('top_header_contact', 'option')) : the_row(); 
                                    $contact = get_sub_field('contact'); 
                                    $value = get_sub_field('value'); 

                                    if($contact == 'phone') {
                                        $href = 'tel:'.$value;
                                        $icon = 'fa fa-phone';
                                    } elseif($contact == 'email') {
                                        $href = 'mailto:'.$value;
                                        $icon = 'fa fa-envelope';
                                    }
                                    echo ' <a href="'.$href.'"> <i class="'.$icon.'"></i> '.$value.' </a>';

                                endwhile; 
                            echo '</div>';
                        endif; 
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="tv-navigation">
            <div class="tv-container">
                <div class="tv-nav-wrapper">
                    <nav class="tv-nav tv-nav-left">
                        <?php
                            wp_nav_menu(array(
                                'theme_location' => 'header-menu',
                                'container'      => false,
                                'items_wrap'     => '%3$s',
                                'fallback_cb'    => false,
                                'depth'          => 3,
                                'walker'         => new Tomatribe_Header_Nav_Walker(),
                                'tv_part'        => 'left',
                            ));
                        ?>
                    </nav>

                    <?php 
                        if($header_logo) :
                            echo '<div class="tv-logo"> 
                                    <a href="'.home_url().'"><img src="'.$header_logo['url'].'" alt="'.$header_logo['alt'].'" style="width: 80px;"></a> 
                                </div>';
                        endif;
                    ?>

                    <nav class="tv-nav tv-nav-right">
                        <?php
                            wp_nav_menu(array(
                                'theme_location' => 'header-menu',
                                'container'      => false,
                                'items_wrap'     => '%3$s',
                                'fallback_cb'    => false,
                                'depth'          => 3,
                                'walker'         => new Tomatribe_Header_Nav_Walker(),
                                'tv_part'        => 'right',
                            ));
                        ?>
                        <button class="tv-icon-btn search-trigger" type="button" aria-label="Search"> <i class="pe-7s-search"></i></button>
                        <div class="tv-account">
                            <button class="tv-icon-btn" type="button"><i class="pe-7s-user"></i></button>
                            <!-- to do -->
                            <div class="tv-account-dropdown">
                                <a href="login.html">Login</a>
                                <a href="register.html">Register</a>
                                <a href="my-account.html">My Account</a>
                            </div>
                            <!-- to do end -->
                        </div>
                        <a href="<?php echo esc_url(function_exists('YITH_WCWL') ? YITH_WCWL()->get_wishlist_url() : '#'); ?>" class="tv-icon-btn tv-icon-with-count"> <i class="pe-7s-like"></i> <span class="tv-count tv-wishlist-count"><?php echo esc_html(tomatribe_wishlist_count()); ?></span> </a>
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="tv-icon-btn tv-icon-with-count"> <i class="pe-7s-shopbag"></i> <?php echo tomatribe_cart_count_html(); ?></a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="tv-search-panel">
            <div class="tv-container">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="text" name="s" value="<?php echo get_search_query(); ?>" placeholder="Search products..." class="tv-search-input" aria-label="Search products" required>
                    <input type="hidden" name="post_type" value="product">
                    <button type="submit" aria-label="Search"> <i class="pe-7s-search"></i> </button>
                </form>
            </div>
        </div>
    </div>

    <div class="tv-mobile-header">
        <div class="tv-mobile-inner">
            <button class="tv-mobile-menu-btn mobile-menu-btn" type="button">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <?php 
                if($header_logo) :
                    echo '
                        <div class="tv-mobile-logo">
                            <a href="'.home_url().'">
                                <img src="'.$header_logo['url'].'" alt="'.$header_logo['alt'].'" >
                            </a>
                        </div>
                    ';
                endif;
            ?>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="tv-mobile-cart">
                <i class="pe-7s-shopbag"></i>
                <?php echo tomatribe_cart_count_html(true); ?>
            </a>
        </div>
    </div>

    <aside class="tv-mobile-offcanvas">
        <div class="tv-mobile-overlay off-canvas-overlay"></div>
        <div class="tv-mobile-panel">
            <button class="tv-mobile-close btn-close-off-canvas" type="button"> <i class="pe-7s-close"></i> </button>
            <?php 
                if($header_logo) :
                    echo '
                        <div class="tv-mobile-panel-logo">
                            <a href="'.home_url().'">
                                <img src="'.$header_logo['url'].'" alt="'.$header_logo['alt'].'">
                            </a>
                        </div>
                    ';
                endif;
            ?>

            <div class="tv-mobile-search">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="text" name="s" value="<?php echo get_search_query(); ?>" placeholder="Search products..." aria-label="Search products" required>
                    <input type="hidden" name="post_type" value="product">
                    <button type="submit" aria-label="Search">
                        <i class="pe-7s-search"></i>
                    </button>
                </form>
            </div>

            <nav class="tv-mobile-navigation">
                <?php
                    wp_nav_menu(array(
                        'theme_location' => 'header-menu',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'fallback_cb'    => false,
                        'depth'          => 3,
                        'walker'         => new Tomatribe_Mobile_Nav_Walker(),
                    ));
                ?>
            </nav>
            <!-- to do -->
            <div class="tv-mobile-account">
                <div class="tv-mobile-account-title"> My Account </div>
                <a href="my-account.html"> My Account</a>
                <a href="login.html"> Login </a>
                <a href="register.html"> Register</a>
            </div>
            <!-- to do end -->

            <?php 
                if (have_rows('top_header_contact', 'option')) :
                    echo '<div class="tv-mobile-contact">';
                        while (have_rows('top_header_contact', 'option')) : the_row(); 
                            $contact = get_sub_field('contact'); 
                            $value = get_sub_field('value'); 

                            if($contact == 'phone') {
                                $href = 'tel:'.$value;
                                $icon = 'fa fa-phone';
                            } elseif($contact == 'email') {
                                $href = 'mailto:'.$value;
                                $icon = 'fa fa-envelope';
                            }
                            echo ' <a href="'.$href.'"> <i class="'.$icon.'"></i> '.$value.' </a>';

                        endwhile; 
                    echo '</div>';
                endif; 

                if (have_rows('top_header_social_links', 'option')) :
                    echo '<div class="tv-mobile-social">';
                        while (have_rows('top_header_social_links', 'option')) : the_row(); 
                            $icon = get_sub_field('icon'); 
                            $link = get_sub_field('link'); 
                            echo '<a href="'.$link.'" target="_blank"> <i class="'.$icon.'"></i> </a>';
                        endwhile; 
                    echo '</div>';
                endif; 
            ?>
        </div>
    </aside>

</header>

<main>