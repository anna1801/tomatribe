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
   * Product card gallery (template/product-card.php): featured image + gallery slider with prev / next arrows, no autoplay.
   * Delegated, so cards added later (Slick clones on the home slider, AJAX category results) just work.
   * The track ends with a copy of the first image. Before sliding, the track jumps (unseen) between the first image and
   * that copy whenever it needs a neighbour to slide to, so it loops both ways.
   */
  $(document).on('click', '.latest-product-arrow', function (event) {
    event.preventDefault();
    var $gallery = $(this).closest('.latest-product-image');
    var $track = $gallery.find('.latest-product-gallery-track');
    var count = $track.children().length - 1;
    var step = parseInt($(this).attr('data-step'), 10);
    var index = $gallery.data('cardIndex') || 0;

    if (index === count && step > 0) index = 0;
    if (index === 0 && step < 0) index = count;
    $track.addClass('no-transition').css('transform', 'translateX(' + (-index * 100) + '%)');
    $track[0].offsetWidth; // apply the jump before sliding

    index += step;
    $track.removeClass('no-transition').css('transform', 'translateX(' + (-index * 100) + '%)');
    $gallery.data('cardIndex', index);
  });
})(jQuery);
