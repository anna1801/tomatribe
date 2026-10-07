(function ($) {
  /*
   * Cart page (woocommerce/cart/cart.php):
   * quantity stepper with automatic cart update, and the sidebar coupon field.
   * Updating, removing and applying coupons is WooCommerce's cart.js; this only drives its form.
   * The form is replaced after every update, so all handlers are delegated.
   */
  var updateTimer;

  function updateCart() {
    clearTimeout(updateTimer);
    updateTimer = setTimeout(function () {
      $('.woocommerce-cart-form :input[name="update_cart"]').prop('disabled', false).trigger('click');
    }, 600);
  }

  // Minus stops at 1 - the trash button removes the item
  function syncButtons($qty) {
    var $input = $qty.find('input.qty');
    var value = parseFloat($input.val()) || 0;
    var min = Math.max(parseFloat($input.attr('min')) || 0, 1);
    var max = parseFloat($input.attr('max'));

    $qty.find('.tp-qty-minus').prop('disabled', value <= min);
    $qty.find('.tp-qty-plus').prop('disabled', !isNaN(max) && max > 0 && value >= max);
  }

  function initSteppers() {
    $('.tp-qty').each(function () {
      syncButtons($(this));
    });
  }

  $(initSteppers);
  $(document.body).on('updated_wc_div', initSteppers);

  $(document).on('click', '.tp-qty-btn', function () {
    var $input = $(this).closest('.tp-qty').find('input.qty');
    var step = parseFloat($input.attr('step')) || 1;
    var value = (parseFloat($input.val()) || 0) + ($(this).hasClass('tp-qty-plus') ? step : -step);

    $input.val(value).trigger('change');
  });

  $(document).on('change', '.tp-qty input.qty', function () {
    if (this.checkValidity && !this.checkValidity()) return;
    syncButtons($(this).closest('.tp-qty'));
    updateCart();
  });

  /*
   * The coupon field is outside the form (form="tp-cart-form"), so WooCommerce's click handler
   * doesn't see the Apply button: mark it as the clicked submit button the way cart.js expects.
   */
  $(document).on('click', '.tp-coupon-apply', function () {
    $('.woocommerce-cart-form :input[type=submit]').removeAttr('clicked');
    $(this).attr('clicked', 'true');
  });

  // Enter would otherwise use the form's first (disabled) submit button
  $(document).on('keydown', '#coupon_code', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      $('.tp-coupon-apply').trigger('click');
    }
  });

  $(document.body).on('applied_coupon', function () {
    $('.tp-coupon-apply').removeAttr('clicked');
    if (!$('.tp-coupon .coupon-error-notice').length) {
      $('#coupon_code').val('');
    }
  });
})(jQuery);
