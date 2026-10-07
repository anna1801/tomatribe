(function ($) {
  /*
   * Product category AJAX filters (template/category-filters.php, includes/category-filters.php).
   * Every change builds a filter URL, loads its results with "tf_ajax=1" into #tf-results
   * and pushes the URL to the history so reloads, back/forward and shared links keep the filters.
   */
  var $form = $('.tf-form');
  var $results = $('#tf-results');
  if (!$form.length || !$results.length) return;

  var $price = $form.find('.tf-price');
  var priceMin = parseInt($price.data('min'), 10);
  var priceMax = parseInt($price.data('max'), 10);
  var request;

  /* ---------- URL <-> form ---------- */

  function buildUrl() {
    var params = new URLSearchParams();
    var groups = {};

    $form.find('input[type="checkbox"]:checked').each(function () {
      (groups[this.name] = groups[this.name] || []).push(this.value);
    });
    $.each(groups, function (name, values) {
      params.set(name, values.join(','));
      // Match any of the selected terms within one attribute
      if (name.indexOf('filter_') === 0) {
        params.set('query_type_' + name.substring(7), 'or');
      }
    });

    if ($price.length) {
      var from = parseInt($price.find('.tf-price-from').val(), 10);
      var to = parseInt($price.find('.tf-price-to').val(), 10);
      if (from > priceMin) params.set('min_price', from);
      if (to < priceMax) params.set('max_price', to);
    }

    var $orderby = $results.find('.tf-orderby');
    if ($orderby.length && $orderby.val() !== String($orderby.data('default'))) {
      params.set('orderby', $orderby.val());
    }

    var query = params.toString().replace(/%2C/g, ',');
    return $form.attr('action') + (query ? '?' + query : '');
  }

  function syncFormFromUrl(url) {
    var params = new URL(url, window.location.href).searchParams;

    $form.find('input[type="checkbox"]').each(function () {
      var values = (params.get(this.name) || '').split(',');
      this.checked = values.indexOf(this.value) !== -1;
    });

    if ($price.length) {
      setPrice(
        params.has('min_price') ? parseInt(params.get('min_price'), 10) : priceMin,
        params.has('max_price') ? parseInt(params.get('max_price'), 10) : priceMax
      );
    }
  }

  /* ---------- Load results ---------- */

  function load(url, push) {
    if (request) request.abort();

    $results.addClass('is-loading');
    var ajaxUrl = url + (url.indexOf('?') === -1 ? '?' : '&') + 'tf_ajax=1';

    request = $.get(ajaxUrl)
      .done(function (html) {
        $results.html(html);
        if (push) window.history.pushState({ tfFilters: true }, '', url);
        afterRender();
      })
      .fail(function (xhr, status) {
        // Fall back to a normal page load
        if (status !== 'abort') window.location.href = url;
      })
      .always(function () {
        $results.removeClass('is-loading');
      });
  }

  function apply() {
    load(buildUrl(), true);
  }

  function afterRender() {
    var active = 0;
    $form.find('input[type="checkbox"]:checked').each(function () { active++; });
    if ($price.length && (getFrom() > priceMin || getTo() < priceMax)) active++;
    $('.tf-sidebar .tf-clear-all').prop('hidden', !active);

    if ($.fn.niceSelect) $results.find('.tf-orderby').niceSelect();

    // Wishlist buttons in the new product cards
    $(document).trigger('yith_infs_added_elem', [$results.get(0)]);
    if (window.wp && wp.hooks) wp.hooks.doAction('yith_wcwl_init_add_to_wishlist_components');
  }

  /* ---------- Price range ---------- */

  function getFrom() { return parseInt($price.find('.tf-range-from').val(), 10); }
  function getTo() { return parseInt($price.find('.tf-range-to').val(), 10); }

  function setPrice(from, to) {
    from = isNaN(from) ? priceMin : Math.min(Math.max(from, priceMin), priceMax);
    to = isNaN(to) ? priceMax : Math.min(Math.max(to, priceMin), priceMax);
    if (from > to) from = to;

    $price.find('.tf-range-from, .tf-price-from').val(from);
    $price.find('.tf-range-to, .tf-price-to').val(to);

    var span = priceMax - priceMin;
    $price.find('.tf-range').css({
      '--tf-from': ((from - priceMin) / span) * 100 + '%',
      '--tf-to': ((to - priceMin) / span) * 100 + '%'
    });
  }

  if ($price.length) {
    setPrice(getFrom(), getTo());

    $price.on('input', '.tf-range-from', function () {
      setPrice(Math.min(getFrom(), getTo()), getTo());
    });
    $price.on('input', '.tf-range-to', function () {
      setPrice(getFrom(), Math.max(getFrom(), getTo()));
    });
    // Apply when the handle is released
    $price.on('change', '.tf-range-from, .tf-range-to', apply);

    $price.on('change', '.tf-price-from, .tf-price-to', function () {
      setPrice(parseInt($price.find('.tf-price-from').val(), 10), parseInt($price.find('.tf-price-to').val(), 10));
      apply();
    });
  }

  /* ---------- Events ---------- */

  $form.on('change', 'input[type="checkbox"]', apply);

  // Enter in a price field shouldn't submit the form
  $form.on('submit', function (e) {
    e.preventDefault();
    $(document.activeElement).trigger('change');
  });

  $results.on('change', '.tf-orderby', apply);

  $results.on('click', '.pagination-box a', function (e) {
    e.preventDefault();
    load(this.href, true);
    $('html, body').animate({ scrollTop: $results.offset().top - 150 }, 300);
  });

  $(document).on('click', '.tf-clear-all', function () {
    $form.find('input[type="checkbox"]').prop('checked', false);
    if ($price.length) setPrice(priceMin, priceMax);
    apply();
  });

  $form.on('click', '.tf-group-toggle', function () {
    var $group = $(this).closest('.tf-group').toggleClass('is-open');
    $(this).attr('aria-expanded', $group.hasClass('is-open'));
  });

  window.addEventListener('popstate', function () {
    syncFormFromUrl(window.location.href);
    load(window.location.href, false);
  });

  /* ---------- Mobile drawer ---------- */

  function closeDrawer() {
    $('body').removeClass('tf-filters-open');
  }

  $results.on('click', '.tf-filter-toggle', function () {
    $('body').addClass('tf-filters-open');
  });
  $(document).on('click', '.tf-sidebar-close, .tf-overlay, .tf-show-results', closeDrawer);
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') closeDrawer();
  });
})(jQuery);
