jQuery(document).ready(function ($) {

    /**
     * Toggle the visibility of the shipping calculator form
     */
    $(document).on('click', '#tpsm-show-hide-calculator-from-button', function () {
        $('#tpsm-shipping-calculator-form-wrapper').toggle();
    });

    /**
     * Handle shipping calculator form submission
     */
    $(document).on('click', '#tpsm-shipping-calculator_save_location_btn', function (e) {
        e.preventDefault();

        // Get input values
        let country   = $('#tpsm-shipping-calculator-country').val();
        let state     = $('#tpsm-shipping-calculator_state').val().trim();
        let city      = $('#tpsm-shipping-calculator_city').val().trim();
        let postcode  = $('#tpsm-shipping-calculator_postcode').val().trim();

        // Sanitize inputs (basic client-side sanitization)
        state    = sanitizeInput(state);
        city     = sanitizeInput(city);
        postcode = sanitizeInput(postcode);

        // Send AJAX request to WordPress backend
        $.ajax({
            url: TPSM.ajax, // Provided via localized script
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'shipping_calculator',
                security: TPSM.nonce, // Nonce for security
                product_id: TPSM.product_id,
                country: country,
                state: state,
                city: city,
                postcode: postcode,
            },
            success: function (response) {
                if (response.success) {
                    $('#tpsm-shipping-calculator-shipping-methods').html(response.data.html);
                } else {
                    alert(response.data?.message || 'Failed to calculate shipping.');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Something went wrong. Please try again.');
            }
        });
    });

    /**
     * Basic sanitization to remove unwanted characters from inputs
     * @param {string} input 
     * @returns {string}
     */
    function sanitizeInput(input) {
        return input.replace(/[^a-zA-Z0-9\s\-]/g, '');
    }

});
