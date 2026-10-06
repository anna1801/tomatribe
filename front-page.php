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
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>