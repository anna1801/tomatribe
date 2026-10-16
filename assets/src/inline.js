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

            // 3 across on desktop: the reviews are screenshots of text, so they need the width to stay readable
            slidesToShow: 3,
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


/* ===============================
   HOME CATEGORIES - PINNED HORIZONTAL SCROLL
   While the section is on screen, vertical scrolling slides the row
   sideways; once the last category is in view the page scrolls on.
   Only kicks in when the categories don't all fit on screen.
================================ */

document.addEventListener("DOMContentLoaded", function () {

    const section =
        document.querySelector(".home-categories-section");

    if (!section) return;

    const sticky = section.querySelector(".home-categories-sticky");
    const list = section.querySelector(".home-categories-list");
    const header = document.querySelector(".tv-header");
    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

    let distance = 0;   // how far the row has to travel sideways
    let progress = 0;   // how far it has travelled
    let stickyTop = 0;
    let ticking = false;
    let lastWidth = window.innerWidth;


    function setup() {

        section.classList.remove("is-pinned");
        section.style.height = "";
        sticky.style.top = "";
        list.style.transform = "";
        list.scrollLeft = 0;
        distance = 0;
        progress = 0;

        const lastItem = list.lastElementChild;

        if (reduceMotion.matches || !lastItem) return;

        const paddingRight = parseFloat(getComputedStyle(list).paddingRight) || 0;

        distance = Math.ceil(
            lastItem.getBoundingClientRect().right + paddingRight - list.getBoundingClientRect().right
        );

        if (distance <= 0) {
            distance = 0;
            return;
        }

        // Extra section height = the vertical scroll spent moving sideways
        section.classList.add("is-pinned");
        section.style.height = (sticky.offsetHeight + distance) + "px";

        update();

    }


    function update() {

        ticking = false;

        if (!distance) return;

        // Centre the pinned block in the space below the sticky header
        const headerBottom = header ? Math.max(0, header.getBoundingClientRect().bottom) : 0;
        const space = window.innerHeight - headerBottom - sticky.offsetHeight;

        stickyTop = space >= 0
            ? headerBottom + space / 2
            : window.innerHeight - sticky.offsetHeight;

        sticky.style.top = stickyTop + "px";

        progress = Math.min(Math.max(stickyTop - section.getBoundingClientRect().top, 0), distance);

        list.style.transform = "translate3d(" + (-progress) + "px, 0, 0)";

    }


    function requestUpdate() {

        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }

    }


    window.addEventListener("scroll", requestUpdate, { passive: true });

    // Mobile address bars fire resize on scroll; only rebuild when the width changes
    window.addEventListener("resize", function () {

        if (window.innerWidth !== lastWidth) {
            lastWidth = window.innerWidth;
            setup();
        } else {
            requestUpdate();
        }

    });

    window.addEventListener("load", setup);

    if (reduceMotion.addEventListener) {
        reduceMotion.addEventListener("change", setup);
    }


    // Keyboard users: scroll the page so a focused, off-screen category slides into view
    list.addEventListener("focusin", function (event) {

        const item = event.target.closest(".home-category-item");

        if (!distance || !item) return;

        sticky.scrollLeft = 0;

        const listLeft = list.getBoundingClientRect().left + progress;
        const paddingLeft = parseFloat(getComputedStyle(list).paddingLeft) || 0;
        const target = Math.min(Math.max(item.getBoundingClientRect().left + progress - listLeft - paddingLeft, 0), distance);
        const sectionTop = window.scrollY + section.getBoundingClientRect().top;

        window.scrollTo(0, sectionTop - stickyTop + target);

    });


    setup();

});


/* ===============================
   ABOUT TABS
   The active tab's bar fills over 6s (CSS animation); when it
   finishes the next tab opens. Pauses on keyboard focus and
   while the section is off screen.
================================ */

document.addEventListener("DOMContentLoaded", function () {

    const wrapper =
        document.querySelector(".founders-tabs");

    if (!wrapper) return;

    const tabs = Array.from(wrapper.querySelectorAll(".founders-tab"));

    if (tabs.length < 2) return;

    const panels = wrapper.querySelectorAll(".founders-tab-panel");
    const images = wrapper.querySelectorAll(".founders-tab-image");

    let current = 0;
    let focused = false;
    let visible = true;


    function activate(index, moveFocus) {

        tabs.forEach(function (tab, i) {

            const active = i === index;

            tab.classList.toggle("active", active);
            tab.setAttribute("aria-selected", active ? "true" : "false");
            tab.tabIndex = active ? 0 : -1;

            panels[i].classList.toggle("active", active);

        });

        // A tab without an image keeps the previous one on screen
        const hasImage = Array.from(images).some(function (image) {
            return Number(image.dataset.tab) === index;
        });

        if (hasImage) {
            images.forEach(function (image) {
                image.classList.toggle("active", Number(image.dataset.tab) === index);
            });
        }

        current = index;

        if (moveFocus) tabs[index].focus();

    }


    function updatePaused() {

        wrapper.classList.toggle("paused", focused || !visible);

    }


    tabs.forEach(function (tab, i) {

        tab.addEventListener("click", function () {
            if (i !== current) activate(i);
        });

    });


    wrapper.querySelector(".founders-tab-nav").addEventListener("keydown", function (event) {

        const last = tabs.length - 1;
        let next = null;

        if (event.key === "ArrowRight") next = current === last ? 0 : current + 1;
        if (event.key === "ArrowLeft") next = current === 0 ? last : current - 1;
        if (event.key === "Home") next = 0;
        if (event.key === "End") next = last;

        if (next === null) return;

        event.preventDefault();
        activate(next, true);

    });


    // Bar finished filling: move on to the next tab
    wrapper.addEventListener("animationend", function (event) {

        if (event.animationName !== "founders-tab-progress") return;
        if (!tabs[current].contains(event.target)) return;

        activate((current + 1) % tabs.length);

    });


    // Keyboard focus only; a mouse click on a tab shouldn't stop autoplay
    wrapper.addEventListener("focusin", function (event) {
        focused = event.target.matches(":focus-visible");
        updatePaused();
    });

    wrapper.addEventListener("focusout", function (event) {
        if (wrapper.contains(event.relatedTarget)) return;
        focused = false;
        updatePaused();
    });


    if ("IntersectionObserver" in window) {

        new IntersectionObserver(function (entries) {
            visible = entries[0].isIntersecting;
            updatePaused();
        }, { threshold: 0.3 }).observe(wrapper);

    }

});
