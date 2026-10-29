(function ($) {
  /*
   * Header wishlist count.
   * YITH fires "added_to_wishlist" / "removed_from_wishlist" (PHP template buttons)
   * and "yith_wcwl_reload_fragments" (React buttons) after every wishlist change.
   * The cart count is kept up to date by WooCommerce cart fragments (see includes/header-counts.php).
   */
  var wishlistTimer;

  function refreshWishlistCount() {
    clearTimeout(wishlistTimer);
    wishlistTimer = setTimeout(function () {
      $.post(tomatribe.ajaxUrl, { action: 'tomatribe_wishlist_count' }, function (response) {
        if (response && response.success) {
          $('.tv-wishlist-count').text(response.data.count);
        }
      });
    }, 300);
  }

  $(document).on('added_to_wishlist removed_from_wishlist yith_wcwl_reload_fragments', refreshWishlistCount);

  /*
   * Off-canvas mini cart (see includes/mini-cart.php).
   * WooCommerce handles the AJAX remove (.remove_from_cart_button) and swaps in the
   * refreshed ".minicart-content-box" fragment; here we only add a loading state
   * and open the drawer after an AJAX add to cart.
   */
  $(document.body).on('click', '.minicart-remove', function () {
    $(this).closest('.minicart-item').addClass('is-removing');
  });

  $(document.body).on('added_to_cart', function () {
    if ($('.minicart-inner').length) {
      $('body').addClass('fix');
      $('.minicart-inner').addClass('show');
    }
  });

  /*
   * Product card gallery (template/product-card.php): featured image + gallery slider with dots, no arrows.
   * One timer drives every card, so cards added later (Slick clones on the home slider, AJAX category results) just work.
   * Doesn't pause on hover; clicking a dot restarts that card's countdown. Neighbouring cards start a little apart.
   * The track ends with a copy of the first image: the last image slides on to it, then the track jumps back to the real first image.
   */
  var cardGalleryDelay = 3500;
  var cardGallerySpeed = 600; // matches the track transition in _inline.scss

  function showCardImage($gallery, index) {
    var $track = $gallery.find('.latest-product-gallery-track');
    var $dots = $gallery.find('.latest-product-dots button');
    var count = $dots.length;

    $track.removeClass('no-transition').css('transform', 'translateX(' + (-index * 100) + '%)');
    $dots.removeClass('is-active').eq(index % count).addClass('is-active');
    $gallery.data({ cardIndex: index, cardNext: Date.now() + cardGalleryDelay });

    if (index === count) {
      setTimeout(function () {
        if ($gallery.data('cardIndex') !== count) return; // a dot was clicked meanwhile
        $track.addClass('no-transition').css('transform', 'translateX(0)');
        $gallery.data('cardIndex', 0);
      }, cardGallerySpeed);
    }
  }

  $(document).on('click', '.latest-product-dots button', function (event) {
    event.preventDefault();
    showCardImage($(this).closest('.latest-product-image'), parseInt($(this).attr('data-index'), 10));
  });

  setInterval(function () {
    if (document.hidden) return;
    var now = Date.now();
    $('.latest-product-image.has-gallery').each(function (i) {
      var $gallery = $(this);
      var next = $gallery.data('cardNext');
      if (!next) {
        $gallery.data('cardNext', now + cardGalleryDelay + (i % 4) * 400);
      } else if (now >= next) {
        showCardImage($gallery, ($gallery.data('cardIndex') || 0) + 1);
      }
    });
  }, 200);
})(jQuery);
