(function ($) {
  /*
   * Product page (single-product.php):
   * gallery, attribute swatches, variation price / SKU / image, size guide drawer, tabs and AJAX add to cart.
   * Variation logic itself is WooCommerce's (add-to-cart-variation.js); the swatches only drive its hidden selects.
   */
  var params = window.tomatribeProduct || {};

  $(function () {
    var $form = $('.tp-cart-form.variations_form');

    /* ---------- Gallery ---------- */
    var $main = $('.tp-gallery-main');
    var $thumbs = $('.tp-gallery-thumb');
    var hasSlider = $main.children('.tp-gallery-slide').length > 1;

    function setActiveThumb(index) {
      $thumbs.removeClass('is-active').eq(index).addClass('is-active');
    }

    function goTo(index) {
      if (hasSlider) {
        $main.slick('slickGoTo', index);
      }
      setActiveThumb(index);
    }

    if (hasSlider) {
      $main.slick({ fade: true, arrows: false, infinite: false, speed: 400, adaptiveHeight: true });
      $main.on('beforeChange', function (event, slick, current, next) {
        setActiveThumb(next);
      });
    }

    $thumbs.on('click', function () {
      goTo(parseInt($(this).attr('data-index'), 10));
    });

    // Hover zoom (jQuery Zoom), desktop only
    function initZoom($el) {
      if ($.fn.zoom && window.matchMedia('(hover: hover)').matches && $el.attr('data-full')) {
        $el.zoom({ url: $el.attr('data-full'), touch: false });
      }
    }
    $main.find('.tp-gallery-zoom').each(function () {
      initZoom($(this));
    });

    // Lightbox (WooCommerce's PhotoSwipe, falls back to opening the image)
    $('.tp-gallery-expand').on('click', function () {
      var index = hasSlider ? $main.slick('slickCurrentSlide') : 0;
      var items = $main.find('.tp-gallery-zoom').map(function () {
        var $el = $(this);
        return {
          src: $el.attr('data-full'),
          w: parseInt($el.attr('data-width'), 10) || 1200,
          h: parseInt($el.attr('data-height'), 10) || 1800,
        };
      }).get();
      var pswp = $('.pswp')[0];

      if (pswp && window.PhotoSwipe && window.PhotoSwipeUI_Default) {
        new PhotoSwipe(pswp, PhotoSwipeUI_Default, items, { index: index, shareEl: false, history: false, closeOnScroll: false }).init();
      } else if (items[index]) {
        window.open(items[index].src, '_blank');
      }
    });

    // Variation image: go to it when it's in the gallery, otherwise show it in place of the first image
    var $firstZoom = $main.find('.tp-gallery-zoom').first();
    var $firstImage = $firstZoom.find('img');
    var $firstThumb = $thumbs.first().find('img');
    var originalImage = null;

    function setAttr($el, name, value) {
      if (value) {
        $el.attr(name, value);
      } else {
        $el.removeAttr(name);
      }
    }

    function resetZoom() {
      $firstZoom.trigger('zoom.destroy');
      initZoom($firstZoom);
    }

    function restoreImage() {
      if (!originalImage) return;
      setAttr($firstImage, 'src', originalImage.src);
      setAttr($firstImage, 'srcset', originalImage.srcset);
      setAttr($firstImage, 'sizes', originalImage.sizes);
      $firstZoom.attr({ 'data-full': originalImage.full, 'data-width': originalImage.width, 'data-height': originalImage.height });
      setAttr($firstThumb, 'src', originalImage.thumb);
      setAttr($firstThumb, 'srcset', originalImage.thumbSrcset);
      originalImage = null;
      resetZoom();
    }

    function showVariationImage(variation) {
      var image = variation && variation.image;
      if (!image || !image.src) {
        restoreImage();
        return;
      }

      var $slides = $main.find('.tp-gallery-slide');
      var $slide = $slides.filter('[data-image-id="' + variation.image_id + '"]');
      if ($slide.length) {
        restoreImage();
        goTo($slides.index($slide));
        return;
      }

      if (!$firstImage.length) return;
      if (!originalImage) {
        originalImage = {
          src: $firstImage.attr('src'),
          srcset: $firstImage.attr('srcset'),
          sizes: $firstImage.attr('sizes'),
          full: $firstZoom.attr('data-full'),
          width: $firstZoom.attr('data-width'),
          height: $firstZoom.attr('data-height'),
          thumb: $firstThumb.attr('src'),
          thumbSrcset: $firstThumb.attr('srcset'),
        };
      }
      setAttr($firstImage, 'src', image.src);
      setAttr($firstImage, 'srcset', image.srcset);
      setAttr($firstImage, 'sizes', image.sizes);
      $firstZoom.attr({ 'data-full': image.full_src, 'data-width': image.full_src_w, 'data-height': image.full_src_h });
      setAttr($firstThumb, 'src', image.gallery_thumbnail_src);
      $firstThumb.removeAttr('srcset');
      resetZoom();
      goTo(0);
    }

    /* ---------- Attribute swatches ---------- */
    function optionFor($select, value) {
      return $select.find('option').filter(function () {
        return this.value === value;
      });
    }

    // WooCommerce removes options that don't match the other selections and disables out of stock ones
    function isAvailable($select, value) {
      var $option = optionFor($select, value);
      return $option.length > 0 && !$option.prop('disabled');
    }

    function syncSwatches() {
      $form.find('.tp-attribute').each(function () {
        var $attribute = $(this);
        var $select = $attribute.find('select');
        var value = $select.val() || '';
        var label = '';

        $attribute.find('.tp-swatch').each(function () {
          var $swatch = $(this);
          var selected = $swatch.attr('data-value') === value;
          if (selected) label = $swatch.attr('title');
          $swatch
            .toggleClass('is-selected', selected)
            .attr('aria-pressed', selected ? 'true' : 'false')
            .toggleClass('is-unavailable', !isAvailable($select, $swatch.attr('data-value')));
        });
        $attribute.find('.tp-attribute-value').text(label);
      });
    }

    $form.on('click', '.tp-swatch', function () {
      var $select = $(this).closest('.tp-attribute').find('select');
      var value = $(this).attr('data-value');
      if ($select.val() === value) return;

      if (!isAvailable($select, value)) {
        // Not available with the other selections: clear them and start from this option
        $form.find('.variations select').not($select).val('').trigger('change');
        if (!isAvailable($select, value)) return;
      }
      $select.val(value).trigger('change');
    });

    $form.on('woocommerce_update_variation_values reset_data', syncSwatches);
    $form.on('change', '.variations select', syncSwatches);

    // After WooCommerce's init: preselect attributes with a single option
    $form.on('wc_variation_form', function () {
      $form.find('.variations select').each(function () {
        var $select = $(this);
        var $options = $select.find('option').filter(function () {
          return this.value !== '' && !this.disabled;
        });
        if (!$select.val() && $options.length === 1 && $select.find('option').filter(function () { return this.value !== ''; }).length === 1) {
          $select.val($options.val()).trigger('change');
        }
      });
      syncSwatches();
    });
    syncSwatches();

    /* ---------- Variation price, discount and SKU ---------- */
    var $priceBox = $('.tp-price-box');
    var defaultPrice = $priceBox.html();
    var $sku = $('.tp-sku');
    var $skuValue = $sku.find('[data-sku]');

    function formatNumber(amount) {
      var parts = Number(amount).toFixed(parseInt(params.decimals, 10) || 0).split('.');
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, params.thousandSep || ',');
      return parts.join(params.decimalSep || '.');
    }

    function money(amount) {
      return '<i class="fa fa-inr"></i> ' + formatNumber(amount);
    }

    function setSku(sku) {
      $skuValue.text(sku || '');
      $sku.prop('hidden', !sku);
    }

    $form.on('found_variation', function (event, variation) {
      var price = parseFloat(variation.display_price);
      var regular = parseFloat(variation.display_regular_price);
      var html = '<span class="tp-price"> ' + money(price) + ' </span>';
      var percent = regular > price ? Math.round((100 * (regular - price)) / regular) : 0;

      if (regular > price) {
        html += ' <del class="tp-regular-price"> ' + money(regular) + ' </del>';
      }
      if (percent >= 1) {
        html += ' <span class="tp-discount"> ' + percent + '% OFF </span>';
      }
      if ($priceBox.length && !isNaN(price)) {
        $priceBox.html(html);
      }

      setSku(variation.sku || $skuValue.attr('data-sku'));
      showVariationImage(variation);
    });

    $form.on('reset_data', function () {
      $priceBox.html(defaultPrice);
      setSku($skuValue.attr('data-sku'));
    });

    $form.on('reset_image', restoreImage);

    /* ---------- Size guide drawer ---------- */
    var $guide = $('#tp-size-guide');
    var lastFocus = null;

    function openGuide() {
      lastFocus = document.activeElement;
      $guide.addClass('is-open').attr('aria-hidden', 'false');
      $('body').addClass('tp-drawer-open');
      $guide.find('.tp-drawer-close').trigger('focus');
    }

    function closeGuide() {
      $guide.removeClass('is-open').attr('aria-hidden', 'true');
      $('body').removeClass('tp-drawer-open');
      if (lastFocus) lastFocus.focus();
    }

    if ($guide.length) {
      $(document).on('click', '.tp-size-guide-btn', openGuide);
      $guide.on('click', '[data-drawer-close]', closeGuide);
      $(document).on('keydown', function (event) {
        if (event.key === 'Escape' && $guide.hasClass('is-open')) closeGuide();
      });
    }

    /* ---------- Tabs ---------- */
    var $tabs = $('.tp-tab');

    function activateTab($tab, focus) {
      $tabs.removeClass('is-active').attr({ 'aria-selected': 'false', tabindex: '-1' });
      $('.tp-tab-panel').prop('hidden', true);
      $tab.addClass('is-active').attr('aria-selected', 'true').removeAttr('tabindex');
      $('#' + $tab.attr('aria-controls')).prop('hidden', false);
      if (focus) $tab.trigger('focus');
    }

    $tabs.on('click', function () {
      activateTab($(this));
    });

    $tabs.on('keydown', function (event) {
      var index = $tabs.index(this);
      var next = null;
      if (event.key === 'ArrowRight') next = (index + 1) % $tabs.length;
      if (event.key === 'ArrowLeft') next = (index - 1 + $tabs.length) % $tabs.length;
      if (event.key === 'Home') next = 0;
      if (event.key === 'End') next = $tabs.length - 1;
      if (next !== null) {
        event.preventDefault();
        activateTab($tabs.eq(next), true);
      }
    });

    function openReviews() {
      var $tab = $('#tp-tab-reviews-tab');
      if (!$tab.length) return;
      activateTab($tab);
      $('html, body').animate({ scrollTop: $tab.offset().top - 120 }, 400);
    }

    $('.tp-review-link').on('click', function (event) {
      event.preventDefault();
      openReviews();
    });

    // Links to reviews and returning from the review form
    if (/^#(tp-panel-reviews-tab|reviews|comments|respond|comment-\d+)$/.test(window.location.hash)) {
      openReviews();
    }

    /* ---------- AJAX add to cart (includes/single-product.php) ---------- */
    $(document).on('submit', 'form.tp-cart-form', function (event) {
      var $cartForm = $(this);
      var $button = $cartForm.find('.single_add_to_cart_button');

      if (!params.ajaxUrl) return;
      event.preventDefault();
      if ($button.hasClass('disabled') || $button.hasClass('loading')) return;

      var data = $cartForm.serializeArray().filter(function (field) {
        return field.name !== 'add-to-cart' && field.name !== 'product_id';
      });
      data.push({ name: 'action', value: 'tomatribe_add_to_cart' });
      data.push({ name: 'product_id', value: $cartForm.find('[name="add-to-cart"]').val() });

      $cartForm.find('.tp-cart-message').remove();
      $button.addClass('loading').prop('disabled', true);

      $.post(params.ajaxUrl, $.param(data))
        .done(function (response) {
          if (response && response.fragments) {
            var wcParams = window.wc_add_to_cart_params;
            if (wcParams && wcParams.cart_redirect_after_add === 'yes') {
              window.location = wcParams.cart_url;
              return;
            }
            $.each(response.fragments, function (selector, html) {
              $(selector).replaceWith(html);
            });
            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
            return;
          }

          var messages = response && response.data && response.data.messages ? response.data.messages : [];
          $('<div class="tp-cart-message" role="alert"></div>')
            .html(messages.length ? messages.join('<br>') : 'Could not add this product to the cart. Please try again.')
            .insertAfter($button);
        })
        .fail(function () {
          // Fall back to the regular form post
          $cartForm[0].submit();
        })
        .always(function () {
          $button.removeClass('loading').prop('disabled', false);
        });
    });
  });
})(jQuery);
