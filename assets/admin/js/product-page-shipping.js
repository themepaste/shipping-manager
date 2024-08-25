/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./src/js/product-page-shipping.js ***!
  \*****************************************/
const {
  __
} = wp.i18n;
(function ($) {
  $(document).ready(function () {
    $('.woocommerce-shipping-calculator').on('submit', function (e) {
      e.preventDefault();
      let country = $('#calc_shipping_country').val();
      let postcode = $('#calc_shipping_postcode').val();
      $.ajax({
        type: 'POST',
        url: woocommerce_params.ajax_url,
        data: {
          action: 'calculate_shipping',
          rates_nonce: tps_manager.rates_nonce,
          country: country,
          postcode: postcode,
          product_id: tps_manager.product_id
        },
        success: function (response) {
          if (response.success) {
            let table_content = '<table>';
            table_content += '<thead><tr><th colspan="2">' + __('Shipping Rates', 'tps-manager') + '</th></tr></thead>';
            table_content += '<tbody>';
            let rates = response.data.shipping_rates;
            $.each(rates, function (index, rate) {
              table_content += `<tr><td>${rate.label}</td><td>${rate.cost}</td></tr>`;
            });
            table_content += '</tbody></table>';
            let table_node = $(table_content);
            $('.tsm-shipping-result').html(table_node);
          } else {
            $('.tsm-shipping-result').html(response.data.message);
          }
        }
      });
    });
  });
})(jQuery);
/******/ })()
;
//# sourceMappingURL=product-page-shipping.js.map