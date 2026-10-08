</main>

<div class="scroll-top not-visible"> <i class="fa fa-angle-up"></i></div>

<footer class="simple-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-about">
                <?php 
                    $footer_logo = get_field('footer_logo', 'option');
                    if($footer_logo) :
                        echo '<a href="'.home_url().'" class="footer-logo">
                                <img src="'.$footer_logo['url'].'" alt="'.$footer_logo['alt'].'"style="width: 80px;">
                            </a>';
                    endif;

                    $footer_logo_tagline = get_field('footer_logo_tagline', 'option');
                    if($footer_logo_tagline) :
                        echo '<div class="footer-tagline">'.$footer_logo_tagline.'</div>';
                    endif;

                    $footer_about = get_field('footer_about', 'option');
                    if($footer_about) :
                        echo '<p>'.$footer_about.'</p>';
                    endif;
                ?>
            </div>
            <?php 
                if (have_rows('footer_contact', 'option')) :
                    echo '<div class="footer-contact">';

                        $footer_contact_label = get_field('footer_contact_label', 'option');
                        if($footer_contact_label) :
                            echo '<h4>'.$footer_contact_label.'</h4>';
                        endif;

                        while (have_rows('footer_contact', 'option')) : the_row(); 
                            $contact = get_sub_field('contact'); 
                            $value = get_sub_field('value'); 

                            if($contact == 'phone') {
                                $href = 'tel:'.$value;
                                $icon = 'fa fa-phone';
                            } elseif($contact == 'email') {
                                $href = 'mailto:'.$value;
                                $icon = 'fa fa-envelope';
                            }

                            echo '<p>
                                    <i class="'.$icon.'"></i>
                                    <a href="'.$href.'" target="_blank"> '.$value.' </a>
                                </p>';
                        endwhile; 
                    echo '</div>';
                endif; 

                if (has_nav_menu('footer-menu')) :
                    echo '<div class="footer-links">';

                        $footer_link_label = get_field('footer_link_label', 'option');
                        if($footer_link_label) :
                            echo '<h4>'.$footer_link_label.'</h4>';
                        endif;

                        wp_nav_menu([
                            'theme_location' => 'footer-menu',
                            'container'      => 'ul',
                            'container_class' => 'footer-menu',
                            'menu_class'     => 'footer-menu-list',
                        ]);
                    echo '</div>';
                endif;

                if (have_rows('footer_social_links', 'option')) :
                    echo '<div class="footer-social">';

                        $footer_address_label = get_field('footer_address_label', 'option');
                        if($footer_address_label) :
                            echo '<h4>'.$footer_address_label.'</h4>';
                        endif;

                        $footer_address = get_field('footer_address', 'option');
                        if($footer_address) :
                            echo '<p class="social-text">'.$footer_address.'</p>';
                        endif;

                        echo '<div class="social-icons">';
                            while (have_rows('footer_social_links', 'option')) : the_row(); 
                                $icon = get_sub_field('icon'); 
                                $link = get_sub_field('link'); 
                                echo '<a href="'.$link.'" target="_blank"> <i class="'.$icon.'"></i> </a>';
                            endwhile; 
                        echo '</div>';
                    echo '</div>';
                endif;                 
            ?>
        </div>

        <div class="footer-bottom">
            <?php 
                $footer_copyright = get_field('footer_copyright', 'option');
                if($footer_copyright) :
                    echo '<p> '.$footer_copyright.' </p>';
                endif;
            
                $locations = get_nav_menu_locations();

                if (isset($locations['bottom-footer-menu'])) :
                    $menu_items = wp_get_nav_menu_items($locations['bottom-footer-menu']);
                    ?>
                    <p class="footer-made">
                        <?php foreach ($menu_items as $index => $item) : ?>
                            <a href="<?php echo esc_url($item->url); ?>" class="footer-made">
                                <?php echo esc_html($item->title); ?>
                            </a>

                            <?php if ($index < count($menu_items) - 1) : ?>
                                <span>•</span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </p>
                <?php endif; 
            ?>
        </div>
    </div>
</footer>

<?php if (tomatribe_show_mini_cart()) : ?>
<div class="offcanvas-minicart-wrapper">
    <div class="minicart-inner">
        <div class="offcanvas-overlay"></div>
        <div class="minicart-inner-content">
            <div class="minicart-close">
                <i class="pe-7s-close"></i>
            </div>
            <?php echo tomatribe_mini_cart_html(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>