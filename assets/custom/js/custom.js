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
})(jQuery);
