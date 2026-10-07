(function ($) {
  /*
   * Wishlist page (woocommerce/share.php): copy link icon in the share list, with a "Link copied" tooltip.
   * Clipboard API where available (https), else a temporary textarea + execCommand.
   * The wishlist is reloaded via AJAX after changes, so the handler is delegated.
   */
  function copyText(text) {
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(text);
    }

    return new Promise(function (resolve, reject) {
      var $temp = $('<textarea readonly>').val(text).css({ position: 'fixed', top: 0, left: 0, opacity: 0 }).appendTo('body');
      $temp[0].select();
      $temp[0].setSelectionRange(0, text.length);
      var copied = false;
      try {
        copied = document.execCommand('copy');
      } catch (e) {}
      $temp.remove();
      copied ? resolve() : reject();
    });
  }

  $(document).on('click', '.tp-share-copy', function () {
    var $button = $(this);
    var $tooltip = $button.find('.tp-share-tooltip');

    copyText($button.attr('data-url')).then(function () {
      $tooltip.text('Link copied');
    }, function () {
      $tooltip.text('Copy failed');
    }).then(function () {
      $button.addClass('is-copied');
      clearTimeout($button.data('timer'));
      $button.data('timer', setTimeout(function () {
        $button.removeClass('is-copied');
        $tooltip.text('');
      }, 2000));
    });
  });
})(jQuery);
