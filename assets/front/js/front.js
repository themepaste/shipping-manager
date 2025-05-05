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

        console.log( country, state, city, postcode );

        // Send AJAX request to WordPress
        // $.ajax({
        //     url: tpsm_ajax_obj.ajax_url,
        //     type: 'POST',
        //     data: {
        //         action: 'tpsm_save_location',
        //         country: country,
        //         state: state,
        //         city: city,
        //         postcode: postcode,
        //         security: tpsm_ajax_obj.nonce
        //     },
        //     success: function (response) {
        //         alert(response.data.message);
        //     },
        //     error: function () {
        //         alert('Something went wrong. Please try again.');
        //     }
        // });
    });
});
