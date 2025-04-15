jQuery(function($){
    $('input[name="tpsm-shipping-fee_type"]').change(function() {
        $('.tpsm-shipping-fees-container').hide();
        $('#' + $(this).val()).show();
    });
});