jQuery(function ($) {
    /**
     * Handles toggling visibility of the shipping fee containers
     * based on the selected shipping fee type radio input.
     */
    $(document).ready(function () {
        $('input[name="tpsm-shipping-fee_type"]').change(function () {
            // Hide all shipping fee containers
            $('.tpsm-shipping-fees-container').hide();

            // Show the selected container by ID
            $('#' + $(this).val()).show();
        });
    });

    /**
     * Handles dynamic repeater functionality for adding/removing
     * shipping fee rows in the weight range pricing section.
     */
    $(document).ready(function () {
        /**
         * Adds a new row to the repeater section when the add button is clicked.
         */
        $('#tpsm-weight-range-pricing-add').click(function () {
            // Clone the last existing repeater row
            let $clone = $('.tpsm-repeater-row').last().clone();

            // Clear all input values within the cloned row
            $clone.find('input').val('');

            // Append the cloned and cleared row to the repeater container
            $('.tpsm-shipping-fees-repeater').append($clone);
        });

        /**
         * Removes a specific repeater row when the delete button is clicked.
         */
        $(document).on('click', '.delete-row', function () {
            // Remove the parent repeater row of the clicked delete button
            $(this).closest('.tpsm-repeater-row').remove();
        });
    });

    $(document).ready(function () {
        /**
         * Adds a new row to the repeater section when the add button is clicked.
         */
        $('#tpsm-dimension-add').click(function () {
            // Clone the last existing repeater row
            let $clone = $('.tpsm-box-size-repeater-row').last().clone();

            // Clear all input values within the cloned row
            $clone.find('input').val('');

            // Append the cloned and cleared row to the repeater container
            $('.tpsm-box-size-shipping-table-wrapper').append($clone);
        });

        /**
         * Removes a specific repeater row when the delete button is clicked.
         */
        $(document).on('click', '.delete-row', function () {
            // Remove the parent repeater row of the clicked delete button
            $(this).closest('.tpsm-box-size-repeater-row').remove();
        });
    });

    $(document).ready(function() {
        $('#tpsm-free-shipping_minimum-amount').change(function() {
            if ($(this).is(':checked')) {
                $('.tpsm-free-shipping_cart-amount_wrapper').show();
            } else {
                $('.tpsm-free-shipping_cart-amount_wrapper').hide();
            }
        });
        $('#tpsm-free-shipping_free-shipping-bar').change(function() {
            if ($(this).is(':checked')) {
                $('#tpsm-shipping-bar-styles-container').show();
            } else {
                $('#tpsm-shipping-bar-styles-container').hide();
            }
        })
    });

    $(document).ready(function () {
        $('.tpsm-color-field').each(function() {
            const $group = $(this);
            const $colorPicker = $group.find('.colorpicker');
            const $hexInput = $group.find('.hexcolor');
          
            $colorPicker.on('input', function() {
              $hexInput.val(this.value);
            });
          
            $hexInput.on('input', function() {
              $colorPicker.val(this.value);
            });
        });
    });
    
    
});
