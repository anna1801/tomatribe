(function ($) {
  /*
   * My Account, logged out (woocommerce/myaccount/form-login.php): switch between the "Log in" and "Register" tabs
   * without reloading, keeping ?action=register in the URL in sync (the links work as normal links without JS).
   */
  $(function () {
    var $auth = $('.tp-auth');
    if (!$auth.find('.tp-auth-tabs').length) return;

    function showTab(tab, focus) {
      $auth.find('.tp-auth-tab').each(function () {
        var active = $(this).data('tab') === tab;
        $(this).toggleClass('is-active', active).attr('aria-selected', active ? 'true' : 'false');
      });
      $auth.find('.tp-auth-panel').each(function () {
        this.hidden = $(this).data('panel') !== tab;
      });

      if (window.history && history.replaceState) {
        var url = new URL(window.location.href);
        if (tab === 'register') {
          url.searchParams.set('action', 'register');
        } else {
          url.searchParams.delete('action');
        }
        history.replaceState(null, '', url.toString());
      }

      if (focus) {
        $auth.find('[data-panel="' + tab + '"] .input-text').first().trigger('focus');
      }
    }

    $auth.on('click', '[data-tab]', function (e) {
      e.preventDefault();
      showTab($(this).data('tab'), true);
    });

    // Arrow keys between the tabs
    $auth.on('keydown', '.tp-auth-tab', function (e) {
      if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
      var $other = $(this).siblings('.tp-auth-tab').first();
      showTab($other.data('tab'), false);
      $other.trigger('focus');
    });
  });
})(jQuery);
