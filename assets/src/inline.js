document.addEventListener("DOMContentLoaded", function () {

    /* ===============================
       SEARCH
    ================================ */

    const searchButton =
        document.querySelector(".search-trigger");

    const searchPanel =
        document.querySelector(".tv-search-panel");

    if (searchButton && searchPanel) {

        searchButton.addEventListener("click", function () {

            searchPanel.classList.toggle("active");

        });

    }


    /* ===============================
       MOBILE MENU
    ================================ */

    const mobileButton =
        document.querySelector(".mobile-menu-btn");

    const mobileCanvas =
        document.querySelector(".tv-mobile-offcanvas");

    const mobileClose =
        document.querySelector(".tv-mobile-close");

    const mobileOverlay =
        document.querySelector(".tv-mobile-overlay");


    if (mobileButton && mobileCanvas) {

        mobileButton.addEventListener("click", function () {

            mobileCanvas.classList.add("active");

            document.body.style.overflow = "hidden";

        });

    }


    function closeMobileMenu() {

        if (mobileCanvas) {

            mobileCanvas.classList.remove("active");

            document.body.style.overflow = "";

        }

    }


    if (mobileClose) {
        mobileClose.addEventListener("click", closeMobileMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener("click", closeMobileMenu);
    }


    /* ===============================
       MOBILE CATEGORY
    ================================ */

    document.querySelectorAll(".tv-mobile-category").forEach(function (category) {

        const categoryButton =
            category.querySelector(".tv-mobile-category-title button");

        if (categoryButton) {

            categoryButton.addEventListener("click", function () {

                category.classList.toggle("open");

            });

        }

    });

});

document.addEventListener("DOMContentLoaded", function () {

    const slides = document.querySelectorAll(
        ".tribal-hero-slide"
    );

    // Hero slider only exists on the home page
    if (!slides.length) {
        return;
    }

    const dots = document.querySelectorAll(
        ".tribal-dot"
    );

    const nextButton = document.querySelector(
        ".tribal-hero-next"
    );

    const prevButton = document.querySelector(
        ".tribal-hero-prev"
    );


    let currentSlide = 0;

    let slideTimer;


    /* ==========================================
       SHOW SLIDE
    ========================================== */

    function showSlide(index) {

        if (index >= slides.length) {
            index = 0;
        }

        if (index < 0) {
            index = slides.length - 1;
        }

        slides.forEach(function (slide) {

            slide.classList.remove("active");

        });


        dots.forEach(function (dot) {

            dot.classList.remove("active");

        });


        slides[index].classList.add("active");

        currentSlide = index;

        restartProgress();

    }


    /* ==========================================
       PROGRESS LINE
    ========================================== */

    function restartProgress() {

        const dot = dots[currentSlide];

        if (!dot) {
            return;
        }

        dot.classList.remove("active");

        // Force reflow so the fill animation starts again from zero
        void dot.offsetWidth;

        dot.classList.add("active");

    }


    /* ==========================================
       NEXT
    ========================================== */

    function nextSlide() {

        showSlide(currentSlide + 1);

        restartTimer();

    }


    /* ==========================================
       PREVIOUS
    ========================================== */

    function previousSlide() {

        showSlide(currentSlide - 1);

        restartTimer();

    }


    /* ==========================================
       AUTO SLIDE
       5 SECONDS
    ========================================== */

    function startTimer() {

        slideTimer = setInterval(function () {

            showSlide(currentSlide + 1);

        }, 5000);

    }


    function restartTimer() {

        clearInterval(slideTimer);

        startTimer();

    }


    /* ==========================================
       ARROWS
    ========================================== */

    if (nextButton) {

        nextButton.addEventListener(
            "click",
            nextSlide
        );

    }


    if (prevButton) {

        prevButton.addEventListener(
            "click",
            previousSlide
        );

    }


    /* ==========================================
       DOTS
    ========================================== */

    dots.forEach(function (dot, index) {

        dot.addEventListener(
            "click",
            function () {

                showSlide(index);

                restartTimer();

            }
        );

    });


    /* ==========================================
       START
    ========================================== */

    showSlide(0);

    startTimer();


    /* ==========================================
       PAUSE ON MOUSE HOVER
    ========================================== */

    const hero = document.querySelector(
        ".tribal-hero"
    );

    if (hero) {

        hero.addEventListener(
            "mouseenter",
            function () {

                clearInterval(slideTimer);

                hero.classList.add("is-paused");

            }
        );


        hero.addEventListener(
            "mouseleave",
            function () {

                hero.classList.remove("is-paused");

                // Timer restarts from zero, so the progress line does too
                restartProgress();

                startTimer();

            }
        );

    }

});

jQuery(document).ready(function($) {

    var $latestProducts = $('.latest-products-slider');

    if (!$latestProducts.length) {
        return;
    }

    if ($latestProducts.hasClass('slick-initialized')) {
        $latestProducts.slick('unslick');
    }

    $latestProducts.slick({

        slidesToShow: 4,
        slidesToScroll: 1,

        infinite: true,

        arrows: true,
        dots: true,

        autoplay: true,
        autoplaySpeed: 3500,

        speed: 650,

        cssEase: 'ease-in-out',

        pauseOnHover: true,
        pauseOnFocus: true,

        swipe: true,
        draggable: true,
        touchMove: true,

        adaptiveHeight: false,

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },

            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },

            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }

        ]

    });


    /* Recalculate slider after images load */
    $(window).on('load', function () {

        if ($latestProducts.hasClass('slick-initialized')) {
            $latestProducts.slick('setPosition');
        }

    });

});

