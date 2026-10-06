<?php get_header(); ?>

<section class="latest-products-section tv-search-results">
    <div class="container">
        <div class="latest-products-heading">
            <span class="latest-products-subtitle"> Search Results </span>
            <h2> <?php echo esc_html(get_search_query()); ?> </h2>
            <p>
                <?php
                    global $wp_query;
                    $total = (int) $wp_query->found_posts;
                    echo esc_html(sprintf(_n('%d product found', '%d products found', $total), $total));
                ?>
            </p>
        </div>

        <?php if (have_posts()) : ?>
            <div class="row g-4">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="col-12 col-md-4 col-lg-3">
                        <?php get_template_part('template/product-card'); ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php
                $links = paginate_links(array(
                    'type'      => 'array',
                    'prev_text' => '<i class="pe-7s-angle-left"></i>',
                    'next_text' => '<i class="pe-7s-angle-right"></i>',
                ));
                if ($links) :
            ?>
                <div class="paginatoin-area text-center mt-5">
                    <ul class="pagination-box">
                        <?php
                            foreach ($links as $link) :
                                if (strpos($link, 'current') !== false) :
                                    echo '<li class="active">' . str_replace(array('<span', '</span>'), array('<a', '</a>'), $link) . '</li>';
                                else :
                                    echo '<li>' . $link . '</li>';
                                endif;
                            endforeach;
                        ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <p class="text-center"> No products matched your search. Try a different keyword. </p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
