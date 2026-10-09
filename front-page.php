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
                ?>
                    <div class="latest-product-slide">
                        <?php get_template_part('template/product-card'); ?>
                    </div>
                <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                ?>
            </div>
            <div class="show_now text-center mt-5">
                <?php
                    $shop_link = get_field('shop_link');
                    if($shop_link) :
                        echo '<a href="'.$shop_link['url'].'" class="black-btn" target="'.$shop_link['target'].'"> '.$shop_link['title'].' </a>';
                    endif;
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php
    $about_featured_image = get_field('about_featured_image');
    $about_description = get_field('about_description');
    if($about_featured_image || $about_description) :
?>
    <section class="founders-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <?php
                        if($about_featured_image) :
                            echo '<div class="founders-image">
                                    <img src="'.$about_featured_image['url'].'" alt="'.$about_featured_image['alt'].'">
                                </div>';
                        endif;
                    ?>
                </div>
                <div class="col-lg-6">
                    <div class="founders-content">
                        <?php
                            if($about_description) :
                                echo '<div class="founders-description">'.$about_description.'</div>';
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php
    $product_categories = get_field('product_category');
    if($product_categories) :
        $product_categories = (array) $product_categories;
?>
    <section class="home-categories-section">
        <div class="home-categories-sticky">
            <div class="container-fluid">
                <div class="home-categories-list">
                    <?php foreach ($product_categories as $category_id) : ?>
                        <?php
                            $category = get_term($category_id, 'product_cat');
                            if (!$category || is_wp_error($category)) :
                                continue;
                            endif;

                            $category_link = get_term_link($category);
                            $cat_featured_image = get_field('cat_featured_image', $category);

                            if (is_array($cat_featured_image)) :
                                $image_id = $cat_featured_image['ID'];
                            elseif (is_numeric($cat_featured_image)) :
                                $image_id = $cat_featured_image;
                            elseif ($cat_featured_image) :
                                $image_id = attachment_url_to_postid($cat_featured_image);
                            else :
                                $image_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            endif;
                        ?>
                        <div class="home-category-item">
                            <a href="<?php echo esc_url($category_link); ?>" class="home-category-image">
                                <?php
                                    if ($image_id) :
                                        echo wp_get_attachment_image($image_id, 'large', false, array('alt' => $category->name));
                                    else :
                                        echo '<img src="'.esc_url(wc_placeholder_img_src('large')).'" alt="'.esc_attr($category->name).'">';
                                    endif;
                                ?>
                            </a>
                            <a href="<?php echo esc_url($category_link); ?>" class="home-category-title"><?php echo esc_html($category->name); ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php
    $testimonials_images = get_field('testimonials_images');
    if($testimonials_images) :
?>
    <section class="testimonials-section">
        <div class="container">
            <?php 
                $testimonials_subtitle = get_field('testimonials_subtitle');
                $testimonials_title = get_field('testimonials_title');
                $testimonials_description = get_field('testimonials_description');
                if($testimonials_subtitle || $testimonials_title || $testimonials_description) :
                    echo '<div class="latest-products-heading">';
                        if($testimonials_subtitle) :
                            echo '<span class="latest-products-subtitle"> '.$testimonials_subtitle.' </span>';
                        endif;
                        if($testimonials_title) :
                            echo '<h2> '.$testimonials_title.' </h2>';
                        endif;
                        if($testimonials_description) :
                            echo '<p> '.$testimonials_description.' </p>';
                        endif;
                    echo '</div>';
                endif;
            ?>
            <div class="testimonials-slider">
                <?php foreach ($testimonials_images as $testimonial_image) : ?>
                    <?php
                        if (is_array($testimonial_image)) :
                            $image_id = $testimonial_image['ID'];
                        elseif (is_numeric($testimonial_image)) :
                            $image_id = $testimonial_image;
                        else :
                            $image_id = attachment_url_to_postid($testimonial_image);
                        endif;
                    ?>
                    <div class="testimonial-slide">
                        <div class="testimonial-card">
                            <span class="testimonial-quote"><i class="fa fa-quote-left"></i></span>
                            <?php echo wp_get_attachment_image($image_id, 'large', false, array('class' => 'testimonial-image')); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>