jQuery(document).ready(function ($) {
    $('#tpsm-shipping-calculator_save_location_btn').on('click', function (e) {
        e.preventDefault();

        let country   = $('#tpsm-shipping-calculator-country').val();
        let state     = $('#tpsm-shipping-calculator_state').val().trim();
        let city      = $('#tpsm-shipping-calculator_city').val().trim();
        let postcode  = $('#tpsm-shipping-calculator_postcode').val().trim();

        // Optional: Sanitize inputs (basic example)
        state    = state.replace(/[^a-zA-Z0-9\s\-]/g, '');
        city     = city.replace(/[^a-zA-Z0-9\s\-]/g, '');
        postcode = postcode.replace(/[^a-zA-Z0-9\s\-]/g, '');

        // Send AJAX request to WordPress
        $.ajax({
            url: TPSM.ajax,
            type: 'POST',
            data: {
                action: 'shipping_calculator',
                country: country,
                state: state,
                city: city,
                postcode: postcode,
                security: TPSM.nonce
            },
            success: function (response) {
                // console.log( response );
                // if (response.success) {
                //     alert(response.data.message);
                // } else {
                //     alert('Failed to calculate shipping.');
                // }
            },
            error: function () {
                alert('Something went wrong. Please try again.');
            }
        });
    });
});