(function ($) {

    "use strict";

    function initLatestProducts() {

        var $slider = $('.latest-products-slider');

        if (!$slider.length) {
            return;
        }

        /*
         * If another script already initialized it,
         * remove that instance first.
         */
        if ($slider.hasClass('slick-initialized')) {
            $slider.slick('unslick');
        }

        /*
         * Initialize Latest Products
         */
        $slider.slick({

            slidesToShow: 4,
            slidesToScroll: 1,

            infinite: true,

            autoplay: true,
            autoplaySpeed: 3500,

            speed: 600,

            arrows: true,
            dots: true,

            pauseOnHover: true,
            pauseOnFocus: true,

            swipe: true,
            draggable: true,
            touchMove: true,

            adaptiveHeight: false,

            cssEase: 'ease-in-out',

            responsive: [

                {
                    breakpoint: 1200,

                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },

                {
                    breakpoint: 992,

                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },

                {
                    breakpoint: 576,

                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }

            ]

        });

        /*
         * Recalculate dimensions after Slick
         * has been rendered.
         */
        setTimeout(function () {

            if ($slider.hasClass('slick-initialized')) {
                $slider.slick('setPosition');
            }

        }, 300);

    }


    /*
     * Wait until the complete page is loaded.
     * This is important because your product images
     * can affect the slider dimensions.
     */
    $(window).on('load', function () {

        initLatestProducts();

    });


    /*
     * Recalculate when browser width changes.
     */
    $(window).on('resize', function () {

        var $slider = $('.latest-products-slider');

        if ($slider.hasClass('slick-initialized')) {

            $slider.slick('setPosition');

        }

    });


    /*
     * Swap button to "Added to Cart" with a tick
     * after WooCommerce adds the product successfully.
     */
    $(document.body).on('added_to_cart', function (event, fragments, cartHash, $button) {

        if ($button && $button.closest('.latest-product-cart').length) {

            $button
                .html('<i class="fa fa-check"></i> Added to Cart')
                .removeClass('add_to_cart_button ajax_add_to_cart')
                .addClass('is-added')
                .removeAttr('href')
                .attr('aria-disabled', 'true');

        }

    });


})(jQuery);

/* =========================================================
   TESTIMONIALS SLIDER
========================================================= */

(function ($) {

    "use strict";

    $(window).on('load', function () {

        var $testimonials = $('.testimonials-slider');

        if (!$testimonials.length || $testimonials.hasClass('slick-initialized')) {
            return;
        }

        $testimonials.slick({

            slidesToShow: 4,
            slidesToScroll: 1,

            infinite: true,

            arrows: true,
            dots: true,

            autoplay: true,
            autoplaySpeed: 4000,

            speed: 650,

            cssEase: 'ease-in-out',

            pauseOnHover: true,
            pauseOnFocus: true,

            swipe: true,
            draggable: true,
            touchMove: true,

            responsive: [

                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },

                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },

                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }

            ]

        });

    });

})(jQuery);
