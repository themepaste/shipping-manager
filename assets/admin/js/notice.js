/**
 * Persists dismissal of the Shipping Manager setup notice.
 *
 * WordPress removes an `is-dismissible` notice from the DOM on its own, but it
 * does not tell the server about it — without this the notice reappeared on the
 * next page load.
 *
 * @package TPSM
 */

jQuery(function ($) {
    $(document).on('click', '.tpsm-setup-notice .notice-dismiss', function () {
        $.post(TPSM_NOTICE.ajax, {
            action: 'tpsm_dismiss_setup_notice',
            nonce: TPSM_NOTICE.nonce,
        });
    });
});
