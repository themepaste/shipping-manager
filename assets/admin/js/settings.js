/**
 * File: tpsm-admin-scripts.js
 * Description: Handles dynamic UI elements for the TPSM shipping method admin settings.
 *
 * @package TPSM
 */

jQuery(function ($) {

    /**
     * Toggles visibility of shipping fee option containers
     * based on the selected radio input value.
     */
    $(document).ready(function () {
        $('input[name="tpsm-shipping-fee_type"]').on('change', function () {
            // Hide all shipping fee containers
            $('.tpsm-shipping-fees-container').hide();

            // Show the container corresponding to the selected value (used as ID)
            $('#' + $(this).val()).show();
        });
    });

    /**
     * Handles dynamic addition and removal of weight-based pricing rows.
     */
    $(document).ready(function () {

        /**
         * Adds a new weight-based pricing row.
         *
         * Triggered when the "Add" button is clicked.
         */
        $('#tpsm-weight-range-pricing-add').on('click', function () {
            // Clone the last row
            var $clone = $('.tpsm-repeater-row').last().clone();

            // Clear input fields in the cloned row
            $clone.find('input').val('');

            // Append the cloned row to the repeater container
            $('.tpsm-shipping-fees-repeater').append($clone);
        });

        /**
         * Removes a specific weight pricing row.
         *
         * Triggered when the delete button is clicked within a row.
         */
        $(document).on('click', '.delete-row', function () {
            $(this).closest('.tpsm-repeater-row').remove();
        });
    });

    /**
     * Handles dynamic addition and removal of box-size dimension rows.
     */
    $(document).ready(function () {

        /**
         * Adds a new dimension row.
         */
        $('#tpsm-dimension-add').on('click', function () {
            var $clone = $('.tpsm-box-size-repeater-row').last().clone();
            $clone.find('input').val('');
            $('.tpsm-box-size-shipping-table-wrapper').append($clone);
        });

        /**
         * Removes a specific dimension row.
         */
        $(document).on('click', '.delete-row', function () {
            $(this).closest('.tpsm-box-size-repeater-row').remove();
        });
    });

    /**
     * Toggles visibility of free shipping minimum amount and shipping bar style settings.
     */
    $(document).ready(function () {

        /**
         * Show/hide minimum cart amount input based on checkbox state.
         */
        $('#tpsm-free-shipping_minimum-amount').on('change', function () {
            if ($(this).is(':checked')) {
                $('.tpsm-free-shipping_cart-amount_wrapper').show();
            } else {
                $('.tpsm-free-shipping_cart-amount_wrapper').hide();
            }
        });

        /**
         * Show/hide the shipping bar style settings.
         */
        $('#tpsm-free-shipping_free-shipping-bar').on('change', function () {
            if ($(this).is(':checked')) {
                $('#tpsm-shipping-bar-styles-container').show();
            } else {
                $('#tpsm-shipping-bar-styles-container').hide();
            }
        });
    });

    /**
     * Synchronizes the hex input with the color picker and vice versa.
     */
    $(document).ready(function () {
        $('.tpsm-color-field').each(function () {
            var $group = $(this);
            var $colorPicker = $group.find('.colorpicker');
            var $hexInput = $group.find('.hexcolor');

            /**
             * Update hex input when the color picker value changes.
             */
            $colorPicker.on('input', function () {
                $hexInput.val(this.value);
            });

            /**
             * Update color picker when hex input value changes.
             */
            $hexInput.on('input', function () {
                $colorPicker.val(this.value);
            });
        });
    });

});
