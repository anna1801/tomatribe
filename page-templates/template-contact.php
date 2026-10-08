<?php
/**
 * Template Name: Contact Page
 */

get_header();
?>

<?php get_template_part('includes/inner-breadcrumb'); ?>
      
<?php 
    $contact_featured_image = get_field('contact_featured_image');
    $form_title = get_field('form_title');
    $form_shortcode = get_field('form_shortcode');

    if($form_shortcode) :
?>
    <section class="contact-us section-padding">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <?php 
                    if($contact_featured_image) :
                        echo '<div class="col-lg-6 col-md-12 col-12">
                                <div class="contact-thumb">
                                    <img src="'.$contact_featured_image['url'].'" alt="'.$contact_featured_image['alt'].'">
                                </div>
                            </div>';
                    endif;
                ?>
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="contact-content">
                        <?php 
                            if($form_title) :
                                echo '<h1 class="contact-title">'.$form_title.'</h1>';
                            endif;
                            echo '<div class="contact_form">' . do_shortcode($form_shortcode) . '</div>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>