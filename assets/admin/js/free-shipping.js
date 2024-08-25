/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./src/js/free-shipping.js ***!
  \*********************************/
/**
 * Admin settings script for free-shipping page
 *
 * @since 1.1.1
 */
const INPUT_WRAPPER = '.input-wrapper';
const MINIMUM_AMOUNT_ID = '#minimum-amount';
const CART_AMOUNT_ID = '#cart-amount';
const DISPLAY_TYPE = 'medium';
(function ($) {
  $(document).ready(function () {
    /**
     * Minimum amount toggle
     *
     * @since 1.1.1
     */
    function cart_amount() {
      let minimum_amount_field = $(MINIMUM_AMOUNT_ID);
      let cart_amount_wrapper = $(CART_AMOUNT_ID).closest(INPUT_WRAPPER);
      console.log('cart_amount function');
      if (minimum_amount_field.is(':checked')) {
        cart_amount_wrapper.show(DISPLAY_TYPE);
      } else {
        cart_amount_wrapper.hide(DISPLAY_TYPE);
      }
    }
    $(MINIMUM_AMOUNT_ID).on('change', cart_amount);
    cart_amount();
  });
})(jQuery);
/******/ })()
;
//# sourceMappingURL=free-shipping.js.map