<?php
/**
 * Template Name: About Page
 */

get_header();
?>

<?php get_template_part('includes/inner-breadcrumb'); ?>
      
<?php 
    $about_details = get_field('about_details');
    $about_featured_image = get_field('about_featured_image');

    if($about_details || $about_featured_image) :
?>
    <section class="about-us section-padding">
        <div class="container">
            <div class="row align-items-center">
                <?php 
                    if($about_featured_image) :
                        echo '<div class="col-lg-5">
                                <div class="about-thumb">
                                    <img src="'.$about_featured_image['url'].'" alt="'.$about_featured_image['alt'].'">
                                </div>
                            </div>';
                    endif;

                    if($about_details) :
                        echo'<div class="col-lg-7">
                                <div class="about-content">
                                    '.$about_details.'
                                </div>
                            </div>';
                    endif;
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>
    
<?php if (have_rows('features')) : ?>
    <section class="choosing-area section-padding pt-0">
        <div class="container">
            <?php 
                $features_title = get_field('features_title');
                $features_details = get_field('features_details');

                if($features_title || $features_details) :
            ?>
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">
                            <?php
                                if($features_title) :
                                    echo '<h2 class="title">'.$features_title.'</h2>';
                                endif;

                                if($features_details) :
                                   echo '<p>'.$features_details.'</p>';
                                endif;
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row mbn-30 justify-content-center">
                <?php while (have_rows('features')) : the_row(); ?>
                    <?php 
                        $icon = get_sub_field('icon');
                        $title = get_sub_field('title');
                        $details = get_sub_field('details');
                    ?>
                    <div class="col-lg-4 col-md-4">
                        <div class="single-choose-item text-center mb-30">
                            <?php
                                if($icon) :
                                    echo '<i class="'.$icon.'"></i>';
                                endif;

                                if($title) :
                                    echo '<h4>'.$title.'</h4>';
                                endif;

                                if($details) :
                                    echo '<p>'.$details.'</p>';
                                endif;
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>