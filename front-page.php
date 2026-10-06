<?php get_header(); ?> 

<?php if (have_rows('hero_banner')) : ?>
    <section class="tribal-hero">
        <div class="tribal-hero-slider">
            <?php $index = 0; ?>
            <?php while (have_rows('hero_banner')) : the_row(); ?>
                <?php 
                    $banner_image = get_sub_field('banner_image');
                    $sub_title = get_sub_field('sub_title');
                    $title = get_sub_field('title');
                    $description = get_sub_field('description');
                    $cta = get_sub_field('cta');
                ?>
                <div class="tribal-hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                    <?php 
                        if($banner_image) :
                            echo '<img src="'.$banner_image['url'].'" alt="'.$banner_image['alt'].'">';
                        endif;
                    ?>
                    <div class="tribal-hero-overlay"></div>
                    <div class="tribal-hero-content">
                        <?php 
                            if($sub_title) :
                                echo '<span>'.$sub_title.'</span>';
                            endif;
                            if($title) :
                                echo '<h1>'.$title.'</h1>';
                            endif;
                            if($description) :
                                echo '<p>'.$description.'</p>';
                            endif;
                            if($cta) :
                                echo '<a href="'.$cta['url'].'" target="'.$cta['target'].'" class="tribal-hero-btn"> '.$cta['title'].' <i class="fa fa-long-arrow-right"></i></a>';
                            endif;
                        ?>
                    </div>
                </div>
                <?php $index++; ?>
            <?php endwhile; ?>
        </div>

        <?php 
            $total_slides = count(get_field('hero_banner'));
            if ( $total_slides > 1 ) :
                echo '
                    <button class="tribal-hero-arrow tribal-hero-prev" type="button" aria-label="Previous slide"> <i class="fa fa-angle-left"></i> </button>
                    <button class="tribal-hero-arrow tribal-hero-next" type="button" aria-label="Next slide"> <i class="fa fa-angle-right"></i> </button>
                    ';
                echo '<div class="tribal-hero-dots">';
                    for ($i = 0; $i < $total_slides; $i++) :
                        echo '<button class="tribal-dot '. ($i === 0 ? 'active' : '').'" data-slide="'.$i.'"></button>';
                    endfor;
                echo '</div>';
            endif; 
        ?>
    </section>
<?php endif; ?>

<?php if (have_rows('benefits')) : ?>
    <section class="simple-policy">
        <div class="container">
            <div class="simple-policy-flex row justify-content-center m-0">
                <?php while (have_rows('benefits')) : the_row(); ?>
                    <?php 
                        $icon = get_sub_field('icon');
                        $title = get_sub_field('title');
                        $description = get_sub_field('description');
                    ?>
                    <div class="simple-policy-item col-lg-3 col-md-6 col-12">
                        <?php 
                            if($icon) :
                                echo '<div class="simple-policy-icon"><i class="'.$icon.'"></i></div>';
                            endif;
                        ?>
                        <div class="simple-policy-content">
                            <?php 
                                if($title) :
                                    echo '<h6>'.$title.'</h6>';
                                endif;
                                if($description) :
                                    echo '<p>'.$description.'</p>';
                                endif;
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php 
    $latest_products_show_hide = get_field('latest_products_show_hide');
    if($latest_products_show_hide) :
?>
    <section class="latest-products-section">
        <div class="container">
            <div class="latest-products-heading">
                <?php 
                    $latest_products_sub_title = get_field('latest_products_sub_title');
                    if($latest_products_sub_title) :
                        echo '<span class="latest-products-subtitle"> '.$latest_products_sub_title.' </span>';
                    endif;

                    $latest_products_title = get_field('latest_products_title');
                    if($latest_products_title) :
                        echo '<h2> '.$latest_products_title.' </h2>';
                    endif;

                    $latest_products_description = get_field('latest_products_description');
                    if($latest_products_description) :
                        echo '<p> '.$latest_products_description.' </p>';
                    endif;
                ?>
            </div>
            <div class="latest-products-slider">
                <?php
                    $latest_products = new WP_Query(array(
                        'post_type'      => 'product',
                        'post_status'    => 'publish',
                        'posts_per_page' => 5,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ));

                    if ($latest_products->have_posts()) :
                        while ($latest_products->have_posts()) : $latest_products->the_post();
                            $product = wc_get_product(get_the_ID());
                            if (!$product) continue;

                            $permalink = get_permalink();
                            $tags = get_the_terms(get_the_ID(), 'product_tag');
                            $rating = (float) $product->get_average_rating();
                ?>
                    <div class="latest-product-slide">
                        <div class="latest-product-card">
                            <div class="latest-product-image">
                                <a href="<?php echo esc_url($permalink); ?>">
                                    <?php echo $product->get_image('woocommerce_full'); ?>
                                </a>

                                <?php if (get_post_time('U', true) >= strtotime('-1 week')) : ?>
                                    <span class="latest-product-badge"> NEW </span>
                                <?php endif; ?>
                                <div class="latest-product-actions">
                                    <?php if (shortcode_exists('yith_wcwl_add_to_wishlist')) : ?>
                                        <div class="latest-product-wishlist" title="Wishlist">
                                            <?php echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="'.$product->get_id().'"]'); ?>
                                        </div>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url($permalink); ?>" title="Quick View">
                                        <i class="pe-7s-search"></i>
                                    </a>
                                </div>
                                <div class="latest-product-cart">
                                    <?php
                                        $cart_classes = 'add_to_cart_button product_type_'.$product->get_type();
                                        if ($product->is_purchasable() && $product->is_in_stock() && $product->supports('ajax_add_to_cart')) :
                                            $cart_classes .= ' ajax_add_to_cart';
                                        endif;
                                    ?>
                                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="<?php echo esc_attr($cart_classes); ?>" data-quantity="1" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" rel="nofollow">
                                        <i class="fa fa-shopping-bag"></i> <?php echo esc_html($product->add_to_cart_text()); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="latest-product-info">
                                <?php if ($tags && !is_wp_error($tags)) : ?>
                                    <span class="latest-product-category"> <?php echo esc_html($tags[0]->name); ?> </span>
                                <?php endif; ?>
                                <h3> <a href="<?php echo esc_url($permalink); ?>"> <?php the_title(); ?> </a> </h3>
                                <div class="latest-product-rating">
                                    <?php
                                        for ($i = 1; $i <= 5; $i++) :
                                            if ($rating >= $i) :
                                                echo '<i class="fa fa-star"></i>';
                                            elseif ($rating >= $i - 0.5) :
                                                echo '<i class="fa fa-star-half-o"></i>';
                                            else :
                                                echo '<i class="fa fa-star-o"></i>';
                                            endif;
                                        endfor;
                                    ?>
                                </div>
                                <?php if ($prices = tomatribe_get_product_prices($product)) : ?>
                                    <div class="latest-product-price">
                                        <span> <?php echo $prices['price']; ?> </span>
                                        <?php if ($prices['regular']) : ?>
                                            <del> <?php echo $prices['regular']; ?> </del>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>